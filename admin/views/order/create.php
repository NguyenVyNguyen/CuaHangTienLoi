<?php
/** @var array $model */
/** @var array $provinces */
/** @var array $customers */
?>

<div class="row">
    <!-- LEFT: SEARCH PRODUCT -->
    <div class="col-4">
        <div class="card card-outline card-primary h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-search"></i> Lựa chọn hàng cần bán</h5>
            </div>
            <div class="card-body">
                <form id="formSearch"
                      method="get"
                      action="index.php?controller=order&action=searchProduct"
                      onsubmit="paginationSearch(event, this, 1)"
                      class="mb-3">

                    <div class="input-group">
                        <input type="hidden" name="pageSize" value="<?= $model['pageSize'] ?>" />
                        <input type="hidden" name="CategoryID" value="<?= $model['CategoryID'] ?>" />
                        <input type="hidden" name="SupplierID" value="<?= $model['SupplierID'] ?>" />

                        <input class="form-control"
                               name="SearchValue"
                               value="<?= htmlspecialchars($model['SearchValue']) ?>"
                               placeholder="Nhập tên mặt hàng cần tìm"
                               autofocus />

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div id="searchResult"></div>
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-8">
        <!-- CART -->
        <div class="row">
            <div class="col-12" id="cart"></div>
        </div>

        <!-- CUSTOMER INFO -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-person"></i> Thông tin khách hàng & nơi giao hàng
                        </h5>
                    </div>

                    <div class="card-body">
                        <form method="post"
                              action="index.php?controller=order&action=createOrder"
                              onsubmit="createOrder(event, this)">

                            <!-- CUSTOMER -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">Khách hàng</label>
                                <div class="col-md-10">
                                    <select class="form-select" name="customerID">
                                        <option value="0">-- Chọn khách hàng --</option>
                                        <?php foreach ($customers as $c): ?>
                                            <option value="<?= $c['CustomerID'] ?>">
                                                <?= $c['CustomerName'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- PROVINCE -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">Tỉnh/thành</label>
                                <div class="col-md-10">
                                    <select class="form-select" name="province">
                                        <option value="">-- Chọn tỉnh/thành --</option>
                                        <?php foreach ($provinces as $p): ?>
                                            <option value="<?= $p['value'] ?>">
                                                <?= $p['text'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- ADDRESS -->
                            <div class="row mb-3">
                                <label class="col-md-2 col-form-label">Địa chỉ</label>
                                <div class="col-md-10">
                                    <textarea class="form-control"
                                              name="address"
                                              placeholder="Địa chỉ nhận hàng..."></textarea>
                                </div>
                            </div>

                            <!-- SUBMIT -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Lập đơn hàng
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= JS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7ie1l5+5hb7ie1l5+5hb7ie1l5+5hb7ie1l5+5hb7ie1l5+5hb7ie1l5+5hb7ie" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
    var form = document.getElementById("formSearch");
    paginationSearch(null, form, 1);
    showCart();
});

// SEARCH PRODUCT
function paginationSearch(event, form, page) {
    if (event) event.preventDefault();

    var url = $(form).attr("action");
    var postData = $(form).serializeArray();
    postData.push({ name: "page", value: page });

    $.ajax({
        url: url,
        type: "GET",
        data: postData,
        success: function (data) {
            $("#searchResult").html(data);
        },
        error: function () {
            alert("Không thể tải danh sách sản phẩm.");
        }
    });
}

// SHOW CART
function showCart() {
    $.ajax({
        url: "index.php?controller=order&action=showCart",
        type: "GET",
        success: function (data) {
            $("#cart").html(data);
        }
    });
}

// ADD CART
function addCartItem(event, form) {
    event.preventDefault();

    $.post(
        "index.php?controller=order&action=addCartItem",
        $(form).serialize(),
        function (res) {
            if (res.code > 0 || res.success === 1) {
                showCart();
            } else {
                alert(res.message);
            }
        }
    );
}

// DELETE ITEM
function deleteCartItem(productID) {
    if (confirm("Xóa mặt hàng này khỏi giỏ?")) {
        $.post(
            "index.php?controller=order&action=deleteCartItem",
            { productId: productID },
            function () {
                showCart();
            }
        );
    }
}

// CLEAR CART
function clearCart() {
    if (confirm("Bạn có chắc muốn xóa toàn bộ giỏ hàng?")) {
        $.post(
            "index.php?controller=order&action=clearCart",
            {},
            function () {
                showCart();
            }
        );
    }
}

// UPDATE ITEM
function updateCartItem(event, form) {
    if (event) event.preventDefault();

    $.post(
        "index.php?controller=order&action=updateCartItem",
        $(form).serialize(),
        function () {
            showCart();
        }
    );
}

// CREATE ORDER
function createOrder(event, form) {
    event.preventDefault();

    const formData = new FormData(form);

    fetch("index.php?controller=order&action=createOrder", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(result => {
        if (result.code === 0) {
            alert(result.message);
        } else {
            window.location.href =
                "index.php?controller=order&action=detail&id=" + result.code;
        }
    })
    .catch(() => {
        alert("Không thể tạo đơn hàng");
    });
}
</script>