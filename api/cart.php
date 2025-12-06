<?php
// api/cart.php
// Xử lý giỏ hàng (add / update / remove) bằng SESSION và trả JSON

session_start();

// Luôn trả JSON
header('Content-Type: application/json; charset=utf-8');

// Chỉ cho phép POST (frontend gửi fetch POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Only POST allowed',
        'cart_count' => 0,
        'cart_html'  => '<p style="padding:20px; text-align:center;">Giỏ hàng trống</p>',
        'subtotal'   => '0đ'
    ]);
    exit;
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Đọc JSON từ body
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Dữ liệu gửi lên không hợp lệ',
        'cart_count' => 0,
        'cart_html'  => '<p style="padding:20px; text-align:center;">Giỏ hàng trống</p>',
        'subtotal'   => '0đ'
    ]);
    exit;
}

$action = $data['action'] ?? '';

$response = [
    'status'     => 'error',
    'message'    => '',
    'cart_count' => 0,
    'cart_html'  => '',
    'subtotal'   => '0đ',
];

function formatMoney($amount) {
    return number_format((float)$amount, 0, ',', '.') . 'đ';
}

/* ============ XỬ LÝ HÀNH ĐỘNG ============ */
if ($action === 'add') {
    $id    = $data['id']    ?? null;
    $name  = $data['name']  ?? '';
    $price = (float)($data['price'] ?? 0);
    $image = $data['image'] ?? '../assets/img/no-image.jpg';

    if (!$id) {
        $response['message'] = 'Thiếu ID sản phẩm';
    } else {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += 1;
        } else {
            $_SESSION['cart'][$id] = [
                'id'    => $id,
                'name'  => $name,
                'price' => $price,
                'image' => $image,
                'qty'   => 1,
                'size'  => $data['size']  ?? 'M',
                'color' => $data['color'] ?? '',
            ];
        }
        $response['status']  = 'success';
        $response['message'] = 'Đã thêm vào giỏ hàng';
    }

} elseif ($action === 'update') {
    $id = $data['id'] ?? null;

    if ($id && isset($_SESSION['cart'][$id])) {
        if (isset($data['qty'])) {
            $qty = max(1, (int)$data['qty']);
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
        if (isset($data['size'])) {
            $_SESSION['cart'][$id]['size'] = $data['size'];
        }
        if (isset($data['color'])) {
            $_SESSION['cart'][$id]['color'] = $data['color'];
        }

        $response['status']  = 'success';
        $response['message'] = 'Đã cập nhật giỏ hàng';
    } else {
        $response['message'] = 'Không tìm thấy sản phẩm trong giỏ';
    }

} elseif ($action === 'remove') {
    $id = $data['id'] ?? null;

    if ($id && isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
        $response['status']  = 'success';
        $response['message'] = 'Đã xóa sản phẩm khỏi giỏ';
    } else {
        $response['message'] = 'Không tìm thấy sản phẩm để xóa';
    }

} else {
    $response['message'] = 'Hành động không hợp lệ';
}

/* ============ TẠO HTML MINI CART ============ */
$totalQty   = 0;
$totalPrice = 0;
$htmlItems  = '';

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $qty   = (int)$item['qty'];
        $price = (float)$item['price'];

        $totalQty   += $qty;
        $totalPrice += $price * $qty;

        $variantInfo = '';
        if (!empty($item['size']) || !empty($item['color'])) {
            $variantInfo = "<p style='font-size:12px;color:#666;margin:0;'>"
                         . htmlspecialchars($item['color'] ?? '')
                         . " / "
                         . htmlspecialchars($item['size'] ?? '')
                         . "</p>";
        }

        // Chuẩn hóa đường dẫn ảnh: bỏ ../ rồi thêm prefix thư mục project
        $imgUrl = str_replace('../', '', $item['image']);
        $imgSrc = '/Shop_Ban_Quan_Ao_4P/' . ltrim($imgUrl, '/');

        $htmlItems .= '
        <div class="mini-cart-item">
            <img src="'.htmlspecialchars($imgSrc).'" alt="'.htmlspecialchars($item['name']).'">
            <div class="item-info">
                <h4>'.htmlspecialchars($item['name']).'</h4>
                '.$variantInfo.'
                <p>'.$qty.' x <span class="price">'.formatMoney($price).'</span></p>
            </div>
            <button class="remove-btn" onclick="removeFromCart('.(int)$item['id'].')">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>';
    }

    $response['cart_html'] = '
        <div class="mini-cart-header">
            Có <span>'.$totalQty.'</span> <span class="highlight">Sản phẩm</span>
        </div>
        <div class="mini-cart-list">
            '.$htmlItems.'
        </div>
        <div class="mini-cart-total">
            Tổng: '.formatMoney($totalPrice).'
        </div>
        <a href="/Shop_Ban_Quan_Ao_4P/pages/thanhtoan.php" class="btn-checkout-dark">
            Thanh toán
        </a>
    ';
} else {
    $response['cart_html'] = '<p style="padding:20px; text-align:center;">Giỏ hàng trống</p>';
}

$response['cart_count'] = $totalQty;
$response['subtotal']   = formatMoney($totalPrice);

echo json_encode($response);
