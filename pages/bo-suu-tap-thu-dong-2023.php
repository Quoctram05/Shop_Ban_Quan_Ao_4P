<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Thu Đông 2023</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="thu-dong-2023">Thu Đông 2023</option>
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

  <!-- SẢN PHẨM THU ĐÔNG 2023 -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ từ trang gốc -->
        <div class="product-card">
          <img src="../assets/img/td5.jpg" alt="SM140" />
          <div class="badge">-25%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Nhung Tăm Lưng Thun Form Slim-Cropped QK023</h4>
          <p><span class="new">259.000₫</span> <span class="old">345.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/td6.jpg" alt="SM139" />
          <div class="badge">-34%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger Rã Chéo Form Slimfit JO012 Màu Đen</h4>
          <p><span class="new">259.000₫</span> <span class="old">395.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/td7.jpg" alt="AT136" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sweater Thêu Logo 4MEN Form Regular AS004</h4>
          <p><span class="new">325.000₫</span></p>
        </div>


        <!-- Bạn có thể thêm sản phẩm khác nếu có -->
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
