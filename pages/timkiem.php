<?php
include('../public/connect.php');

$q = $_GET['q'] ?? '';
$q = trim($q);

// Prepared statement để tìm theo product.name
$stmt = $conn->prepare("
  SELECT
    p.id,
    p.name,
    -- Lấy ảnh chính nếu có (view đã có sẵn trong DB)
    (SELECT vi.main_image FROM v_product_main_image vi WHERE vi.product_id = p.id) AS main_image,
    -- Lấy giá thấp nhất trong các biến thể nếu có
    (SELECT MIN(vp.final_price) FROM v_variant_pricing vp WHERE vp.product_id = p.id) AS min_price,
    (SELECT MAX(vp.compare_at_price) FROM v_variant_pricing vp WHERE vp.product_id = p.id) AS max_compare
  FROM product p
  WHERE p.name LIKE CONCAT('%', ?, '%')
  ORDER BY p.updated_at DESC, p.created_at DESC
");
$stmt->bind_param("s", $q);
$stmt->execute();
$res = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kết quả tìm kiếm - 4MEN</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <h2 style="text-align:center; margin:30px 0;">
    Kết quả tìm kiếm cho: <span style="color:#b35d2a;"><?php echo htmlspecialchars($q); ?></span>
  </h2>

  <div class="container">
    <div class="product-grid">
      <?php if ($res->num_rows): ?>
        <?php while ($row = $res->fetch_assoc()): ?>
          <div class="product-card">
            <img src="<?php echo $row['main_image'] ? '..'.$row['main_image'] : '../assets/img/ao1.jpg'; ?>"
                 alt="<?php echo htmlspecialchars($row['name']); ?>">
            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
            <?php if ($row['min_price']): ?>
              <p>
                <span class="new"><?php echo number_format($row['min_price'], 0, ',', '.'); ?>đ</span>
                <?php if ($row['max_compare'] && $row['max_compare'] > $row['min_price']): ?>
                  <span class="old"><?php echo number_format($row['max_compare'], 0, ',', '.'); ?>đ</span>
                <?php endif; ?>
              </p>
            <?php else: ?>
              <p><span class="new">Giá đang cập nhật</span></p>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="grid-column: 1/-1; text-align:center;">Không tìm thấy sản phẩm nào phù hợp.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
