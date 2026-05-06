<?php
$title = "Xóa người giao hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>

    <div>
        <button type="submit" form="formDelete" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>
            Xóa
        </button>

        <a href="index.php?controller=shipper&action=index" class="btn btn-secondary ms-1">
            Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="formDelete" method="post"
              action="index.php?controller=shipper&action=deletePost&id=<?= $model['ShipperID'] ?>">

            <div class="alert alert-warning">
                Bạn có chắc muốn xóa người giao hàng này không?
            </div>

            <dl class="row">
                <dt class="col-sm-3">Tên</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['ShipperName']) ?></dd>

                <dt class="col-sm-3">Điện thoại</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Phone']) ?></dd>
            </dl>
        </form>
    </div>
</div>