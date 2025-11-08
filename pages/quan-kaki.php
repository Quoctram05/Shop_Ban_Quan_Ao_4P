<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Kaki Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả kaki</option>
          <option value="plain">Kaki Trơn</option>
          <option value="diệu">Kaki Diễu</option>
          <option value="logo">Kaki Thêu / Logo</option>
          <option value="velvet">Kaki Nhung / Tăm</option>
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

  <!-- DANH SÁCH SẢN PHẨM KAKI -->
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
    const PAGE_CATEGORIES = 'quan-kaki'; 
</script>
</body>
</html>
