<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Lót Nam</p>
    </div>
  </section>

  <!-- Filter & Sort bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả loại</option>
          <option value="boxer">Boxer</option>
          <option value="tam-giac">Tam giác</option>
          <option value="long">Ống dài</option>
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

  <!-- Danh sách sản phẩm Quần Lót Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/sip1.jpg" alt="QL069">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Boxer Lụa Băng Thông Hơi QL069</h4>
          <p><span class="new">119,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/sip2.jpg" alt="QL068">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Boxer Lụa Băng Phối Lưới QL068</h4>
          <p><span class="new">119,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/sip3.jpg" alt="QL067">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Boxer Đục Lỗ Thoáng Khí QL067</h4>
          <p><span class="new">107,100₫</span> <span class="old">119,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/sip4.jpg" alt="QL066">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Boxer Lụa Băng hoàn thiện QL066</h4>
          <p><span class="new">119,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm nếu cần -->
      </div>

      <!-- Phân trang -->
      <div class="pagination">
        <button class="page-btn prev" disabled>&laquo;</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn next">&raquo;</button>
      </div>
    </div>
  </section>

<?php include('../footer.php'); ?>
</body>
</html>
