<?php
// admin/product_detail.php

// 1. KHỞI ĐỘNG SESSION & KẾT NỐI
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../public/connect.php'; // $conn

// 2. CHECK QUYỀN ADMIN
$user_role = $_SESSION['user_role'] ?? '';
if ($user_role !== 'QuanTriVien') {
    header('Location: /Shop_Ban_Quan_Ao_4P/login.php');
    exit;
}

// 3. LẤY ID SẢN PHẨM (ƯU TIÊN TỪ POST ĐỂ REDIRECT SAU KHI SUBMIT)
$productId = (int)($_POST['san_pham_id'] ?? ($_GET['id'] ?? 0));
if ($productId <= 0) {
    header('Location: san-pham.php');
    exit;
}

// 4. XỬ LÝ THÊM / SỬA / XÓA BIẾN THỂ
$variantFlash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['variant_action'] ?? '';
    $variantId = (int)($_POST['variant_id'] ?? 0);

    try {
        if ($action === 'create') {
            // THÊM BIẾN THỂ MỚI
            $sku   = trim($_POST['sku'] ?? '');
            $mau   = trim($_POST['mau_sac'] ?? '');
            $size  = trim($_POST['kich_co'] ?? '');
            $giaGoc = (float)($_POST['gia_goc'] ?? 0);
            $giaBan = (float)($_POST['gia_ban'] ?? 0);
            $ton   = (int)($_POST['so_luong_ton'] ?? 0);
            $img   = trim($_POST['hinh_anh_dai_dien'] ?? '');

            if ($mau === '' || $size === '' || $giaBan <= 0) {
                throw new Exception('Vui lòng nhập đầy đủ Màu, Size và Giá bán.');
            }

            $stmt = $conn->prepare("
                INSERT INTO bien_the_san_pham 
                (san_pham_id, sku, mau_sac, kich_co, gia_goc, gia_ban, so_luong_ton, hinh_anh_dai_dien)
                VALUES (:sp, :sku, :mau, :size, :goc, :ban, :ton, :img)
            ");
            $stmt->execute([
                ':sp'  => $productId,
                ':sku' => $sku ?: null,
                ':mau' => $mau,
                ':size'=> $size,
                ':goc' => $giaGoc ?: $giaBan,
                ':ban' => $giaBan,
                ':ton' => $ton,
                ':img' => $img ?: null,
            ]);

            $variantFlash = ['ok' => true, 'msg' => 'Đã thêm biến thể mới.'];

        } elseif ($action === 'update' && $variantId > 0) {
            // CẬP NHẬT BIẾN THỂ
            $sku   = trim($_POST['sku'] ?? '');
            $mau   = trim($_POST['mau_sac'] ?? '');
            $size  = trim($_POST['kich_co'] ?? '');
            $giaGoc = (float)($_POST['gia_goc'] ?? 0);
            $giaBan = (float)($_POST['gia_ban'] ?? 0);
            $ton   = (int)($_POST['so_luong_ton'] ?? 0);
            $img   = trim($_POST['hinh_anh_dai_dien'] ?? '');

            if ($mau === '' || $size === '' || $giaBan <= 0) {
                throw new Exception('Vui lòng nhập đầy đủ Màu, Size và Giá bán khi cập nhật.');
            }

            $stmt = $conn->prepare("
                UPDATE bien_the_san_pham
                SET sku = :sku,
                    mau_sac = :mau,
                    kich_co = :size,
                    gia_goc = :goc,
                    gia_ban = :ban,
                    so_luong_ton = :ton,
                    hinh_anh_dai_dien = :img
                WHERE id = :id AND san_pham_id = :sp
            ");
            $stmt->execute([
                ':sku' => $sku ?: null,
                ':mau' => $mau,
                ':size'=> $size,
                ':goc' => $giaGoc ?: $giaBan,
                ':ban' => $giaBan,
                ':ton' => $ton,
                ':img' => $img ?: null,
                ':id'  => $variantId,
                ':sp'  => $productId,
            ]);

            $variantFlash = ['ok' => true, 'msg' => 'Đã cập nhật biến thể.'];

        } elseif ($action === 'delete' && $variantId > 0) {
            // XÓA BIẾN THỂ
            $stmt = $conn->prepare("DELETE FROM bien_the_san_pham WHERE id = :id AND san_pham_id = :sp");
            $stmt->execute([':id' => $variantId, ':sp' => $productId]);
            $variantFlash = ['ok' => true, 'msg' => 'Đã xóa biến thể.'];
        }
    } catch (Exception $e) {
        $variantFlash = ['ok' => false, 'msg' => $e->getMessage()];
    }

    // Lưu flash & redirect để tránh F5 submit lại
    $_SESSION['variant_flash'] = $variantFlash;
    header('Location: product_detail.php?id=' . $productId);
    exit;
}

// Lấy flash từ session (nếu có)
if (isset($_SESSION['variant_flash'])) {
    $variantFlash = $_SESSION['variant_flash'];
    unset($_SESSION['variant_flash']);
}

// 5. TRUY VẤN DỮ LIỆU SẢN PHẨM + BIẾN THỂ
try {
    // A. Thông tin SẢN PHẨM
    $stmt = $conn->prepare("
        SELECT sp.*, dm.ten_danh_muc
        FROM san_pham sp
        LEFT JOIN danh_muc dm ON dm.id = sp.danh_muc_id
        WHERE sp.id = :id
    ");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch();

    if (!$product) {
        die("Sản phẩm không tồn tại hoặc đã bị xóa.");
    }

    // B. Danh sách BIẾN THỂ
    $stmt_variants = $conn->prepare("
        SELECT * FROM bien_the_san_pham 
        WHERE san_pham_id = :id 
        ORDER BY mau_sac, kich_co
    ");
    $stmt_variants->execute([':id' => $productId]);
    $variants = $stmt_variants->fetchAll();

    // C. Ảnh đại diện
    $mainImage = '/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg';
    if (!empty($product['hinh_anh_chinh'])) {
        $imgUrl    = str_replace('../', '', $product['hinh_anh_chinh']);
        $mainImage = '/Shop_Ban_Quan_Ao_4P/' . ltrim($imgUrl, '/');
    } elseif (!empty($variants) && !empty($variants[0]['hinh_anh_dai_dien'])) {
        $imgUrl    = str_replace('../', '', $variants[0]['hinh_anh_dai_dien']);
        $mainImage = '/Shop_Ban_Quan_Ao_4P/' . ltrim($imgUrl, '/');
    }

    // D. Tính tổng tồn & khoảng giá
    $totalStock = 0;
    $prices     = [];

    foreach ($variants as $v) {
        $totalStock += (int)$v['so_luong_ton'];
        if ($v['gia_ban'] > 0) {
            $prices[] = (float)$v['gia_ban'];
        }
    }

    $minPrice = !empty($prices) ? min($prices) : 0;
    $maxPrice = !empty($prices) ? max($prices) : 0;

} catch (Exception $e) {
    die("Lỗi hệ thống: " . $e->getMessage());
}

// Hàm định dạng tiền
if (!function_exists('money_vn')) {
    function money_vn($n) { return number_format((float)$n, 0, ',', '.'); }
}

// Header
$page_title = 'Chi tiết: ' . $product['ten_san_pham'];
$active     = 'products';
include __DIR__.'/partials/header.php';
?>

<style>
    .glass { background: rgba(255, 255, 255, 0.95); }
    .fade-in { animation: fade .5s ease both; }
    @keyframes fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
    .color-dot {
        display:inline-block;width:12px;height:12px;border-radius:50%;
        border:1px solid #ddd;margin-right:5px;vertical-align:middle;
    }
</style>

<main class="flex-1 overflow-y-auto relative z-10 bg-slate-50/50">
    <!-- HEADER -->
    <header class="sticky top-0 z-20 glass border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="san-pham.php" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition" title="Quay lại">
                    <i class="fa-solid fa-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Chi tiết sản phẩm</h1>
                    <div class="text-sm text-gray-500">Quản lý thông tin & kho hàng</div>
                </div>
            </div>
            <div class="flex gap-2">
                <button
                    type="button"
                    onclick="startAddVariant()"
                    class="px-4 py-2 text-sm font-medium text-white bg-[#c72002] hover:bg-[#9a1902] rounded-lg transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm biến thể
                </button>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">

        <?php if ($variantFlash): ?>
            <div class="mb-4 p-4 rounded-xl border flex items-center gap-3
                <?= $variantFlash['ok'] ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-red-50 border-red-200 text-red-700' ?>">
                <?php if($variantFlash['ok']): ?>
                    <i class="fa-solid fa-circle-check"></i>
                <?php else: ?>
                    <i class="fa-solid fa-triangle-exclamation"></i>
                <?php endif; ?>
                <span><?= htmlspecialchars($variantFlash['msg']) ?></span>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- ẢNH & TRẠNG THÁI -->
            <div class="lg:col-span-4 fade-in">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col items-center justify-center relative">
                    <div class="absolute top-4 right-4">
                        <?php if ($product['trang_thai'] == 'DangBan'): ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Đang bán</span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Ngừng bán</span>
                        <?php endif; ?>
                    </div>

                    <img src="<?=htmlspecialchars($mainImage)?>"
                         alt="<?=htmlspecialchars($product['ten_san_pham'])?>"
                         class="w-full max-h-[400px] object-contain rounded-xl"
                         onerror="this.src='/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg'">
                </div>
            </div>

            <!-- THÔNG TIN SẢN PHẨM -->
            <div class="lg:col-span-8 space-y-6 fade-in" style="animation-delay: 0.1s">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl"></div>

                    <div class="border-b border-gray-100 pb-6 mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                                <i class="fa-solid fa-layer-group mr-1"></i>
                                <?=htmlspecialchars($product['ten_danh_muc'] ?? 'Chưa phân loại')?>
                            </span>
                            <span class="text-gray-400 text-sm font-mono">#ID: <?=$product['id']?></span>
                        </div>

                        <h2 class="text-3xl font-extrabold text-gray-900 leading-tight mb-2">
                            <?=htmlspecialchars($product['ten_san_pham'])?>
                        </h2>

                        <div class="flex items-center gap-2 text-gray-500 text-sm">
                            <i class="fa-solid fa-globe"></i> Slug:
                            <span class="font-mono bg-gray-100 px-2 rounded text-xs"><?=$product['slug']?></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Khoảng giá bán</p>
                            <?php if ($minPrice > 0): ?>
                                <p class="text-2xl font-black text-[#c72002]">
                                    <?=money_vn($minPrice)?><?php if($minPrice != $maxPrice) echo ' - ' . money_vn($maxPrice); ?>đ
                                </p>
                            <?php else: ?>
                                <p class="text-lg font-bold text-gray-400">Chưa thiết lập giá</p>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 bg-orange-50 rounded-xl border border-orange-100">
                            <p class="text-sm text-orange-600 mb-1">Tổng tồn kho</p>
                            <p class="text-2xl font-black text-orange-700"><?=number_format($totalStock)?></p>
                        </div>
                    </div>

                    <div class="prose text-gray-600 text-sm leading-relaxed">
                        <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-[#c72002]"></i> Mô tả sản phẩm
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 whitespace-pre-line">
                            <?= !empty($product['mo_ta']) ? $product['mo_ta'] : 'Chưa có mô tả cho sản phẩm này.' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH BIẾN THỂ -->
        <div class="fade-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-sitemap text-[#c72002]"></i>
                    Danh sách Biến thể (Màu / Size)
                </h3>
                <span class="text-sm font-medium bg-white px-4 py-1.5 rounded-full border shadow-sm text-gray-600">
                    Số lượng biến thể: <strong><?=count($variants)?></strong>
                </span>
            </div>

            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200">
                <?php if ($variants): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold w-20">Ảnh</th>
                                <th class="px-6 py-4 font-semibold">SKU</th>
                                <th class="px-6 py-4 font-semibold">Màu sắc</th>
                                <th class="px-6 py-4 font-semibold">Kích cỡ</th>
                                <th class="px-6 py-4 font-semibold text-right">Giá gốc</th>
                                <th class="px-6 py-4 font-semibold text-right">Giá bán</th>
                                <th class="px-6 py-4 font-semibold text-center">Tồn kho</th>
                                <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                                <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($variants as $v):
                                $imgVar    = str_replace('../', '', $v['hinh_anh_dai_dien'] ?? '');
                                $imgVarSrc = !empty($imgVar) ? '/Shop_Ban_Quan_Ao_4P/' . ltrim($imgVar, '/') : '/Shop_Ban_Quan_Ao_4P/assets/img/no-image.jpg';
                                $isOutOfStock = $v['so_luong_ton'] <= 0;

                                $colorHex = match($v['mau_sac']) {
                                    'Đen'  => '#000',
                                    'Trắng'=> '#fff',
                                    'Xám'  => '#888',
                                    'Xanh' => 'blue',
                                    'Đỏ'   => 'red',
                                    'Vàng' => 'yellow',
                                    default=> '#eee'
                                };

                                $jsData = htmlspecialchars(json_encode([
                                    'id'            => $v['id'],
                                    'sku'           => $v['sku'],
                                    'mau_sac'       => $v['mau_sac'],
                                    'kich_co'       => $v['kich_co'],
                                    'gia_goc'       => $v['gia_goc'],
                                    'gia_ban'       => $v['gia_ban'],
                                    'so_luong_ton'  => $v['so_luong_ton'],
                                    'hinh_anh_dai_dien' => $v['hinh_anh_dai_dien'],
                                ], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="hover:bg-orange-50/30 transition group">
                                <td class="px-6 py-3">
                                    <img src="<?=$imgVarSrc?>" class="w-10 h-12 object-cover rounded border border-gray-200" alt="Var">
                                </td>
                                <td class="px-6 py-3 font-mono text-xs text-gray-500">
                                    <?=htmlspecialchars($v['sku'] ?? '---')?>
                                </td>
                                <td class="px-6 py-3 font-medium text-gray-800">
                                    <div class="flex items-center gap-2">
                                        <span class="color-dot" style="background-color: <?=$colorHex?>"></span>
                                        <?=htmlspecialchars($v['mau_sac'])?>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold text-gray-600 border border-gray-200">
                                        <?=htmlspecialchars($v['kich_co'])?>
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right text-gray-400 line-through text-sm">
                                    <?=money_vn($v['gia_goc'])?>đ
                                </td>
                                <td class="px-6 py-3 text-right font-bold text-[#c72002]">
                                    <?=money_vn($v['gia_ban'])?>đ
                                </td>
                                <td class="px-6 py-3 text-center font-bold text-gray-700">
                                    <?=number_format($v['so_luong_ton'])?>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <?php if ($isOutOfStock): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            Hết hàng
                                        </span>
                                    <?php elseif ($v['so_luong_ton'] < 10): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            Sắp hết
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            Sẵn sàng
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                                onclick="startEditVariant(<?=$jsData?>)"
                                                class="px-2.5 py-1 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                            Sửa
                                        </button>
                                        <form method="POST" onsubmit="return confirm('Xóa biến thể này?');">
                                            <input type="hidden" name="san_pham_id" value="<?=$productId?>">
                                            <input type="hidden" name="variant_action" value="delete">
                                            <input type="hidden" name="variant_id" value="<?=$v['id']?>">
                                            <button type="submit"
                                                    class="px-2.5 py-1 text-xs bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="p-12 text-center text-gray-400 flex flex-col items-center">
                        <i class="fa-solid fa-box-open text-4xl mb-3 opacity-30"></i>
                        <p class="mb-2">Sản phẩm này chưa có biến thể (Màu/Size).</p>
                        <p class="text-sm text-gray-400">Vui lòng thêm biến thể để bắt đầu bán hàng.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- FORM THÊM / SỬA BIẾN THỂ -->
        <div id="variantFormWrapper" class="fade-in" style="animation-delay: 0.3s">
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="variantFormTitle" class="text-lg font-bold text-gray-800">
                        Thêm biến thể mới
                    </h3>
                </div>

                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <input type="hidden" name="san_pham_id" value="<?=$productId?>">
                    <input type="hidden" name="variant_action" id="variant_action" value="create">
                    <input type="hidden" name="variant_id" id="variant_id" value="">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU (tùy chọn)</label>
                        <input type="text" name="sku" id="field_sku"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Màu sắc</label>
                        <input type="text" name="mau_sac" id="field_mau_sac" required
                               placeholder="VD: Đen, Trắng, Xám..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kích cỡ</label>
                        <input type="text" name="kich_co" id="field_kich_co" required
                               placeholder="VD: S, M, L, XL..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá gốc (₫)</label>
                        <input type="number" min="0" step="1000" name="gia_goc" id="field_gia_goc"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giá bán (₫) <span class="text-red-500">*</span></label>
                        <input type="number" min="0" step="1000" name="gia_ban" id="field_gia_ban" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng tồn</label>
                        <input type="number" min="0" step="1" name="so_luong_ton" id="field_so_luong_ton"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            URL ảnh đại diện (VD: assets/img/ao1-m-den-s.jpg)
                        </label>
                        <input type="text" name="hinh_anh_dai_dien" id="field_hinh_anh_dai_dien"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-[#c72002]">
                        <p class="text-xs text-gray-400 mt-1">
                            Nếu để trống sẽ dùng ảnh mặc định hoặc ảnh sản phẩm cha.
                        </p>
                    </div>

                    <div class="md:col-span-2 lg:col-span-3 flex justify-end gap-3 pt-2">
                        <button type="button"
                                onclick="startAddVariant()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                            Làm mới form
                        </button>
                        <button type="submit"
                                id="variantSubmitBtn"
                                class="px-5 py-2 bg-[#c72002] text-white rounded-lg hover:bg-[#9a1902] font-medium">
                            Lưu biến thể
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

<script>
    function scrollToVariantForm() {
        const formWrap = document.getElementById('variantFormWrapper');
        if (formWrap) {
            formWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function startAddVariant() {
        document.getElementById('variant_action').value = 'create';
        document.getElementById('variant_id').value = '';
        document.getElementById('variantFormTitle').innerText = 'Thêm biến thể mới';
        document.getElementById('variantSubmitBtn').innerText = 'Lưu biến thể';

        document.getElementById('field_sku').value = '';
        document.getElementById('field_mau_sac').value = '';
        document.getElementById('field_kich_co').value = '';
        document.getElementById('field_gia_goc').value = '';
        document.getElementById('field_gia_ban').value = '';
        document.getElementById('field_so_luong_ton').value = '';
        document.getElementById('field_hinh_anh_dai_dien').value = '';

        scrollToVariantForm();
        document.getElementById('field_mau_sac').focus();
    }

    function startEditVariant(data) {
        document.getElementById('variant_action').value = 'update';
        document.getElementById('variant_id').value = data.id;
        document.getElementById('variantFormTitle').innerText = 'Cập nhật biến thể';
        document.getElementById('variantSubmitBtn').innerText = 'Cập nhật biến thể';

        document.getElementById('field_sku').value = data.sku ?? '';
        document.getElementById('field_mau_sac').value = data.mau_sac ?? '';
        document.getElementById('field_kich_co').value = data.kich_co ?? '';
        document.getElementById('field_gia_goc').value = data.gia_goc ?? '';
        document.getElementById('field_gia_ban').value = data.gia_ban ?? '';
        document.getElementById('field_so_luong_ton').value = data.so_luong_ton ?? '';
        document.getElementById('field_hinh_anh_dai_dien').value = data.hinh_anh_dai_dien ?? '';

        scrollToVariantForm();
    }
</script>

</div>
</body>
</html>
