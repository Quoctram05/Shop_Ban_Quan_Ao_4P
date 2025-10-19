<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Áo Hoodie & Sweatshirt Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc theo:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="hoodie">Hoodie</option>
          <option value="sweatshirt">Sweatshirt</option>
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

  <!-- Danh sách sản phẩm Hoodie & Sweatshirt -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/hoodie1.jpg" alt="AH007">
          <div class="badge">-22%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Hoodie Thêu Logo AH007</h4>
          <p><span class="new">595,000₫</span> <span class="old">760,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/hoodie2.jpg" alt="AH006">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Hoodie Khóa Zip Phối Dây AH006</h4>
          <p><span class="new">445,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/hoodie3.jpg" alt="AH008">
          <div class="badge">-22%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Hoodie Rã Phối Viền AH008</h4>
          <p><span class="new">595,000₫</span> <span class="old">760,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/hoodie4.jpg" alt="AS004">
          <div class="badge">-70%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Sweatshirt Thêu 4MEN AS004</h4>
          <p><span class="new">135,000₫</span> <span class="old">449,000₫</span></p>
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
