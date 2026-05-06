<div class="card">
    <div class="card-body">

        <form method="post"
              action="index.php?controller=employee&action=delete&id=<?= $model['EmployeeID'] ?>">

            <?php if ($model['AllowDelete']): ?>
                <div class="alert alert-warning">
                    Bạn có chắc chắn muốn xóa?
                </div>

                <button class="btn btn-danger">Xóa</button>
            <?php else: ?>
                <div class="alert alert-warning">
                    Không thể xóa
                </div>
            <?php endif; ?>

            <a href="index.php?controller=employee" class="btn btn-secondary">Quay lại</a>

        </form>

    </div>
</div>