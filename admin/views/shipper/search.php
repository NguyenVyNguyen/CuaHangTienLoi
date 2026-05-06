<div class="mb-2 text-muted">
    Danh sách có <b><?= $result['RowCount'] ?></b> người giao hàng
</div>

<div class="card">
    <div class="card-body p-1">
        <table class="table table-bordered">
            <thead class="table-secondary">
                <tr>
                    <th>Tên người giao hàng</th>
                    <th>Điện thoại</th>
                    <th style="width:100px;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="3" class="text-center text-danger">
                            Không tìm thấy dữ liệu
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['ShipperName']) ?></td>
                            <td><?= htmlspecialchars($item['Phone']) ?></td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary"
                                    href="index.php?controller=shipper&action=edit&id=<?= $item['ShipperID'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a class="btn btn-sm btn-danger"
                                    href="index.php?controller=shipper&action=delete&id=<?= $item['ShipperID'] ?>">
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