<?php include_once 'app/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="mb-4 text-center">
                <i class="fas fa-history me-2"></i> Lịch sử mua hàng
            </h1>
            
            <?php if (empty($orders)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i> Bạn chưa có đơn hàng nào.
                </div>
                <div class="text-center mt-4">
                    <a href="<?= UrlHelper::url('Product') ?>" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i> Mua sắm ngay
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Ngày đặt</th>
                                <th>Số lượng sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($order['id']) ?></td>
                                    <td>
                                        <?php 
                                        if (isset($order['order_date']) && !empty($order['order_date'])) {
                                            echo date('d/m/Y H:i', strtotime($order['order_date']));
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($order['item_count']) ?> sản phẩm</td>
                                    <td><?= number_format($order['total_amount'], 0, ',', '.') ?> đ</td>
                                    <td>
                                        <?php
                                        $status = $order['status'] ?? 'pending';
                                        $statusClass = '';
                                        $statusText = '';
                                        
                                        switch($status) {
                                            case 'completed':
                                                $statusClass = 'success';
                                                $statusText = 'Đã hoàn thành';
                                                break;
                                            case 'processing':
                                                $statusClass = 'primary';
                                                $statusText = 'Đang xử lý';
                                                break;
                                            case 'cancelled':
                                                $statusClass = 'danger';
                                                $statusText = 'Đã hủy';
                                                break;
                                            default:
                                                $statusClass = 'warning';
                                                $statusText = 'Chờ xác nhận';
                                                break;
                                        }
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= UrlHelper::url('Product/orderConfirmation/' . $order['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?> 