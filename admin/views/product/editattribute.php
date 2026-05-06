
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" form="formEdit" class="btn btn-primary" style="margin-right: 10px;">
            <i class="bi bi-save"></i> Lưu thuộc tính
        </button>

        <a href="index.php?controller=product&action=edit&id=<?= $model['ProductID'] ?? 0 ?>#attributes"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h5 class="mb-0">Thông tin thuộc tính</h5>
            </div>

            <div class="card-body">

                <form id="formEdit" method="post"
                      action="index.php?controller=product&action=saveAttribute">

                    <input type="hidden" name="AttributeID" value="<?= $model['AttributeID'] ?>" />
                    <input type="hidden" name="ProductID" value="<?= $model['ProductID'] ?>" />

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mb-3">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Tên thuộc tính</label>
                        <div class="col-sm-9">
                            <input type="text" name="AttributeName"
                                   class="form-control"
                                   value="<?= htmlspecialchars($model['AttributeName'] ?? '') ?>"
                                   required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Giá trị</label>
                        <div class="col-sm-9">
                            <input type="text" name="AttributeValue"
                                   class="form-control"
                                   value="<?= htmlspecialchars($model['AttributeValue'] ?? '') ?>"
                                   required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Thứ tự hiển thị</label>
                        <div class="col-sm-3">
                            <input type="number" name="DisplayOrder"
                                   class="form-control"
                                   value="<?= $model['DisplayOrder'] ?? 1 ?>" />
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>