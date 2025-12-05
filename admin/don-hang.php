<?php
// admin/don-hang.php

// 1. KẾT NỐI DATABASE (Sử dụng thông tin của shop_thoi_trang_hoc)
// --- XÓA DÒNG include("define.php") VÌ KHÔNG CẦN THIẾT ---

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'shop_thoi_trang_hoc';

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Lỗi kết nối CSDL: " . $e->getMessage());
}

function df($n){ return number_format((float)$n, 0, ',', '.'); }

/* ====== Filters ====== */
$q    = trim($_GET['q'] ?? '');
$st   = trim($_GET['st'] ?? ''); 
$per  = max(1, (int)($_GET['per'] ?? 10));
$page = max(1, (int)($_GET['page'] ?? 1));

$today = (new DateTime())->format('Y-m-d');
$first = (new DateTime('first day of this month'))->format('Y-m-d');
$d1    = $_GET['d1'] ?? $first;
$d2    = $_GET['d2'] ?? $today;

/* ====== Mapping Trạng thái hiển thị ====== */
$statusMap = [
    'ChoXuLy'   => ['label' => 'Chờ xử lý',   'class' => 'bg-amber-100 text-amber-700'],
    'DaXacNhan' => ['label' => 'Đã xác nhận', 'class' => 'bg-blue-100 text-blue-700'],
    'DangGiao'  => ['label' => 'Đang giao',   'class' => 'bg-purple-100 text-purple-700'],
    'HoanThanh' => ['label' => 'Hoàn thành',  'class' => 'bg-green-100 text-green-700'],
    'DaHuy'     => ['label' => 'Đã hủy',      'class' => 'bg-red-100 text-red-700'],
];

/* ====== Stats: Thống kê nhanh ====== */
$sqlToday = "SELECT COUNT(*) as so_don, COALESCE(SUM(tong_tien),0) as doanh_thu 
             FROM don_hang 
             WHERE DATE(ngay_dat) = CURDATE() AND trang_thai != 'DaHuy'";
$statToday = $pdo->query($sqlToday)->fetch();

$sqlMonth = "SELECT COUNT(*) as so_don, COALESCE(SUM(tong_tien),0) as doanh_thu 
             FROM don_hang 
             WHERE YEAR(ngay_dat) = YEAR(CURDATE()) 
             AND MONTH(ngay_dat) = MONTH(CURDATE()) 
             AND trang_thai != 'DaHuy'";
$statMonth = $pdo->query($sqlMonth)->fetch();

$statBySt = $pdo->prepare("
  SELECT trang_thai, COUNT(*) as cnt 
  FROM don_hang
  WHERE DATE(ngay_dat) BETWEEN :d1 AND :d2
  GROUP BY trang_thai
");
$statBySt->execute([':d1' => $d1, ':d2' => $d2]);
$bySt = $statBySt->fetchAll(PDO::FETCH_KEY_PAIR);

/* ====== Pagination & List ====== */

// SỬA LỖI SQL Ở ĐÂY: Thay 'id' thành 'd.id' để tránh lỗi ambiguous
$where = "WHERE (:q = '' OR ho_ten LIKE CONCAT('%',:q2,'%') OR so_dien_thoai LIKE CONCAT('%',:q2,'%') OR d.id = :q_exact)
          AND (:st = '' OR trang_thai = :st)
          AND DATE(ngay_dat) BETWEEN :d1 AND :d2";

// Đếm tổng số đơn
$countSql = "SELECT COUNT(*) FROM don_hang d $where"; // Thêm alias d vào đây cho chắc
$qExact = ctype_digit($q) ? (int)$q : -1;

$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute([
    ':q' => $q, ':q2' => $q, ':q_exact' => $qExact,
    ':st' => $st, ':d1' => $d1, ':d2' => $d2
]);
$totalRows = (int)$stmtCount->fetchColumn();
$pages     = max(1, (int)ceil($totalRows / $per));
if ($page > $pages) $page = $pages;
$offset    = ($page - 1) * $per;

// Lấy danh sách đơn hàng
$sqlList = "
  SELECT d.*, 
         COUNT(ct.id) as line_count, 
         COALESCE(SUM(ct.so_luong), 0) as qty_sum
  FROM don_hang d
  LEFT JOIN chi_tiet_don_hang ct ON d.id = ct.don_hang_id
  $where
  GROUP BY d.id
  ORDER BY d.ngay_dat DESC
  LIMIT :lim OFFSET :off
";

$list = $pdo->prepare($sqlList);
$list->bindValue(':q', $q);
$list->bindValue(':q2', $q);
$list->bindValue(':q_exact', $qExact, PDO::PARAM_INT);
$list->bindValue(':st', $st);
$list->bindValue(':d1', $d1);
$list->bindValue(':d2', $d2);
$list->bindValue(':lim', $per, PDO::PARAM_INT);
$list->bindValue(':off', $offset, PDO::PARAM_INT);
$list->execute();
$rows = $list->fetchAll();

function build_url($arr){
  return htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query($arr);
}
?>

<?php
$page_title = 'Quản lý Đơn hàng';
$active = 'orders';
// Include header (nếu có file)
if(file_exists(__DIR__ . '/partials/header.php')) require __DIR__ . '/partials/header.php';
else echo '<div class="flex h-screen bg-slate-50">'; 
?>

<style>
.glass{background:rgba(255,255,255,.95);backdrop-filter:saturate(180%) blur(10px)}
.fade-in{animation:fade .5s ease both}
@keyframes fade{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
.card{transition:transform .15s ease, box-shadow .15s ease}
.card:hover{transform:translateY(-2px); box-shadow:0 10px 20px rgba(0,0,0,.05)}
.pill{box-shadow: inset 0 0 0 1px rgba(0,0,0,.05)}
</style>

<main class="flex-1 overflow-y-auto relative z-10 p-4">
    <header class="mb-6">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Đơn hàng</h1>
            <p class="text-sm text-slate-500">Quản lý và theo dõi trạng thái đơn hàng</p>
        </div>
        
        <form method="get" class="flex gap-2 items-center flex-wrap bg-white p-2 rounded-xl shadow-sm border border-slate-200">
          <div class="relative">
            <input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Mã đơn / Tên / SĐT..."
                   class="w-64 pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <svg class="absolute left-3 top-2.5 text-slate-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="7" cy="7" r="6"/><path d="m14 14-3-3"/></svg>
          </div>
          
          <input type="date" name="d1" value="<?=htmlspecialchars($d1)?>" class="px-3 py-2 rounded-lg border border-slate-300 text-sm">
          <span class="text-slate-400">-</span>
          <input type="date" name="d2" value="<?=htmlspecialchars($d2)?>" class="px-3 py-2 rounded-lg border border-slate-300 text-sm">
          
          <select name="st" class="px-3 py-2 rounded-lg border border-slate-300 text-sm bg-white">
            <option value="">Tất cả trạng thái</option>
            <?php foreach($statusMap as $key => $val): ?>
              <option value="<?=$key?>" <?=$st===$key?'selected':''?>><?=$val['label']?></option>
            <?php endforeach; ?>
          </select>
          
          <button class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition text-sm font-medium">Lọc</button>
        </form>
      </div>
    </header>

    <section class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
          <div class="text-slate-500 text-sm font-medium">Đơn hôm nay</div>
          <div class="text-2xl font-bold mt-1 text-slate-800" data-count="<?=$statToday['so_don']?>">0</div>
          <div class="text-xs text-slate-500 mt-1">Doanh thu: <b class="text-emerald-600"><?=df($statToday['doanh_thu'])?>đ</b></div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100">
          <div class="text-slate-500 text-sm font-medium">Đơn tháng này</div>
          <div class="text-2xl font-bold mt-1 text-slate-800" data-count="<?=$statMonth['so_don']?>">0</div>
          <div class="text-xs text-slate-500 mt-1">Doanh thu: <b class="text-emerald-600"><?=df($statMonth['doanh_thu'])?>đ</b></div>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-100 col-span-2">
          <div class="text-slate-500 text-sm font-medium mb-2">Thống kê theo trạng thái (Kết quả lọc)</div>
          <div class="flex gap-2 flex-wrap">
            <?php foreach($statusMap as $key => $val): 
                $cnt = $bySt[$key] ?? 0;
                if($cnt > 0):
            ?>
              <span class="px-3 py-1 rounded-full text-xs font-medium border <?=$val['class']?> border-opacity-20 bg-opacity-10">
                  <?=$val['label']?>: <b><?=$cnt?></b>
              </span>
            <?php endif; endforeach; ?>
          </div>
        </div>
      </div>

      <div class="grid gap-3">
        <?php if(empty($rows)): ?>
            <div class="text-center py-10 text-slate-500 bg-white rounded-xl shadow-sm">Không tìm thấy đơn hàng nào.</div>
        <?php else: foreach($rows as $o):
          $stInfo = $statusMap[$o['trang_thai']] ?? ['label' => $o['trang_thai'], 'class' => 'bg-gray-100 text-gray-700'];
        ?>
        <div class="bg-white rounded-xl p-4 card border border-slate-100 shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-start gap-4 flex-1">
              <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-sm shrink-0">
                #<?=$o['id']?>
              </div>
              <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-bold text-slate-800 text-lg"><?=htmlspecialchars($o['ho_ten'] ?? 'Khách vãng lai')?></span>
                    <span class="px-2.5 py-0.5 text-xs rounded-md font-medium <?=$stInfo['class']?>">
                        <?=$stInfo['label']?>
                    </span>
                </div>
                <div class="text-sm text-slate-500 mt-0.5">
                  SĐT: <?=htmlspecialchars($o['so_dien_thoai'])?> • 
                  Ngày: <?=date('d/m/Y H:i', strtotime($o['ngay_dat']))?>
                  <?php if($o['phuong_thuc_thanh_toan']): ?>
                    • TT: <span class="uppercase"><?=htmlspecialchars($o['phuong_thuc_thanh_toan'])?></span>
                  <?php endif; ?>
                </div>
                <div class="mt-2 text-sm text-slate-600 flex gap-4">
                  <span>Sản phẩm: <b><?=$o['line_count']?></b> loại</span>
                  <span>Tổng SL: <b><?=$o['qty_sum']?></b></span>
                  <span>Tổng tiền: <b class="text-red-600 text-base"><?=df($o['tong_tien'])?>đ</b></span>
                </div>
                <div class="text-xs text-slate-400 mt-1 truncate max-w-lg">
                    Địa chỉ: <?=htmlspecialchars($o['dia_chi'])?>
                </div>
              </div>
            </div>

            <div class="flex gap-2 shrink-0 self-end md:self-center">
              <a class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium transition" 
                 href="order_detail.php?id=<?=$o['id']?>">
                 Chi tiết
              </a>

            </div>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>

      <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-slate-500">
          Hiển thị <b><?=empty($rows) ? 0 : $offset+1?></b> - <b><?=$offset+count($rows)?></b> trên <b><?=$totalRows?></b> đơn
        </div>
        <nav class="flex items-center gap-1">
          <?php
            $base = ['q'=>$q, 'st'=>$st, 'd1'=>$d1, 'd2'=>$d2, 'per'=>$per];
            if ($page > 1) echo '<a class="px-3 py-1.5 rounded-lg border bg-white hover:bg-slate-50 text-sm" href="'.build_url($base+['page'=>$page-1]).'">Trước</a>';
            
            $win = 2; $start = max(1, $page-$win); $end = min($pages, $page+$win);
            
            if($start > 1){ 
                echo '<a class="px-3 py-1.5 rounded-lg border bg-white hover:bg-slate-50 text-sm" href="'.build_url($base+['page'=>1]).'">1</a>'; 
                if($start > 2) echo '<span class="px-2 text-slate-400">...</span>'; 
            }
            
            for($p = $start; $p <= $end; $p++){ 
                $cls = $p == $page ? 'bg-blue-600 text-white border-blue-600 shadow-sm shadow-blue-200' : 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200';
                echo '<a class="px-3 py-1.5 rounded-lg border text-sm font-medium transition '.$cls.'" href="'.build_url($base+['page'=>$p]).'">'.$p.'</a>'; 
            }
            
            if($end < $pages){ 
                if($end < $pages - 1) echo '<span class="px-2 text-slate-400">...</span>'; 
                echo '<a class="px-3 py-1.5 rounded-lg border bg-white hover:bg-slate-50 text-sm" href="'.build_url($base+['page'=>$pages]).'">'.$pages.'</a>'; 
            }
            
            if ($page < $pages) echo '<a class="px-3 py-1.5 rounded-lg border bg-white hover:bg-slate-50 text-sm" href="'.build_url($base+['page'=>$page+1]).'">Sau</a>';
          ?>
        </nav>
      </div>
    </section>
</main>
<?php if(!file_exists(__DIR__ . '/partials/header.php')) echo '</div>'; ?>

<script>
document.querySelectorAll('[data-count]').forEach(el=>{
  const t = +el.dataset.count; 
  if(t === 0) { el.textContent = '0'; return; }
  let v = 0, step = Math.max(1, Math.round(t/20));
  const tick = () => { 
      v += step; 
      if(v > t) v = t; 
      el.textContent = new Intl.NumberFormat('vi-VN').format(v); 
      if(v < t) requestAnimationFrame(tick); 
  };
  tick();
});
</script>