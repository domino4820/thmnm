<?php include_once 'app/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <i class="fas fa-exclamation-triangle text-warning display-1 mb-4"></i>
                    <h1 class="text-danger mb-4">Đã xảy ra lỗi</h1>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']); 
                            ?>
                        </div>
                    <?php else: ?>
                        <p class="lead mb-4">Đã xảy ra lỗi không xác định. Vui lòng thử lại sau.</p>
                    <?php endif; ?>
                    
                    <div class="mt-4">
                        <a href="<?= UrlHelper::url('') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Quay lại trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?> 