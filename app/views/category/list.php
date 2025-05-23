<?php include 'app/views/shares/header.php'; ?>
<h1>Danh mục sản phẩm</h1>
<ul class="list-group">
    <?php foreach ($categories as $category): ?>
    <li class="list-group-item">
        <h2><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></h2>
        <p><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></p>
    </li>
    <?php endforeach; ?>
</ul>
<?php include 'app/views/shares/footer.php'; ?> 