<?php
$title = "Quản lý Đơn hàng";
?>

<div class="card mb-3">
    <div class="card-body">

        <!-- FORM SEARCH -->
        <form id="formSearch"
              action="index.php?controller=order&action=search"
              method="get"
              data-target="searchResult"
              onsubmit="paginationSearch(event, this, 1)">

            <input type="hidden" name="page" value="<?= $model['page'] ?? 1 ?>" />

            <div class="row g-2">

                <!-- TRẠNG THÁI -->
                <div class="col-md-3">
                    <label class="form-label">Trạng thái đơn hàng</label>
                    <select name="status" class="form-control">
                        <option value="0" <?= ($model['status'] ?? 0) == 0 ? 'selected' : '' ?>>-- Tất cả --</option>
                        <option value="1" <?= ($model['status'] ?? 0) == 1 ? 'selected' : '' ?>>Đang chờ duyệt</option>
                        <option value="2" <?= ($model['status'] ?? 0) == 2 ? 'selected' : '' ?>>Đã duyệt</option>
                        <option value="3" <?= ($model['status'] ?? 0) == 3 ? 'selected' : '' ?>>Đang giao</option>
                        <option value="4" <?= ($model['status'] ?? 0) == 4 ? 'selected' : '' ?>>Hoàn tất</option>
                        <option value="-1" <?= ($model['status'] ?? 0) == -1 ? 'selected' : '' ?>>Bị hủy</option>
                        <option value="-2" <?= ($model['status'] ?? 0) == -2 ? 'selected' : '' ?>>Bị từ chối</option>
                    </select>
                </div>

                <!-- NGÀY -->
                <div class="col-md-4">
                    <label class="form-label">Thời gian lập đơn hàng</label>
                    <div class="input-group">
                        <input type="date" name="dateFrom"
                               class="form-control"
                               value="<?= $model['dateFrom'] ?? '' ?>">

                        <span class="input-group-text">-</span>

                        <input type="date" name="dateTo"
                               class="form-control"
                               value="<?= $model['dateTo'] ?? '' ?>">
                    </div>
                </div>

                <!-- TÊN KHÁCH -->
                <div class="col-md-5">
                    <label class="form-label">Tên khách hàng</label>
                    <div class="input-group">
                        <input type="text"
                               name="searchValue"
                               class="form-control"
                               placeholder="Tên khách hàng..."
                               value="<?= htmlspecialchars($model['searchValue'] ?? '') ?>">

                        <button class="btn btn-info" type="submit">
                            <i class="bi bi-search me-1"></i>
                            Tìm
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<!-- KẾT QUẢ -->
<div id="searchResult"></div>

<script>
function paginationSearch(event, form, page) {
    if (event) event.preventDefault();

    let pageInput = form.querySelector("input[name='page']");
    if (pageInput) pageInput.value = page;

    fetch(form.action + "&" + new URLSearchParams(new FormData(form)))
        .then(res => res.text())
        .then(html => {
            document.getElementById(form.dataset.target).innerHTML = html;
        });
}

document.addEventListener("DOMContentLoaded", function () {
    paginationSearch(null, document.getElementById("formSearch"), 1);
});
</script>