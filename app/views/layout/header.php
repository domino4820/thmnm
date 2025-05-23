<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Gundam Store'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background-color: #2c3e50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            color: #ecf0f1 !important;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #bdc3c7 !important;
            transition: color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
        }
        .gundam-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .gundam-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-robot"></i> Gundam Store
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Product"><i class="fas fa-robot"></i> Gundam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Category"><i class="fas fa-tags"></i> Danh Mục</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Cart"><i class="fas fa-shopping-cart"></i> Giỏ Hàng</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php 
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
    </div>
    <div class="container"><?php // Thêm div container để đóng container từ header ?></div>
</body>
</html> 