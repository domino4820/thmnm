<?php
// Check if session is not already active before starting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'app/views/layout/header.php'; 
?>

<style>
    .search-section {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .main-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 20px;
        color: #333;
        font-weight: 700;
    }
    
    .main-title:after {
        content: "";
        position: absolute;
        width: 60px;
        height: 3px;
        background-color: #4e73df;
        bottom: 0;
        left: 0;
    }
    
    .order-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .order-card .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 15px 20px;
    }
    
    .order-id {
        font-weight: 700;
        color: #4e73df;
    }
    
    .order-date {
        color: #666;
        font-size: 0.9rem;
    }
    
    .order-amount {
        font-weight: 700;
        color: #20a779;
    }
    
    .customer-info {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e3e6f0;
    }
    
    .customer-info-item {
        display: flex;
        margin-bottom: 5px;
    }
    
    .info-label {
        width: 120px;
        color: #666;
        font-size: 0.9rem;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    .empty-state {
        text-align: center;
        padding: 50px 0;
        color: #6c757d;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }
</style>

<div class="row mb-4">
    <div class="col-12">
        <a href="/Product" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm
        </a>
    </div>
</div>

<h1 class="main-title">Tra cứu đơn hàng</h1>

<div class="search-section">
    <form action="/Product/searchOrders" method="GET" class="row g-3">
        <div class="col-md-9">
            <label for="search" class="form-label">Tìm kiếm đơn hàng</label>
            <input type="text" class="form-control" id="search" name="search" 
                   placeholder="Nhập tên, email, số điện thoại hoặc mã đơn hàng..." 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <div class="form-text">Tìm kiếm theo tên khách hàng, email, số điện thoại hoặc mã đơn hàng</div>
        </div>
        
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
</div>

<?php if (isset($_GET['search']) && empty($_GET['search'])): ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-search"></i>
        </div>
        <p>Vui lòng nhập thông tin để tìm kiếm đơn hàng</p>
    </div>
<?php elseif (isset($_GET['search']) && empty($orders)): ?>
    <div class="empty-state">
        <div class="empty-icon">
            <i class="fas fa-file-alt"></i>
        </div>
        <p>Không tìm thấy đơn hàng nào phù hợp với từ khóa: <strong><?php echo htmlspecialchars($_GET['search']); ?></strong></p>
    </div>
<?php elseif (!empty($orders)): ?>
    <div class="row">
        <?php foreach ($orders as $order): ?>
            <div class="col-md-6">
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <div class="order-id">#<?php echo $order->id; ?></div>
                            <div class="order-date">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                <?php echo date('d/m/Y H:i', strtotime($order->order_date)); ?>
                            </div>
                        </div>
                        <div class="order-amount">
                            <?php echo number_format($order->total_amount, 0, ',', '.') . '₫'; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Thông tin khách hàng</h5>
                        
                        <div class="customer-info">
                            <div class="customer-info-item">
                                <div class="info-label">
                                    <i class="fas fa-user me-1"></i> Họ tên:
                                </div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order->customer_name); ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($order->customer_email)): ?>
                                <div class="customer-info-item">
                                    <div class="info-label">
                                        <i class="fas fa-envelope me-1"></i> Email:
                                    </div>
                                    <div class="info-value">
                                        <?php echo htmlspecialchars($order->customer_email); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="customer-info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone me-1"></i> Số điện thoại:
                                </div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order->customer_phone); ?>
                                </div>
                            </div>
                            
                            <div class="customer-info-item">
                                <div class="info-label">
                                    <i class="fas fa-map-marker-alt me-1"></i> Địa chỉ:
                                </div>
                                <div class="info-value">
                                    <?php echo htmlspecialchars($order->customer_address); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="/Product/orderConfirmation/<?php echo $order->id; ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-eye me-1"></i> Xem chi tiết đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include_once 'app/views/layout/footer.php'; ?> 