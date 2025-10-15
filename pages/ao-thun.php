<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Thun Nam</p>
    </div>
  </section>

  <!-- Filter & Sort bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả áo thun</option>
          <option value="round-neck">Cổ tròn</option>
          <option value="v-neck">Cổ tim</option>
          <option value="long-sleeve">Tay dài</option>
          <option value="graphic">Họa tiết / in</option>
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

  <!-- Danh sách sản phẩm Áo Thun -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/thun1.jpg" alt="Áo Thun AT179">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Ba Lỗ AT179</h4>
          <p><span class="new">195,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/thun2.jpg" alt="Áo Thun AT177">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun phối vai AT177</h4>
          <p><span class="new">245,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/thun3.jpg" alt="Áo Thun AT176">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun phối 2 màu AT176</h4>
          <p><span class="new">245,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/thun4.jpg" alt="Áo Thun AT174">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun Wash Loang AT174</h4>
          <p><span class="new">325,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm nếu cần -->
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
