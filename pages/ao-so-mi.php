<?php 
  // Bạn có thể giữ header và navbar của bạn
  include('../header.php'); 
  include('../navbar.php'); 
?>

<body>

  <section class="breadcrumb">
    <div class="container">
      <p>4P / Áo Sơ Mi Nam</p>
    </div>
  </section>

  <section class="filter-bar">
    <div class="container">
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định (Mới nhất)</option>
          <option value="price-asc">Giá tăng dần</option>
          <option value="price-desc">Giá giảm dần</option>
        </select>
      </div>
    </div>
  </section>

  <section class="product-section section">
    <div class="container">
    
      <div class="product-grid" id="product-grid">
        <p>Đang tải sản phẩm, vui lòng chờ...</p>
      </div>

      <div class="pagination" id="pagination-controls">
        </div>

    </div>
  </section>

<?php 
  // Bạn có thể giữ footer của bạn
  include('../footer.php'); 
?>

<script>
    const PAGE_CATEGORIES = 'ao-so-mi'; 
</script>


</body>
</html>