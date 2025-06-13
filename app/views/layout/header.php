<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Store</title>
    
    <?php 
    // Add PHP at the top to include helpers
    require_once 'app/helpers/SessionHelper.php'; 
    require_once 'app/helpers/UrlHelper.php';
    require_once 'app/config/database.php';
    
    $isLoggedIn = SessionHelper::isLoggedIn();
    $userRole = SessionHelper::getRole();
    $username = SessionHelper::getUsername();
    ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= UrlHelper::asset('css/custom.css') ?>">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="<?= UrlHelper::asset('css/animations.css') ?>">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: #1e3799;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            padding-right: 1.5rem;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.5rem;
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .navbar-brand:hover {
            color: #1e3799;
        }

        .nav-item {
            display: flex;
            align-items: center;
        }

        .nav-link {
            color: #4e4e4e;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
            position: relative;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover {
            color: #4facfe;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 80%;
        }

        .nav-link i {
            margin-right: 6px;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .cart-icon {
            position: relative;
        }

        .cart-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-radius: 50%;
            padding: 4px 7px;
            font-size: 0.7rem;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            padding: 0.5rem 0;
            min-width: 14rem;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .dropdown-item:hover {
            background-color: #f0f7ff;
        }
        
        .dropdown-item i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            color: #4facfe;
        }
        
        .role-badge {
            font-size: 0.7rem;
            padding: 0.25em 0.6em;
            border-radius: 10px;
            font-weight: 600;
            margin-left: 6px;
        }
        
        .role-admin {
            background-color: #dc3545;
            color: white;
        }
        
        .role-employee {
            background-color: #fd7e14;
            color: white;
        }
        
        .role-user {
            background-color: #0d6efd;
            color: white;
        }

        .search-form {
            flex: 0 1 350px;
            margin: 0 1rem;
        }

        @media (max-width: 992px) {
            .search-form {
                margin: 1rem 0;
                flex: 1 1 100%;
            }
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= UrlHelper::url('') ?>">
                <i class="fas fa-robot"></i> Gundam Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= UrlHelper::url('') ?>">
                            <i class="fas fa-home"></i> Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= UrlHelper::url('Product') ?>">
                            <i class="fas fa-robot"></i> Gundam
                        </a>
                    </li>
                    
                    <?php if (SessionHelper::canManageProducts()): ?>
                    <!-- Admin and Employee options -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="productDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-boxes"></i> Quản lý sản phẩm
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="productDropdown">
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('Product/add') ?>"><i class="fas fa-plus"></i> Thêm sản phẩm</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('Product') ?>"><i class="fas fa-list"></i> Danh sách sản phẩm</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('Category/create') ?>"><i class="fas fa-folder-plus"></i> Thêm danh mục</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('Category/list') ?>"><i class="fas fa-tags"></i> Quản lý danh mục</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('ApiManagement/products') ?>"><i class="fas fa-server"></i> Quản lý sản phẩm (API)</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('ApiManagement/categories') ?>"><i class="fas fa-sitemap"></i> Quản lý danh mục (API)</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('ApiFrontend') ?>"><i class="fas fa-code"></i> Frontend API</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (SessionHelper::isAdmin()): ?>
                    <!-- Admin-only options -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield"></i> Quản lý hệ thống
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('account/listusers') ?>"><i class="fas fa-users"></i> Danh sách người dùng</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('account/createuser') ?>"><i class="fas fa-user-plus"></i> Thêm người dùng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('Order/list') ?>"><i class="fas fa-shopping-bag"></i> Quản lý đơn hàng</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <form class="d-flex search-form" action="<?= UrlHelper::url('Product') ?>" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="search" placeholder="Tìm mô hình Gundam..." aria-label="Search">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link cart-icon" href="<?= UrlHelper::url('Product/cart') ?>">
                            <i class="fas fa-shopping-cart"></i> Giỏ Hàng
                            <?php 
                            // 获取当前用户的购物车
                            $cartCount = 0;
                            if (SessionHelper::isLoggedIn()) {
                                $user_id = SessionHelper::getUserId();
                                if (isset($_SESSION['user_carts'][$user_id]) && !empty($_SESSION['user_carts'][$user_id])) {
                                    $cartCount = array_sum(array_column($_SESSION['user_carts'][$user_id], 'quantity'));
                                }
                            } else {
                                if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
                                    $cartCount = array_sum(array_column($_SESSION['guest_cart'], 'quantity'));
                                }
                            }
                            
                            if ($cartCount > 0) {
                                echo '<span class="badge">' . $cartCount . '</span>';
                            }
                            ?>
                        </a>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php
                            // 获取用户头像
                            $userAvatar = SessionHelper::getUserAvatar();
                            
                            if (!empty($userAvatar)):
                            ?>
                                <img src="<?= UrlHelper::url($userAvatar) ?>" 
                                     class="user-avatar">
                            <?php else: ?>
                                <i class="fas fa-user-circle"></i>
                            <?php endif; ?>
                            
                            <?= htmlspecialchars($username) ?>
                            <?php if ($userRole): ?>
                                <span class="role-badge role-<?= strtolower($userRole) ?>"><?= ucfirst(strtolower($userRole)) ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('account/profile') ?>"><i class="fas fa-user"></i> Hồ sơ</a></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('product/orderHistory') ?>"><i class="fas fa-history"></i> Lịch sử mua hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= UrlHelper::url('account/logout') ?>"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= UrlHelper::url('account/login') ?>">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= UrlHelper::url('account/register') ?>">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>
    <!-- Main Container -->
    <div class="container mt-4">