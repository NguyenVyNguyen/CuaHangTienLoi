<?php
$title = ($model['CategoryID'] == 0) ? "Bổ sung loại hàng" : "Cập nhật loại hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>
    
    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            <i class="bi bi-save"></i>
            Lưu dữ liệu
        </button>
        <a href="index.php?controller=category&action=index" class="btn btn-secondary ms-1">
            Quay lại
        </a>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-body">
        <form id="formEdit" method="post" action="index.php?controller=category&action=save">

            <input type="hidden" name="CategoryID" value="<?= $model['CategoryID'] ?>">

            <div class="mb-3">
                <label class="form-label">Tên loại hàng</label>
                <input type="text" name="CategoryName"
                    value="<?= htmlspecialchars($model['CategoryName']) ?>"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="Description"
                    class="form-control"><?= htmlspecialchars($model['Description']) ?></textarea>
            </div>
        </form>
    </div>
</div>