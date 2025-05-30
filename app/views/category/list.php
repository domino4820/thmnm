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

include_once 'app/views/layout/header.php'; 
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Danh mục sản phẩm</h1>
                <a href="/Category/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Danh Mục Mới
                </a>
            </div>

            <?php if ($SuccessMessage): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($SuccessMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($ErrorMessage): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($ErrorMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($categories)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> Chưa có danh mục nào
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Tên Danh Mục</th>
                                <th>Mô Tả</th>
                                <th class="text-center">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($category->id); ?></td>
                                <td><?php echo htmlspecialchars($category->name); ?></td>
                                <td><?php echo htmlspecialchars($category->description); ?></td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="/Category/edit/<?php echo $category->id; ?>" class="btn btn-sm btn-warning me-2">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $category->id; ?>)" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </div>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Xác Nhận Xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa danh mục này không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="#" id="deleteLink" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(categoryId) {
    const deleteLink = document.getElementById('deleteLink');
    deleteLink.href = `/Category/delete/${categoryId}`;
    
    // Use Bootstrap's modal method to show the modal
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
