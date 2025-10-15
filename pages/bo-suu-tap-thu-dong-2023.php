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
          <img src="assets/img/thu-dong-2023/SM140.jpg" alt="SM140" />
          <div class="badge">-25%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi Tay Dài Rã Ngực Thêu 4M SM140</h4>
          <p><span class="new">259.000₫</span> <span class="old">345.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/SM139.jpg" alt="SM139" />
          <div class="badge">-34%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi Tay Dài Rã Phối SM139</h4>
          <p><span class="new">259.000₫</span> <span class="old">395.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/AT136.jpg" alt="AT136" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun Interlock Wash Acid AT136</h4>
          <p><span class="new">325.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/QJ086.jpg" alt="QJ086" />
          <div class="badge">-24%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jeans Đen Wash Cào QJ086</h4>
          <p><span class="new">399.000₫</span> <span class="old">525.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/QS050.jpg" alt="QS050" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short Nhung Tăm QS050</h4>
          <p><span class="new">283.500₫</span> <span class="old">315.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/QK023.jpg" alt="QK023" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Nhung Tăm QK023</h4>
          <p><span class="new">159.000₫</span> <span class="old">385.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/PO118.jpg" alt="PO118" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo PO118</h4>
          <p><span class="new">345.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/AK054.jpg" alt="AK054" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Da Lộn AK054</h4>
          <p><span class="new">645.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/JO012.jpg" alt="JO012" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jogger JO012</h4>
          <p><span class="new">299.000₫</span> <span class="old">425.000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/thu-dong-2023/AS004.jpg" alt="AS004" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sweater AS004</h4>
          <p><span class="new">299.000₫</span> <span class="old">449.000₫</span></p>
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
