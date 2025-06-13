<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }
        .order-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .order-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-header h1 {
            color: #28a745;
            margin-bottom: 10px;
        }
        .order-id {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 20px;
        }
        .order-section {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-title {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .customer-info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            display: block;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
        }
        .order-items th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 12px;
            font-weight: 600;
        }
        .order-items td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-items tr:last-child td {
            border-bottom: none;
        }
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .product-name {
            font-weight: 500;
        }
        .text-right {
            text-align: right;
        }
        .order-total {
            font-size: 1.2rem;
            font-weight: 600;
            text-align: right;
            padding: 15px 12px;
            border-top: 2px solid #f0f0f0;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .product-placeholder {
            width: 60px;
            height: 60px;
            background-color: #f0f0f0;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <div class="order-header">
            <h1><i class="fas fa-check-circle"></i> Đặt hàng thành công!</h1>
            <div class="order-id">Mã đơn hàng: #<?php echo $order['id']; ?></div>
            <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.</p>
        </div>

        <div class="order-section">
            <h2 class="section-title">Thông tin khách hàng</h2>
            <div class="customer-info">
                <div class="info-item">
                    <span class="info-label">Họ và tên:</span>
                    <span><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span><?php echo htmlspecialchars($order['customer_email'] ?? 'Không có'); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Số điện thoại:</span>
                    <span><?php echo htmlspecialchars($order['customer_phone']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Địa chỉ giao hàng:</span>
                    <span><?php echo htmlspecialchars($order['customer_address']); ?></span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Ngày đặt hàng:</span>
                    <span>
                        <?php 
                        if (isset($order['order_date']) && !empty($order['order_date'])) {
                            echo date('d/m/Y H:i', strtotime($order['order_date']));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="order-section">
            <h2 class="section-title">Chi tiết đơn hàng</h2>
            <table class="order-items">
                <thead>
                    <tr>
                        <th style="width: 50%">Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td>
                                <div class="product-info">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="/public/<?php echo htmlspecialchars($item['image']); ?>" 
                                            alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                            class="product-img">
                                    <?php else: ?>
                                        <div class="product-placeholder">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="product-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                                </div>
                            </td>
                            <td><?php echo number_format($item['price'], 0, ',', '.') . ' VNĐ'; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td class="text-right"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.') . ' VNĐ'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="order-total">
                Tổng thanh toán: <?php echo number_format($order['total_amount'], 0, ',', '.') . ' VNĐ'; ?>
            </div>
        </div>
        
        <div class="btn-group">
            <a href="/Product" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
            </a>
            <a href="#" onclick="window.print(); return false;" class="btn btn-success">
                <i class="fas fa-print"></i> In đơn hàng
            </a>
        </div>
    </div>
</body>
</html>
