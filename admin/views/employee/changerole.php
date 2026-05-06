<?php
$title = "Phân quyền nhân viên";
?>

<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>

        <div>
            <button type="submit" form="formChangeRole" class="btn btn-primary">
                <i class="bi bi-shield-check me-1"></i>
                Lưu phân quyền
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
                        <i class="bi bi-shield-check me-2"></i>
                        Phân quyền cho nhân viên
                    </h5>
                </div>

                <div class="card-body">
                    <!-- ✅ FIX ACTION -->
                    <form id="formChangeRole" method="post" action="index.php?controller=employee&action=saveRole">
                        
                        <input type="hidden" name="id" value="<?= $model->EmployeeID ?>">

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i><?= $success ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <fieldset class="mb-4">
                            <legend class="fs-6 text-muted mb-3 border-bottom pb-2">
                                <i class="bi bi-person me-1"></i>
                                Thông tin nhân viên
                            </legend>

                            <dl class="row mb-0">
                                <dt class="col-sm-3">Họ và tên</dt>
                                <dd class="col-sm-9 fw-bold"><?= htmlspecialchars($model->FullName) ?></dd>

                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($model->Email) ?></dd>

                                <dt class="col-sm-3">Trạng thái</dt>
                                <dd class="col-sm-9">
                                    <?php if ($model->IsWorking): ?>
                                        <span class="badge bg-success">Đang làm việc</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Đã nghỉ việc</span>
                                    <?php endif; ?>
                                </dd>
                            </dl>
                        </fieldset>

                        <fieldset>
                            <legend class="fs-6 text-muted mb-3 border-bottom pb-2">
                                <i class="bi bi-shield-lock me-1"></i>
                                Danh sách quyền
                            </legend>

                            <?php 
                            // ✅ ROLE MỚI
                            $availableRoles = [
                                ['value' => 'Admin', 'name' => 'Quản lý hệ thống', 'desc' => 'Toàn quyền hệ thống'],
                                ['value' => 'Sales', 'name' => 'Nhân viên bán hàng', 'desc' => 'Quản lý đơn hàng, khách hàng'],
                                ['value' => 'Warehouse', 'name' => 'Quản lý kho', 'desc' => 'Quản lý sản phẩm, tồn kho'],
                            ];

                            // ✅ FIX: convert string → array
                            $currentRoles = !empty($model->RoleNames) 
                                ? explode(",", $model->RoleNames) 
                                : [];
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:60px;" class="text-center">Chọn</th>
                                            <th>Tên quyền</th>
                                            <th>Mô tả quyền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($availableRoles as $role): 
                                            $isChecked = in_array($role['value'], $currentRoles) ? 'checked' : '';
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="roles[]" value="<?= $role['value'] ?>" 
                                                       <?= $isChecked ?>>
                                            </td>
                                            <td class="fw-bold"><?= $role['name'] ?></td>
                                            <td class="text-muted small"><?= $role['desc'] ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>