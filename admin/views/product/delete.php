<?php
$viewTitle = "Xóa mặt hàng";
?>

<div class="mb-3">
    <?php if ($allowDelete): ?>
        <button type="submit" form="formDelete" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>
            Xóa mặt hàng
        </button>
    <?php endif; ?>
    <a href="/Product" class="btn btn-secondary ms-1">
        <i class="bi bi-arrow-left"></i>
        Quay lại
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-danger card-outline">
            <div class="card-header">
                <h5 class="card-title mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Xác nhận xóa mặt hàng
                </h5>
            </div>

            <div class="card-body">
                <form id="formDelete" method="post" action="/Product/Delete.php?id=<?= $model->ProductID ?>">

                    <?php if ($allowDelete): ?>
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="bi bi-info-circle me-2"></i>
                            Bạn có chắc chắn muốn xóa mặt hàng sau không? (Hành động này không thể hoàn tác)
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="bi bi-exclamation-octagon me-2"></i>
                            Mặt hàng này đã có trong đơn hàng hoặc dữ liệu liên quan nên <strong>KHÔNG THỂ XÓA</strong>!
                        </div>
                    <?php endif; ?>

                    <div class="row mt-3">
                        <div class="col-md-8">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Tên mặt hàng</dt>
                                <dd class="col-sm-8 fw-bold text-primary"><?= htmlspecialchars($model->ProductName) ?></dd>

                                <dt class="col-sm-4">Đơn vị tính</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($model->Unit) ?></dd>

                                <dt class="col-sm-4">Giá bán</dt>
                                <dd class="col-sm-8 text-danger fw-bold"><?= number_format($model->Price, 0, '.', ',') ?> đ</dd>

                                <dt class="col-sm-4">Trạng thái</dt>
                                <dd class="col-sm-8">
                                    <?php if ($model->IsSelling): ?>
                                        <span class="badge bg-success">Đang bán</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ngừng kinh doanh</span>
                                    <?php endif; ?>
                                </dd>

                                <dt class="col-sm-4">Mô tả</dt>
                                <dd class="col-sm-8">
                                    <?= empty($model->ProductDescription) ? "(Không có mô tả)" : htmlspecialchars($model->ProductDescription) ?>
                                </dd>
                            </dl>
                        </div>

                        <div class="col-md-4 text-center">
                            <?php 
                                $imageName = empty($model->Photo) ? "noproduct.png" : $model->Photo;
                            ?>
                            <img src="/images/products/<?= $imageName ?>"
                                 class="img-fluid img-thumbnail"
                                 alt="Ảnh mặt hàng"
                                 style="max-height: 250px; object-fit: contain;" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>