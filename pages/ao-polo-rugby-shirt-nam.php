<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Polo & Rugby Shirt Nam</p>
    </div>
  </section>

  <!-- Filter & Sort -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="polo">Polo</option>
          <option value="rugby">Rugby Shirt</option>
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

  <!-- Danh sách sản phẩm -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm PO170 -->
        <div class="product-card">
          <img src="assets/img/polo1.jpg" alt="PO170">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Thể Thao Phối Viền PO170</h4>
          <p><span class="new">345,000₫</span></p>
        </div>
        <!-- Thêm các sản phẩm khác theo mẫu -->
        <div class="product-card">
          <img src="assets/img/polo2.jpg" alt="PO169">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Thể Thao PO169</h4>
          <p><span class="new">315,000₫</span> <span class="old">345,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/polo3.jpg" alt="PO168">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO168</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/polo4.jpg" alt="PO167">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO167</h4>
          <p><span class="new">395,000₫</span></p>
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
