<?php
session_start();
require_once('../public/connect.php');

// 1. KIỂM TRA REQUEST & GIỎ HÀNG
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Truy cập không hợp lệ.");
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Giỏ hàng trống.");
}

// 2. LẤY DỮ LIỆU TỪ FORM
$ho_ten = $_POST['fullname'] ?? 'Khách lẻ';
$so_dien_thoai = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$dia_chi = $_POST['address'] ?? '';
$ghi_chu = $_POST['note'] ?? '';
$phuong_thuc = $_POST['payment_method'] ?? 'cod';

// Lấy ID người dùng (nếu đã đăng nhập)
$nguoi_dung_id = $_SESSION['user_id'] ?? null;

// Tính tổng tiền
$tong_tien = 0;
foreach ($_SESSION['cart'] as $item) {
    $qty = $item['qty'] ?? 1;
    $price = $item['price'] ?? 0;
    $tong_tien += $qty * $price;
}

// Phí vận chuyển
$shipping_fee = ($tong_tien >= 300000) ? 0 : 30000;
$final_total = $tong_tien + $shipping_fee;

// === BẮT ĐẦU TRANSACTION (GIAO DỊCH) ===
try {
    $conn->beginTransaction();

    // 3. INSERT VÀO BẢNG DON_HANG
    $sql_order = "INSERT INTO don_hang (ho_ten, nguoi_dung_id, email, dia_chi, ghi_chu, so_dien_thoai, ngay_dat, tong_tien, trang_thai, phuong_thuc_thanh_toan) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, 'ChoXuLy', ?)";
    
    $stmt = $conn->prepare($sql_order);
    $stmt->execute([
        $ho_ten, 
        $nguoi_dung_id, 
        $email, 
        $dia_chi, 
        $ghi_chu, 
        $so_dien_thoai, 
        $final_total, 
        $phuong_thuc
    ]);

    // Lấy ID đơn hàng vừa tạo
    $don_hang_id = $conn->lastInsertId();


    // 4. INSERT CHI TIẾT & TRỪ TỒN KHO
    $sql_detail = "INSERT INTO chi_tiet_don_hang (don_hang_id, san_pham_id, ten_san_pham, bien_the_id, so_luong, don_gia) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);

    // Chuẩn bị câu lệnh tìm biến thể ID (Dựa trên Product ID, Màu, Size)
    // Lưu ý: tên cột mau_sac và kich_co phải khớp trong DB
    $sql_find_variant = "SELECT id, so_luong_ton FROM bien_the_san_pham WHERE san_pham_id = ? AND mau_sac = ? AND kich_co = ?";
    $stmt_find_variant = $conn->prepare($sql_find_variant);

    // Chuẩn bị câu lệnh trừ tồn kho
    $sql_update_stock = "UPDATE bien_the_san_pham SET so_luong_ton = so_luong_ton - ? WHERE id = ?";
    $stmt_stock = $conn->prepare($sql_update_stock);


    foreach ($_SESSION['cart'] as $item) {
        $san_pham_id = $item['id'];
        $ten_san_pham = $item['name'];
        $so_luong = $item['qty'] ?? 1;
        $don_gia = $item['price'];
        
        // Lấy màu và size từ session (nếu không có thì lấy chuỗi rỗng hoặc mặc định)
        $mau_sac = $item['color'] ?? '';
        $kich_co = $item['size'] ?? '';
        
        $bien_the_id = null;

        // Logic tìm và trừ tồn kho
        if ($mau_sac && $kich_co) {
            $stmt_find_variant->execute([$san_pham_id, $mau_sac, $kich_co]);
            $variant = $stmt_find_variant->fetch();
            
            if ($variant) {
                $bien_the_id = $variant['id'];
                $ton_kho_hien_tai = $variant['so_luong_ton'];

                // Kiểm tra đủ hàng không
                if ($ton_kho_hien_tai < $so_luong) {
                    throw new Exception("Sản phẩm '$ten_san_pham' ($mau_sac - $kich_co) chỉ còn $ton_kho_hien_tai cái, không đủ để bán!");
                }

                // TRỪ TỒN KHO
                $stmt_stock->execute([$so_luong, $bien_the_id]);
            } else {
                // Trường hợp không tìm thấy biến thể khớp màu/size trong DB (có thể do dữ liệu cũ)
                // Vẫn cho phép đặt hàng nhưng bien_the_id sẽ là NULL
            }
        }

        // Lưu chi tiết đơn hàng
        $stmt_detail->execute([
            $don_hang_id,
            $san_pham_id,
            $ten_san_pham,
            $bien_the_id,
            $so_luong,
            $don_gia
        ]);
    }

    // === LƯU THÀNH CÔNG (COMMIT) ===
    $conn->commit();
    unset($_SESSION['cart']);
    
    session_write_close();
// 5. CHUYỂN HƯỚNG TRANG (LOGIC MỚI)
    if ($phuong_thuc == 'momo') {
        // Chuyển sang trang giả lập MoMo
        header("Location: ../pages/momo-gateway.php?order_id=$don_hang_id");
    } 
    elseif ($phuong_thuc == 'bank') {
        // Chuyển sang trang QR Ngân hàng
        header("Location: ../pages/cong-thanh-toan.php?order_id=$don_hang_id");
    } 
    else {
        // COD (Tiền mặt)
        header("Location: ../pages/dat-hang-thanh-cong.php?id=$don_hang_id");
    }
    exit();

} catch (Exception $e) {
    // === BẮT LỖI (CATCH) ===
    if ($conn->inTransaction()) {
        $conn->rollBack(); // Hủy toàn bộ thao tác
    }
    
    die("<h1>Lỗi đặt hàng:</h1><p>" . $e->getMessage() . "</p><a href='../pages/thanhtoan.php'>Quay lại thanh toán</a>");
}
?>