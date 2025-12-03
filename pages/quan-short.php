<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4P / Quần Short Nam</p>
    </div>
  </section>

  <!-- Filter & Sort -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc danh mục:</span>
        <select id="filter-type">
          <option value="all">Tất cả short</option>
          <option value="kaki">Short Kaki</option>
          <option value="jean">Short Jean</option>
          <option value="thun">Short Thun</option>
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

  <!-- Product section: Quần Short -->
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
    const PAGE_CATEGORIES = 'quan-short'; 
</script>
</body>
</html>
