<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-info card-outline mt-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 id="photos" class="mb-0 fw-bold">
                        <i class="bi bi-images me-2"></i> Thư viện ảnh
                    </h6>
                    <a class="btn btn-sm btn-primary"
                        href="index.php?controller=product&action=createPhoto&id=<?= $productID ?? 0 ?>">
                        <i class="bi bi-plus-circle me-1"></i> Thêm ảnh
                    </a>
                </div>
            </div>
            <div class="card-body p-1">
                <table class="table table-bordered table-hover mb-0">

                    <?php if (empty($model)): ?>
                        <tr>
                            <td colspan="5" class="text-center">
                                <i class="bi bi-info"></i>
                                Chưa có ảnh nào được thêm vào sản phẩm này.
                            </td>
                        </tr>
                    <?php else: ?>
                        <thead class="table-secondary">
                            <tr>
                                <th style="width:120px">Ảnh</th>
                                <th>Mô tả</th>
                                <th style="width:120px" class="text-center">Thứ tự</th>
                                <th style="width:120px">Hiển thị</th>
                                <th style="width:100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model as $item): ?>

                                <?php if (!is_object($item)) continue;
                                ?>

                                <tr>
                                    <td class="text-center">
                                        <img src="/images/products/<?= htmlspecialchars($item->Photo ?? 'noproduct.png') ?>"
                                            class="img-thumbnail"
                                            style="max-height:80px;" />
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($item->Description ?? '') ?>
                                    </td>

                                    <td class="text-center">
                                        <?= $item->DisplayOrder ?? 0 ?>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <?= !empty($item->IsHidden) ? "Ẩn" : "Hiện" ?>
                                        </span>
                                    </td>

                                    <td class="text-center text-nowrap">
                                        <a class="btn btn-sm btn-primary"
                                            href="index.php?controller=product&action=editPhoto&id=<?= $item->ProductID ?>&photoId=<?= $item->PhotoID ?>">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <a class="btn btn-sm btn-danger"
                                            href="index.php?controller=product&action=deletePhoto&id=<?= $item->ProductID ?>&photoId=<?= $item->PhotoID ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>