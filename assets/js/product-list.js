// Chạy code khi toàn bộ cây HTML đã được tải
document.addEventListener('DOMContentLoaded', () => {

    // === 1. LẤY CÁC PHẦN TỬ (ELEMENTS) ===
    const productGrid = document.getElementById('product-grid');
    const paginationControls = document.getElementById('pagination-controls');
    const sortSelect = document.getElementById('sort-select');
    const filterSelect = document.getElementById('filter-type'); // Bộ lọc

    // === 2. KHỞI TẠO TRẠNG THÁI (STATE) ===
    // Đọc các biến được định nghĩa từ file PHP
    let currentPage = 1;
    let currentSort = 'default';
    let currentCategory = (typeof PAGE_CATEGORIES !== 'undefined') ? PAGE_CATEGORIES : null;
    let currentSearchQuery = (typeof PAGE_SEARCH_QUERY !== 'undefined') ? PAGE_SEARCH_QUERY : null;
    let currentIsSalePage = (typeof IS_SALE_PAGE !== 'undefined') && IS_SALE_PAGE === true;

    // === 3. HÀM CHÍNH: GỌI API VÀ "VẼ" LẠI GIAO DIỆN ===
    // Hàm này sẽ đọc các biến trạng thái ở trên
    async function fetchProducts() {
        
        // Hiển thị loading
        productGrid.innerHTML = '<p>Đang tải sản phẩm...</p>';
        paginationControls.innerHTML = ''; 

        // Xây dựng URL động dựa trên trạng thái
        let queryParams = [];

        // 1. Thêm Category (Nếu có)
        if (currentCategory) {
            if (Array.isArray(currentCategory)) {
                queryParams.push(currentCategory.map(cat => `category[]=${cat}`).join('&'));
            } else {
                queryParams.push(`category=${currentCategory}`);
            }
        }

        // 2. Thêm Filter Sale (Nếu có)
        if (currentIsSalePage) {
            queryParams.push('sale=true');
        }
        
        // 3. (MỚI) Thêm Search Query (Nếu có)
        // (Kiểm tra xem biến PAGE_SEARCH_QUERY có được định nghĩa trong file PHP không)
        if (typeof PAGE_SEARCH_QUERY !== 'undefined' && PAGE_SEARCH_QUERY) {
            // encodeURIComponent để xử lý ký tự đặc biệt (ví dụ: "áo sơ mi")
            queryParams.push(`q=${encodeURIComponent(PAGE_SEARCH_QUERY)}`);
        }

        // 4. Thêm Sắp xếp và Phân trang (Luôn có)
        queryParams.push(`page=${currentPage}`);
        queryParams.push(`sort=${currentSort}`);

        // 5. Nối tất cả lại
        const queryString = queryParams.join('&');

        try {
            const response = await fetch(`../api/products.php?${queryString}`);

            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }

            const data = await response.json();

            // 6. "VẼ" SẢN PHẨM (Đã có logic giá)
            productGrid.innerHTML = ''; 
            if (data.products && data.products.length > 0) {
                
                data.products.forEach(product => {
                    // Lấy cả hai giá từ API
                    const displayPrice = Number(product.display_price);
                    const originalPrice = Number(product.original_price);

                    let priceHTML = ''; // Biến để chứa HTML của giá
                    let badgeHTML = ''; // Biến để chứa mác giảm giá
                    
                    // Kiểm tra xem có giảm giá không
                    if (originalPrice > (displayPrice + 1000)) {
                        // CÓ GIẢM GIÁ
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        const formattedOriginal = originalPrice.toLocaleString('vi-VN');
                        
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}₫</span>
                                <span class="old-price">${formattedOriginal}₫</span>
                            </p>
                        `;
                        
                        // Sửa lỗi tính % (chia cho 0 và sai công thức)
                        let discountPercent = 0;
                        if (originalPrice > 0) { 
                            discountPercent = Math.round(((originalPrice - displayPrice) / originalPrice) * 100); 
                        }
                        badgeHTML = `<div class="badge-sale">-${discountPercent}%</div>`;

                    } else {
                        // KHÔNG GIẢM GIÁ
                        const formattedDisplay = displayPrice.toLocaleString('vi-VN');
                        
                        priceHTML = `
                            <p class="price">
                                <span class="new-price">${formattedDisplay}₫</span>
                            </p>
                        `;
                    }

                    // Xây dựng thẻ HTML cuối cùng
                    const productCardHTML = `
                        <div class="product-card">
                            <img src="${product.image_url}" alt="${product.ten_san_pham}">
                            ${badgeHTML} 
                            <div class="overlay"><button></button></div>
                            <h4>${product.ten_san_pham}</h4>
                            ${priceHTML}
                        </div>
                    `;
                    productGrid.innerHTML += productCardHTML;
                });

            } else {
                productGrid.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
            }

            // 7. "VẼ" CÁC NÚT PHÂN TRANG
            createPaginationButtons(data.pagination.total_pages);

        } catch (error) {
            console.error('Không thể tải sản phẩm:', error);
            productGrid.innerHTML = `<p>Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại. (${error.message})</p>`;
        }
    }

    // === 4. HÀM PHỤ: TẠO CÁC NÚT PHÂN TRANG ===
    function createPaginationButtons(totalPages) {
        paginationControls.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-btn';
            pageBtn.innerText = i;
            if (i === currentPage) pageBtn.classList.add('active');
            
            pageBtn.addEventListener('click', () => {
                currentPage = i; // Cập nhật trạng thái
                fetchProducts(); // Gọi lại hàm chính
                window.scrollTo(0, 0);
            });
            paginationControls.appendChild(pageBtn);
        }
    }

    // === 5. LẮNG NGHE SỰ KIỆN ===

    // 1. Khi người dùng thay đổi Sắp xếp
    if(sortSelect) {
        sortSelect.addEventListener('change', () => {
            currentSort = sortSelect.value; // Cập nhật trạng thái
            currentPage = 1; // Reset trang
            fetchProducts(); // Gọi lại hàm chính
        });
    }

    // 2. Khi người dùng thay đổi Bộ lọc
    if (filterSelect) { 
        filterSelect.addEventListener('change', () => {
            currentCategory = filterSelect.value; // Cập nhật trạng thái
            currentPage = 1; // Reset trang
            
            // Khi lọc, ta reset các trạng thái khác
            currentIsSalePage = false; 
            currentSearchQuery = null;

            fetchProducts(); // Gọi lại hàm chính
        });
    }

    // === 6. GỌI LẦN ĐẦU TIÊN KHI TẢI TRANG ===
    fetchProducts();

});