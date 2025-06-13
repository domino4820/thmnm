<?php include_once 'app/views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-lg mb-4">
                <div class="card-body text-center">
                    <?php if (!empty($account->avatar)): ?>
                        <img src="<?= UrlHelper::url($account->avatar) ?>" 
                            class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($account->fullname); ?>&background=random&color=fff&size=128" 
                            class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php endif; ?>
                    <h5 class="my-3"><?php echo htmlspecialchars($account->fullname); ?></h5>
                    <p class="text-muted mb-1">
                        <span class="badge role-badge role-<?php echo $account->role; ?>">
                            <?php 
                                echo $account->role === 'admin' ? 'Quản trị viên' : 
                                    ($account->role === 'employee' ? 'Nhân viên' : 'Khách hàng'); 
                            ?>
                        </span>
                    </p>
                    <?php if (!empty($account->email)): ?>
                        <p class="text-muted mb-1">
                            <i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($account->email); ?>
                        </p>
                    <?php endif; ?>
                    <?php if (!empty($account->sdt)): ?>
                        <p class="text-muted mb-1">
                            <i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($account->sdt); ?>
                        </p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-center mt-4">
                        <a href="<?= UrlHelper::url('product/orderHistory') ?>" class="btn btn-outline-primary me-2">
                            <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                        </a>
                        <a href="<?= UrlHelper::url('account/logout') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Thông tin cá nhân</h5>
                </div>
                
                <div class="card-body">
                    <?php if (isset($errors) && count($errors) > 0): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?php echo htmlspecialchars($err); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= UrlHelper::url('account/updateprofile') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($account->username); ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($account->fullname); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($account->email ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($account->sdt ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <label class="input-group-text" for="avatar"><i class="fas fa-image"></i></label>
                            </div>
                            <div class="form-text">Chấp nhận file ảnh JPG, PNG (tối đa 2MB)</div>
                            <?php if (!empty($account->avatar)): ?>
                                <div class="mt-2">
                                    <span class="badge bg-info">Đã có ảnh đại diện</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2"><?php echo htmlspecialchars($account->dia_chi ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tinh_thanh" class="form-label">Tỉnh/Thành phố</label>
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
                                    <label for="quan_huyen" class="form-label">Quận/Huyện</label>
                                    <select class="form-select" id="quan_huyen" name="quan_huyen">
                                        <option value="">-- Chọn Quận/Huyện --</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phuong_xa" class="form-label">Phường/Xã</label>
                                    <select class="form-select" id="phuong_xa" name="phuong_xa">
                                        <option value="">-- Chọn Phường/Xã --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 mb-3">
                            <h5>Đổi mật khẩu</h5>
                            <p class="text-muted small">Để trống nếu không muốn đổi mật khẩu</p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Cập nhật thông tin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Similar script for updating dropdowns
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