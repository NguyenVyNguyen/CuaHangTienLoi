<?php
$title = "Quản lý Nhân viên";
?>

<div class="d-flex justify-content-between mb-3">
    <h5><?= $title ?></h5>

    <a href="index.php?controller=employee&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Bổ sung
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="formSearch"
              action="index.php?controller=employee&action=search"
              method="get"
              data-target="searchResult"
              onsubmit="paginationSearch(event, this, 1)">

            <input type="hidden" name="page" value="1" />
            <input type="hidden" name="pageSize" value="10" />

            <div class="input-group">
                <input type="text"
                       name="SearchValue"
                       class="form-control"
                       value="<?= htmlspecialchars($model['SearchValue'] ?? '') ?>"
                       placeholder="Nhập tên nhân viên cần tìm..." autofocus />

                <button class="btn btn-info">
                    <i class="bi bi-search me-1"></i> Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<div id="searchResult"></div>

<script>
function paginationSearch(event, form, page) {
    if (event) event.preventDefault();

    form.querySelector("input[name='page']").value = page;

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