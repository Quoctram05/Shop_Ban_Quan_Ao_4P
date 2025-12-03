<?php
// Nhúng file kết nối CSDL
require_once 'public/connect.php';

// Cấu hình thông tin Admin bạn muốn tạo
$fullname = "Quản Trị Viên";
$email    = "admin@gmail.com";     // Email đăng nhập
$password = "123456";            // Mật khẩu (Sẽ được mã hóa)
$phone    = "0909000999";
$role     = "QuanTriVien";         // Vai trò Admin

try {
    // 1. Kiểm tra xem email này đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM nguoi_dung WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        echo "Lỗi: Email <b>$email</b> đã tồn tại rồi!";
    } else {
        // 2. Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Chèn vào CSDL
        $sql = "INSERT INTO nguoi_dung (ho_ten, email, mat_khau, so_dien_thoai, vai_tro, ngay_tao) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fullname, $email, $hashed_password, $phone, $role]);

        echo "<h2 style='color:green'>Tạo Admin thành công!</h2>";
        echo "Email: <b>$email</b><br>";
        echo "Mật khẩu: <b>$password</b><br>";
        echo "<br><a href='login.php'>Đến trang đăng nhập ngay</a>";
    }

} catch (PDOException $e) {
    echo "Lỗi hệ thống: " . $e->getMessage();
}
?>