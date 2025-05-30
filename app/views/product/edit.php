<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh Sửa Sản Phẩm</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-error {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
        }
        .existing-image {
            max-width: 200px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Chỉnh Sửa Sản Phẩm</h1>

    <?php 
    // Hiển thị thông báo lỗi từ session
    if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
        <div class="alert alert-error">
            <?php 
            foreach ($_SESSION['errors'] as $error) {
                echo htmlspecialchars($error) . "<br>";
            }
            unset($_SESSION['errors']); // Xóa thông báo sau khi hiển thị
            ?>
                        </div>
                    <?php endif; ?>

    <form action="/Product/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id); ?>">
        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image ?? ''); ?>">

    <div class="form-group">
            <label for="name">Tên Sản Phẩm *</label>
            <input type="text" id="name" name="name" 
                   value="<?php echo htmlspecialchars($_SESSION['form_data']['name'] ?? $product->name); ?>" 
                   required>
                        </div>

    <div class="form-group">
            <label for="description">Mô Tả *</label>
            <textarea id="description" name="description" rows="4" required><?php 
                echo htmlspecialchars($_SESSION['form_data']['description'] ?? $product->description); 
            ?></textarea>
                        </div>

    <div class="form-group">
            <label for="price">Giá *</label>
            <input type="number" id="price" name="price" min="0" step="1000" 
                   value="<?php echo htmlspecialchars($_SESSION['form_data']['price'] ?? $product->price); ?>" 
                   required>
                        </div>

    <div class="form-group">
            <label for="category_id">Danh Mục</label>
            <select id="category_id" name="category_id">
                <option value="">Chọn Danh Mục</option>
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

    <div class="form-group">
            <label for="image">Hình Ảnh</label>
        <?php if ($product->image): ?>
                <div>
                    <img src="/public/<?php echo htmlspecialchars($product->image); ?>"
                         alt="Hình ảnh hiện tại" class="existing-image">
        </div>
        <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/*">
    </div>

        <button type="submit" class="btn">Cập Nhật Sản Phẩm</button>
</form>

    <?php 
    // Xóa dữ liệu form đã lưu sau khi sử dụng
    if (isset($_SESSION['form_data'])) {
        unset($_SESSION['form_data']);
    }
    ?>
</body>
</html>
