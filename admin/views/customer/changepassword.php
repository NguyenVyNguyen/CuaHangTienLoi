<?php
$title = "Mật khẩu khách hàng";
?>

<div class="container-fluid mt-3">

    <!-- TOOLBAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>

        <div>
            <button type="submit" form="formChangePassword" class="btn btn-primary">
                <i class="bi bi-key me-1"></i>
                Đổi mật khẩu
            </button>
            <a href="index.php?controller=customer" class="btn btn-secondary ms-1">
                <i class="bi bi-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Đổi mật khẩu khách hàng
                    </h5>
                </div>

                <div class="card-body">

                    <form id="formChangePassword" method="post"
                          action="index.php?controller=customer&action=changePassword&id=<?= $model['CustomerID'] ?>">

                        <!-- THÔNG TIN KHÁCH HÀNG -->
                        <fieldset class="mb-4">
                            <legend class="fs-6 text-muted mb-3">
                                <i class="bi bi-person me-1"></i>
                                Thông tin khách hàng
                            </legend>

                            <dl class="row mb-0">
                                <dt class="col-sm-3">Tên khách hàng</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($model['CustomerName'] ?? '') ?></dd>

                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($model['Email'] ?? '') ?></dd>

                                <dt class="col-sm-3">Trạng thái</dt>
                                <dd class="col-sm-9">
                                    <?php if (!empty($model['IsLocked'])): ?>
                                        <span class="badge bg-warning">Đã bị khóa</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </fieldset>

                        <!-- MẬT KHẨU -->
                        <fieldset>
                            <legend class="fs-6 text-muted mb-3">
                                <i class="bi bi-key me-1"></i>
                                Thông tin mật khẩu
                            </legend>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input type="password"
                                       name="NewPassword"
                                       class="form-control"
                                       placeholder="Nhập mật khẩu mới">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input type="password"
                                       name="ConfirmPassword"
                                       class="form-control"
                                       placeholder="Nhập lại mật khẩu mới">
                            </div>

                        </fieldset>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>