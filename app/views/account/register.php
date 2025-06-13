<?php 
include_once 'app/views/layout/header.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/UrlHelper.php';
?>

<div class="container py-5">
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-8 col-lg-8">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
                    
                    <?php if (isset($errors) && count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?php echo htmlspecialchars($err); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= UrlHelper::url('account/save') ?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" 
                                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="fullname">Họ và tên <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        <input type="text" class="form-control" id="fullname" name="fullname"
                                            value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="sdt">Số điện thoại <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="tel" class="form-control" id="sdt" name="sdt"
                                            value="<?php echo isset($_POST['sdt']) ? htmlspecialchars($_POST['sdt']) : ''; ?>"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="avatar">Ảnh đại diện</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <label class="input-group-text" for="avatar"><i class="fas fa-image"></i></label>
                            </div>
                            <div class="form-text">Chấp nhận file ảnh JPG, PNG (tối đa 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="dia_chi">Địa chỉ</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2"><?php echo isset($_POST['dia_chi']) ? htmlspecialchars($_POST['dia_chi']) : ''; ?></textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="tinh_thanh">Tỉnh/Thành phố</label>
                                    <select class="form-select" id="tinh_thanh" name="tinh_thanh">
                                        <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                        <option value="Hà Nội">Hà Nội</option>
                                        <option value="TP HCM">TP Hồ Chí Minh</option>
                                        <option value="Đà Nẵng">Đà Nẵng</option>
                                        <!-- Thêm các tỉnh thành khác -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="quan_huyen">Quận/Huyện</label>
                                    <select class="form-select" id="quan_huyen" name="quan_huyen">
                                        <option value="">-- Chọn Quận/Huyện --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="phuong_xa">Phường/Xã</label>
                                    <select class="form-select" id="phuong_xa" name="phuong_xa">
                                        <option value="">-- Chọn Phường/Xã --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="password">Mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="confirmpassword">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (SessionHelper::isAdmin()): ?>
                        <div class="mb-4">
                            <label class="form-label" for="role">Vai trò</label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] == 'user') ? 'selected' : ''; ?>>Khách hàng</option>
                                <option value="employee" <?php echo (isset($_POST['role']) && $_POST['role'] == 'employee') ? 'selected' : ''; ?>>Nhân viên</option>
                                <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <?php else: ?>
                            <input type="hidden" name="role" value="user">
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i> Đăng ký
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p>Đã có tài khoản? <a href="<?= UrlHelper::url('account/login') ?>" class="text-decoration-none">Đăng nhập</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simple script to simulate dependent dropdowns
document.getElementById('tinh_thanh').addEventListener('change', function() {
    const quanHuyen = document.getElementById('quan_huyen');
    quanHuyen.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
    
    if (this.value === 'Hà Nội') {
        const districts = ['Ba Đình', 'Hoàn Kiếm', 'Hai Bà Trưng', 'Đống Đa', 'Tây Hồ', 'Cầu Giấy', 'Thanh Xuân'];
        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            quanHuyen.appendChild(option);
        });
    } else if (this.value === 'TP HCM') {
        const districts = ['Quận 1', 'Quận 2', 'Quận 3', 'Quận 4', 'Quận 5', 'Quận 6', 'Quận 7', 'Bình Thạnh', 'Phú Nhuận'];
        districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            quanHuyen.appendChild(option);
        });
    }
});

document.getElementById('quan_huyen').addEventListener('change', function() {
    const phuongXa = document.getElementById('phuong_xa');
    phuongXa.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
    
    // Add some sample wards/communes based on district
    if (this.value) {
        const wards = ['Phường 1', 'Phường 2', 'Phường 3', 'Phường 4', 'Phường 5'];
        wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward;
            phuongXa.appendChild(option);
        });
    }
});
</script>

<?php include_once 'app/views/layout/footer.php'; ?>