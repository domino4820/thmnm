    <?php 
include_once 'app/views/layout/header.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/helpers/UrlHelper.php';

// Đảm bảo quyền quản lý sản phẩm
SessionHelper::requireProductManager();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Chỉnh sửa sản phẩm</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form action="<?= UrlHelper::url('Product/update') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id); ?>">
        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image ?? ''); ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" 
                   value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? $product->name); ?>" 
                   required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" name="description" rows="4" required><?php 
                echo htmlspecialchars($_SESSION['form_data']['description'] ?? $product->description); 
            ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="price" name="price" min="0" step="1000" 
                   value="<?php echo htmlspecialchars($_SESSION['form_data']['price'] ?? $product->price); ?>" 
                   required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                </div>
                        </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
            <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>" 
                        <?php 
                        $selectedCategory = $_SESSION['form_data']['category_id'] ?? $product->category_id;
                        echo ($selectedCategory == $category->id) ? 'selected' : ''; 
                        ?>>
                        <?php echo htmlspecialchars($category->name); ?>
            </option>
            <?php endforeach; ?>
        </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                            <?php if (!empty($product->image)): ?>
                                <div class="mb-2">
                                    <img src="<?= UrlHelper::url($product->image) ?>" 
                                         alt="Hình ảnh hiện tại" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">Để trống nếu không muốn thay đổi hình ảnh</div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-save me-2"></i> Cập nhật sản phẩm
                            </button>
                            <a href="<?= UrlHelper::url('Product/list') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

    <?php 
    // Xóa dữ liệu form đã lưu sau khi sử dụng
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }

include_once 'app/views/layout/footer.php'; 
    ?>
