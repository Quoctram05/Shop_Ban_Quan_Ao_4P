<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Thắt Lưng Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc danh mục:</span>
        <select id="filter-type">
          <option value="all">Tất cả thắt lưng</option>
          <option value="khoa-tu-dong">Khóa tự động</option>
          <option value="khoa-kim">Khóa kim</option>
          <option value="da-tron">Da trơn</option>
          <option value="da-ghep">Da ghép / phối</option>
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

  <!-- Sản phẩm Thắt Lưng -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/tl1.jpg" alt="TL195">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt Lưng Khóa Tự Động TL195</h4>
          <p><span class="new">275,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tl2.jpg" alt="TL194">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt Lưng Khóa Tự Động TL194</h4>
          <p><span class="new">245,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tl3.jpg" alt="TL193">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt Lưng Khóa Tự Động TL193</h4>
          <p><span class="new">275,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tl4.jpg" alt="TL192">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt Lưng Khóa Tự Động TL192</h4>
          <p><span class="new">245,000₫</span></p>
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
