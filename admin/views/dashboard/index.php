<?php 
$title = "Dashboard - Tổng quan"; // Đặt tiêu đề trang
include __DIR__ . "/../layout/header.php"; 
?>

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?php echo $model["totalProducts"] ?? 0; ?></h3>
                <p>Sản phẩm</p>
            </div>
            <div class="icon">
                <i class="bi bi-bag"></i>
            </div>
            <a href="#" class="small-box-footer">Xem thêm <i class="bi bi-arrow-right-circle"></i></a>
        </div>
    </div>
    
    </div>

<?php include __DIR__ . "/../layout/footer.php"; ?>