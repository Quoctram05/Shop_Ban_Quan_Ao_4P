<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb / phân trang nhỏ -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Thời trang mới nhất</p>
    </div>
  </section>

  <!-- Phần sắp xếp & bộ lọc -->
  <section class="filter-bar">
    <div class="container">
      <div class="sort">
        <span>Sắp xếp:</span>
        <select id="sort-select">
          <option value="default">Mặc định</option>
          <option value="newest">Mới nhất</option>
          <option value="popular">Xem nhiều</option>
          <option value="price-desc">Giá giảm dần</option>
          <option value="price-asc">Giá tăng dần</option>
        </select>
      </div>
    </div>
  </section>

  <!-- Danh sách sản phẩm mới -->
  <section class="product-list section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Các mục sản phẩm sẽ chèn JS hoặc viết tĩnh -->
        <!-- Ví dụ: -->
        <div class="product-card">
          <img src="assets/img/product1.jpg" alt="Sản phẩm 1" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Sơ Mi SM179</h4>
          <p><span class="new">315,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="assets/img/product2.jpg" alt="Sản phẩm 2" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jean QJ117</h4>
          <p><span class="new">545,000₫</span></p>
        </div>
        <!-- Thêm nhiều sản phẩm tương tự -->
      </div>
    </div>
  </section>

<?php include('../footer.php'); ?>

</body>
</html>
