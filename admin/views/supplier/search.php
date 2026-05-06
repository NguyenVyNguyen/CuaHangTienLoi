<?php
/** @var array $result */
?>
<div class="mb-2 text-muted">
    Danh sách có <b><?= $result['RowCount'] ?></b> nhà cung cấp
</div>

<div class="card">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Tên nhà cung cấp</th>
                    <th>Tên giao dịch</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Tỉnh / Thành</th>
                    <th class="text-center" style="width:120px;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Không tìm thấy nhà cung cấp!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['SupplierName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['ContactName'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Address'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['Province'] ?? '') ?></td>
                            <td class="text-center">
                                <a href="index.php?controller=supplier&action=edit&id=<?= $item['SupplierID'] ?>"
                                   class="btn btn-sm btn-primary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="index.php?controller=supplier&action=delete&id=<?= $item['SupplierID'] ?>"
                                   class="btn btn-sm btn-danger">
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