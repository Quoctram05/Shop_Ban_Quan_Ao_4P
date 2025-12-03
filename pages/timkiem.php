<?php
include('../header.php'); 
include('../navbar.php');

// Lấy query tìm kiếm từ URL (q=...)
$q = $_GET['q'] ?? '';
$q = trim($q);
?>

<section class="breadcrumb">
    <div class="container">
      <p>4P / Tìm kiếm</p>
    </div>
</section>

<section class="filter-bar">
    <div class="container">
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mới nhất</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
</section>

<section class="product-section section">
    <div class="container">
        
        <h2 style="text-align:center; margin-bottom:30px;">
        Kết quả tìm kiếm cho: <span style="color:#b35d2a;"><?php echo htmlspecialchars($q); ?></span>
        </h2>
    
      <div class="product-grid" id="product-grid">
        <p>Đang tải sản phẩm, vui lòng chờ...</p>
      </div>

      <div class="pagination" id="pagination-controls">
      </div>

    </div>
</section>

<?php include('../footer.php'); ?>

<script>
    // 1. (KHÔNG cần PAGE_CATEGORIES)
    // 2. (KHÔNG cần IS_SALE_PAGE)
    
    // 3. Định nghĩa biến query tìm kiếm cho JS
    // (Dùng json_encode để xử lý các ký tự đặc biệt như ' " &)
    const PAGE_SEARCH_QUERY = <?php echo json_encode($q); ?>; 
</script>

<script src="../assets/js/product-list.js"></script>

</body>
</html>