<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Tết 2024</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="tet-2024">Tết 2024</option>
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

  <!-- SẢN PHẨM TẾT 2024 -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm Tết 2024 -->
        <div class="product-card">
          <img src="assets/img/tet2024/AT132.jpg" alt="AT132" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun Cổ Tròn Trơn Form Slimfit AT132</h4>
          <p><span class="new">122,500₫</span> <span class="old">245,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/tet2024/AH004.jpg" alt="AH004" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Hoodie Rã Phối Thêu AH004</h4>
          <p><span class="new">298,000₫</span> <span class="old">595,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/tet2024/SM143.jpg" alt="SM143" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi Sọc Tay Dài SM143</h4>
          <p><span class="new">188,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/tet2024/QJ093.jpg" alt="QJ093" />
          <div class="badge">-30%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jeans Wash Xám QJ093</h4>
          <p><span class="new">382,000₫</span> <span class="old">545,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/tet2024/QJ091.jpg" alt="QJ091" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jeans Xanh Đậm QJ091</h4>
          <p><span class="new">273,000₫</span> <span class="old">545,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="assets/img/tet2024/QJ090.jpg" alt="QJ090" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jeans Xanh Nhạt Thêu 4MEN QJ090</h4>
          <p><span class="new">545,000₫</span></p>
        </div>

        <!-- Thêm các sản phẩm khác nếu muốn -->
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
