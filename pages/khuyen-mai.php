<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Khuyến mãi</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Sản phẩm khuyến mãi -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm khuyến mãi -->
        <div class="product-card">
          <img src="assets/img/product1.jpg" alt="Sản phẩm KM 1">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS080</h4>
          <p><span class="new">284,000₫</span> <span class="old">315,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/product2.jpg" alt="Sản phẩm KM 2">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS079</h4>
          <p><span class="new">284,000₫</span> <span class="old">315,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/product3.jpg" alt="Sản phẩm KM 3">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO170</h4>
          <p><span class="new">311,000₫</span> <span class="old">345,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/product4.jpg" alt="Sản phẩm KM 4">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO169</h4>
          <p><span class="new">284,000₫</span> <span class="old">315,000₫</span></p>
        </div>

        <!-- bạn có thể thêm nhiều sản phẩm khuyến mãi hơn -->
      </div>

      <!-- Phân trang -->
      <div class="pagination">
        <button class="page-btn prev" disabled>&laquo;</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn next">&raquo;</button>
      </div>
    </div>
  </section>

<?php include('../footer.php'); ?>

</body>
</html>
