<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Tây Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Bộ lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả quần tây</option>
          <option value="slimfit">Slimfit</option>
          <option value="regular">Regular</option>
          <option value="sidetab">Sidetab</option>
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

  <!-- LIST SẢN PHẨM QUẦN TÂY -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm -->
        <div class="product-card">
          <img src="assets/img/qt069.jpg" alt="QT069">
          <div class="badge">-18%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây Sidetab Slimfit QT069</h4>
          <p><span class="new">349,000₫</span> <span class="old">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/qt068.jpg" alt="QT068">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây Sidetab Regular QT068</h4>
          <p><span class="new">475,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/qt067.jpg" alt="QT067">
          <div class="badge">-18%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây Sidetab Slimfit QT067</h4>
          <p><span class="new">349,000₫</span> <span class="old">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/qt031.jpg" alt="QT031">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây Trơn Rayon QT031</h4>
          <p><span class="new">495,000₫</span></p>
        </div>
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
