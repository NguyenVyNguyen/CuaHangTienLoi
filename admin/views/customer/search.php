<div class="mb-2 text-muted">
    Danh sách có <b><?= $result['RowCount'] ?></b> khách hàng
</div>

<div class="card">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th>Tên khách hàng</th>
                    <th>Tên giao dịch</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Tỉnh</th>
                    <th>Trạng thái</th>
                    <th style="width:140px;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="8" class="text-center text-danger">
                            Không có khách hàng
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['CustomerName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['ContactName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Province'] ?? '') ?></td>
                            <td>
                                <?php if ($item['IsLocked']): ?>
                                    <span class="badge bg-danger">Khóa</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary"
                                    href="index.php?controller=customer&action=edit&id=<?= $item['CustomerID'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a class="btn btn-sm btn-danger"
                                    href="index.php?controller=customer&action=delete&id=<?= $item['CustomerID'] ?>">
                                    <i class="bi bi-trash"></i>
                                </a>

                                <a class="btn btn-sm btn-warning"
                                    href="index.php?controller=customer&action=changePassword&id=<?= $item['CustomerID'] ?>">
                                    <i class="bi bi-key"></i>
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