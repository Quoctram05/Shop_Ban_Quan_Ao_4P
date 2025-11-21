document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // 1. CÁC HÀM GỌI API GIỎ HÀNG
    // ============================================================

    // Hàm cập nhật giỏ hàng (Có thêm tham số 'reload' để quyết định có F5 trang hay không)
    async function updateCartItem(id, qty = null, size = null, color = null, shouldReload = true) {
        try {
            const payload = { action: 'update', id: id };
            if (qty !== null) payload.qty = qty;
            if (size !== null) payload.size = size;
            if (color !== null) payload.color = color;

            const response = await fetch('../api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            
            const data = await response.json();
            
            if (data.status === 'success' && shouldReload) {
                location.reload(); 
            }
        } catch (error) {
            console.error('Lỗi cập nhật:', error);
        }
    }

    // Hàm xóa sản phẩm
    async function removeCartItem(id) {
        if(!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        try {
            const response = await fetch('../api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'remove', id: id })
            });
            const data = await response.json();
            if (data.status === 'success') location.reload();
        } catch (error) { console.error('Lỗi xóa:', error); }
    }

    // ============================================================
    // 2. LOGIC XỬ LÝ BIẾN THỂ
    // ============================================================

    async function initVariants() {
        const products = document.querySelectorAll('.product-item');
        
        for (const product of products) {
            const productId = product.dataset.productId; 
            const cartId = product.dataset.id; // ID trong session (key)

            try {
                const response = await fetch(`../api/get_variants.php?product_id=${productId}`);
                const data = await response.json();
                
                if (data.status === 'success' && data.variants.length > 0) {
                    product.dataset.variants = JSON.stringify(data.variants);
                    // Vẽ option và LẤY GIÁ TRỊ MẶC ĐỊNH
                    const defaults = renderOptions(product, data.variants);
                    
                    // === KHẮC PHỤC LỖI NULL ===
                    // Tự động cập nhật Session với giá trị mặc định ngay khi tải trang
                    // (Tham số false để không reload trang liên tục)
                    if (defaults.color && defaults.size) {
                        updateCartItem(cartId, null, defaults.size, defaults.color, false);
                    }
                }
            } catch (err) { console.error("Lỗi lấy biến thể:", err); }
        }
    }

    function renderOptions(productRow, variants) {
        const colorSelect = productRow.querySelector('.color-select');
        const sizeSelect = productRow.querySelector('.size-select');
        const stockStatus = productRow.querySelector('.stock-status');
        const btnIncrease = productRow.querySelector('.btn-increase');
        const qtyInput = productRow.querySelector('.qty-input');
        
        let currentColor = colorSelect.value; // Giá trị hiện tại trong HTML (có thể là rỗng hoặc 'Chọn màu')

        // 1. Xử lý Màu
        const uniqueColors = [...new Set(variants.map(v => v.mau_sac))];
        if (uniqueColors.length > 0) {
            // Nếu chưa chọn màu (hoặc màu không hợp lệ), chọn màu đầu tiên làm mặc định
            if (!uniqueColors.includes(currentColor) || currentColor === '' || currentColor === 'Chọn màu') {
                currentColor = uniqueColors[0];
            }
            
            colorSelect.innerHTML = uniqueColors.map(c => 
                `<option value="${c}" ${c === currentColor ? 'selected' : ''}>${c}</option>`
            ).join('');
        }

        // 2. Xử lý Size
        const availableSizes = variants.filter(v => v.mau_sac === currentColor);
        let currentSize = sizeSelect.value; // Giá trị hiện tại

        // Tìm size mặc định hợp lệ đầu tiên nếu chưa có size
        const firstValidSize = availableSizes.length > 0 ? availableSizes[0].kich_co : '';
        
        // Kiểm tra xem size hiện tại có nằm trong danh sách mới không, nếu không thì lấy cái đầu tiên
        const isSizeValid = availableSizes.some(v => v.kich_co === currentSize);
        if (!isSizeValid || currentSize === '' || currentSize === 'Chọn size') {
            currentSize = firstValidSize;
        }

        if (availableSizes.length > 0) {
            sizeSelect.innerHTML = availableSizes.map(v => {
                const isOutOfStock = v.so_luong_ton <= 0;
                const stockText = isOutOfStock ? ' (Hết)' : '';
                const disabledAttr = isOutOfStock ? 'disabled' : '';
                const selectedAttr = v.kich_co === currentSize ? 'selected' : '';
                return `<option value="${v.kich_co}" ${disabledAttr} ${selectedAttr}>${v.kich_co}${stockText}</option>`;
            }).join('');
        } else {
            sizeSelect.innerHTML = '<option>Hết hàng</option>';
        }

        // 3. Kiểm tra Tồn kho
        const currentVariant = variants.find(v => v.mau_sac === currentColor && v.kich_co === currentSize);
        
        if (currentVariant) {
            const currentQty = parseInt(qtyInput.value);
            if (currentVariant.so_luong_ton <= 0) {
                stockStatus.innerText = "Tạm hết hàng";
                stockStatus.className = "stock-status text-xs text-red-500 font-bold";
                btnIncrease.disabled = true;
            } else if (currentVariant.so_luong_ton < currentQty) {
                stockStatus.innerText = `Chỉ còn ${currentVariant.so_luong_ton}`;
                stockStatus.className = "stock-status text-xs text-orange-500";
                btnIncrease.disabled = true;
            } else {
                stockStatus.innerText = `Còn hàng: ${currentVariant.so_luong_ton}`;
                stockStatus.className = "stock-status text-xs text-green-600";
                btnIncrease.disabled = false;
            }
        }

        // Trả về giá trị mặc định để hàm init cập nhật server
        return { color: currentColor, size: currentSize };
    }

    // ============================================================
    // 3. GÁN SỰ KIỆN
    // ============================================================

    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('color-select')) {
            const productRow = e.target.closest('.product-item');
            const variants = JSON.parse(productRow.dataset.variants);
            const id = productRow.dataset.id;
            
            // Khi đổi màu, vẽ lại size và lấy size mới nhất
            const defaults = renderOptions(productRow, variants);
            
            // Cập nhật Session (reload trang để thấy giá mới nếu có)
            updateCartItem(id, null, defaults.size, defaults.color, true);
        }

        if (e.target.classList.contains('size-select')) {
            const productRow = e.target.closest('.product-item');
            const id = productRow.dataset.id;
            const newSize = e.target.value;
            // Cập nhật Session
            updateCartItem(id, null, newSize, null, true);
        }
    });

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-increase')) {
            const id = e.target.dataset.id;
            const input = e.target.parentElement.querySelector('.qty-input');
            updateCartItem(id, parseInt(input.value) + 1);
        }
        if (e.target.classList.contains('btn-decrease')) {
            const id = e.target.dataset.id;
            const input = e.target.parentElement.querySelector('.qty-input');
            const newQty = parseInt(input.value) - 1;
            if (newQty >= 1) updateCartItem(id, newQty);
            else removeCartItem(id);
        }
        if (e.target.closest('.btn-remove')) {
            const id = e.target.closest('.btn-remove').dataset.id;
            removeCartItem(id);
        }
    });

    initVariants();
});