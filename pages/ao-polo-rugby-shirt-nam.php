<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4P / Áo Polo & Rugby Shirt Nam</p>
    </div>
  </section>

  <!-- Filter & Sort -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="polo">Polo</option>
          <option value="rugby">Rugby Shirt</option>
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

  <!-- Danh sách sản phẩm -->
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
    const PAGE_CATEGORIES = ['ao-polo', 'ao-rugby-shirt'];
</script>

</body>
</html>
