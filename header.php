<?php
// --- QUAN TRỌNG: KHỞI ĐỘNG SESSION NGAY ĐẦU FILE ---
// Nếu session chưa bắt đầu thì mới bắt đầu (để tránh lỗi "Session already started")
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Đặt base URL (đường dẫn gốc đến thư mục chính)
// Bạn có thể sửa '/SHOP_BAN_QUAN_AO_4P/' thành tên thư mục thật của bạn nếu khác
// $base_url = '/SHOP_BAN_QUAN_AO_4P/';
$base_url = '/Shop_Ban_Quan_Ao_4P/';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>4P | Thời trang nam</title>
  <link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
  <script defer src="<?php echo $base_url; ?>assets/js/script.js"></script>
  <script defer src="<?php echo $base_url; ?>assets/js/search.js"></script>
  <script src="<?php echo $base_url; ?>assets/js/product-list.js"></script>
</head>
<body>