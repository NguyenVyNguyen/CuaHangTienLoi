<?php
/** @var array $cart */

$stt = 0;
$sumPrice = 0;
?>
<pre><?php print_r($cart); ?></pre>

<div class="card card-outline card-success">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-cart"></i>
            Danh sách mặt hàng đã chọn
        </h5>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th class="text-center">#</th>
                    <th>Tên hàng</th>
                    <th class="text-center" style="width:100px">ĐVT</th>
                    <th class="text-center" style="width:100px">SL</th>
                    <th class="text-end" style="width:150px">Giá</th>
                    <th class="text-end" style="width:150px">Thành tiền</th>
                    <th style="width:80px"></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($cart as $item): 
                    $stt++;
                    $total = $item["Quantity"] * $item["SalePrice"];
                    $sumPrice += $total;
                ?>
                    <tr>
                        <td class="text-center"><?= $stt ?></td>
                        <td><?= htmlspecialchars($item["ProductName"]) ?></td>
                        <td class="text-center"><?= $item["Unit"] ?></td>
                        <td class="text-center"><?= $item["Quantity"] ?></td>
                        <td class="text-end"><?= number_format($item["SalePrice"]) ?></td>
                        <td class="text-end"><?= number_format($total) ?></td>

                        <td class="text-end text-nowrap">
                            <!-- EDIT -->
                            <a class="btn btn-sm btn-primary"
                               href="index.php?controller=order&action=editCartItem&productId=<?= $item["ProductID"] ?>">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- DELETE -->
                            <button class="btn btn-sm btn-danger"
                                    onclick="deleteCartItem(<?= $item['ProductID'] ?>)">
                                <i class="bi bi-dash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Tổng cộng:</th>
                    <th class="text-end"><?= number_format($sumPrice) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <div class="text-end">
            <button class="btn btn-sm btn-danger mt-2"
                    onclick="clearCart()">
                <i class="bi bi-trash"></i>
                Xóa giỏ hàng
            </button>
        </div>
    </div>
</div>