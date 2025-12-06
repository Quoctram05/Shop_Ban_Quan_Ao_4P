<!-- ccccccccccccccccccccccccccccc -->
<?php 
include('header.php'); 
include('navbar.php'); 
include('public/connect.php');
?>


  <!-- ===== BANNER SLIDER ===== -->
  <section class="banner">
      <!-- Thanh tiến trình -->
    <div class="progress-bar">
      <div class="progress"></div>
    </div>
    <div class="slides">
      <div class="slide active">
        <img src="assets/img/slide-2-trang-chu-slide-2.jpg" alt="Banner 1">
      </div>
      <div class="slide">
        <img src="assets/img/Screenshot 2025-10-14 150733.png" alt="Banner 2">
      </div>
      <div class="slide">
        <img src="assets/img/LineupBanner-men-DRY-EX-01-pc.avif" alt="Banner 3">
      </div>
    </div>
  
  <button class="prev"><i class="fa-solid fa-chevron-left"></i></button>
  <button class="next"><i class="fa-solid fa-chevron-right"></i></button>
   <!-- 3 chấm dưới slider -->
  <div class="dots">
    <span class="dot active"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>
</section>

  </section>

  <!-- ===== SẢN PHẨM HOT ===== -->
  <section class="section">
    <div class="container">
      <h2>THỜI TRANG HOT NHẤT</h2>
      <div class="product-grid" id="hot-products-grid">
         <p style="text-align:center; width:100%">Đang tải sản phẩm hot...</p>
      </div>
    </div>
  </section>

<section class="collection-preview">
  <div class="container">
    <div class="collection-grid" id="collection-grid">
       <p style="text-align:center; width:100%">Đang tải bộ sưu tập...</p>
    </div>
  </div>
</section>


   <!-- ===== SẢN PHẨM MỚI NHẤT ===== -->
  <section class="section">
    <div class="container">
      <h2>THỜI TRANG MỚI NHẤT </h2>
        <div class="product-grid" id="new-products-grid">
         <p style="text-align:center; width:100%">Đang tải sản phẩm mới...</p>
        </div>
    </div>
  </section>

  <!-- Quick View Modal -->
  <div id="quick-view-modal" class="quick-view">
    <div class="quick-view__overlay"></div>
    <div class="quick-view__content">
      <button class="quick-view__close" type="button">&times;</button>
      <div id="quick-view-body"><!-- Nội dung sẽ load bằng AJAX --></div>
    </div>
  </div>

  <?php include('footer.php'); ?>
  <script src="assets/js/home.js"></script>
</body>
</html>
