<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sản Phẩm</title>
    <?php 
    // Add PHP at the top to include helpers
    require_once 'app/helpers/UrlHelper.php';
    ?>
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
        
        .header {
            background-color: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            margin-bottom: 30px;
        }
        
        .product-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .product-image-container {
            position: relative;
            overflow: hidden;
            height: 500px;
            background-color: #f8f9fc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.5s ease;
            display: block;
            margin: 0 auto;
        }
        
        .product-image:hover {
            transform: scale(1.05);
        }
        
        .product-details {
            padding: 2rem;
        }
        
        .product-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .product-title:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
        }
        
        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .price-tag {
            background-color: #e8f0fe;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
        }
        
        .product-meta {
            margin-bottom: 2rem;
        }
        
        .product-meta-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .meta-label {
            width: 120px;
            font-weight: 500;
            color: #666;
        }
        
        .meta-value {
            flex-grow: 1;
            color: #333;
        }
        
        .product-description {
            background-color: #f9f9fc;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            position: relative;
            line-height: 1.6;
        }
        
        .description-title {
            position: absolute;
            top: -10px;
            left: 15px;
            background-color: var(--primary-color);
            color: white;
            padding: 0.2rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .add-to-cart-section {
            background-color: #f9f9fc;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .quantity-input {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .quantity-input label {
            margin-right: 15px;
            font-weight: 500;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
        }
        
        .quantity-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f3f9;
            border: 1px solid var(--border-color);
            cursor: pointer;
            font-size: 1.2rem;
            user-select: none;
            transition: all 0.2s;
        }
        
        .quantity-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .quantity-btn.minus {
            border-radius: 8px 0 0 8px;
        }
        
        .quantity-btn.plus {
            border-radius: 0 8px 8px 0;
        }
        
        .quantity-input input {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 1px solid var(--border-color);
            border-left: none;
            border-right: none;
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-lg {
            padding: 15px 30px;
            font-size: 1.1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #3a5ccc;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.2);
        }
        
        .btn-danger {
            background-color: #e74a3b;
            border: none;
        }
        
        .btn-danger:hover {
            background-color: #d52a1a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(231, 74, 59, 0.2);
        }
        
        .btn-warning {
            background-color: #f6c23e;
            border: none;
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #f4b619;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(246, 194, 62, 0.2);
        }
        
        .btn-secondary {
            background-color: #858796;
            border: none;
        }
        
        .btn-secondary:hover {
            background-color: #717384;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(133, 135, 150, 0.2);
        }
        
        .product-features {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            color: #666;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e8f0fe;
            color: var(--primary-color);
            border-radius: 50%;
            font-size: 1.2rem;
        }
        
        .breadcrumb-wrapper {
            margin-bottom: 20px;
        }
        
        .admin-actions {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 30px;
        }
        
        .admin-actions-title {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
        }
        
        @media (max-width: 991px) {
            .product-image-container {
                height: 400px;
            }
        }
        
        @media (max-width: 767px) {
            .product-image-container {
                height: 300px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
        
        .alert {
            border-radius: 10px;
            padding: 1rem 1.5rem;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <div class="header">
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
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-5">
                <div class="product-image-container">
                    <?php if ($product->image): ?>
                        <img src="<?= UrlHelper::asset(htmlspecialchars($product->image)); ?>" 
                             alt="<?php echo htmlspecialchars($product->name); ?>" 
                             class="product-image"
                             onerror="this.src='<?= UrlHelper::asset('uploads/products/default.jpg') ?>'">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <div><i class="fas fa-image fa-5x text-muted"></i></div>
                                <p class="mt-3 text-muted">Không có hình ảnh</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="product-details">
                    <h1 class="product-title"><?php echo htmlspecialchars($product->name); ?></h1>
                    
                    <div class="product-price">
                        <div class="price-tag">
                            <i class="fas fa-tag me-2"></i>
                            <?php echo number_format($product->price, 0, ',', '.') . '₫'; ?>
                        </div>
                    </div>
                    
                    <?php if ($product->category_name): ?>
                        <div class="product-meta">
                            <div class="product-meta-item">
                                <div class="meta-label"><i class="fas fa-folder-open me-2"></i> Danh Mục:</div>
                                <div class="meta-value"><?php echo htmlspecialchars($product->category_name); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-description">
                        <div class="description-title">Mô tả sản phẩm</div>
                        <p class="mb-0"><?php echo htmlspecialchars($product->description); ?></p>
                    </div>
                    
                    <div class="add-to-cart-section">
                        <div class="quantity-input">
                            <label for="quantity">Số lượng:</label>
                            <div class="quantity-control">
                                <div class="quantity-btn minus">-</div>
                                <input type="number" id="quantity" name="quantity" value="1" min="1">
                                <div class="quantity-btn plus">+</div>
                            </div>
                        </div>
                        
                        <div class="action-buttons">
                            <form action="/Product/addToCart" method="POST" style="flex-basis: 48%;">
                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                <input type="hidden" name="quantity" id="cart-quantity" value="1">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                                </button>
                            </form>
                            
                            <form action="/Product/buyNow" method="POST" style="flex-basis: 48%;">
                                <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                <input type="hidden" name="quantity" id="buy-quantity" value="1">
                                <button type="submit" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-bolt"></i> Mua Ngay
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="product-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div>Giao hàng miễn phí</div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>Bảo hành 12 tháng</div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <div>Đổi trả trong 30 ngày</div>
                        </div>
                    </div>
                    
                    <div class="admin-actions">
                        <div class="admin-actions-title">Quản trị</div>
                        <div class="d-flex gap-2">
                            <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Chỉnh Sửa
                            </a>
                            <a href="/Product" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Danh sách sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Quantity controls
            const minusBtn = document.querySelector('.quantity-btn.minus');
            const plusBtn = document.querySelector('.quantity-btn.plus');
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart-quantity');
            const buyQuantityInput = document.getElementById('buy-quantity');
            
            minusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                if (value > 1) {
                    value--;
                    quantityInput.value = value;
                    cartQuantityInput.value = value;
                    buyQuantityInput.value = value;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let value = parseInt(quantityInput.value);
                value++;
                quantityInput.value = value;
                cartQuantityInput.value = value;
                buyQuantityInput.value = value;
            });
            
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                if (value < 1 || isNaN(value)) {
                    value = 1;
                    this.value = value;
                }
                cartQuantityInput.value = value;
                buyQuantityInput.value = value;
            });
        });
    </script>
</body>
</html>
