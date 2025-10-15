<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Khoác Nam</p>
    </div>
  </section>

  <!-- Filter & Sort bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Bộ lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả loại khoác</option>
          <option value="du">Khoác dù</option>
          <option value="bomber">Bomber</option>
          <option value="jean">Jean jacket</option>
          <option value="kaki">Khoác kaki</option>
          <option value="da">Khoác da</option>
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

  <!-- Danh sách sản phẩm Áo Khoác Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/khoac1.jpg" alt="Khoác Dù AK075">
          <div class="badge">-28%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Dù AK075</h4>
          <p><span class="new">319,000₫</span> <span class="old">445,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/khoac2.jpg" alt="Khoác Bomber AK064">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Bomber AK064</h4>
          <p><span class="new">645,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/khoac3.jpg" alt="Khoác Kaki AK062">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Kaki AK062</h4>
          <p><span class="new">585,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/khoac4.jpg" alt="Khoác Varsity AK059">
          <div class="badge">-20%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Varsity AK059</h4>
          <p><span class="new">480,000₫</span> <span class="old">600,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm tương tự -->
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
