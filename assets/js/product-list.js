document.addEventListener('DOMContentLoaded', () => {

    // === 1. LẤY CÁC PHẦN TỬ ===
    const productGrid = document.getElementById('product-grid');
    const paginationControls = document.getElementById('pagination-controls');
    const sortSelect = document.getElementById('sort-select');
    const filterSelect = document.getElementById('filter-type'); 

    // === 2. KHỞI TẠO TRẠNG THÁI ===
    let currentPage = 1;
    let currentSort = 'default';
    let currentCategory = (typeof PAGE_CATEGORIES !== 'undefined') ? PAGE_CATEGORIES : null;
    let currentSearchQuery = (typeof PAGE_SEARCH_QUERY !== 'undefined') ? PAGE_SEARCH_QUERY : null;
    let currentIsSalePage = (typeof IS_SALE_PAGE !== 'undefined') && IS_SALE_PAGE === true;

    // === 3. HÀM CHÍNH: GỌI API VÀ VẼ GIAO DIỆN ===
    async function fetchProducts() {
        
        productGrid.innerHTML = '<p style="text-align:center; width:100%">Đang tải sản phẩm...</p>';
        paginationControls.innerHTML = ''; 

        // Xây dựng URL
        let queryParams = [];
        if (currentCategory) {
            if (Array.isArray(currentCategory)) queryParams.push(currentCategory.map(cat => `category[]=${cat}`).join('&'));
            else queryParams.push(`category=${currentCategory}`);
        }
        if (currentIsSalePage) queryParams.push('sale=true');
        if (currentSearchQuery) queryParams.push(`q=${encodeURIComponent(currentSearchQuery)}`);
        queryParams.push(`page=${currentPage}`);
        queryParams.push(`sort=${currentSort}`);

        const queryString = queryParams.join('&');

        try {
            const response = await fetch(`../api/products.php?${queryString}`);
            if (!response.ok) throw new Error(`Lỗi HTTP: ${response.status}`);
            const data = await response.json();

            productGrid.innerHTML = ''; 

            if (data.products && data.products.length > 0) {
                
                data.products.forEach(product => {
                    // --- 1. XỬ LÝ GIÁ & MÁC GIẢM GIÁ ---
                    const displayPrice = Number(product.display_price);
                    const originalPrice = Number(product.original_price);
                    let priceHTML = '';
                    let badgeHTML = '';
                    
                    // Logic hiển thị giá giống hình ảnh (-8% nền đen, giá mới đỏ, giá cũ gạch)
                    if (originalPrice > (displayPrice + 1000)) {
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        const formattedOriginal = originalPrice.toLocaleString('vi-VN');
                        
                        // Mác giảm giá (Góc phải)
                        let discountPercent = 0;
                        if (originalPrice > 0) { 
                            discountPercent = Math.round(((originalPrice - displayPrice) / originalPrice) * 100); 
                        }
                        badgeHTML = `<div class="badge-sale">-${discountPercent}%</div>`;

                        // Giá: Mới (Đỏ) - Cũ (Gạch)
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}đ</span>
                                <span class="old-price">${formattedOriginal}đ</span>
                            </p>
                        `;
                    } else {
                        // Không giảm giá
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}đ</span>
                            </p>
                        `;
                    }

                    // --- 2. XỬ LÝ ĐƯỜNG DẪN VÀ ẢNH ---
                    // Xử lý ảnh để hiển thị đúng (xóa ../ nếu có)
                    // Tuy nhiên ở trang con (pages/) thì giữ nguyên ../ là đúng. 
                    // Nhưng để lưu vào giỏ hàng (cart.js dùng ở mọi nơi) ta nên chuẩn hóa.
                    // Ở đây ta cứ để nguyên đường dẫn từ DB trả về để hiển thị ảnh
                    let imgSrc = product.image_url || '../assets/img/no-image.jpg';
                    
                    // Link chi tiết sản phẩm
                    const linkDetail = `product-detail.php?slug=${product.slug}`;


                    // --- 3. VẼ HTML (SỬA LỖI CÚ PHÁP CỦA BẠN TẠI ĐÂY) ---
                    const productCardHTML = `
                        <div class="product-card">
                            <a href="${linkDetail}">
                                <img src="${imgSrc}" alt="${product.ten_san_pham}">
                            </a>
                            
                            ${badgeHTML} 
                            
                            <div class="overlay">
                                <button class="add-to-cart-btn" 
                                    data-id="${product.id}" 
                                    data-name="${product.ten_san_pham}" 
                                    data-price="${displayPrice}" 
                                    data-image="${imgSrc}">
                                </button> 
                            </div>
                            
                            <h4><a href="${linkDetail}">${product.ten_san_pham}</a></h4>
                            
                            ${priceHTML}
                        </div>
                    `;
                    
                    productGrid.innerHTML += productCardHTML;
                });

            } else {
                productGrid.innerHTML = '<p style="grid-column: 1/-1; text-align:center;">Không tìm thấy sản phẩm nào.</p>';
            }

            // Vẽ phân trang
            createPaginationButtons(data.pagination.total_pages);

        } catch (error) {
            console.error('Lỗi:', error);
            productGrid.innerHTML = `<p>Lỗi tải dữ liệu. (${error.message})</p>`;
        }
    }

    // === 4. HÀM PHÂN TRANG ===
    function createPaginationButtons(totalPages) {
        paginationControls.innerHTML = '';
        if(totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-btn';
            pageBtn.innerText = i;
            if (i === currentPage) pageBtn.classList.add('active');
            
            pageBtn.addEventListener('click', () => {
                currentPage = i; 
                fetchProducts(); 
                window.scrollTo(0, 0);
            });
            paginationControls.appendChild(pageBtn);
        }
    }

    // === 5. GÁN SỰ KIỆN ===
    if(sortSelect) {
        sortSelect.addEventListener('change', () => {
            currentSort = sortSelect.value;
            currentPage = 1;
            fetchProducts();
        });
    }

    if (filterSelect) { 
        filterSelect.addEventListener('change', () => {
            currentCategory = filterSelect.value;
            currentPage = 1;
            currentIsSalePage = false; 
            currentSearchQuery = null;
            fetchProducts();
        });
    }

    // Chạy lần đầu
    fetchProducts();
});