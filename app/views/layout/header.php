<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gundam Store</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Animations -->
    <link rel="stylesheet" href="/public/css/animations.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: #4a6cf7;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        .cart-icon {
            position: relative;
        }

        .cart-icon .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-robot"></i> Gundam Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home"></i> Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Product">
                            <i class="fas fa-robot"></i> Gundam
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Category">
                            <i class="fas fa-list"></i> Danh Mục
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item cart-icon">
                        <a class="nav-link" href="/Cart">
                            <i class="fas fa-shopping-cart"></i> Giỏ Hàng
                            <span class="badge">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4"><?php
    // Hiển thị thông báo lỗi
    if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['error']); 
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php 
    // Hiển thị thông báo thành công
    if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['success']); 
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    </main>
    <div class="container"><?php // Thêm div container để đóng container từ header ?></div>
</body>
</html> 