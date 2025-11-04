// Chạy code khi toàn bộ cây HTML đã được tải
document.addEventListener('DOMContentLoaded', () => {

    // Lấy các phần tử (element) quan trọng
    const productGrid = document.getElementById('product-grid');
    const paginationControls = document.getElementById('pagination-controls');
    const sortSelect = document.getElementById('sort-select');

    // Biến để lưu trạng thái hiện tại
    let currentPage = 1;
    let currentSort = 'default';

    // === HÀM CHÍNH: GỌI API VÀ "VẼ" LẠI GIAO DIỆN ===
    async function fetchProducts(page, sort) {
        
        // Cập nhật trạng thái
        currentPage = page;
        currentSort = sort;

        // Hiển thị loading
        productGrid.innerHTML = '<p>Đang tải sản phẩm...</p>';
        paginationControls.innerHTML = ''; // Xóa nút phân trang cũ

        try {
            // 1. GỌI API
            // Đường dẫn '../api/products.php' là tính từ file .php (ao-so-mi.php)
            const response = await fetch(
                `../api/products.php?category=ao-len&page=${page}&sort=${sort}`
            );

            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }

            const data = await response.json();

            // 2. "VẼ" SẢN PHẨM
            productGrid.innerHTML = ''; // Xóa chữ "Đang tải..."
            
            if (data.products && data.products.length > 0) {
                data.products.forEach(product => {
                    // Format giá tiền cho đẹp (ví dụ: 356,000₫)
                    const formattedPrice = Number(product.display_price).toLocaleString('vi-VN');

                    // Tạo 1 thẻ HTML cho mỗi sản phẩm
                    const productCardHTML = `
                        <div class="product-card">
                            <img src="${product.image_url}" alt="${product.ten_san_pham}">
                            <div class="badge">New</div>
                            <h4>${product.ten_san_pham}</h4>
                            <p><span class="new">${formattedPrice}₫</span></p>
                        </div>
                    `;
                    // Thêm thẻ HTML vào grid
                    productGrid.innerHTML += productCardHTML;
                });
            } else {
                productGrid.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
            }

            // 3. "VẼ" CÁC NÚT PHÂN TRANG
            createPaginationButtons(data.pagination.total_pages);

        } catch (error) {
            console.error('Không thể tải sản phẩm:', error);
            productGrid.innerHTML = `<p>Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại. (${error.message})</p>`;
        }
    }

    // === HÀM PHỤ: TẠO CÁC NÚT PHÂN TRANG ===
    function createPaginationButtons(totalPages) {
        paginationControls.innerHTML = ''; // Xóa nút cũ

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = 'page-btn';
            pageBtn.innerText = i;
            
            if (i === currentPage) {
                pageBtn.classList.add('active'); // Đánh dấu trang hiện tại
            }

            // Thêm sự kiện click cho nút
            pageBtn.addEventListener('click', () => {
                fetchProducts(i, currentSort); // Gọi API với số trang mới
                window.scrollTo(0, 0); // Cuộn lên đầu trang
            });

            paginationControls.appendChild(pageBtn);
        }
    }

    // === LẮNG NGHE SỰ KIỆN ===

    // 1. Khi người dùng thay đổi Sắp xếp
    sortSelect.addEventListener('change', () => {
        const newSortValue = sortSelect.value;
        // Quay về trang 1 khi sắp xếp lại
        fetchProducts(1, newSortValue);
    });

    // 2. GỌI LẦN ĐẦU TIÊN KHI TẢI TRANG
    // Tải sản phẩm cho trang 1, sắp xếp 'default'
    fetchProducts(1, 'default');

});