<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Ví Da Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại ví:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="vi-ngang">Ví ngang</option>
          <option value="vi-dung">Ví đứng</option>
          <option value="vi-cam-tay">Ví cầm tay</option>
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

  <!-- SẢN PHẨM VÍ DA NAM -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm ví da -->
        <div class="product-card">
          <img src="../assets/img/vd1.jpg" alt="BV060" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Epsom Mini Dáng Ngang BV060</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/vd2.jpg" alt="BV059" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Saffiano Khóa Kéo Đứng BV059</h4>
          <p><span class="new">345,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/vd3.jpg" alt="BV058" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Saffiano Mini Đứng BV058</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/vd4.jpg" alt="BV057" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Nappa Có Khóa Dáng Ngang BV057</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <!-- Bạn có thể thêm nhiều sản phẩm ví da -->
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
