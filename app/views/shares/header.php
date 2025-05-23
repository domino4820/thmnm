<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --secondary-color: #2c3e50;
            --accent-color: #f86f03;
            --light-color: #ecf0f1;
            --dark-color: #1e293b;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --border-radius: 12px;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition-fast: 0.3s ease;
            --transition-slow: 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.8s ease forwards;
        }
        
        .animate-pulse:hover {
            animation: pulse 1s infinite;
        }
        
        .animate-float {
            animation: float 3s ease infinite;
        }
        
        .animate-slide-right {
            animation: slideInRight 0.6s ease forwards;
        }
        
        .animate-slide-left {
            animation: slideInLeft 0.6s ease forwards;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Glassmorphism effect for navbar */
        .navbar {
            background-color: rgba(30, 41, 59, 0.98) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 800;
            color: white !important;
            font-size: 1.6rem;
            position: relative;
            overflow: hidden;
        }
        
        .navbar-brand i {
            color: var(--primary-color);
            margin-right: 10px;
            transform: translateY(0);
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover i {
            animation: float 1s ease infinite;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.4s ease;
            position: relative;
            margin: 0 5px;
            padding: 8px 15px !important;
            border-radius: 6px;
        }
        
        .nav-link:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            color: white !important;
            background-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(74, 108, 247, 0.4);
        }
        
        .nav-link.active i {
            transform: scale(1.2);
        }
        
        .nav-link i {
            transition: transform 0.3s ease;
        }
        
        .container-fluid {
            padding-top: 2rem;
            padding-bottom: 2rem;
            flex: 1;
        }
        
        /* Enhanced card styling */
        .card {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            border: none;
            overflow: hidden;
            position: relative;
            animation: fadeIn 0.8s ease;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        
        .card:hover::before {
            transform: scaleX(1);
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }
        
        .card-title {
            transition: color 0.3s ease;
        }
        
        .card:hover .card-title {
            color: var(--primary-color);
        }
        
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            transition: left 0.6s ease;
        }
        
        .btn:hover::after {
            left: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), #6284fd);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(to right, #3a5bf5, #5170fd);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(74, 108, 247, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(to right, var(--success-color), #4ade80);
            border: none;
        }
        
        .btn-success:hover {
            background: linear-gradient(to right, #16a34a, #22c55e);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(34, 197, 94, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(to right, var(--warning-color), #fbbf24);
            border: none;
            color: white;
        }
        
        .btn-warning:hover {
            background: linear-gradient(to right, #d97706, #f59e0b);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(245, 158, 11, 0.3);
        }
        
        .btn-danger {
            background: linear-gradient(to right, var(--danger-color), #f87171);
            border: none;
        }
        
        .btn-danger:hover {
            background: linear-gradient(to right, #dc2626, #ef4444);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(239, 68, 68, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(74, 108, 247, 0.3);
        }
        
        .page-header {
            padding: 2.5rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            background: linear-gradient(to right, #f8fafc, #f1f5f9);
            border-radius: var(--border-radius);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }
        
        .page-header h1 {
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            position: relative;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.85rem 1.2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.4s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(74, 108, 247, 0.15);
            border-color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.75rem;
        }
        
        /* Product image hover effect */
        .product-img-container {
            overflow: hidden;
        }
        
        .product-img-container img {
            transition: transform 0.6s ease;
        }
        
        .card:hover .product-img-container img {
            transform: scale(1.1);
        }
        
        /* Price tag styling */
        .price-tag {
            font-weight: 700;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .card:hover .price-tag {
            transform: scale(1.1);
        }
        
        /* Badge styling */
        .badge {
            padding: 0.35em 0.8em;
            font-weight: 600;
            border-radius: 6px;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand animate-float" href="/projectbanhang/"><i class="fas fa-box"></i> Quản lý sản phẩm</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/projectbanhang/Product/"><i class="fas fa-list me-1"></i> Danh sách sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/projectbanhang/Product/add"><i class="fas fa-plus me-1"></i> Thêm sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/projectbanhang/Category/list"><i class="fas fa-tag me-1"></i> Danh mục</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid container py-4">
