<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Quần Jogger Nam</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Lọc:</span>
        <select id="filter-type">
          <option value="all">Tất cả jogger</option>
          <option value="kaki">Jogger Kaki</option>
          <option value="dù">Jogger dù / vải mềm</option>
          <option value="ripped">Jogger rách / phối túi</option>
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

  <!-- DANH SÁCH SẢN PHẨM JOGGER -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/jg1.jpg" alt="JO016">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger Kaki JO016</h4>
          <p><span class="new">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/jg2.jpg" alt="JO015">
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger Kaki JO015</h4>
          <p><span class="new">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/jg3.jpg" alt="JO013">
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger Túi Chéo JO013</h4>
          <p><span class="new">213,000₫</span> <span class="old">425,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/jg4.jpg" alt="JO012">
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger Rã Chéo JO012</h4>
          <p><span class="new">213,000₫</span> <span class="old">425,000₫</span></p>
        </div>
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
