<?php
session_start();
require_once __DIR__ . '/../public/connect.php';

$base_url = '/Shop_Ban_Quan_Ao_4P/';

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($productId <= 0) {
    http_response_code(404);
    echo 'Sản phẩm không hợp lệ.';
    exit;
}

function money_vnd($n) { return number_format((float)$n, 0, ',', '.') . 'đ'; }
function asset_path($path) {
    $clean = $path ? str_replace('../', '', $path) : 'assets/img/no-image.jpg';
    return '/Shop_Ban_Quan_Ao_4P/' . ltrim($clean, '/');
}

// Lấy sản phẩm
$stmt = $conn->prepare("SELECT * FROM san_pham WHERE id = ? AND trang_thai = 'DangBan' LIMIT 1");
$stmt->execute([$productId]);
$product = $stmt->fetch();
if (!$product) {
    http_response_code(404);
    echo 'Không tìm thấy sản phẩm.';
    exit;
}

// Biến thể
$variantStmt = $conn->prepare("SELECT * FROM bien_the_san_pham WHERE san_pham_id = ? ORDER BY id ASC");
$variantStmt->execute([$productId]);
$variants = $variantStmt->fetchAll();
if (empty($variants)) {
    http_response_code(404);
    echo 'Sản phẩm chưa có biến thể.';
    exit;
}
$firstVariant = $variants[0];

// Ảnh phụ
$galleryStmt = $conn->prepare("SELECT url_hinh_anh, alt_text FROM thu_vien_anh WHERE san_pham_id = ?");
$galleryStmt->execute([$productId]);
$gallery = $galleryStmt->fetchAll();

include('../header.php');
include('../navbar.php');
?>

<div class="container" style="padding: 40px 0;">
  <div class="product-detail-wrapper">
    <div class="product-detail-left">
      <div class="product-detail-main">
        <img id="detail-main-img" src="<?php echo htmlspecialchars(asset_path($firstVariant['hinh_anh_dai_dien'])); ?>"
             alt="<?php echo htmlspecialchars($product['ten_san_pham']); ?>">
      </div>
      <div class="product-detail-thumbs">
        <?php foreach ($variants as $v): ?>
          <div class="thumb js-thumb"
               data-img="<?php echo htmlspecialchars(asset_path($v['hinh_anh_dai_dien'])); ?>"
               data-price="<?php echo (float)$v['gia_ban']; ?>"
               data-price-old="<?php echo (float)$v['gia_goc']; ?>">
            <img src="<?php echo htmlspecialchars(asset_path($v['hinh_anh_dai_dien'])); ?>"
                 alt="<?php echo htmlspecialchars($v['mau_sac'] . ' ' . $v['kich_co']); ?>">
            <span><?php echo htmlspecialchars($v['mau_sac'] . ' - ' . $v['kich_co']); ?></span>
          </div>
        <?php endforeach; ?>
        <?php foreach ($gallery as $img): ?>
          <div class="thumb js-thumb"
               data-img="<?php echo htmlspecialchars(asset_path($img['url_hinh_anh'])); ?>">
            <img src="<?php echo htmlspecialchars(asset_path($img['url_hinh_anh'])); ?>"
                 alt="<?php echo htmlspecialchars($img['alt_text'] ?? ''); ?>">
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="product-detail-right">
      <h1><?php echo htmlspecialchars($product['ten_san_pham']); ?></h1>
      <div class="detail-price">
        <span id="detail-price-new"><?php echo money_vnd($firstVariant['gia_ban']); ?></span>
        <?php if ((float)$firstVariant['gia_goc'] > (float)$firstVariant['gia_ban']): ?>
          <span id="detail-price-old"><?php echo money_vnd($firstVariant['gia_goc']); ?></span>
        <?php else: ?>
          <span id="detail-price-old" style="display:none;"></span>
        <?php endif; ?>
      </div>

      <div class="detail-desc">
        <?php echo $product['mo_ta']; ?>
      </div>

      <form id="detail-add-cart-form" class="detail-form">
        <input type="hidden" name="san_pham_id" value="<?php echo (int)$product['id']; ?>">

        <label>Chọn màu / size:</label>
        <select name="bien_the_id" id="detail-variant-select">
          <?php foreach ($variants as $v): ?>
            <option value="<?php echo (int)$v['id']; ?>"
                    data-img="<?php echo htmlspecialchars(asset_path($v['hinh_anh_dai_dien'])); ?>"
                    data-price="<?php echo (float)$v['gia_ban']; ?>"
                    data-price-old="<?php echo (float)$v['gia_goc']; ?>">
              <?php echo htmlspecialchars($v['mau_sac'] . ' - ' . $v['kich_co']); ?> (<?php echo money_vnd($v['gia_ban']); ?>)
            </option>
          <?php endforeach; ?>
        </select>

        <label>Số lượng:</label>
        <input type="number" name="so_luong" id="detail-qty" min="1" value="1">

        <div class="detail-actions">
          <button type="button" class="btn-red js-detail-add-cart">+ Thêm vào giỏ hàng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../footer.php'); ?>

<script>
(function() {
  const selectVariant = document.getElementById('detail-variant-select');
  const mainImg       = document.getElementById('detail-main-img');
  const priceNewEl    = document.getElementById('detail-price-new');
  const priceOldEl    = document.getElementById('detail-price-old');

  function formatVND(n) {
    return new Intl.NumberFormat('vi-VN').format(n) + 'đ';
  }

  function updateDisplay(option) {
    if (!option) return;
    const img = option.dataset.img;
    const price = parseFloat(option.dataset.price || '0');
    const priceOld = parseFloat(option.dataset['priceOld'] || '0');
    if (img && mainImg) mainImg.src = img;
    if (priceNewEl) priceNewEl.textContent = formatVND(price);
    if (priceOldEl) {
      if (priceOld > price) {
        priceOldEl.style.display = '';
        priceOldEl.textContent = formatVND(priceOld);
      } else {
        priceOldEl.style.display = 'none';
      }
    }
  }

  if (selectVariant) {
    updateDisplay(selectVariant.options[selectVariant.selectedIndex]);
    selectVariant.addEventListener('change', function() {
      updateDisplay(this.options[this.selectedIndex]);
    });
  }

  document.querySelectorAll('.js-thumb').forEach(el => {
    el.addEventListener('click', () => {
      const img = el.dataset.img;
      if (img && mainImg) mainImg.src = img;
    });
  });

  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.js-detail-add-cart');
    if (!btn) return;
    e.preventDefault();
    const form = document.getElementById('detail-add-cart-form');
    if (!form) return;

    const formData = new FormData(form);

    fetch('<?php echo $base_url; ?>ajax/add-to-cart.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.text())
      .then(html => {
        const miniCartContainer = document.querySelector('#mini-cart-dropdown .cart-content');
        if (miniCartContainer) {
          miniCartContainer.innerHTML = html;
          const header = miniCartContainer.querySelector('[data-cart-count]');
          const badge  = document.getElementById('header-cart-count');
          if (header && badge) {
            badge.textContent = header.getAttribute('data-cart-count') || badge.textContent;
          }
        }
        const dropdown = document.getElementById('mini-cart-dropdown');
        if (dropdown) {
          dropdown.style.display = 'block';
          setTimeout(() => { dropdown.style.display = ''; }, 2500);
        }
      })
      .catch(err => console.error('Add-to-cart error:', err));
  });
})();
</script>
