<?php
$title = "Quản lý Mặt hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>

    <a href="index.php?controller=product&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Bổ sung
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="formSearch"
            action="index.php?controller=product&action=search"
            method="get"
            data-target="searchResult"
            onsubmit="paginationSearch(event, this, 1)">

            <input type="hidden" name="Page" value="1" />
            <input type="hidden" name="PageSize" value="10" />

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Loại hàng</label>
                        <select class="form-control" name="CategoryID">
                            <option value="0">-- Tất cả --</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c['value'] ?>" <?= (($model['CategoryID'] ?? 0) == $c['value']) ? 'selected' : '' ?>>
                                    <?= $c['text'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Nhà cung cấp</label>
                        <select class="form-control" name="SupplierID">
                            <option value="0">-- Tất cả --</option>
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= $s['value'] ?>" <?= (($model['SupplierID'] ?? 0) == $s['value']) ? 'selected' : '' ?>>
                                    <?= $s['text'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Khoảng giá</label>
                        <div class="input-group">
                            <input type="text" name="MinPrice" class="form-control money-input" placeholder="0" value="<?= number_format($model['MinPrice'] ?? 0) ?>" />

                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text">-</span>
                            </div>

                            <input type="text" name="MaxPrice" class="form-control money-input" placeholder="0" value="<?= number_format($model['MaxPrice'] ?? 0) ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Tên mặt hàng</label>
                        <div class="input-group">
                            <input type="text" name="SearchValue" class="form-control" placeholder="Tên mặt hàng..." autofocus value="<?= htmlspecialchars($model['SearchValue'] ?? '') ?>" />

                            <div class="input-group-append">
                                <button class="btn btn-info" type="submit">
                                    <i class="bi bi-search"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="searchResult"></div>

<script>
function paginationSearch(event, form, page) {
    if (event) event.preventDefault();

    let formData = new FormData(form);
    formData.append("page", page);

    fetch(form.action, {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById("searchResult").innerHTML = html;
    });
}

document.addEventListener("DOMContentLoaded", function () {
    paginationSearch(null, document.getElementById("formSearch"), 1);
});
</script>