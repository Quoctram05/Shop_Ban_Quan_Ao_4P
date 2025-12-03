<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4P / Giày Dép Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc danh mục:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="giay">Giày Nam</option>
          <option value="sandal">Sandal Nam</option>
          <option value="dep">Dép Nam</option>
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

  <!-- SẢN PHẨM GIÀY DÉP NAM -->
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
  const PAGE_CATEGORIES = ['giay', 'dep'];
</script>
</body>
</html>
