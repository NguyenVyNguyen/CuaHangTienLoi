<h5>Chi tiết khuyến mãi</h5>

<div class="card">
    <div class="card-body">

        <p><b><?= $promotion['PromotionName'] ?></b></p>

        <!-- CATEGORY -->
        <?php if (!empty($targets['categories'])): ?>
            <h6>Áp dụng cho loại hàng:</h6>
            <?php foreach ($targets['categories'] as $c): ?>
                <span class="badge bg-primary"><?= $c['CategoryName'] ?></span>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- PRODUCT -->
        <?php if (!empty($targets['products'])): ?>
            <h6 class="mt-3">Áp dụng cho sản phẩm:</h6>
            <?php foreach ($targets['products'] as $p): ?>
                <span class="badge bg-success"><?= $p['ProductName'] ?></span>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<a href="index.php?controller=promotion" class="btn btn-secondary mt-3">
    Quay lại
</a>