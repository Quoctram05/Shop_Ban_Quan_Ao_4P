<?php 
include('../header.php'); 
include('../navbar.php'); 
include('../public/connect.php');
?>
  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Hoodie & Sweatshirt Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="hoodie">Hoodie</option>
          <option value="sweatshirt">Sweatshirt</option>
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

  <!-- Danh sách sản phẩm Hoodie & Sweatshirt -->
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
  const PAGE_CATEGORIES = ['ao-hoodie', 'ao-sweatshirt'];
</script>

</body>
</html>
