<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Giày Dép Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc danh mục:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="giay">Giày Nam</option>
          <option value="sandal">Sandal Nam</option>
          <option value="dep">Dép Nam</option>
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

  <!-- SẢN PHẨM GIÀY DÉP NAM -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/giay1.jpg" alt="GI021">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Giày Derby GI021</h4>
          <p><span class="new">878,000₫</span> <span class="old">975,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/giay2.jpg" alt="GI020">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Giày Penny Loafer GI020</h4>
          <p><span class="new">851,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/giay3.jpg" alt="GI019">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Giày Oxford GI019</h4>
          <p><span class="new">790,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/giay4.jpg" alt="SND001">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Sandal Da SND001</h4>
          <p><span class="new">325,000₫</span></p>
        </div>
        <!-- Thêm sản phẩm theo mẫu nếu muốn -->
      </div>

      <!-- PHÂN TRANG -->
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
