<?php include_once 'app/views/layout/header.php'; 
require_once 'app/helpers/UrlHelper.php';
?>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Đăng nhập</h2>
                    
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i> <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= UrlHelper::url('account/checkLogin') ?>" method="post">
                        <div class="mb-4">
                            <label class="form-label" for="username">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" id="username" class="form-control" 
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                    required autofocus>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label" for="password">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p>Chưa có tài khoản? <a href="<?= UrlHelper::url('account/register') ?>" class="text-decoration-none">Đăng ký ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?>