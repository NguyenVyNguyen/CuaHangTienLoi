<div class="mb-2 text-muted">
    Danh sách này có <strong><?= $result['RowCount'] ?? 0 ?></strong> mặt hàng
</div>

<div class="card">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover mb-0 align-middle">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center" style="width:80px;">Ảnh</th>
                    <th class="text-center">Mặt hàng</th>
                    <th class="text-center" style="width:120px;">Đơn vị</th>
                    <th class="text-center" style="width:120px;">Giá</th>
                    <th class="text-center" style="width:160px;">Khuyến mãi</th>
                    <th class="text-center" style="width:140px;">Giá sau giảm</th>
                    <th class="text-center" style="width:140px;">Trạng thái</th>
                    <th class="text-center" style="width:100px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="8" class="text-center text-danger">Không có mặt hàng nào phù hợp!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td class="text-center">
                                <?php $photo = empty($item['Photo']) ? 'noproduct.png' : $item['Photo']; ?>
                                <img src="images/products/<?= htmlspecialchars($photo) ?>"
                                    class="img-thumbnail" style="width:60px; height:60px; object-fit:cover;"
                                    alt="<?= htmlspecialchars($item['ProductName']) ?>" />
                            </td>

                            <td>
                                <strong><?= htmlspecialchars($item['ProductName']) ?></strong><br />
                                <small class="text-muted"><?= htmlspecialchars($item['ProductDescription']) ?></small>
                            </td>

                            <td class="text-center"><?= htmlspecialchars($item['Unit']) ?></td>

                            <td class="text-center fw-bold text-primary">
                                <?= number_format($item['Price']) ?>
                            </td>

                            <!-- 🔥 Khuyến mãi -->
                            <td class="text-center">
                                <?php if (isset($item['DiscountValue']) && $item['DiscountValue'] > 0): ?>
                                    <span class="badge bg-info text-dark">
                                        <?= htmlspecialchars($item['PromotionName'] ?? 'Khuyến mãi') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Không có</span>
                                <?php endif; ?>
                            </td>

                            <!-- 🔥 Giá sau giảm -->
                            <td class="text-center fw-bold">
                                <?php if (!empty($item['DiscountValue']) && $item['DiscountValue'] > 0): ?>
                                    <span class="text-danger">
                                        <?= number_format($item['FinalPrice']) ?>
                                    </span>
                                    <br>
                                    <small class="text-muted text-decoration-line-through">
                                        <?= number_format($item['Price']) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-primary">
                                        <?= number_format($item['Price']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <?php if ($item['IsSelling']): ?>
                                    <span class="badge bg-success">Đang bán</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ngừng bán</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center text-nowrap">
                                <a href="index.php?controller=product&action=edit&id=<?= $item['ProductID'] ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?controller=product&action=delete&id=<?= $item['ProductID'] ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($result['Pages']) && count($result['Pages']) > 1): ?>
        <div class="card-footer">
            <ul class="pagination justify-content-center mb-0">
                <?php foreach ($result['Pages'] as $p): ?>
                    <?php if ($p['Page'] == 0): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php else: ?>
                        <li class="page-item <?= $p['IsCurrent'] ? 'active' : '' ?>">
                            <a class="page-link" href="javascript:;"
                                onclick="paginationSearch(event, document.getElementById('formSearch'), <?= $p['Page'] ?>)">
                                <?= $p['Page'] ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>