<?php 
include_once 'app/views/layout/header.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/UrlHelper.php';

// Ensure only admins can access this page
SessionHelper::requireAdmin();
?>

<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users me-2"></i> Quản lý người dùng</h4>
            <a href="<?= UrlHelper::url('Account/createuser') ?>" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i> Thêm người dùng mới
            </a>
        </div>
        
        <div class="card-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i> <?php echo $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên đăng nhập</th>
                            <th scope="col">Họ tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">SĐT</th>
                            <th scope="col">Vai trò</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $index => $user): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <?php if (!empty($user->avatar)): ?>
                                            <img src="<?= UrlHelper::url($user->avatar) ?>" 
                                                class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user->fullname); ?>&background=random&color=fff&size=40" 
                                                class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user->username); ?></td>
                                    <td><?php echo htmlspecialchars($user->fullname); ?></td>
                                    <td><?php echo htmlspecialchars($user->email ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($user->sdt ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge role-badge role-<?php echo $user->role; ?>">
                                            <?php 
                                                echo $user->role === 'admin' ? 'Quản trị viên' : 
                                                    ($user->role === 'employee' ? 'Nhân viên' : 'Khách hàng'); 
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        // 检查是否存在created_at字段且不为空
                                        if (isset($user->created_at) && !empty($user->created_at)) {
                                            echo date('d/m/Y', strtotime($user->created_at)); 
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= UrlHelper::url('Account/edituser?id=' . $user->id) ?>" class="btn btn-sm btn-outline-primary me-1" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['username'] !== $user->username): ?>
                                            <a href="<?= UrlHelper::url('Account/deleteuser?id=' . $user->id) ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"
                                               title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-danger" disabled title="Không thể xóa tài khoản của bạn">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-2"></i> Không có người dùng nào trong hệ thống.
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.role-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.8rem;
}
.role-admin {
    background-color: #dc3545;
    color: white;
}
.role-employee {
    background-color: #fd7e14;
    color: white;
}
.role-user {
    background-color: #0d6efd;
    color: white;
}
</style>

<?php include_once 'app/views/layout/footer.php'; ?> 