<?php
$title = ($model['CustomerID'] == 0) ? "Bổ sung khách hàng" : "Cập nhật khách hàng";
?>

<!-- FIX: thêm container + margin-top -->
<div class="container-fluid mt-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><?= $title ?></h5>

        <div>
            <button type="submit" form="formEdit" class="btn btn-primary">
                <i class="bi bi-save"></i>
                Lưu dữ liệu
            </button>
            <a href="index.php?controller=customer" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people me-2"></i>
                        Thông tin khách hàng
                    </h5>
                </div>

                <!-- FIX: thêm card-body để có padding -->
                <div class="card-body">

                    <form id="formEdit" method="post"
                        action="index.php?controller=customer&action=save">

                        <input type="hidden" name="CustomerID" value="<?= $model['CustomerID'] ?>">

                        <div class="row align-items-end">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Tên khách hàng</label>
                                <input type="text"
                                    name="CustomerName"
                                    class="form-control"
                                    value="<?= htmlspecialchars($model['CustomerName'] ?? '') ?>"
                                    autofocus>
                                <?php if (!empty($errors['CustomerName'])): ?>
                                    <span class="text-danger"><?= $errors['CustomerName'] ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-5 mb-3">
                                <label class="form-label">Tên giao dịch</label>
                                <input type="text"
                                    name="ContactName"
                                    class="form-control"
                                    value="<?= htmlspecialchars($model['ContactName'] ?? '') ?>">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label d-block">Trạng thái</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="IsLocked"
                                        id="IsLocked"
                                        value="1"
                                        <?= !empty($model['IsLocked']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="IsLocked">
                                        Đã bị khóa
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="tel"
                                    name="Phone"
                                    class="form-control"
                                    placeholder="Nhập số điện thoại"
                                    value="<?= htmlspecialchars($model['Phone'] ?? '') ?>">
                            </div>

                            <div class="col-md-7 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                    name="Email"
                                    class="form-control"
                                    placeholder="Nhập email"
                                    value="<?= htmlspecialchars($model['Email'] ?? '') ?>">
                                <?php if (!empty($errors['Email'])): ?>
                                    <span class="text-danger"><?= $errors['Email'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control"
                                name="Address"
                                rows="3"
                                placeholder="Nhập địa chỉ"><?= htmlspecialchars($model['Address'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tỉnh / Thành</label>
                            <select name="Province" class="form-control select2" style="width: 100%;">
                                <?php foreach ($provinces as $p): ?>
                                    <option value="<?= htmlspecialchars($p['value'] ?? '') ?>"
                                        <?= (($p['value'] ?? '') == ($model['Province'] ?? '')) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['text'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <?php if (!empty($errors['Province'])): ?>
                                <span class="text-danger"><?= $errors['Province'] ?></span>
                            <?php endif; ?>
                        </div>

                    </form>

                </div> 
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "-- Tỉnh/Thành phố --",
            allowClear: true
        });
    });
</script>