<?php
$title = "Xóa loại hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>

    <div>
        <button type="submit" form="formDelete" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>
            Xóa
        </button>

        <a href="index.php?controller=category&action=index" class="btn btn-secondary ms-1">
            <i class="bi bi-arrow-left"></i>
            Quay lại
        </a>
    </div>
</div>

<div class="card card-danger card-outline">
    <div class="card-body">
        <form id="formDelete" method="post"
            action="index.php?controller=category&action=deletePost&id=<?= $model['CategoryID'] ?>">

            <div class="alert alert-warning">
                Bạn có chắc muốn xóa loại hàng này không?
            </div>

            <dl class="row">
                <dt class="col-sm-3">Tên loại hàng</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['CategoryName']) ?></dd>

                <dt class="col-sm-3">Mô tả</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Description']) ?></dd>
            </dl>
        </form>
    </div>
</div>