<?php
$title = "Xóa khách hàng";
$allowDelete = $model['AllowDelete'] ?? false;
?>

<div class="container-fluid mt-3">

    <!-- TOOLBAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>

        <div>
            <?php if ($allowDelete): ?>
                <button type="submit" form="formDelete" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>
                    Xóa
                </button>
            <?php endif; ?>

            <a href="index.php?controller=customer" class="btn btn-secondary ms-1">
                <i class="bi bi-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Xác nhận xóa khách hàng
                    </h5>
                </div>

                <div class="card-body">

                    <form id="formDelete" method="post"
                          action="index.php?controller=customer&action=deletePost&id=<?= $model['CustomerID'] ?>">

                        <!-- ALERT -->
                        <?php if ($allowDelete): ?>
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="bi bi-info-circle me-2"></i>
                                 Bạn có chắc chắn muốn xóa khách hàng sau không?
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning d-flex align-items-center">
                                <i class="bi bi-info-circle me-2"></i>
                                Khách hàng này không thể xóa vì đã có đơn hàng.
                            </div>
                        <?php endif; ?>

                        <!-- INFO -->
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Tên khách hàng</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['CustomerName'] ?? '') ?></dd>

                            <dt class="col-sm-4">Tên giao dịch</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['ContactName'] ?? '') ?></dd>

                            <dt class="col-sm-4">Trạng thái</dt>
                            <dd class="col-sm-8">
                                <?php if (!empty($model['IsLocked'])): ?>
                                    <span class="badge bg-warning">Đã bị khóa</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Đang hoạt động</span>
                                <?php endif; ?>
                            </dd>

                            <dt class="col-sm-4">Điện thoại</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['Phone'] ?? '') ?></dd>

                            <dt class="col-sm-4">Email</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['Email'] ?? '') ?></dd>

                            <dt class="col-sm-4">Địa chỉ</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['Address'] ?? '') ?></dd>

                            <dt class="col-sm-4">Tỉnh / Thành</dt>
                            <dd class="col-sm-8"><?= htmlspecialchars($model['Province'] ?? '') ?></dd>
                        </dl>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>