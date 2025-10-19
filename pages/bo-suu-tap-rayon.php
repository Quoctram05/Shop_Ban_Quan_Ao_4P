<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ sưu tập / Rayon</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="rayon">Rayon Collection</option>
          <!-- nếu bạn có nhiều collection, thêm vào -->
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

  <!-- SẢN PHẨM RAYON -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ sản phẩm trong collection Rayon -->
        <div class="product-card">
          <img src="../assets/img/ro1.jpg" alt="PO164" />
          <div class="badge">-13%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Rayon In Logo Tròn PO164</h4>
          <p><span class="new">345,000₫</span> <span class="old">395,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/ro2.jpg" alt="PO163" />
          <div class="badge">-8%</div>
          <div like overlay><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Rayon Rã Phối PO163</h4>
          <p><span class="new">345,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/ro3.jpg" alt="PO162" />
          <div class="badge">-8%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Rayon Nanh Sói PO162</h4>
          <p><span class="new">345,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/ro4.jpg" alt="PO161" />
          <div class="badge">-8%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Polo Rayon Bo Sọc PO161</h4>
          <p><span class="new">345,000₫</span> <span class="old">375,000₫</span></p>
        </div>

        <!-- Thêm sản phẩm khác nếu có -->
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
