<?php
// Check if session is not already active before starting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'app/views/layout/header.php'; 
?>

<style>
    .gundam-card .card-img-top {
        height: 250px;
        object-fit: cover;
        object-position: center;
        width: 100%;
    }

    .product-img-container {
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .product-img-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Bộ Sưu Tập Gundam</h1>
            <a href="/Product/add" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm Mô Hình Mới
            </a>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if (empty($products)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-robot"></i> Chưa có mô hình Gundam nào trong bộ sưu tập
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card h-100 gundam-card">
                    <div class="product-img-container">
                        <?php if ($product->image): ?>
                            <img src="/public/<?php echo htmlspecialchars($product->image); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($product->name); ?>">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                <i class="fas fa-robot text-muted" style="font-size: 5rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product->name); ?></h5>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars(substr($product->description, 0, 100) . '...'); ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">
                                <?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?>
                            </span>
                            <div class="btn-group">
                                <a href="/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete(<?php echo $product->id; ?>)" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mô hình Gundam này?')) {
        window.location.href = '/Product/delete/' + id;
    }
}
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
