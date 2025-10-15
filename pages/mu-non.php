<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Nón Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại nón:</span>
        <select id="filter-type">
          <option value="all">Tất cả nón</option>
          <option value="luoi-trai">Nón lưỡi trai</option>
          <option value="snapback">Snapback</option>
          <option value="non-len">Nón len</option>
        </select>
      </div>
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="views">Xem nhiều</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Sản phẩm Nón Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/mu1.jpg" alt="MU010" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón Lưỡi Trai Thêu MU010</h4>
          <p>
            <span class="new">176,000₫</span>
            <span class="old">195,000₫</span>
          </p>
        </div>
        <div class="product-card">
          <img src="assets/img/mu2.jpg" alt="MU009" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón Lưỡi Trai Thêu MU009</h4>
          <p>
            <span class="new">176,000₫</span>
            <span class="old">195,000₫</span>
          </p>
        </div>
        <div class="product-card">
          <img src="assets/img/mu3.jpg" alt="MU008" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón Thêu Mỏ Neo MU008</h4>
          <p>
            <span class="new">176,000₫</span>
            <span class="old">195,000₫</span>
          </p>
        </div>
        <div class="product-card">
          <img src="assets/img/mu4.jpg" alt="MU004" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón LT Thêu Full Of Hope MU004</h4>
          <p>
            <span class="new">158,000₫</span>
            <span class="old">175,000₫</span>
          </p>
        </div>
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
