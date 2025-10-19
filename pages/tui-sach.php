<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- Breadcrumb -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Túi Xách Nam</p>
    </div>
  </section>

  <!-- Filter & Sort Bar -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Loại túi:</span>
        <select id="filter-type">
          <option value="all">Tất cả</option>
          <option value="deo-cheo">Túi đeo chéo</option>
          <option value="xach-tay">Túi xách tay</option>
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

  <!-- Sản phẩm Túi Xách Nam -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm -->
        <div class="product-card">
          <img src="../assets/img/tx1.jpg" alt="TX018" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Túi Xách Jean TX018</h4>
          <p><span class="new">175,000₫</span></p>
        </div>
        <div class="product-card">
          <img src="../assets/img/tx2.jpg" alt="TX014" />
          <div class="badge">New</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Túi Bao Tử Casual TX014</h4>
          <p><span class="new">285,000₫</span></p>
        </div>
        <!-- bạn có thể thêm các sản phẩm khác theo mẫu -->
      </div>

      <!-- Phân trang -->
      <div class="pagination">
        <button class="page-btn prev" disabled>&laquo;</button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn next">&raquo;</button>
      </div>
    </div>
  </section>

<?php include('../footer.php'); ?>/div>
  </footer>
</body>
</html>
