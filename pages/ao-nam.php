<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>
  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Bộ lọc:</span>
        <select id="filter-type">
          <option value="ao">Tất cả loại áo</option>
          <option value="ao-polo">Polo</option>
          <option value="ao-thun">Thun</option>
          <option value="ao-so-mi">Sơ mi</option>
          <option value="ao-khoac">Áo Khoác</option>
          <option value="ao-hoodie">Hoodie</option>
          <option value="ao-len">Áo Len</option>
          <option value="ao-vest">Vest</option>
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

  <!-- Danh sách sản phẩm Áo Nam -->
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
    const PAGE_CATEGORIES = 'ao'; 
</script>

</body>
</html>
