<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4P / Thắt Lưng Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc danh mục:</span>
        <select id="filter-type">
          <option value="all">Tất cả thắt lưng</option>
          <option value="khoa-tu-dong">Khóa tự động</option>
          <option value="khoa-kim">Khóa kim</option>
          <option value="da-tron">Da trơn</option>
          <option value="da-ghep">Da ghép / phối</option>
        </select>
      </div>
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="views">Xem nhiều</option>
          <option value="price-asc">Giá tăng dần</option>
          <option value="price-desc">Giá giảm dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Sản phẩm Thắt Lưng -->
    <section class="product-section section">
    <div class="container">
    
      <div class="product-grid" id="product-grid">
        <p>Đang tải sản phẩm, vui lòng chờ...</p>
      </div>

      <div class="pagination" id="pagination-controls">
        </div>

    </div>
  </section>

<?php include('../footer.php'); ?>
<script>
    const PAGE_CATEGORIES = 'that-lung'; 
</script>
</body>
</html>
