// assets/js/cart.js

document.addEventListener('DOMContentLoaded', function () {
    // URL API giỏ hàng
    // TODO: sửa lại cho đúng tên thư mục trên host (phân biệt hoa/thường!)
    const CART_API_URL = '/Shop_Ban_Quan_Ao_4P/api/cart.php';
    // Ví dụ nếu thư mục của bạn là SHOP_BAN_QUAN_AO_4P thì:
    // const CART_API_URL = '/SHOP_BAN_QUAN_AO_4P/api/cart.php';

    // Nút & badge ở header
    const headerCartBtn   = document.getElementById('header-cart-btn');
    const cartCountBadge  = document.getElementById('header-cart-count');

    // Box chứa nội dung dropdown mini-cart
    const miniCartBox     = document.querySelector('#mini-cart-dropdown .cart-content');
    const targetContainer = miniCartBox || document.getElementById('mini-cart-dropdown');

    if (!targetContainer) {
        console.warn('Không tìm thấy container cho mini cart');
    }

    // ================== HÀM GỌI API GIỎ HÀNG ==================
    async function updateCart(action, productData = {}) {
        try {
            const response = await fetch(CART_API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action, ...productData })
            });

            // Kiểm tra xem server có trả JSON thật không
            const contentType = response.headers.get('content-type') || '';
            let data;

            if (contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                console.error('cart.php KHÔNG trả JSON. Nội dung nhận được:', text);
                return;
            }

            if (data.status === 'success' || action === 'get') {
                // Cập nhật số lượng badge
                if (cartCountBadge) {
                    cartCountBadge.innerText = data.cart_count ?? 0;
                }

                // Cập nhật HTML mini cart
                if (targetContainer) {
                    targetContainer.innerHTML = data.cart_html ?? '';
                }
            } else {
                console.warn('Cart API trả về lỗi:', data.message);
            }
        } catch (error) {
            console.error('Lỗi giỏ hàng (fetch thất bại):', error);
        }
    }

    // ================== THÊM VÀO GIỎ ==================
    document.body.addEventListener('click', function (e) {
        const btn = e.target.closest('.add-to-cart-btn');
        if (!btn) return;

        e.preventDefault();

        const productData = {
            id:    btn.dataset.id,
            name:  btn.dataset.name,
            price: btn.dataset.price,
            image: btn.dataset.image,
            // nếu sau này có size / color thì thêm:
            // size:  btn.dataset.size,
            // color: btn.dataset.color,
        };

        updateCart('add', productData);

        // Hiệu ứng hiện mini cart báo đã thêm
        const dropdown = document.getElementById('mini-cart-dropdown');
        if (dropdown) {
            dropdown.style.display = 'block';
            setTimeout(() => { dropdown.style.display = ''; }, 3000);
        }
    });

    // ================== XÓA SẢN PHẨM TRONG MINI-CART ==================
    window.removeFromCart = function (id) {
        updateCart('remove', { id: id });
    };

    // ================== LOAD GIỎ HÀNG LÚC VÀO TRANG ==================
    updateCart('get');
});
