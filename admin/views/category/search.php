<div class="mb-2 text-muted">
    Danh sách có <b><?= $result['RowCount'] ?></b> loại hàng
</div>

<div class="card shadow-sm">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-secondary">
                <tr>
                    <th>Tên loại hàng</th>
                    <th>Mô tả</th>
                    <th style="width:110px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($result['DataItems'])): ?>
                    <tr>
                        <td colspan="3" class="text-center text-danger">Không có dữ liệu</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['DataItems'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['CategoryName']) ?></td>
                            <td><?= htmlspecialchars($item['Description']) ?></td>
                            <td class="text-center">

                                <a class="btn btn-sm btn-primary" title="Sửa"
                                    href="index.php?controller=category&action=edit&id=<?= $item['CategoryID'] ?>">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a class="btn btn-sm btn-danger" title="Xóa"
                                    href="index.php?controller=category&action=delete&id=<?= $item['CategoryID'] ?>">
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