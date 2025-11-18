document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("search");
    const suggestionBox = document.getElementById("suggestions");

    // Biến dùng để trì hoãn (Debounce)
    let timeout = null; 

    // Xác định đường dẫn (giữ nguyên như cũ)
    const isPagesFolder = window.location.pathname.includes('/pages/');
    const apiPath = isPagesFolder ? '../public/search_suggestion.php' : 'public/search_suggestion.php';
    const searchResultPath = isPagesFolder ? 'timkiem.php' : 'pages/timkiem.php';

    searchInput.addEventListener("input", function() {
        const query = this.value.trim();

        // 1. Xóa lệnh chờ cũ nếu người dùng vẫn đang gõ hoặc thao tác
        clearTimeout(timeout);

        // 2. Nếu xóa hết chữ, ẩn ngay lập tức (không cần chờ)
        if (query.length < 2) {
            suggestionBox.style.display = "none";
            suggestionBox.innerHTML = "";
            return;
        }

        // 3. Thiết lập lệnh chờ mới (300ms)
        // Code bên trong này chỉ chạy khi bạn ngừng thao tác quá 300ms
        timeout = setTimeout(function() {
            
            fetch(`${apiPath}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionBox.innerHTML = "";
                    
                    if (data.length > 0) {
                        let htmlContent = '';
                        data.forEach(product => {
                            let imgSrc = 'assets/img/no-image.jpg';
                            if (product.hinh_anh) {
                                imgSrc = isPagesFolder ? product.hinh_anh : product.hinh_anh.replace(/^\.\.\//, '');
                            }
                            
                            const link = `${searchResultPath}?q=${encodeURIComponent(product.ten_san_pham)}`;
                            
                            htmlContent += `
                                <a href="${link}" class="suggestion-item">
                                    <img src="${imgSrc}" alt="img" class="suggestion-img">
                                    <span class="suggestion-text">${product.ten_san_pham}</span>
                                </a>
                            `;
                        });

                        suggestionBox.innerHTML = htmlContent;
                        suggestionBox.style.display = "block";
                    } else {
                        suggestionBox.style.display = "none";
                    }
                })
                .catch(err => {
                    console.error("Lỗi:", err);
                });

        }, 300); // <-- Đợi 300ms mới chạy tìm kiếm
    });

    // Ẩn khi click ra ngoài
    document.addEventListener("click", function(e) {
        if (!searchInput.contains(e.target) && !suggestionBox.contains(e.target)) {
            suggestionBox.style.display = "none";
        }
    });
});