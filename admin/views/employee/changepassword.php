<?php
$title = "Mật khẩu nhân viên";
?>

<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>

        <div>
            <button type="submit" form="formChangePasswordEmployee" class="btn btn-primary">
                <i class="bi bi-key me-1"></i>
                Đổi mật khẩu
            </button>
            <a href="index.php?controller=employee" class="btn btn-secondary ms-1">
                <i class="bi bi-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-lock me-2"></i>
                        Đổi mật khẩu nhân viên
                    </h5>
                </div>

                <div class="card-body">
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $err): ?>
                                <div><i class="bi bi-exclamation-triangle me-2"></i><?= $err ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i><?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form id="formChangePasswordEmployee" method="post"
                          action="index.php?controller=employee&action=changePasswordPost">

                        <input type="hidden" name="id" value="<?= $model->EmployeeID ?>">

                        <fieldset class="mb-4">
                            <legend class="fs-6 text-muted mb-3 border-bottom pb-2">
                                <i class="bi bi-person me-1"></i>
                                Thông tin tài khoản
                            </legend>

                            <dl class="row mb-0">
                                <dt class="col-sm-3">Họ và tên</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($model->FullName) ?></dd>

                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($model->Email) ?></dd>
                            </dl>
                        </fieldset>

                        <fieldset>
                            <legend class="fs-6 text-muted mb-3 border-bottom pb-2">
                                <i class="bi bi-lock me-1"></i>
                                Thiết lập mật khẩu mới
                            </legend>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mật khẩu mới</label>
                                <input type="password"
                                       name="newPassword"
                                       class="form-control"
                                       placeholder="Nhập mật khẩu mới">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Xác nhận mật khẩu</label>
                                <input type="password"
                                       name="confirmPassword"
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