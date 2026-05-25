<?php

/** @var array $data */

// FIX: tránh undefined
$page = isset($data['Page']) ? (int)$data['Page'] : 1;
$pageCount = isset($data['PageCount']) ? (int)$data['PageCount'] : 1;
$items = isset($data['DataItems']) ? $data['DataItems'] : [];
?>

<?php if (!empty($items)): ?>

    <?php foreach ($items as $item): ?>
        <form method="post"
            class="border rounded p-2 mb-2 shadow-sm">

            <input type="hidden" name="productId" value="<?= $item['ProductID'] ?>" />

            <strong class="d-block text-truncate">
                <?= htmlspecialchars($item['ProductName']) ?>
            </strong>

            <div class="row mt-2 align-items-center">
                <!-- IMAGE -->
                <div class="col-4">
                    <?php
                    $photo = !empty($item['Photo'])
                        ? "assets/images/products/" . $item['Photo']
                        : "assets/images/products/demo.png";
                    ?>
                    <img src="<?= $photo ?>"
                        class="img-fluid border rounded"
                        style="height:70px; width:100%; object-fit:cover" />
                </div>

                <!-- INFO -->
                <div class="col-8">
                    <div class="row g-2">
                        <!-- PRICE -->
                        <div class="col-7">
                            <label class="small mb-0">Giá</label>
                            <input class="form-control form-control-sm"
                                name="price"
                                value="<?= $item['Price'] ?>" />
                        </div>

                        <!-- QUANTITY -->
                        <div class="col-5">
                            <label class="small mb-0">SL</label>
                            <input type="number"
                                name="quantity"
                                value="1"
                                min="1"
                                class="form-control form-control-sm" />
                        </div>
                    </div>

                    <button type="button"
                        class="btn btn-sm btn-primary mt-2 w-100"
                        onclick="addCartItem(this.closest('form'))">
                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                    </button>
                </div>
            </div>
        </form>
    <?php endforeach; ?>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-between mt-2">

        <!-- PREV -->
        <button type="button"
            class="btn btn-outline-primary"
            onclick="paginationSearch(event, document.getElementById('formSearch'), <?= max(1, $page - 1) ?>)"
            <?= ($page <= 1) ? 'disabled' : '' ?>>
            <i class="bi bi-arrow-left"></i>
        </button>

        <!-- PAGE INFO -->
        <span class="align-self-center">
            Trang <?= $page ?> / <?= $pageCount ?>
        </span>

        <!-- NEXT -->
        <button type="button"
            class="btn btn-outline-primary"
            onclick="paginationSearch(event, document.getElementById('formSearch'), <?= min($pageCount, $page + 1) ?>)"
            <?= ($page >= $pageCount) ? 'disabled' : '' ?>>
            <i class="bi bi-arrow-right"></i>
        </button>

    </div>

<?php else: ?>

    <div class="text-center text-muted p-3">
        Không có mặt hàng nào.
    </div>

<?php endif; ?>