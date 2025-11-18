document.addEventListener('DOMContentLoaded', () => {

    // ==============================================
    // CẤU HÌNH CHUNG
    // ==============================================
    const ITEMS_LIMIT = 4; // Số lượng sản phẩm hiển thị (4 hoặc 8)

    // Cấu hình các Bộ Sưu Tập (Tiêu đề, Link, và Cách lấy ảnh đại diện)
    const collectionsConfig = [
        {
            title: "POLO",
            link: "pages/ao-polo-rugby-shirt-nam.php", 
            apiQuery: "category=ao-polo&sort=default" // Lấy áo polo mới nhất
        },
        {
            title: "NEW ARRIVALS",
            link: "pages/hang-moi-ve.php", 
            apiQuery: "sort=default" // Lấy sản phẩm mới nhất bất kỳ
        },
        {
            title: "ÁO THUN",
            link: "pages/ao-thun.php",
            apiQuery: "category=ao-thun&sort=default" // Lấy áo thun mới nhất
        }
    ];


    // ==============================================
    // 1. KHỞI CHẠY CÁC HÀM TẢI DỮ LIỆU
    // ==============================================

    // 1.1. Tải sản phẩm HOT (Ví dụ: Sắp xếp giá giảm dần)
    // Lưu ý: Dùng đường dẫn 'api/products.php' (không có ../) vì đang ở index.php
    fetchHomeProducts('api/products.php?sort=price-desc&page=1', 'hot-products-grid');

    // 1.2. Tải sản phẩm MỚI NHẤT
    fetchHomeProducts('api/products.php?sort=default&page=1', 'new-products-grid');

    // 1.3. Tải Bộ Sưu Tập
    loadCollections(collectionsConfig, 'collection-grid');


    // ==============================================
    // 2. ĐỊNH NGHĨA HÀM XỬ LÝ SẢN PHẨM (HOT/MỚI)
    // ==============================================
    async function fetchHomeProducts(apiUrl, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.products && data.products.length > 0) {
                container.innerHTML = ''; // Xóa chữ "Đang tải..."

                // Chỉ lấy số lượng giới hạn (ví dụ 4 sản phẩm)
                const productsToShow = data.products.slice(0, ITEMS_LIMIT);

                productsToShow.forEach(product => {
                    // --- A. Logic Tính Giá & Mác Giảm Giá ---
                    const displayPrice = Number(product.display_price);
                    const originalPrice = Number(product.original_price);
                    let priceHTML = '';
                    let badgeHTML = '';

                    if (originalPrice > (displayPrice + 1000)) {
                        // Có giảm giá
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        const formattedOriginal = originalPrice.toLocaleString('vi-VN');
                        
                        let discountPercent = 0;
                        if (originalPrice > 0) {
                            discountPercent = Math.round(((originalPrice - displayPrice) / originalPrice) * 100);
                        }

                        badgeHTML = `<div class="badge-sale">-${discountPercent}%</div>`;
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}₫</span>
                                <span class="old-price">${formattedOriginal}₫</span>
                            </p>`;
                    } else {
                        // Không giảm giá
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}₫</span>
                            </p>`;
                    }

                    // --- B. Xử lý đường dẫn (Quan trọng cho index.php) ---
                    // 1. Link chi tiết: Phải đi vào thư mục pages/
                    const linkDetail = `pages/chi-tiet.php?slug=${product.slug}`;
                    
                    // 2. Link ảnh: Xóa dấu '../' ở đầu vì index.php nằm ở gốc
                    let cleanImage = product.image_url ? product.image_url.replace(/^\.\.\//, '') : 'assets/img/no-image.jpg';

                    // --- C. Vẽ HTML ---
                    const html = `
                        <div class="product-card">
                            <a href="${linkDetail}">
                                <img src="${cleanImage}" alt="${product.ten_san_pham}">
                            </a>
                            ${badgeHTML}
                            <div class="overlay">
                                <button onclick="window.location.href='${linkDetail}'"></button>
                            </div>
                            <h4><a href="${linkDetail}">${product.ten_san_pham}</a></h4>
                            ${priceHTML}
                        </div>
                    `;
                    container.innerHTML += html;
                });

            } else {
                container.innerHTML = '<p>Đang cập nhật sản phẩm...</p>';
            }

        } catch (error) {
            console.error(`Lỗi tải mục ${containerId}:`, error);
            container.innerHTML = '<p>Lỗi kết nối server.</p>';
        }
    }


    // ==============================================
    // 3. ĐỊNH NGHĨA HÀM XỬ LÝ BỘ SƯU TẬP
    // ==============================================
    async function loadCollections(items, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        container.innerHTML = ''; // Xóa loading

        // Duyệt qua từng cấu hình bộ sưu tập
        for (const item of items) {
            try {
                // Gọi API lấy đúng 1 sản phẩm đại diện
                const response = await fetch(`api/products.php?${item.apiQuery}&page=1`);
                const data = await response.json();

                let imageUrl = 'assets/img/default.jpg'; // Ảnh mặc định
                
                if (data.products && data.products.length > 0) {
                    // Lấy ảnh sản phẩm đầu tiên
                    let rawImg = data.products[0].image_url;
                    // Xử lý đường dẫn ảnh cho index.php
                    if (rawImg) {
                        imageUrl = rawImg.replace(/^\.\.\//, ''); 
                    }
                }

                // Vẽ HTML cho 1 ô Collection
                const html = `
                    <div class="collection-item">
                        <a href="${item.link}">
                            <img src="${imageUrl}" alt="${item.title}">
                        </a>
                        <div class="overlay"></div>
                        <h3><a href="${item.link}">${item.title}</a></h3>
                    </div>
                `;
                container.innerHTML += html;

            } catch (error) {
                console.error(`Lỗi tải collection ${item.title}:`, error);
            }
        }
    }

});