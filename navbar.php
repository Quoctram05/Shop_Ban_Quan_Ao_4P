
<header class="main-header">
  <div class="container">
    <div class="logo">
      <ul>
        <li><a href="<?php echo $base_url; ?>index.php"><img src="<?php echo $base_url; ?>assets/img/logo.png" alt="4P"></a></li>
      </ul>
    </div>

    <nav class="nav-menu">
      <ul>
        <li><a href="<?php echo $base_url; ?>pages/thoi-trang-moi-nhat.php">HÀNG MỚI VỀ</a></li>
<!-- 
        <li class="mega">
          <a href="<?php echo $base_url; ?>index.php">BỘ SƯU TẬP</a>
          <div class="mega-box">
            <div class="column">
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-wrinkle-x.php">WRINKLE-X™ COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-rayon.php">RAYON COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-tet-2025.php">SPRING 2025 COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-thu-dong-2024.php">HERITAGE GLAMOUR COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-he-2024.php">BON VOYAGE COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-tennis-club-2024.php">TENNIS CLUB COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-tet-2024.php">HOME IS... COLLECTION</a>
              <a href="<?php echo $base_url; ?>pages/bo-suu-tap-thu-dong-2023.php">LOOKBACK TO FORWARD COLLECTION</a>
            </div>
          </div>
        </li> -->

        <li class="mega">
          <a href="<?php echo $base_url; ?>pages/ao-nam.php">ÁO NAM</a>
          <div class="mega-box">
            <div class="column">
              <a href="<?php echo $base_url; ?>pages/ao-so-mi.php">Áo sơ mi</a>
              <a href="<?php echo $base_url; ?>pages/ao-polo-rugby-shirt-nam.php">Áo Polo & Rugby Shirt</a>
              <a href="<?php echo $base_url; ?>pages/ao-thun.php">Áo thun</a>
              <a href="<?php echo $base_url; ?>pages/ao-khoac.php">Áo khoác</a>
              <a href="<?php echo $base_url; ?>pages/ao-hoodie-sweatshirt.php">Áo Hoodie & Sweatshirt</a>
              <a href="<?php echo $base_url; ?>pages/ao-vest-ghi-le.php">Áo Vest & Ghi lê</a>
              <a href="<?php echo $base_url; ?>pages/ao-len.php">Áo len</a>
            </div>
          </div>
        </li>

        <li class="mega">
          <a href="<?php echo $base_url; ?>pages/quan-nam.php">QUẦN NAM</a>
          <div class="mega-box">
            <div class="column">
              <a href="<?php echo $base_url; ?>pages/quan-jean.php">Quần jeans</a>
              <a href="<?php echo $base_url; ?>pages/quan-tay.php">Quần tây</a>
              <a href="<?php echo $base_url; ?>pages/quan-kaki.php">Quần kaki</a>
              <a href="<?php echo $base_url; ?>pages/quan-jogger.php">Quần jogger</a>
              <a href="<?php echo $base_url; ?>pages/quan-short.php">Quần short</a>
              <a href="<?php echo $base_url; ?>pages/quan-lot.php">Quần lót</a>
            </div>
          </div>
        </li>

        <li class="mega">
          <a href="<?php echo $base_url; ?>pages/phu-kien-nam.php">PHỤ KIỆN</a>
          <div class="mega-box">
            <div class="column">
              <a href="<?php echo $base_url; ?>pages/giay-dep.php">Giày Dép</a>
              <a href="<?php echo $base_url; ?>pages/that-lung.php">Thắt lưng</a>
              <a href="<?php echo $base_url; ?>pages/vi-da.php">Ví da</a>
              <a href="<?php echo $base_url; ?>pages/ca-vat-no.php">Cà vạt & Nơ</a>
              <a href="<?php echo $base_url; ?>pages/vo-nam.php">Vớ nam</a>
              <a href="<?php echo $base_url; ?>pages/mu-non.php">Mũ Nón</a>
              <a href="<?php echo $base_url; ?>pages/tui-sach.php">Túi sách</a>
            </div>
          </div>
        </li>

        <li><a href="<?php echo $base_url; ?>pages/khuyen-mai.php">OUTLET SALE</a></li>
      </ul>
    </nav>

    <div class="header-actions">

      <div class="search-box">
          <form action="<?php echo $base_url; ?>pages/timkiem.php" method="GET">
              <input type="text" id="search" name="q" class="search-input" placeholder="Tìm kiếm..." autocomplete="off">
              
              <button type="submit" class="search-btn">
                  <i class="fa-solid fa-magnifying-glass"></i>
              </button>
          </form>
          <div id="suggestions"></div>
      </div> 
      <div class="cart-wrapper">
        <a href="<?php echo $base_url; ?>pages/thanhtoan.php" class="cart" id="header-cart-btn">
            <div class="cart-icon-box">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="count" id="header-cart-count">0</span>
            </div>
        </a>
        <div class="mini-cart-box" id="mini-cart-dropdown">
            <div class="cart-content">
                <p style="padding: 10px; text-align: center;">Đang tải...</p>
            </div>
        </div>
      </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-menu">
                <a href="<?php echo $base_url; ?>pages/profile.php">
                    <div class="user-icon-box">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="user-info">
                        <span class="user-name">
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Tài khoản'); ?>
                        </span>
                        <span class="user-role">
                            <?php 
                                $role = $_SESSION['user_role'] ?? '';
                                echo ($role === 'QuanTriVien') ? 'Quản trị viên' : 'Thành viên';
                            ?>
                        </span>
                    </div>
                </a>
                <?php if ($role === 'QuanTriVien'): ?>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <a href="<?php echo $base_url; ?>admin/index.php"
                           style="display:flex;align-items:center;gap:6px;padding:8px 12px;border-radius:9999px;background:#0f172a;color:#fff;font-weight:600;white-space:nowrap;">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span>Trang quản trị</span>
                        </a>
                        <a href="<?php echo $base_url; ?>logout.php"
                           style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:9999px;background:#f8fafc;color:#0f172a;border:1px solid #e2e8f0;transition:all .15s ease;"
                           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f8fafc'">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <a href="<?php echo $base_url; ?>login.php" class="icon-btn">
                <div class="user-icon-box">
                    <i class="fa-regular fa-user"></i>
                </div>
            </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
