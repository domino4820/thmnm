<?php
// Lấy dữ liệu form từ session nếu có lỗi
$form_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];

// Xóa dữ liệu session sau khi sử dụng
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><?php echo $pageTitle; ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="/Product/save" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" 
                                       id="name" name="name" 
                                       value="<?php echo htmlspecialchars($form_data['name'] ?? ''); ?>"
                                       required>
                                <?php if(isset($errors['name'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['name']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" 
                                          id="description" name="description" 
                                          rows="3" required><?php echo htmlspecialchars($form_data['description'] ?? ''); ?></textarea>
                                <?php if(isset($errors['description'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['description']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Giá</label>
                                <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" 
                                       id="price" name="price" 
                                       value="<?php echo htmlspecialchars($form_data['price'] ?? ''); ?>"
                                       min="0" step="1000" required>
                                <?php if(isset($errors['price'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['price']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" 
                                        id="category_id" name="category_id" required>
                                    <option value="">Chọn danh mục</option>
                                    <?php foreach($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>" 
                                            <?php echo (isset($form_data['category_id']) && $form_data['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($errors['category_id'])): ?>
                                    <div class="invalid-feedback">
                                        <?php echo $errors['category_id']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
