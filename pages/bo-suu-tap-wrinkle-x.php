<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Wrinkle-X</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="wrinkle-x">Wrinkle-X</option>
          <option value="rayon">Rayon</option>
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

  <!-- Sản phẩm trong bộ sưu tập -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm Wrinkle-X -->
        <div class="product-card">
          <img src="../assets/img/wx1.jpg" alt="SM181" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM181 – Wrinkle-X</h4>
          <p><span class="new">475,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/wx2.jpg" alt="SM176" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM176 – Wrinkle-X</h4>
          <p><span class="new">515,000₫</span></p>
        </div>
        <!-- Thêm các sản phẩm khác tương tự -->
      </div>

      <!-- Phân trang -->
      <div class="pagination">
        <button class="page-btn prev" disabled>&laquo;</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn next">&raquo;</button>
      </div>
    </div>
  </section>

<?php include('../footer.php'); ?>

</body>
</html>
