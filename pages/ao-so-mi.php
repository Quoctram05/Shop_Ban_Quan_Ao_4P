<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Sơ Mi Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả sơ mi</option>
          <option value="long-sleeve">Tay dài</option>
          <option value="short-sleeve">Tay ngắn</option>
          <option value="pattern">Họa tiết</option>
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

  <!-- Danh sách sản phẩm Áo Sơ Mi -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/somi1.jpg" alt="Sơ mi SM179">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM179</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/somi2.jpg" alt="Sơ mi SM195">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM195</h4>
          <p><span class="new">395,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/somi3.jpg" alt="Sơ mi SM194">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM194</h4>
          <p><span class="new">395,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/somi4.jpg" alt="Sơ mi SM193">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM193</h4>
          <p><span class="new">395,000₫</span></p>
        </div>
        <!-- Bạn có thể thêm nhiều sản phẩm -->
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
