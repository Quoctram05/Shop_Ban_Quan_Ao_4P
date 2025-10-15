<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Vớ Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại vớ:</span>
        <select id="filter-type">
          <option value="all">Tất cả vớ</option>
          <option value="co-ngan">Cổ ngắn</option>
          <option value="co-cao">Cổ cao</option>
          <option value="thể-thao">Vớ thể thao</option>
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

  <!-- Các sản phẩm vớ -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/vo1.jpg" alt="VO063" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Vớ Nam cổ dài VO063</h4>
          <p><span class="new">45,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/vo2.jpg" alt="VO061" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Vớ Nam cổ cao VO061</h4>
          <p><span class="new">45,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/vo3.jpg" alt="VO058" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Vớ Nam VO058</h4>
          <p><span class="new">31,500₫</span> <span class="old">35,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/vo4.jpg" alt="VO053" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Vớ Nam cổ ngắn VO053</h4>
          <p><span class="new">35,000₫</span></p>
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
