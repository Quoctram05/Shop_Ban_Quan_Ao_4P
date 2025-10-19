<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Len Nam</p>
    </div>
  </section>

  <!-- Filter & Sort bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả len</option>
          <option value="round-neck">Cổ tròn</option>
          <option value="v-neck">Cổ V</option>
          <option value="pattern">Họa tiết / đan</option>
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

  <!-- Sản phẩm Len -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ 4 sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/len1.jpg" alt="AL014">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Len Tay Dài AL014</h4>
          <p><span class="new">485,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/len2.jpg" alt="AL012">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Len Họa Tiết AL012</h4>
          <p><span class="new">485,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/len3.jpg" alt="AL013">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Len Cổ V AL013</h4>
          <p><span class="new">485,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/len4.jpg" alt="AL011">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Len AL011</h4>
          <p><span class="new">485,000₫</span></p>
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
