<?php
$title = ($model->ProductID == 0) ? "Thêm sản phẩm" : "Cập nhật sản phẩm";
?>

<!-- TOOLBAR -->
<div class="d-flex justify-content-between mb-3">
    <h5><?= $title ?></h5>

    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu dữ liệu
        </button>
        <a href="index.php?controller=product" class="btn btn-secondary ms-1">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="bi bi-box-seam mr-2"></i> Thông tin mặt hàng</h5>
            </div>

            <div class="card-body">

                <?php if (!empty($_SESSION['SuccessMessage'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= $_SESSION['SuccessMessage'] ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    <?php unset($_SESSION['SuccessMessage']); ?>
                <?php endif; ?>

                <form id="formEdit" method="post" action="index.php?controller=product&action=save" enctype="multipart/form-data">
                    
                    <input type="hidden" name="ProductID" value="<?= $model->ProductID ?? 0 ?>" />
                    <input type="hidden" name="Photo" value="<?= htmlspecialchars($model->Photo ?? '') ?>" />

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tên mặt hàng</label>
                        <div class="col-sm-9">
                            <input type="text" name="ProductName" class="form-control" autofocus
                                value="<?= htmlspecialchars($model->ProductName ?? '') ?>" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Loại hàng</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="CategoryID">
                                <option value="0">-- Chọn loại hàng --</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= $c['value'] ?>"
                                        <?= (($model->CategoryID ?? 0) == $c['value']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['text']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-2 col-form-label text-right">Nhà cung cấp</label>
                        <div class="col-sm-4">
                            <select class="form-control" name="SupplierID">
                                <option value="0">-- Chọn nhà cung cấp --</option>
                                <?php foreach ($suppliers as $s): ?>
                                    <option value="<?= $s['value'] ?>"
                                        <?= (($model->SupplierID ?? 0) == $s['value']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['text']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Đơn vị tính</label>
                        <div class="col-sm-3">
                            <input type="text" name="Unit" class="form-control"
                                value="<?= htmlspecialchars($model->Unit ?? '') ?>" />
                        </div>

                        <label class="col-sm-2 col-form-label text-right">Giá</label>
                        <div class="col-sm-4">
                            <input type="text" name="Price" class="form-control"
                                value="<?= number_format($model->Price ?? 0) ?>" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Trạng thái</label>
                        <div class="col-sm-9">
                            <input type="checkbox" name="IsSelling" value="1"
                                <?= !empty($model->IsSelling) ? 'checked' : '' ?> />
                            Đang bán
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Ảnh</label>
                        <div class="col-sm-9">
                            <input type="file" name="uploadPhoto" class="form-control-file"
                                onchange="document.getElementById('imgPreview').src = window.URL.createObjectURL(this.files[0])" />

                            <div class="mt-2">
                                <?php $photo = empty($model->Photo) ? 'noproduct.png' : $model->Photo; ?>
                                <img id="imgPreview"
                                    src="images/products/<?= htmlspecialchars($photo) ?>"
                                    class="img-thumbnail"
                                    style="max-height: 160px;" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Mô tả</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="ProductDescription" rows="3"><?= htmlspecialchars($model->ProductDescription ?? '') ?></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($model->ProductID) && $model->ProductID > 0): ?>
    <hr />
    <?php include 'ListPhotos.php'; ?>
    <?php include 'ListAttributes.php'; ?>
<?php endif; ?>