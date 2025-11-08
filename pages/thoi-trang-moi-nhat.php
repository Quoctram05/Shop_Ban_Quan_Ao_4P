<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

    <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Thời trang mới nhất</p>
    </div>
  </section>

    <section class="filter-bar">
    <div class="container">
      <div class="sort">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định (Mới nhất)</option>
          <option value="popular">Xem nhiều</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
  </section>

    <section class="product-list section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <p>Đang tải sản phẩm, vui lòng chờ...</p>
      </div>

      <div class="pagination" id="pagination-controls"></div>

    </div>
  </section>

<?php include('../footer.php'); ?>

<script src="../assets/js/product-list.js"></script>

</body>
</html>