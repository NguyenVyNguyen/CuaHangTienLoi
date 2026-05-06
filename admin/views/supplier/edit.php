<?php
$title = ($model['SupplierID'] == 0) ? "Bổ sung nhà cung cấp" : "Cập nhật nhà cung cấp";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5><?= $title ?></h5>

    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu dữ liệu
        </button>

        <a href="index.php?controller=supplier&action=index" class="btn btn-secondary">
            Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="formEdit" method="post"
            action="index.php?controller=supplier&action=save">

            <input type="hidden" name="SupplierID" value="<?= $model['SupplierID'] ?>">

            <div class="mb-3">
                <label>Tên nhà cung cấp</label>
                <input type="text" name="SupplierName"
                    value="<?= htmlspecialchars($model['SupplierName']) ?>"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label>Tên giao dịch</label>
                <input type="text" name="ContactName"
                    value="<?= htmlspecialchars($model['ContactName']) ?>"
                    class="form-control">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Điện thoại</label>
                    <input type="text" name="Phone"
                        value="<?= htmlspecialchars($model['Phone']) ?>"
                        class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="Email"
                        value="<?= htmlspecialchars($model['Email']) ?>"
                        class="form-control">
                </div>
            </div>

            <div class="mb-3">
                <label>Địa chỉ</label>
                <textarea name="Address"
                    class="form-control"><?= htmlspecialchars($model['Address']) ?></textarea>
            </div>

            <div class="mb-3">
                <label>Tỉnh / Thành</label>
                <select name="Province" class="form-control select2" style="width: 100%;">
                    <?php foreach ($provinces as $p): ?>
                        <option value="<?= htmlspecialchars($p['value'] ?? '') ?>"
                            <?= (($p['value'] ?? '') == ($model['Province'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['text'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </form>
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