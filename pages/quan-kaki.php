<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Kaki Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả kaki</option>
          <option value="plain">Kaki Trơn</option>
          <option value="diệu">Kaki Diễu</option>
          <option value="logo">Kaki Thêu / Logo</option>
          <option value="velvet">Kaki Nhung / Tăm</option>
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

  <!-- DANH SÁCH SẢN PHẨM KAKI -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm kaki -->
        <div class="product-card">
          <img src="../assets/img/kaki1.jpg" alt="QK031">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Diễu 2 Bên Miệng Túi Slimfit QK031</h4>
          <p><span class="new">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/kaki2.jpg" alt="QK028">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Trơn Signature QK028</h4>
          <p><span class="new">475,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/kaki3.jpg" alt="QK030">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Thêu Logo 4M Premium QK030</h4>
          <p><span class="new">395,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/kaki4.jpg" alt="QK023">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Nhung Tăm Lưng Thun Slim-Cropped QK023</h4>
          <p><span class="new">159,000₫</span> <span class="old">385,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm kaki khác nếu muốn -->
      </div>

      <!-- PHÂN TRANG -->
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
