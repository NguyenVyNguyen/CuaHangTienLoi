<?php
$currentPage = $result['page'] ?? 1;
$pageSize = $result['pageSize'] ?? 10;
$rowCount = $result['total'] ?? 0;
$pageCount = ceil($rowCount / $pageSize);

$stt = ($currentPage - 1) * $pageSize;
?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped mb-0 align-middle">
                <thead>
                    <tr>
                        <th style="width:80px" class="text-center">STT</th>
                        <th>Mã đơn</th>
                        <th>Ngày lập</th>
                        <th>Khách hàng</th>
                        <th style="width:150px">Trạng thái</th>
                        <th style="width:140px" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($result['data'])): ?>
                        <?php foreach ($result['data'] as $order): ?>
                            <tr>
                                <td class="text-center"><?= ++$stt ?></td>
                                <td><?= $order->OrderID ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order->OrderTime)) ?></td>
                                <td><?= htmlspecialchars($order->CustomerName) ?></td>
                                <td><?= $order->Status ?></td>
                                <td class="text-center">
                                    <a href="index.php?controller=order&action=detail&id=<?= $order->OrderID ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?controller=order&action=delete&id=<?= $order->OrderID ?>" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Không có đơn hàng nào
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            Có <strong><?= $rowCount ?></strong> đơn hàng
        </div>

        <?php if ($pageCount > 1): ?>
            <ul class="pagination pagination-sm mb-0">

                <!-- PREV -->
                <li class="page-item <?= ($currentPage == 1) ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="javascript:void(0)"
                       onclick="paginationSearch(null, document.getElementById('formSearch'), <?= $currentPage - 1 ?>)">
                        «
                    </a>
                </li>

                <?php
                $startPage = $currentPage - 2;
                $endPage = $currentPage + 2;

                if ($startPage < 1) {
                    $startPage = 1;
                    $endPage = min(5, $pageCount);
                }

                if ($endPage > $pageCount) {
                    $endPage = $pageCount;
                    $startPage = max(1, $pageCount - 4);
                }
                ?>

                <?php for ($p = $startPage; $p <= $endPage; $p++): ?>
                    <?php if ($p == $currentPage): ?>
                        <li class="page-item active">
                            <span class="page-link"><?= $p ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link"
                               href="javascript:void(0)"
                               onclick="paginationSearch(null, document.getElementById('formSearch'), <?= $p ?>)">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- NEXT -->
                <li class="page-item <?= ($currentPage == $pageCount) ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="javascript:void(0)"
                       onclick="paginationSearch(null, document.getElementById('formSearch'), <?= $currentPage + 1 ?>)">
                        »
                    </a>
                </li>

            </ul>
        <?php endif; ?>
    </div>
</div>