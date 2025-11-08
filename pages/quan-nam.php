<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Nam</p>
    </div>
  </section>

  <!-- Filter & Sort -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Bộ lọc:</span>
        <select id="filter-type">
          <option value="quan">Tất cả quần</option>
          <option value="quan-tay">Quần Tây</option>
          <option value="quan-kaki">Quần Kaki</option>
          <option value="quan-jean">Quần Jean</option>
          <option value="quan-short">Quần Short</option>
        </select>
      </div>
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="price-asc">Giá tăng dần</option>
          <option value="price-desc">Giá giảm dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Danh sách sản phẩm Quần Nam -->
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
    const PAGE_CATEGORIES = 'quan'; 
</script>
</body>
</html>
