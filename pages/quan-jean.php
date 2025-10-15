<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Jean Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả jean</option>
          <option value="plain">Jean Trơn</option>
          <option value="ripped">Jean Rách</option>
          <option value="wash">Jean Wash</option>
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

  <!-- Danh sách sản phẩm Quần Jean Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="assets/img/jean1.jpg" alt="Jean trơn QJ114">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Jean Trơn QJ114</h4>
          <p><span class="new">518,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/jean2.jpg" alt="Jean rách QJ117">
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Jean Rách QJ117</h4>
          <p><span class="new">490,000₫</span> <span class="old">545,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/jean3.jpg" alt="Jean wash QJ115">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Jean Wash QJ115</h4>
          <p><span class="new">528,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/jean4.jpg" alt="Jean trơn QJ116">
          <div class="badge">-15%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Jean Trơn QJ116</h4>
          <p><span class="new">463,000₫</span> <span class="old">545,000₫</span></p>
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
