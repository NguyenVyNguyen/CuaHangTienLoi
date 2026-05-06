<?php $title = "Xóa nhà cung cấp"; ?>

<div class="d-flex justify-content-between mb-3">
    <h5><?= $title ?></h5>

    <div>
        <?php if ($model['AllowDelete']): ?>
            <button type="submit" form="formDelete" class="btn btn-danger">
                Xóa
            </button>
        <?php endif; ?>

        <a href="index.php?controller=supplier&action=index"
           class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <form id="formDelete" method="post"
              action="index.php?controller=supplier&action=deletePost&id=<?= $model['SupplierID'] ?>">

            <?php if ($model['AllowDelete']): ?>
                <div class="alert alert-warning">
                    Bạn có chắc muốn xóa không?
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    Không thể xóa do có dữ liệu liên quan
                </div>
            <?php endif; ?>

            <dl class="row">
                <dt class="col-sm-3">Tên</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['SupplierName']) ?></dd>

                <dt class="col-sm-3">Giao dịch</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['ContactName']) ?></dd>

                <dt class="col-sm-3">Điện thoại</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Phone']) ?></dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Email']) ?></dd>

                <dt class="col-sm-3">Địa chỉ</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Address']) ?></dd>

                <dt class="col-sm-3">Tỉnh</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($model['Province']) ?></dd>
            </dl>

        </form>
    </div>
</div>