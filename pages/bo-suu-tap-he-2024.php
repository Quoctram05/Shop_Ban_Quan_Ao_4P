<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>

  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Hè 2024</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="he-2024">Hè 2024</option>
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

  <!-- Sản phẩm Hè 2024 -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm của Hè 2024 -->
        <div class="product-card">
          <img src="../assets/img/he1.jpg" alt="SM152" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi Oxford SM152</h4>
          <p><span class="new">345,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/he2.jpg" alt="TL165" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt lưng đầu kim TL165</h4>
          <p><span class="new">138,000₫</span> <span class="old">275,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/he3.jpg" alt="DE005" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Dép Sandal DE005</h4>
          <p><span class="new">258,000₫</span> <span class="old">515,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/he4.jpg" alt="TL166" />
          <div class="badge">-50%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Thắt lưng TL166</h4>
          <p><span class="new">138,000₫</span> <span class="old">275,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/he5.jpg" alt="QS055" />
          <div class="badge">-30%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Short QS055</h4>
          <p><span class="new">263,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <!-- Thêm tiếp sản phẩm nếu có -->
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
