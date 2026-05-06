<?php
$title = ($model->EmployeeID == 0) ? "Thêm nhân viên" : "Cập nhật nhân viên";
?>

<!-- TOOLBAR -->
<div class="d-flex justify-content-between mb-3">
    <h5><?= $title ?></h5>

    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu dữ liệu
        </button>
        <a href="index.php?controller=employee" class="btn btn-secondary ms-1">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Thông tin nhân viên
                </h5>
            </div>

            <div class="card-body">
                <form id="formEdit"
                    method="post"
                    enctype="multipart/form-data"
                    action="index.php?controller=employee&action=save">

                    <!-- ID -->
                    <input type="hidden" name="EmployeeID" value="<?= $model->EmployeeID ?>">

                    <!-- HỌ TÊN + NGÀY SINH -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Họ và tên</label>
                        <div class="col-sm-5">
                            <input type="text"
                                name="FullName"
                                value="<?= htmlspecialchars($model->FullName) ?>"
                                class="form-control" autofocus>

                            <?php if (!empty($errors['FullName'])): ?>
                                <span class="text-danger"><?= $errors['FullName'] ?></span>
                            <?php endif; ?>
                        </div>

                        <label class="col-sm-2 col-form-label text-end">Ngày sinh</label>
                        <div class="col-sm-2">
                            <input type="date"
                                name="BirthDate"
                                value="<?= $model->BirthDate ?>"
                                class="form-control">
                        </div>
                    </div>

                    <!-- ĐỊA CHỈ -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Địa chỉ</label>
                        <div class="col-sm-9">
                            <textarea name="Address"
                                class="form-control"
                                rows="3"><?= htmlspecialchars($model->Address) ?></textarea>
                        </div>
                    </div>

                    <!-- PHONE + EMAIL -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Điện thoại</label>
                        <div class="col-sm-2">
                            <input type="text"
                                name="Phone"
                                value="<?= $model->Phone ?>"
                                class="form-control">
                        </div>

                        <label class="col-sm-1 col-form-label text-end">Email</label>
                        <div class="col-sm-6">
                            <input type="email"
                                name="Email"
                                value="<?= $model->Email ?>"
                                class="form-control">

                            <?php if (!empty($errors['Email'])): ?>
                                <span class="text-danger"><?= $errors['Email'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ẢNH -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Ảnh</label>
                        <div class="col-sm-9">

                            <input type="hidden" name="CurrentPhoto" value="<?= $model->Photo ?>">

                            <input type="file"
                                name="uploadPhoto"
                                class="form-control"
                                accept="image/*"
                                data-img-preview="imgPreview"
                                onchange="previewImage(this)">

                            <div class="mt-2">
                                <img id="imgPreview"
                                    src="/admin/images/employees/<?= !empty($model->Photo) ? $model->Photo : 'nophoto.png' ?>"
                                    class="img-thumbnail"
                                    style="max-height:150px;">
                            </div>
                        </div>
                    </div>

                    <!-- TRẠNG THÁI -->
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Trạng thái</label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="IsWorking"
                                    <?= $model->IsWorking ? 'checked' : '' ?>>
                                <label class="form-check-label">
                                    Đang làm việc
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- VALIDATION SUMMARY -->
                    <?php if (!empty($errors)): ?>
                        <div class="row mb-3">
                            <div class="col-sm-9 offset-3">
                                <div class="text-danger">
                                    <?php foreach ($errors as $err): ?>
                                        <div><?= $err ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        const img = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
        }
    }
</script>