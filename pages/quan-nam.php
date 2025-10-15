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
          <option value="all">Tất cả quần</option>
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
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/quan1.jpg" alt="Quần Tây QT069">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây Slimfit QT069</h4>
          <p><span class="new">349,000₫</span> <span class="old">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/quan2.jpg" alt="Quần Kaki QK031">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Diễu QK031</h4>
          <p><span class="new">404,000₫</span> <span class="old">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/quan3.jpg" alt="Quần Jean QJ114">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jean QJ114</h4>
          <p><span class="new">518,000₫</span> <span class="old">545,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/quan4.jpg" alt="Quần Short QS076">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS076</h4>
          <p><span class="new">394,000₫</span> <span class="old">415,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm tùy bạn -->
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
