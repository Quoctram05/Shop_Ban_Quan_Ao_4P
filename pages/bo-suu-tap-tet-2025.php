<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Tết 2025</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="tet-2025">Tết 2025</option>
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

  <!-- SẢN PHẨM / BỘ SƯU TẬP TẾT 2025 -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ một số sản phẩm Tết -->
        <div class="product-card">
          <img src="../assets/img/t1.jpg" alt="AK064" />
          <div class="badge">-20%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Bomber AK064 – Tết 2025</h4>
          <p><span class="new">516,000₫</span> <span class="old">645,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/t2.jpg" alt="QJ111" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jean QJ111 – Tết 2025</h4>
          <p><span class="new">436,000₫</span> <span class="old">545,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/t3.jpg" alt="QT066" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Tây QT066 – Tết 2025</h4>
          <p><span class="new">356,000₫</span> <span class="old">395,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/t4.jpg" alt="QS062" />
          <div class="badge">-20%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS062 – Tết 2025</h4>
          <p><span class="new">300,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <!-- Thêm nhiều sản phẩm theo mẫu -->
      </div>

      <!-- PHÂN TRANG -->
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
