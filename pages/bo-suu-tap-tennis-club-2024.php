<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Tennis Club 2024</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="tennis-club-2024">Tennis Club 2024</option>
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

  <!-- Sản phẩm Tennis Club -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm trong Tennis Club 2024 -->
        <div class="product-card">
          <img src="../assets/img/tennis1.jpg" alt="QK028" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Kaki Trơn Signature QK028</h4>
          <p><span class="new">475,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tennis2.jpg" alt="PO133" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Phối Bo Thêu Ngực PO133</h4>
          <p><span class="new">375,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tennis3.jpg" alt="AK059" />
          <div class="badge">-30%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Varsity AK059</h4>
          <p><span class="new">480,000₫</span> <span class="old">685,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tennis4.jpg" alt="QS057" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS057</h4>
          <p><span class="new">375,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tennis5.jpg" alt="AT151" />
          <div class="badge">-30%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Thun Wash In Graphic AT151</h4>
          <p><span class="new">207,000₫</span> <span class="old">295,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tennis6.jpg" alt="MU010" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Nón Lưỡi Trai MU010</h4>
          <p><span class="new">195,000₫</span></p>
        </div>
        <!-- Có thể thêm nhiều sản phẩm tương tự -->
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
