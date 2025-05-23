<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Sản Phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .product-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            gap: 30px;
        }
        .product-image {
            max-width: 300px;
            border-radius: 8px;
            object-fit: cover;
        }
        .product-details {
            flex-grow: 1;
        }
        .product-title {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .product-info {
            margin-bottom: 15px;
        }
        .product-info label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 120px;
        }
        .product-description {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-warning {
            background-color: #ffc107;
            color: black;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <div class="product-container">
        <?php if ($product->image): ?>
            <img src="/public/<?php echo htmlspecialchars($product->image); ?>" 
                 alt="<?php echo htmlspecialchars($product->name); ?>" 
                 class="product-image">
        <?php else: ?>
            <div style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; width: 300px; height: 300px; border-radius: 8px;">
                <span style="color: #999;">Không có hình ảnh</span>
            </div>
        <?php endif; ?>

        <div class="product-details">
            <h1 class="product-title"><?php echo htmlspecialchars($product->name); ?></h1>

            <div class="product-description">
                <p><?php echo htmlspecialchars($product->description); ?></p>
            </div>

            <div class="product-info">
                <label>Giá:</label>
                <span><?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?></span>
            </div>

            <?php if ($product->category_name): ?>
                <div class="product-info">
                    <label>Danh Mục:</label>
                    <span><?php echo htmlspecialchars($product->category_name); ?></span>
                </div>
            <?php endif; ?>

            <div class="product-actions">
                <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Chỉnh Sửa</a>
                <a href="/Product" class="btn btn-secondary">Quay Lại</a>
            </div>
        </div>
    </div>
</body>
</html>
