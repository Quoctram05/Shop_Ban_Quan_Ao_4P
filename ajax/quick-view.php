<?php
session_start();
require_once __DIR__ . '/../public/connect.php';

$baseUrl = '/Shop_Ban_Quan_Ao_4P/';
header('Content-Type: text/html; charset=utf-8');

$sanPhamId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($sanPhamId <= 0) {
    echo '<p style="padding:20px;">Sản phẩm không hợp lệ.</p>';
    exit;
}

function money_vnd($n) {
    return number_format((float)$n, 0, ',', '.') . 'đ';
}

function asset_path($path, $base) {
    if (!$path) return $base . 'assets/img/no-image.jpg';
    $clean = str_replace('../', '', $path);
    return $base . ltrim($clean, '/');
}

// Lấy 1 biến thể mặc định kèm thông tin sản phẩm
$stmt = $conn->prepare("
    SELECT sp.id,
           sp.ten_san_pham,
           sp.mo_ta,
           sp.slug,
           b.id AS bien_the_id,
           b.mau_sac,
           b.kich_co,
           b.gia_goc,
           b.gia_ban,
           b.hinh_anh_dai_dien
    FROM san_pham sp
    JOIN bien_the_san_pham b ON b.san_pham_id = sp.id
    WHERE sp.id = ?
    ORDER BY b.id ASC
    LIMIT 1
");
$stmt->execute([$sanPhamId]);
$product = $stmt->fetch();

if (!$product) {
    echo '<p style="padding:20px;">Không tìm thấy sản phẩm.</p>';
    exit;
}

// Danh sách biến thể
$variantsStmt = $conn->prepare("
    SELECT id, mau_sac, kich_co, gia_goc, gia_ban
    FROM bien_the_san_pham
    WHERE san_pham_id = ?
    ORDER BY id
");
$variantsStmt->execute([$sanPhamId]);
$variants = $variantsStmt->fetchAll();

// Ảnh phụ
$galleryStmt = $conn->prepare("
    SELECT url_hinh_anh, alt_text
    FROM thu_vien_anh
    WHERE san_pham_id = ?
");
$galleryStmt->execute([$sanPhamId]);
$gallery = $galleryStmt->fetchAll();

$imageMain = asset_path($product['hinh_anh_dai_dien'] ?? '', $baseUrl);
$desc = trim(strip_tags($product['mo_ta'] ?? ''));
if (strlen($desc) > 260) {
    $desc = mb_substr($desc, 0, 260) . '...';
}

$detailLink = $baseUrl . 'pages/chi-tiet.php?slug=' . urlencode($product['slug']) . '&id=' . $product['id'];
?>

<div class="qv-inner">
    <div class="qv-left">
        <img src="<?php echo htmlspecialchars($imageMain); ?>" alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>">
        <?php if (!empty($gallery)): ?>
            <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
                <?php foreach ($gallery as $img): 
                    $src = asset_path($img['url_hinh_anh'], $baseUrl);
                ?>
                    <img src="<?php echo htmlspecialchars($src); ?>" alt="<?php echo htmlspecialchars($img['alt_text'] ?? ''); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="qv-right">
        <h2><?php echo htmlspecialchars($product['ten_san_pham']); ?></h2>
        <p class="qv-price">
            <span class="new-price"><?php echo money_vnd($product['gia_ban']); ?></span>
            <?php if ((float)$product['gia_goc'] > (float)$product['gia_ban']): ?>
                <span class="old-price"><?php echo money_vnd($product['gia_goc']); ?></span>
            <?php endif; ?>
        </p>
        <?php if ($desc): ?>
            <p class="qv-desc"><?php echo htmlspecialchars($desc); ?></p>
        <?php endif; ?>

        <form id="quick-view-add-cart-form">
            <input type="hidden" name="san_pham_id" value="<?php echo (int)$product['id']; ?>">
            
            <div class="qv-select">
                <label>Chọn màu / size:</label>
                <select name="bien_the_id" required>
                    <?php foreach ($variants as $v): ?>
                        <option value="<?php echo (int)$v['id']; ?>">
                            <?php echo htmlspecialchars($v['mau_sac']); ?> - <?php echo htmlspecialchars($v['kich_co']); ?> (<?php echo money_vnd($v['gia_ban']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="qv-qty">
                <label>Số lượng:</label>
                <input type="number" name="so_luong" min="1" value="1">
            </div>

            <div class="qv-actions">
                <button type="button" class="btn-red js-add-to-cart-from-modal">
                    + Thêm vào giỏ hàng
                </button>
                <a class="btn-blue" href="<?php echo htmlspecialchars($detailLink); ?>">
                    » Xem chi tiết
                </a>
            </div>
        </form>
    </div>
</div>
