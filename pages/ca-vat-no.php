<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Cà Vạt & Nơ Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="ca-vat">Cà Vạt Nam</option>
          <option value="no">Nơ Nam</option>
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

  <!-- Product section -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/cavat1.jpg" alt="CV082">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Cà Vạt Sọc CV082</h4>
          <p><span class="new">145,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/cavat2.jpg" alt="CV081">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Cà Vạt Sọc CV081</h4>
          <p><span class="new">145,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/cavat3.jpg" alt="CV080">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Cà Vạt Họa Tiết CV080</h4>
          <p><span class="new">145,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/no1.jpg" alt="NO020">
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nơ Nam Sọc Caro NO020</h4>
          <p><span class="new">32,500₫</span> <span class="old">65,000₫</span></p>
        </div>
      </div>

      <!-- Pagination -->
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
