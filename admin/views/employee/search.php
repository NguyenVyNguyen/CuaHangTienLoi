<div class="mb-2 text-muted">
    Danh sách này có <strong><?= $result['total'] ?></strong> nhân viên
</div>

<div class="card">
    <div class="card-body p-1">
        <table class="table table-bordered table-hover mb-0 align-middle">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center" style="width:80px;">Ảnh</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Quyền</th> <!-- ✅ THÊM -->
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($result['data'])): ?>
                    <tr>
                        <td colspan="9" class="text-center text-danger">
                            Không có dữ liệu
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['data'] as $item): ?>
                        <tr>
                            <td class="text-center">
                                <img src="/admin/images/employees/<?= $item->Photo ?: 'nophoto.png' ?>"
                                    style="width:50px;height:50px;object-fit:cover;">
                            </td>

                            <td><?= htmlspecialchars($item->FullName) ?></td>
                            <td><?= $item->BirthDate ? date('d/m/Y', strtotime($item->BirthDate)) : '' ?></td>
                            <td><?= $item->Phone ?></td>
                            <td><?= $item->Email ?></td>
                            <td><?= $item->Address ?></td>

                            <!-- ✅ ROLE -->
                            <td>
                                <?php
                                    if (!empty($item->RoleNames)) {
                                        $roles = explode(",", $item->RoleNames);
                                        foreach ($roles as $r) {
                                            echo '<span class="badge bg-info me-1">' . htmlspecialchars($r) . '</span>';
                                        }
                                    } else {
                                        echo '<span class="text-muted">Chưa có</span>';
                                    }
                                ?>
                            </td>

                            <td class="text-center">
                                <?php if ($item->IsWorking): ?>
                                    <span class="badge bg-success">Đang làm việc</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Đã nghỉ</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <a href="index.php?controller=employee&action=edit&id=<?= $item->EmployeeID ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?controller=employee&action=delete&id=<?= $item->EmployeeID ?>" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <a href="index.php?controller=employee&action=changePassword&id=<?= $item->EmployeeID ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-key"></i>
                                </a>
                                <a href="index.php?controller=employee&action=changeRole&id=<?= $item->EmployeeID ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-shield-check"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <ul class="pagination justify-content-center mb-0">

            <?php if (!empty($result['pages'])): ?>
                <?php foreach ($result['pages'] as $p): ?>

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
            <?php endif; ?>

        </ul>
    </div>
</div>