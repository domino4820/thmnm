<?php 
include_once 'app/views/layout/header.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/UrlHelper.php';

// Ensure only admins can access this page
SessionHelper::requireAdmin();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i> Chỉnh sửa người dùng</h4>
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
                    
                    <form action="<?= UrlHelper::url('Account/updateuser') ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user->username); ?>" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user->fullname); ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->email ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sdt" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="sdt" name="sdt" value="<?php echo htmlspecialchars($user->sdt ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-select" id="role" name="role" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role; ?>" <?php echo ($user->role === $role) ? 'selected' : ''; ?>>
                                        <?php 
                                            echo $role === 'admin' ? 'Quản trị viên' : 
                                                ($role === 'employee' ? 'Nhân viên' : 'Khách hàng'); 
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Ảnh đại diện</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                        <label class="input-group-text" for="avatar"><i class="fas fa-image"></i></label>
                                    </div>
                                    <div class="form-text">Chấp nhận file ảnh JPG, PNG (tối đa 2MB)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($user->avatar)): ?>
                                <div class="text-center mt-2">
                                    <img src="<?= UrlHelper::url($user->avatar) ?>" 
                                        class="img-thumbnail" style="max-height: 100px;">
                                    <p class="small text-muted mt-1">Ảnh đại diện hiện tại</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">Địa chỉ</label>
                            <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2"><?php echo htmlspecialchars($user->dia_chi ?? ''); ?></textarea>
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
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>
                        
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i> Cập nhật
                            </button>
                            <a href="<?= UrlHelper::url('Account/listusers') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script for updating dropdowns
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

// Initialize dropdowns if address is already set
window.addEventListener('DOMContentLoaded', function() {
    const addressText = document.getElementById('dia_chi').value;
    if (addressText) {
        // Try to extract location information from address text
        // This is a simple implementation - in a real app, you'd need more sophisticated parsing
        const parts = addressText.split(', ');
        if (parts.length >= 3) {
            // Assuming format: street, ward, district, city
            const cityIndex = parts.length - 1;
            const districtIndex = parts.length - 2;
            const wardIndex = parts.length - 3;
            
            // Set city if it exists in our options
            const tinhThanh = document.getElementById('tinh_thanh');
            for (let i = 0; i < tinhThanh.options.length; i++) {
                if (tinhThanh.options[i].value && parts[cityIndex].includes(tinhThanh.options[i].value)) {
                    tinhThanh.value = tinhThanh.options[i].value;
                    // Trigger change event to load districts
                    tinhThanh.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }
    }
});
</script>

<?php include_once 'app/views/layout/footer.php'; ?> 