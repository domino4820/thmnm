<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #1cc88a;
            --text-color: #5a5c69;
            --border-color: #e3e6f0;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fc;
            color: var(--text-color);
            padding-bottom: 50px;
        }
        
        .checkout-header {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        .page-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
            position: relative;
        }
        
        .page-title:after {
            content: "";
            position: absolute;
            width: 80px;
            height: 2px;
            background-color: var(--primary-color);
            bottom: -2px;
            left: 0;
        }
        
        .checkout-section {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        
        .checkout-section:hover {
            transform: translateY(-5px);
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-label {
            font-weight: 500;
            color: #555;
        }
        
        .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
            border-color: var(--primary-color);
        }
        
        .cart-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .cart-item-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .cart-item-details {
            flex-grow: 1;
        }
        
        .cart-item-name {
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
        }
        
        .cart-item-quantity {
            font-size: 0.9rem;
            color: #777;
        }
        
        .cart-item-price {
            font-weight: 600;
            color: var(--primary-color);
            min-width: 100px;
            text-align: right;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 0.95rem;
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            padding-top: 15px;
            margin-top: 15px;
            border-top: 2px solid var(--border-color);
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #3a5ccc;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-success {
            background-color: var(--accent-color);
            border: none;
        }
        
        .btn-success:hover {
            background-color: #18a978;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        
        .product-placeholder {
            width: 60px;
            height: 60px;
            background-color: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767px) {
            .checkout-container {
                flex-direction: column;
            }
            .order-summary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/Product" class="text-decoration-none">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-store me-2"></i> MyStore
                    </h5>
                </a>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/Product" class="text-decoration-none">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="/Product/cart" class="text-decoration-none">Giỏ hàng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title"><?php echo $pageTitle; ?></h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="row g-4">
            <div class="col-md-8">
                <div class="checkout-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-circle me-2"></i> Thông tin khách hàng
                    </h3>
                    <form action="/Product/processCheckout" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="customer_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" id="customer_email" name="customer_email" class="form-control" placeholder="email@example.com">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" id="customer_phone" name="customer_phone" class="form-control" required>
                            </div>
                            
                            <div class="col-12">
                                <label for="customer_address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                <textarea id="customer_address" name="customer_address" class="form-control" rows="3" required></textarea>
                                <div class="form-text">Vui lòng cung cấp địa chỉ đầy đủ để việc giao hàng diễn ra thuận lợi.</div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/Product/cart" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-1"></i> Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="checkout-section">
                    <h3 class="section-title">
                        <i class="fas fa-shopping-cart me-2"></i> Tóm tắt đơn hàng
                    </h3>
                    
                    <div class="cart-items">
                        <?php foreach ($cart as $item): ?>
                            <div class="cart-item">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/public/<?php echo htmlspecialchars($item['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                         class="cart-item-image">
                                <?php else: ?>
                                    <div class="product-placeholder">
                                        <i class="fas fa-box"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="cart-item-details">
                                    <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                    <div class="cart-item-quantity">x<?php echo $item['quantity']; ?></div>
                                </div>
                                <div class="cart-item-price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.') . '₫'; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-total">
                        <span>Tổng thanh toán:</span>
                        <span><?php echo number_format($totalAmount, 0, ',', '.') . '₫'; ?></span>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <div class="d-flex align-items-center justify-content-center text-secondary mb-2">
                        <i class="fas fa-shield-alt me-2"></i> Thanh toán bảo mật
                    </div>
                    <div class="payment-icons d-flex justify-content-center gap-2">
                        <i class="fab fa-cc-visa fa-2x text-secondary"></i>
                        <i class="fab fa-cc-mastercard fa-2x text-secondary"></i>
                        <i class="fab fa-cc-paypal fa-2x text-secondary"></i>
                        <i class="fas fa-money-bill-wave fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 