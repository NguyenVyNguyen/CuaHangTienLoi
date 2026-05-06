<?php
$title = "Quản lý Người giao hàng";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>
    <a href="index.php?controller=shipper&action=create" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Bổ sung
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="formSearch"
            action="index.php?controller=shipper&action=search"
            method="post"
            onsubmit="paginationSearch(event, this, 1)">

            <input type="hidden" name="Page" value="1" />
            <input type="hidden" name="PageSize" value="10" />

            <div class="input-group">
                <input type="text"
                    name="SearchValue"
                    class="form-control"
                    value="<?= htmlspecialchars($model['SearchValue'] ?? '') ?>"
                    placeholder="Tên người giao hàng..." />

                <button class="btn btn-info" type="submit">
                    Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<div id="searchResult"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        paginationSearch(null, document.getElementById("formSearch"), 1);
    });

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
            })
            .catch(err => console.error(err));
    }
</script>