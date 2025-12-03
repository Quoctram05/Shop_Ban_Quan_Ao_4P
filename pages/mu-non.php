<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4P / Nón Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại nón:</span>
        <select id="filter-type">
          <option value="all">Tất cả nón</option>
          <option value="luoi-trai">Nón lưỡi trai</option>
          <option value="snapback">Snapback</option>
          <option value="non-len">Nón len</option>
        </select>
      </div>
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="views">Xem nhiều</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Sản phẩm Nón Nam -->
  <section class="product-section section">
    <div class="container">
    
      <div class="product-grid" id="product-grid">
        <p>Đang tải sản phẩm, vui lòng chờ...</p>
      </div>

      <div class="pagination" id="pagination-controls">
        </div>
    </div>
  </section>

    </div>
  </section>
<?php include('../footer.php'); ?>
<script>
    const PAGE_CATEGORIES = 'non-mu'; 
</script>
</body>
</html>
