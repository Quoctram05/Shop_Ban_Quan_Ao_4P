<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>
  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Bộ lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả loại áo</option>
          <option value="polo">Polo</option>
          <option value="tshirt">Thun</option>
          <option value="shirt">Sơ mi</option>
        </select>
      </div>
      <div class="sort-group">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="price-asc">Giá tăng dần</option>
          <option value="price-desc">Giá giảm dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Danh sách sản phẩm Áo Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/an1.jpg" alt="Áo Polo 1">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO170</h4>
          <p><span class="new">284,000₫</span> <span class="old">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/an2.jpg" alt="Áo Thun 1">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>
Áo Thun Wash Loang In Chữ Hopeless Dream Form Regular AT172 Màu Xanh Đá</h4>
          <p><span class="new">275,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/an3.jpg" alt="Áo Sơ Mi 1">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi Tay Ngắn Oxford Thêu 4M Ở Túi Form Regular SM191 Màu Trắng</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/an4.jpg" alt="Áo Thun 2">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>
-28%
Áo Khoác Dù Trượt Nước Thêu 3D Logo 4M Form Regular AK075 Màu Kem</h4>
          <p><span class="new">221,000₫</span> <span class="old">245,000₫</span></p>
        </div>
        <!-- Bạn có thể thêm nhiều card hơn -->
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
