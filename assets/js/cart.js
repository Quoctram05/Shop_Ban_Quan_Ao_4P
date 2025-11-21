// assets/js/cart.js

document.addEventListener('DOMContentLoaded', function() {
    // Element mới
    const headerCartBtn = document.getElementById('header-cart-btn');
    const cartCountBadge = document.getElementById('header-cart-count');
    
    // Element chứa nội dung dropdown
    const miniCartBox = document.querySelector('#mini-cart-dropdown .cart-content');
    // Nếu chưa có div con .cart-content thì gán trực tiếp vào box cha
    const targetContainer = miniCartBox || document.getElementById('mini-cart-dropdown');

    // --- HÀM GỌI API ---
    async function updateCart(action, productData = {}) {
        try {
            const response = await fetch('/SHOP_BAN_QUAN_AO_4P/api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, ...productData })
            });
            const data = await response.json();

            if (data.status === 'success' || action === 'get') {
                // 1. Cập nhật số lượng Badge
                if(cartCountBadge) cartCountBadge.innerText = data.cart_count;
                
                // 2. Cập nhật nội dung Dropdown
                if(targetContainer) targetContainer.innerHTML = data.cart_html;
            }
        } catch (error) {
            console.error('Lỗi giỏ hàng:', error);
        }
    }

    // --- SỰ KIỆN THÊM VÀO GIỎ ---
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (btn) {
            e.preventDefault();
            const productData = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                price: btn.dataset.price,
                image: btn.dataset.image
            };
            updateCart('add', productData);
            
            // (Tùy chọn) Hiệu ứng mở dropdown nhẹ để báo hiệu đã thêm
            const dropdown = document.getElementById('mini-cart-dropdown');
            dropdown.style.display = 'block';
            setTimeout(() => { dropdown.style.display = ''; }, 3000); // Ẩn sau 3s (trả về hover)
        }
    });

    // --- HÀM XÓA SẢN PHẨM ---
    window.removeFromCart = function(id) {
        updateCart('remove', { id: id });
    };

    // Load giỏ hàng khi vào trang
    updateCart('get');
});