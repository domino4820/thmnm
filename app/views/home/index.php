<?php 
// Đảm bảo session được khởi tạo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'app/views/layout/header.php'; 
?>

<div class="position-relative mb-5">
    <div class="row">
        <div class="col-12">
            <img src="/public/uploads/banner-gundam.jpg" alt="Gundam Banner" class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
            <div class="position-absolute top-50 start-50 translate-middle text-center text-white p-4" style="background-color: rgba(0,0,0,0.5); border-radius: 10px;">
                <h2 class="mb-3">Khám Phá Thế Giới Gundam</h2>
                <p class="mb-4">
                    Chúng tôi cung cấp những mô hình Gundam chất lượng cao, 
                    từ các dòng Classic đến những bộ sưu tập mới nhất. 
                    Mỗi mô hình đều được chọn lọc kỹ lưỡng để mang đến trải nghiệm tuyệt vời nhất.
                </p>
                <a href="/Product" class="btn btn-primary btn-lg">
                    <i class="fas fa-robot"></i> Xem Bộ Sưu Tập
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 text-center mb-4">
        <h2>Mô Hình Nổi Bật</h2>
        <p class="text-muted">Những mô hình Gundam mới nhất và được yêu thích nhất</p>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
    <?php if (empty($featuredProducts)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-robot"></i> Chưa có mô hình Gundam nào
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($featuredProducts as $product): ?>
            <div class="col">
                <div class="card h-100 gundam-card">
                    <?php if ($product->image): ?>
                        <img src="/public/<?php echo htmlspecialchars($product->image); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product->name); ?>">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="fas fa-robot text-muted" style="font-size: 5rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product->name); ?></h5>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars(substr($product->description, 0, 100) . '...'); ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">
                                <?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?>
                            </span>
                            <a href="/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include_once 'app/views/layout/footer.php'; ?> 