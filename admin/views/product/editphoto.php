<div class="row mb-3">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" form="formEdit" class="btn btn-primary" style="margin-right: 10px;">
            <i class="bi bi-save"></i> Lưu ảnh
        </button>

        <a href="index.php?controller=product&action=edit&id=<?= $model['ProductID'] ?? 0 ?>#photos"
           class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h5 class="mb-0">Thông tin ảnh mặt hàng</h5>
            </div>

            <div class="card-body">

                <form id="formEdit" method="post"
                      enctype="multipart/form-data"
                      action="index.php?controller=product&action=savePhoto">

                    <input type="hidden" name="PhotoID" value="<?= $model['PhotoID'] ?>" />
                    <input type="hidden" name="ProductID" value="<?= $model['ProductID'] ?>" />

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mb-3">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Ảnh</label>
                        <div class="col-sm-9">

                            <input type="hidden" name="Photo" value="<?= htmlspecialchars($model['Photo'] ?? 'nophoto.png') ?>" />

                            <input type="file"
                                   name="uploadPhoto"
                                   class="form-control"
                                   accept="image/*"
                                   onchange="previewImage(this)" />

                            <div class="mt-2">
                                <?php 
                                    $imageName = (!empty($model['Photo'])) ? $model['Photo'] : "nophoto.png";
                                ?>
                                <img id="imgPreview"
                                     src="/images/productphotos/<?= $imageName ?>"
                                     class="img-thumbnail"
                                     style="max-height:160px;" />
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Mô tả</label>
                        <div class="col-sm-9">
                            <input type="text" name="Description" 
                                   value="<?= htmlspecialchars($model['Description'] ?? '') ?>" 
                                   class="form-control" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Thứ tự</label>
                        <div class="col-sm-3">
                            <input type="number" name="DisplayOrder" 
                                   value="<?= $model['DisplayOrder'] ?? 1 ?>" 
                                   class="form-control" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Trạng thái</label>
                        <div class="col-sm-9">
                            <div class="form-check mt-2">
                                <input name="IsHidden" class="form-check-input" type="checkbox" value="1" 
                                       id="IsHidden" <?= !empty($model['IsHidden']) ? 'checked' : '' ?> />
                                <label class="form-check-label" for="IsHidden">Ẩn ảnh</label>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imgPreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>