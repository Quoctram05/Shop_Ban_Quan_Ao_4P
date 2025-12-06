document.addEventListener('DOMContentLoaded', () => {

    const ITEMS_LIMIT = 4;
    const BASE_URL = '/Shop_Ban_Quan_Ao_4P/';
    const QUICK_VIEW_URL = `${BASE_URL}ajax/quick-view.php`;
    const ADD_CART_URL   = `${BASE_URL}ajax/add-to-cart.php`;
    const quickViewModal  = document.getElementById('quick-view-modal');
    const quickViewBody   = document.getElementById('quick-view-body');
    const miniCartBox     = document.getElementById('mini-cart-dropdown');
    const miniCartContent = document.querySelector('#mini-cart-dropdown .cart-content');
    const headerCartCount = document.getElementById('header-cart-count');

    // Cấu hình các Bộ Sưu Tập
    const collectionsConfig = [
        {
            title: "POLO",
            link: "pages/ao-polo-rugby-shirt-nam.php", 
            apiQuery: "category=ao-polo&sort=default"
        },
        {
            title: "NEW ARRIVALS",
            link: "pages/thoi-trang-moi-nhat.php", 
            apiQuery: "sort=default" 
        },
        {
            title: "ÁO THUN",
            link: "pages/ao-thun.php",
            apiQuery: "category=ao-thun&sort=default"
        }
    ];

    // 1. Gọi hàm tải dữ liệu
    fetchHomeProducts('api/products.php?sort=price-desc&page=1', 'hot-products-grid');
    fetchHomeProducts('api/products.php?sort=default&page=1', 'new-products-grid');
    loadCollections(collectionsConfig, 'collection-grid');


    // 2. Hàm xử lý Sản phẩm
    async function fetchHomeProducts(apiUrl, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.products && data.products.length > 0) {
                container.innerHTML = '';

                const productsToShow = data.products.slice(0, ITEMS_LIMIT);

                productsToShow.forEach(product => {
                    // Logic tính giá
                    const displayPrice = Number(product.display_price);
                    const originalPrice = Number(product.original_price);
                    let priceHTML = '';
                    let badgeHTML = '';

                    if (originalPrice > (displayPrice + 1000)) {
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        const formattedOriginal = originalPrice.toLocaleString('vi-VN');
                        let discountPercent = 0;
                        if (originalPrice > 0) {
                            discountPercent = Math.round(((originalPrice - displayPrice) / originalPrice) * 100);
                        }
                        badgeHTML = `<div class="badge-sale">-${discountPercent}%</div>`;
                        priceHTML = `<p class="price"><span class="new-price">${formattedDisplay}đ</span> <span class="old-price">${formattedOriginal}đ</span></p>`;
                    } else {
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        priceHTML = `<p class="price"><span class="new-price">${formattedDisplay}đ</span></p>`;
                    }

                    const linkDetail = `pages/chi-tiet.php?slug=${product.slug}`;
                    let cleanImage = product.image_url ? product.image_url.replace(/^\.\.\//, '') : 'assets/img/no-image.jpg';

                    // Thêm class add-to-cart-btn và data attributes
                    const html = `
                        <div class="product-card">
                            <a href="${linkDetail}">
                                <img src="${cleanImage}" alt="${product.ten_san_pham}">
                            </a>
                            ${badgeHTML}
                            <div class="overlay">
                                <a href="#"
                                   class="btn-cart js-open-quick-view"
                                   data-product-id="${product.id}">
                                   <i class="fa-solid fa-cart-shopping"></i>
                                </a>
                            </div>
                            <h4><a href="${linkDetail}">${product.ten_san_pham}</a></h4>
                            ${priceHTML}
                        </div>
                    `;
                    container.innerHTML += html;
                });

            } else {
                container.innerHTML = '<p>Đang cập nhật...</p>';
            }

        } catch (error) {
            console.error(`Lỗi tải mục ${containerId}:`, error);
        }
    }

    // 3. Hàm xử lý Bộ Sưu Tập
    async function loadCollections(items, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = ''; 

        for (const item of items) {
            try {
                const response = await fetch(`api/products.php?${item.apiQuery}&page=1`);
                const data = await response.json();
                let imageUrl = 'assets/img/default.jpg'; 
                
                if (data.products && data.products.length > 0) {
                    let rawImg = data.products[0].image_url;
                    if (rawImg) imageUrl = rawImg.replace(/^\.\.\//, ''); 
                }

                const html = `
                    <div class="collection-item">
                        <a href="${item.link}"><img src="${imageUrl}" alt="${item.title}"></a>
                        <div class="overlay"></div>
                        <h3><a href="${item.link}">${item.title}</a></h3>
                    </div>
                `;
                container.innerHTML += html;
            } catch (error) { console.error(`Lỗi tải collection:`, error); }
        }
    }

    // ---------- Quick View modal mở từ nút giỏ hàng đỏ ----------
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-open-quick-view');
        if (!btn) return;
        e.preventDefault();

        const id = btn.dataset.productId;
        if (!id) return;

        fetch(`${QUICK_VIEW_URL}?id=${encodeURIComponent(id)}`)
            .then(res => res.text())
            .then(html => {
                if (quickViewBody) quickViewBody.innerHTML = html;
                if (quickViewModal) quickViewModal.classList.add('is-open');
            })
            .catch(err => console.error('Quick view error:', err));
    });

    // Đóng modal
    if (quickViewModal) {
        quickViewModal.addEventListener('click', function (e) {
            if (e.target.classList.contains('quick-view__overlay') || e.target.classList.contains('quick-view__close')) {
                quickViewModal.classList.remove('is-open');
            }
        });
    }

    // Thêm giỏ từ modal
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-add-to-cart-from-modal');
        if (!btn) return;
        e.preventDefault();

        const form = document.getElementById('quick-view-add-cart-form');
        if (!form) return;

        const formData = new FormData(form);

        fetch(ADD_CART_URL, { method: 'POST', body: formData })
            .then(res => res.text())
            .then(html => {
                if (miniCartContent) {
                    miniCartContent.innerHTML = html;
                    const header = miniCartContent.querySelector('[data-cart-count]');
                    if (header && headerCartCount) {
                        headerCartCount.textContent = header.getAttribute('data-cart-count') || headerCartCount.textContent;
                    }
                }
                if (miniCartBox) {
                    miniCartBox.style.display = 'block';
                    setTimeout(() => { miniCartBox.style.display = ''; }, 2500);
                }
                if (quickViewModal) quickViewModal.classList.remove('is-open');
            })
            .catch(err => console.error('Add to cart (modal) error:', err));
    });

});
