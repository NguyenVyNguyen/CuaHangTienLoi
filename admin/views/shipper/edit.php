<?php
$title = ($model['ShipperID'] == 0) ? "Bổ sung người giao hàng" : "Cập nhật người giao hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>

    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            <i class="bi bi-save"></i>
            Lưu dữ liệu
        </button>
        <a href="index.php?controller=shipper&action=index" class="btn btn-secondary ms-1">
            Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form id="formEdit" method="post"
              action="index.php?controller=shipper&action=save">

            <input type="hidden" name="ShipperID" value="<?= $model['ShipperID'] ?>">

            <div class="mb-3">
                <label>Tên người giao hàng</label>
                <input type="text" name="ShipperName"
                       value="<?= htmlspecialchars($model['ShipperName']) ?>"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Điện thoại</label>
                <input type="text" name="Phone"
                       value="<?= htmlspecialchars($model['Phone']) ?>"
                       class="form-control">
            </div>

        </form>
    </div>
</div>