<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Admin - YvNgynShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/shop/public/lib/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="/shop/public/css/site.css" />
</head>

<body class="sidebar-mini layout-fixed">
    <div class="wrapper">

        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="/shop/index.php" class="nav-link">Trang chủ</a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    <?php
                    // Giả sử bạn dùng Session để kiểm tra đăng nhập thay cho @User.Identity
                    if (isset($_SESSION['user'])):
                        $user = $_SESSION['user'];
                    ?>
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                <img src="/shop/public/images/employees/<?= $user['photo'] ?>"
                                    class="user-image rounded-circle shadow" alt="User Image" />
                                <span class="d-none d-md-inline"><?= $user['name'] ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <li class="user-header bg-primary">
                                    <img src="/shop/public/images/employees/<?= $user['photo'] ?>"
                                        class="rounded-circle shadow" alt="User Image" />
                                    <p><?= $user['name'] ?></p>
                                </li>
                                <li class="user-footer">
                                    <a href="/shop/account/changepassword.php" class="btn btn-default btn-flat">Đổi mật khẩu</a>
                                    <a href="/shop/account/logout.php" class="btn btn-default btn-flat float-right">Thoát</a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="/shop/account/login.php" class="btn btn-primary btn-sm">Đăng nhập</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <?php include __DIR__ . "/sidebar.php"; ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><?php echo $title ?? 'Quản lý hệ thống'; ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">