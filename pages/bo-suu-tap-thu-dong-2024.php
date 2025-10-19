<?php include('../header.php'); ?>
<?php include('../navbar.php'); ?>


  <!-- BREADCRUMB -->
  <section class="breadcrumb">
    <div class="container">
      <p>4MEN / Bộ Sưu Tập / Thu Đông 2024</p>
    </div>
  </section>

  <!-- FILTER & SORT BAR -->
  <section class="filter-bar">
    <div class="container">
      <div class="filter-group">
        <span>Hiển thị:</span>
        <select id="filter-collection">
          <option value="all">Tất cả</option>
          <option value="thu-dong-2024">Thu Đông 2024</option>
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

  <!-- SẢN PHẨM THU ĐÔNG 2024 -->
  <section class="product-section section">
    <div class="container">
      <div class="product-grid" id="product-grid">
        <!-- Ví dụ các sản phẩm Thu Đông 2024 -->
        <div class="product-card">
          <img src="../assets/img/td1.jpg" alt="SM167" />
          <div class="badge">-37%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Overshirt Flannel Caro Thêu SM167</h4>
          <p><span class="new">299,000₫</span> <span class="old">475,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/td2.jpg" alt="QJ106" />
          <div class="badge">-27%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Quần Jeans Wash Có Thêu QJ106</h4>
          <p><span class="new">399,000₫</span> <span class="old">545,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/td3.jpg" alt="CV077" />
          <div class="badge">-10%</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Cà Vạt Họa Tiết CV077</h4>
          <p><span class="new">131,000₫</span> <span class="old">145,000₫</span></p>
        </div>

        <div class="product-card">
          <img src="../assets/img/td4.jpg" alt="AK062" />
          <div class="badge">-</div>
          <div class="overlay"><button>Thêm vào giỏ</button></div>
          <h4>Áo Khoác Kaki Business Jacket AK062</h4>
          <p><span class="new">527,000₫</span></p>
        </div>

        <!-- Thêm nhiều sản phẩm khác nếu có -->
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
