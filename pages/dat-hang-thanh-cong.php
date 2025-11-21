<?php include('../header.php'); ?>

<div class="container" style="padding: 50px 0; text-align: center;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        
        <i class="fa-solid fa-circle-check" style="font-size: 60px; color: #28a745; margin-bottom: 20px;"></i>
        
        <h2 style="font-weight: bold; margin-bottom: 10px;">ĐẶT HÀNG THÀNH CÔNG!</h2>
        <p>Cảm ơn bạn đã mua sắm tại 4MEN.</p>
        <p>Mã đơn hàng của bạn là: <strong style="color: #b35d2a;">#<?php echo $_GET['id'] ?? '---'; ?></strong></p>
        
        <div style="margin-top: 30px;">
            <a href="../index.php" class="btn btn-primary" style="background-color: #b35d2a; border: none; padding: 10px 25px;">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>

<?php include('../footer.php'); ?>