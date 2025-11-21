<?php
session_start();
require_once '../public/connect.php';

// Khởi tạo giỏ hàng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Nhận dữ liệu JSON
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

$response = [
    'status' => 'error',
    'message' => '',
    'cart_count' => 0,
    'cart_html' => '',
    'subtotal' => '0đ'
];

function formatMoney($amount) {
    return number_format($amount, 0, ',', '.') . 'đ';
}

// --- XỬ LÝ CÁC HÀNH ĐỘNG ---
if ($action == 'add') {
    $id = $data['id'];
    
    // Nếu sản phẩm đã có, tăng số lượng
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        // Thêm mới
        $_SESSION['cart'][$id] = [
            'id' => $data['id'],
            'name' => $data['name'],
            'price' => $data['price'],
            'image' => $data['image'],
            'qty' => 1,
            'size' => 'M', // Mặc định
            'color' => ''  // Mặc định
        ];
    }
    $response['status'] = 'success';
} 
elseif ($action == 'update') {
    // === ĐÂY LÀ PHẦN QUAN TRỌNG BẠN ĐANG THIẾU ===
    $id = $data['id'];
    
    if (isset($_SESSION['cart'][$id])) {
        // Cập nhật số lượng
        if (isset($data['qty'])) {
            $_SESSION['cart'][$id]['qty'] = intval($data['qty']);
        }
        
        // Cập nhật Size (QUAN TRỌNG)
        if (isset($data['size'])) {
            $_SESSION['cart'][$id]['size'] = $data['size'];
        }

        // Cập nhật Màu (QUAN TRỌNG)
        if (isset($data['color'])) {
            $_SESSION['cart'][$id]['color'] = $data['color'];
        }
    }
    $response['status'] = 'success';
}
elseif ($action == 'remove') {
    $id = $data['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    $response['status'] = 'success';
}

// --- TẠO HTML CHO SIDEBAR (Mini Cart) ---
$totalQty = 0;
$totalPrice = 0;
$html = '';

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $totalQty += $item['qty'];
        $totalPrice += $item['price'] * $item['qty'];
        
        // Hiển thị cả Size và Màu trong Mini Cart
        $variantInfo = "";
        if (!empty($item['size']) || !empty($item['color'])) {
            $variantInfo = "<p style='font-size: 12px; color: #666; margin: 0;'>" . 
                           ($item['color'] ?? '') . " / " . ($item['size'] ?? '') . 
                           "</p>";
        }

        $imgUrl = str_replace('../', '', $item['image']);

        $html .= '
        <div class="mini-cart-item">
            <img src="/SHOP_BAN_QUAN_AO_4P/'.$imgUrl.'" alt="'.$item['name'].'">
            <div class="item-info">
                <h4>'.$item['name'].'</h4>
                '.$variantInfo.'
                <p>'.$item['qty'].' x <span class="price">'.formatMoney($item['price']).'</span></p>
            </div>
            <button class="remove-btn" onclick="removeFromCart('.$item['id'].')"><i class="fa-solid fa-xmark"></i></button>
        </div>';
    }
    
    // HTML đầy đủ cho dropdown
    $finalHtml = '
    <div class="mini-cart-header">
        Có <span>'.$totalQty.'</span> <span class="highlight">Sản phẩm</span>
    </div>
    <div class="mini-cart-list">
        '.$html.'
    </div>
    <div class="mini-cart-total">
        Tổng: '.formatMoney($totalPrice).'
    </div>
    <a href="/SHOP_BAN_QUAN_AO_4P/pages/thanhtoan.php" class="btn-checkout-dark">Thanh toán</a>
    ';
    
    $response['cart_html'] = $finalHtml;

} else {
    $response['cart_html'] = '<p style="padding: 20px; text-align: center;">Giỏ hàng trống</p>';
}

$response['cart_count'] = $totalQty;
$response['subtotal'] = formatMoney($totalPrice);

echo json_encode($response);
?>