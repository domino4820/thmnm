<?php include 'app/views/layout/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Chỉnh Sửa Danh Mục</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error']; 
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= UrlHelper::url('Category/update/' . $category['category_id']) ?>" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên Danh Mục</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô Tả</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            <a href="<?= UrlHelper::url('Category/list') ?>" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/layout/footer.php'; ?> 