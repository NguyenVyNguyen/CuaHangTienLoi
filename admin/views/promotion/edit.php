<?php
$title = ($model['PromotionID'] == 0) ? "Bổ sung khuyến mãi" : "Cập nhật khuyến mãi";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?= $title ?></h5>

    <div>
        <button type="submit" form="formEdit" class="btn btn-primary">
            Lưu dữ liệu
        </button>
        <a href="index.php?controller=promotion" class="btn btn-secondary ms-1">
            Quay lại
        </a>
    </div>
</div>

<div class="card card-primary card-outline">
    <div class="card-body">
        <form id="formEdit" method="post" action="index.php?controller=promotion&action=save">

            <input type="hidden" name="PromotionID" value="<?= $model['PromotionID'] ?>">

            <!-- TÊN -->
            <div class="mb-3">
                <label class="form-label">Tên khuyến mãi</label>
                <input type="text" name="PromotionName"
                    value="<?= htmlspecialchars($model['PromotionName'] ?? '') ?>"
                    class="form-control">
            </div>

            <!-- CATEGORY -->
            <div class="mb-3">
                <label class="form-label">Chọn loại hàng</label>
                <select name="CategoryID" id="categorySelect" class="form-control">
                    <option value="0">-- Tất cả --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['CategoryID'] ?>">
                            <?= $c['CategoryName'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- PRODUCT -->
            <div class="mb-3">
                <label class="form-label">Chọn sản phẩm</label>

                <input type="text" id="productSearch"
                    class="form-control mb-2"
                    placeholder="Tìm sản phẩm...">

                <div id="productResult"
                    style="max-height:250px; overflow:auto; border:1px solid #ddd; padding:10px;">
                </div>

                <div class="mt-2">
                    <b>Đã chọn:</b>
                    <div id="selectedProducts"></div>
                </div>
            </div>

            <!-- LOẠI GIẢM -->
            <div class="mb-3">
                <label class="form-label">Loại giảm</label>
                <select name="DiscountType" class="form-control">
                    <option value="1" <?= ($model['DiscountType'] ?? 1) == 1 ? 'selected' : '' ?>>
                        Phần trăm (%)
                    </option>
                    <option value="2" <?= ($model['DiscountType'] ?? 1) == 2 ? 'selected' : '' ?>>
                        Tiền (VND)
                    </option>
                </select>
            </div>

            <!-- GIÁ TRỊ -->
            <div class="mb-3">
                <label class="form-label">Giá trị giảm</label>
                <input type="number" name="DiscountValue"
                    value="<?= $model['DiscountValue'] ?? 0 ?>"
                    class="form-control">
            </div>

            <!-- NGÀY -->
            <div class="mb-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="datetime-local" name="StartDate"
                    value="<?= isset($model['StartDate']) ? date('Y-m-d\TH:i', strtotime($model['StartDate'])) : '' ?>"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="datetime-local" name="EndDate"
                    value="<?= isset($model['EndDate']) ? date('Y-m-d\TH:i', strtotime($model['EndDate'])) : '' ?>"
                    class="form-control">
            </div>

            <!-- ACTIVE -->
            <div class="form-check">
                <input type="checkbox" name="IsActive"
                    <?= !empty($model['IsActive']) ? 'checked' : '' ?>
                    class="form-check-input">
                <label class="form-check-label">Hoạt động</label>
            </div>

        </form>
    </div>
</div>

<script>
let selectedProducts = {};

// SEARCH
let timeout;
document.getElementById("productSearch").addEventListener("keyup", function () {
    clearTimeout(timeout);

    let keyword = this.value;

    timeout = setTimeout(() => {
        fetch(`index.php?controller=product&action=searchAjax&keyword=${keyword}`)
            .then(res => res.json())
            .then(data => {
                let html = "";

                data.forEach(p => {
                    let checked = selectedProducts[p.ProductID] ? "checked" : "";

                    html += `
                        <div class="form-check">
                            <input type="checkbox" ${checked}
                                onchange="toggleProduct(${p.ProductID}, '${p.ProductName.replace(/'/g, "\\'")}', this)">
                            <label>${p.ProductName}</label>
                        </div>
                    `;
                });

                document.getElementById("productResult").innerHTML = html;
            });
    }, 300);
});

// TOGGLE
function toggleProduct(id, name, checkbox) {
    if (checkbox.checked) {
        selectedProducts[id] = name;
    } else {
        delete selectedProducts[id];
    }
    renderSelected();
}

// RENDER
function renderSelected() {
    let html = "";

    Object.keys(selectedProducts).forEach(id => {
        html += `<span class="badge bg-primary me-1">${selectedProducts[id]}</span>`;
    });

    document.getElementById("selectedProducts").innerHTML = html;
}

// CATEGORY → ADD (KHÔNG clear)
document.getElementById("categorySelect").addEventListener("change", function () {
    let categoryID = this.value;

    if (categoryID == 0) return;

    fetch("index.php?controller=product&action=getByCategory&id=" + categoryID)
        .then(res => res.json())
        .then(data => {
            data.forEach(p => {
                selectedProducts[p.ProductID] = p.ProductName;
            });
            renderSelected();
        });
});

// SUBMIT
document.getElementById("formEdit").addEventListener("submit", function () {
    Object.keys(selectedProducts).forEach(id => {
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "ProductIDs[]";
        input.value = id;
        this.appendChild(input);
    });
});

window.onload = function () {
    renderSelected();
};
</script>