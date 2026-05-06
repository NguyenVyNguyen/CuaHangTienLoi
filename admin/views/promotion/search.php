<div class="mb-2 text-muted">
    Danh sách có <b><?= $result['RowCount'] ?></b> khuyến mãi
</div>

<div class="card shadow-sm">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Tên khuyến mãi</th>
                    <th>Giảm giá</th>
                    <th>Bắt đầu</th>
                    <th>Kết thúc</th>
                    <th>Trạng thái</th>
                    <th style="width:150px;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="6" class="text-center text-danger">
                            Không có dữ liệu
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['PromotionName']) ?></td>

                            <td>
                                <?= $item['DiscountType'] == 1
                                    ? $item['DiscountValue'] . '%'
                                    : number_format($item['DiscountValue']) . 'đ' ?>
                            </td>

                            <td>
                                <?= date('d/m/Y', strtotime($item['StartDate'])) ?>
                            </td>

                            <td>
                                <?= date('d/m/Y', strtotime($item['EndDate'])) ?>
                            </td>

                            <td>
                                <?php if ($item['IsActive']): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tắt</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a class="btn btn-sm btn-info"
                                    href="index.php?controller=promotion&action=detail&id=<?= $item['PromotionID'] ?>">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a class="btn btn-sm btn-primary"
                                    href="index.php?controller=promotion&action=edit&id=<?= $item['PromotionID'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a class="btn btn-sm btn-danger"
                                    href="index.php?controller=promotion&action=delete&id=<?= $item['PromotionID'] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGING -->
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