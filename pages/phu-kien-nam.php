<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Phụ kiện Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả phụ kiện</option>
          <option value="vi-da">Ví da</option>
          <option value="that-lung">Thắt lưng</option>
          <option value="non">Nón</option>
          <option value="vi">Ví nhỏ</option>
          <option value="tui-xach">Túi xách</option>
          <option value="vo">Vớ</option>
          <option value="mat-kinh">Mắt kính</option>
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

  <!-- Danh sách sản phẩm phụ kiện -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ card phụ kiện -->
        <div class="product-card">
          <img src="../assets/img/pk1.jpg" alt="Ví da">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Epsom Mini BV060</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/pk2.jpg" alt="Thắt lưng">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt lưng khóa tự động TL195</h4>
          <p><span class="new">275,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/pk3.jpg" alt="Ví da">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Ví Da Saffiano BV059</h4>
          <p><span class="new">345,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/pk4.jpg" alt="Nón">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón Snapback NB001</h4>
          <p><span class="new">199,000₫</span></p>
        </div>
        <!-- Thêm các sản phẩm phụ khác -->
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
