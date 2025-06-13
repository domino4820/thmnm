<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .cart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            align-items: center;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .cart-item-info {
            flex-grow: 1;
        }
        .cart-item-name {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .cart-item-price {
            color: #e83e8c;
            margin-bottom: 5px;
        }
        .cart-item-actions {
            min-width: 120px;
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
        .cart-total {
            text-align: right;
            font-size: 1.2em;
            padding: 15px 0;
            border-top: 2px solid #eee;
            margin-top: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.875rem;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #888;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .quantity-input {
            width: 60px;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <h1><?php echo $pageTitle; ?></h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="cart-container">
        <?php if (empty($cart)): ?>
            <div class="empty-cart">
                <h3>Giỏ hàng trống</h3>
                <p>Vui lòng thêm sản phẩm vào giỏ hàng</p>
                <a href="/Product" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <form action="/Product/updateCart" method="POST">
                <?php foreach ($cart as $index => $item): ?>
                    <div class="cart-item">
                        <?php if (isset($item['image']) && $item['image']): ?>
                            <img src="/public/<?php echo htmlspecialchars($item['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                 class="cart-item-image">
                        <?php else: ?>
                            <div style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 5px;">
                                <span style="color: #999;">No Image</span>
                            </div>
                        <?php endif; ?>

                        <div class="cart-item-info">
                            <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="cart-item-price"><?php echo number_format($item['price'], 0, ',', '.') . ' VNĐ'; ?></div>
                            <div>
                                <label for="quantity_<?php echo $item['product_id']; ?>">Số lượng:</label>
                                <input type="number" 
                                       id="quantity_<?php echo $item['product_id']; ?>" 
                                       name="quantity[<?php echo $item['product_id']; ?>]" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" 
                                       class="quantity-input">
                            </div>
                            <div>Thành tiền: <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.') . ' VNĐ'; ?></div>
                        </div>
                        
                        <div class="cart-item-actions">
                            <a href="/Product/removeFromCart?product_id=<?php echo $item['product_id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="cart-total">
                    <strong>Tổng tiền: <?php echo number_format($totalAmount, 0, ',', '.') . ' VNĐ'; ?></strong>
                </div>

                <div class="cart-actions">
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync-alt"></i> Cập nhật giỏ hàng
                        </button>
                    </div>
                    <div>
                        <a href="/Product" class="btn btn-secondary">Tiếp tục mua sắm</a>
                        <a href="/Product/checkout" class="btn btn-success">Thanh toán</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html> 