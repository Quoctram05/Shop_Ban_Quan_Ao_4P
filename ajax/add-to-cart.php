<?php
session_start();
require_once __DIR__ . '/../public/connect.php';

header('Content-Type: text/html; charset=utf-8');

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$bienTheId = (int)($_POST['bien_the_id'] ?? 0);
$sanPhamId = (int)($_POST['san_pham_id'] ?? 0);
$soLuong   = max(1, (int)($_POST['so_luong'] ?? 1));

if ($bienTheId <= 0) {
    echo '<p style="padding:16px;">Thiếu biến thể sản phẩm.</p>';
    exit;
}

function format_vnd($n) {
    return number_format((float)$n, 0, ',', '.') . 'đ';
}

function asset_path($path) {
    $clean = $path ? str_replace('../', '', $path) : 'assets/img/no-image.jpg';
    return '/Shop_Ban_Quan_Ao_4P/' . ltrim($clean, '/');
}

// Lấy thông tin biến thể + sản phẩm
$stmt = $conn->prepare("
    SELECT b.id AS bien_the_id,
           b.san_pham_id,
           b.mau_sac,
           b.kich_co,
           b.gia_ban,
           b.hinh_anh_dai_dien,
           sp.ten_san_pham
    FROM bien_the_san_pham b
    JOIN san_pham sp ON sp.id = b.san_pham_id
    WHERE b.id = ?
    LIMIT 1
");
$stmt->execute([$bienTheId]);
$row = $stmt->fetch();

if (!$row) {
    echo '<p style="padding:16px;">Không tìm thấy sản phẩm.</p>';
    exit;
}

$cartKey = $row['bien_the_id'];

if (isset($_SESSION['cart'][$cartKey])) {
    $_SESSION['cart'][$cartKey]['so_luong'] += $soLuong;
    $_SESSION['cart'][$cartKey]['qty']      += $soLuong;
} else {
    $_SESSION['cart'][$cartKey] = [
        'bien_the_id'  => $row['bien_the_id'],
        'san_pham_id'  => $row['san_pham_id'],
        'ten_san_pham' => $row['ten_san_pham'],
        'mau_sac'      => $row['mau_sac'],
        'kich_co'      => $row['kich_co'],
        'gia_ban'      => (float)$row['gia_ban'],
        'so_luong'     => $soLuong,
        'hinh_anh'     => $row['hinh_anh_dai_dien'],
        // giữ thêm field cũ để không phá api/cart.php
        'id'           => $row['bien_the_id'],
        'name'         => $row['ten_san_pham'],
        'price'        => (float)$row['gia_ban'],
        'image'        => $row['hinh_anh_dai_dien'],
        'qty'          => $soLuong,
        'size'         => $row['kich_co'],
        'color'        => $row['mau_sac'],
    ];
}

$totalQty = 0;
$totalPrice = 0;
$htmlItems = '';

foreach ($_SESSION['cart'] as $item) {
    $qty = (int)$item['so_luong'];
    $price = (float)$item['gia_ban'];
    $totalQty += $qty;
    $totalPrice += $qty * $price;

    $imgSrc = asset_path($item['hinh_anh'] ?? '');

    $htmlItems .= '
        <div class="mini-cart-item">
            <img src="'.htmlspecialchars($imgSrc).'" alt="'.htmlspecialchars($item['ten_san_pham']).'">
            <div class="item-info">
                <h4>'.htmlspecialchars($item['ten_san_pham']).'</h4>
                <p style="font-size:12px;color:#666;margin:2px 0;">'.htmlspecialchars($item['mau_sac']).' / '.htmlspecialchars($item['kich_co']).'</p>
                <p>'.$qty.' x <span class="price">'.format_vnd($price).'</span></p>
            </div>
        </div>
    ';
}

if ($totalQty === 0) {
    echo '<p style="padding:20px; text-align:center;">Giỏ hàng trống</p>';
    exit;
}

echo '
    <div class="mini-cart-header" data-cart-count="'.(int)$totalQty.'">
        Có <span>'.$totalQty.'</span> <span class="highlight">sản phẩm</span>
    </div>
    <div class="mini-cart-list">
        '.$htmlItems.'
    </div>
    <div class="mini-cart-total">
        Tổng: '.format_vnd($totalPrice).'
    </div>
    <a href="/Shop_Ban_Quan_Ao_4P/pages/thanhtoan.php" class="btn-checkout-dark">
        Gửi đơn hàng
    </a>
';
