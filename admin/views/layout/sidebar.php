<aside class="main-sidebar sidebar-dark-primary elevation-4">
    
    <a href="/shop/index.php" class="brand-link">
        <img src="/shop/public/lib/adminlte/assets/img/Logo.png" 
             alt="Logo" 
             class="brand-image img-circle elevation-3 opacity-75 shadow" />
        <span class="brand-text font-weight-light">YvNgyn Shop</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="index.php" class="nav-link">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Trang chủ</p>
                    </a>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-archive"></i>
                        <p>
                            Quản lý dữ liệu
                            <i class="right bi bi-chevron-left"></i> </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?controller=supplier&action=index" class="nav-link">
                                <i class="nav-icon bi bi-truck"></i>
                                <p>Nhà cung cấp</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=customer&action=index" class="nav-link">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Khách hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=shipper&action=index" class="nav-link">
                                <i class="nav-icon bi bi-bicycle"></i>
                                <p>Người giao hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=employee&action=index" class="nav-link">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Nhân viên</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-box"></i>
                        <p>
                            Quản lý hàng hóa
                            <i class="right bi bi-chevron-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?controller=category&action=index" class="nav-link">
                                <i class="nav-icon bi bi-collection"></i>
                                <p>Loại hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=product&action=index" class="nav-link">
                                <i class="nav-icon bi bi-boxes"></i>
                                <p>Mặt hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=promotion&action=index" class="nav-link">
                                <i class="nav-icon bi bi-tag"></i>
                                <p>Khuyến mãi</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon bi bi-cash-stack"></i>
                        <p>
                            Quản lý bán hàng
                            <i class="right bi bi-chevron-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="index.php?controller=order&action=create" class="nav-link">
                                <i class="nav-icon bi bi-cart-plus"></i>
                                <p>Lập đơn hàng</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="index.php?controller=order&action=index" class="nav-link">
                                <i class="nav-icon bi bi-receipt"></i>
                                <p>Quản lý đơn hàng</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>