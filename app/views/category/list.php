<?php 
// Check if session is not already active before starting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define variables with default null values
$SuccessMessage = null;
$ErrorMessage = null;

// Check and assign session messages
if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    $SuccessMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}

if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $ErrorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}

include 'app/views/layout/header.php'; 
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Danh Sách Danh Mục</h2>
        <a href="<?= UrlHelper::url('Category/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Thêm Danh Mục Mới
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($categories)): ?>
                <div class="alert alert-info">
                    Chưa có danh mục nào. Hãy tạo danh mục mới.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên Danh Mục</th>
                                <th>Mô Tả</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category['category_id'] ?></td>
                                    <td><?= htmlspecialchars($category['name']) ?></td>
                                    <td><?= htmlspecialchars($category['description'] ?? 'Không có mô tả') ?></td>
                                    <td>
                                        <a href="<?= UrlHelper::url('Category/edit/' . $category['category_id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="<?= UrlHelper::url('Category/delete/' . $category['category_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            <i class="fas fa-trash"></i> Xóa
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

<?php include 'app/views/layout/footer.php'; ?>
