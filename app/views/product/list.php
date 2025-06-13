<?php
// Check if session is not already active before starting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'app/views/layout/header.php'; 
require_once 'app/helpers/UrlHelper.php';
?>

<style>
    .gundam-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
        cursor: pointer;
    }
    
    .gundam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .card-img-top {
        height: 250px;
        object-fit: cover;
        object-position: center;
        width: 100%;
    }

    .product-img-container {
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background-color: #f9f9f9;
        border-radius: 10px 10px 0 0;
        position: relative;
    }

    .product-img-container img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.4s ease;
        display: block;
        margin: 0 auto;
    }
    
    .gundam-card:hover .product-img-container img {
        transform: scale(1.05);
    }
    
    .product-action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .btn-buy-now {
        background-color: #dc3545;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-buy-now:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }
    
    .form-row {
        display: flex;
        gap: 5px;
    }
    
    .card-title {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .card-text {
        color: #666;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        height: 3em;
    }
    
    .search-section {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .clear-button {
        color: #6c757d;
        text-decoration: none;
        margin-left: 10px;
        font-size: 0.9rem;
    }
    
    .clear-button:hover {
        color: #dc3545;
    }
    
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        --bs-pagination-color: #4e73df;
        --bs-pagination-hover-color: #3a5ccc;
        --bs-pagination-active-bg: #4e73df;
        --bs-pagination-active-border-color: #4e73df;
    }
    
    .btn-admin {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    
    .main-title {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 20px;
        color: #333;
        font-weight: 700;
    }
    
    .main-title:after {
        content: "";
        position: absolute;
        width: 60px;
        height: 3px;
        background-color: #4e73df;
        bottom: 0;
        left: 0;
    }
    
    .category-badge {
        background-color: #e8f0fe;
        color: #4e73df;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .price-text {
        color: #4e73df;
        font-weight: 700;
    }
    
    .order-search-link {
        font-size: 0.9rem;
        text-decoration: none;
    }
</style>

<!-- Search & Filter Section -->
<div class="search-section">
    <form action="<?= UrlHelper::url('Product') ?>" method="GET" class="row g-3">
        <div class="col-md-5">
            <label for="search" class="form-label">Tìm kiếm sản phẩm</label>
            <input type="text" class="form-control" id="search" name="search" placeholder="Tên sản phẩm hoặc mô tả..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        </div>
        
        <div class="col-md-4">
            <label for="category_id" class="form-label">Lọc theo danh mục</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>" <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <?php if (!empty($_GET['search']) || !empty($_GET['category_id'])): ?>
                <a href="<?= UrlHelper::url('Product') ?>" class="clear-button">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="main-title mb-0">Bộ Sưu Tập Gundam</h1>
                <p class="text-muted">
                    <?php echo number_format($totalProducts); ?> sản phẩm 
                    <?php if (!empty($_GET['search'])): ?>
                        | Kết quả tìm kiếm cho: <strong><?php echo htmlspecialchars($_GET['search']); ?></strong>
                    <?php endif; ?>
                    
                    <?php if (!empty($_GET['category_id'])): 
                        $categoryName = '';
                        foreach ($categories as $cat) {
                            if ($cat->id == $_GET['category_id']) {
                                $categoryName = $cat->name;
                                break;
                            }
                        }
                    ?>
                        | Danh mục: <strong><?php echo htmlspecialchars($categoryName); ?></strong>
                    <?php endif; ?>
                </p>
            </div>
            <div>
                <a href="<?= UrlHelper::url('Product/cart') ?>" class="btn btn-success me-2">
                    <i class="fas fa-shopping-cart"></i> Xem Giỏ Hàng
                </a>
                <?php if (SessionHelper::canManageProducts()): ?>
                <a href="<?= UrlHelper::url('Product/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Mô Hình Mới
                </a>
                <?php endif; ?>
                <a href="<?= UrlHelper::url('Product/searchOrders') ?>" class="order-search-link ms-3">
                    <i class="fas fa-search"></i> Tìm đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
    <?php if (empty($products)): ?>
        <div class="col-12">
            <div class="alert alert-info text-center" role="alert">
                <i class="fas fa-robot"></i> Chưa có mô hình Gundam nào trong bộ sưu tập
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="col">
                <div class="card gundam-card h-100" onclick="goToProductDetail(<?php echo $product->id; ?>, event)">
                    <div class="product-img-container">
                        <?php if ($product->image): ?>
                            <img src="<?= UrlHelper::asset(htmlspecialchars($product->image)); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($product->name); ?>"
                                 onerror="this.src='<?= UrlHelper::asset('uploads/products/default.jpg') ?>'">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                <i class="fas fa-robot text-muted" style="font-size: 5rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <?php if ($product->category_name): ?>
                            <div class="mb-2">
                                <span class="category-badge"><?php echo htmlspecialchars($product->category_name); ?></span>
                            </div>
                        <?php endif; ?>
                    
                        <h5 class="card-title"><?php echo htmlspecialchars($product->name); ?></h5>
                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars(substr($product->description, 0, 100) . '...'); ?>
                        </p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-text">
                                    <?php echo number_format($product->price, 0, ',', '.') . '₫'; ?>
                                </span>
                                <div class="btn-group" onclick="event.stopPropagation();">
                                    <a href="<?= UrlHelper::url('Product/show/' . $product->id) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Chi tiết
                                    </a>
                                    <?php if (SessionHelper::canManageProducts()): ?>
                                    <a href="<?= UrlHelper::url('Product/edit/' . $product->id) ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $product->id; ?>)" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- Add to Cart Form -->
                            <div class="mt-3" onclick="event.stopPropagation();">
                                <div class="form-row">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm" style="width: 60px;" id="quantity_<?php echo $product->id; ?>">
                                    <div class="product-action-buttons">
                                        <form action="<?= UrlHelper::url('Product/addToCart') ?>" method="POST" class="d-inline">
                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                            <input type="hidden" name="quantity" value="1" id="cart_quantity_<?php echo $product->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="updateQuantity(<?php echo $product->id; ?>, 'cart')">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                        
                                        <form action="<?= UrlHelper::url('Product/buyNow') ?>" method="POST" class="d-inline">
                                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                                            <input type="hidden" name="quantity" value="1" id="buy_quantity_<?php echo $product->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-buy-now" onclick="updateQuantity(<?php echo $product->id; ?>, 'buy')">
                                                <i class="fas fa-bolt"></i> Mua Ngay
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="pagination-container">
        <nav aria-label="Product pagination">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?><?php echo (!empty($_GET['category_id']) ? '&category_id=' . urlencode($_GET['category_id']) : ''); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                if ($startPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1<?php echo (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?><?php echo (!empty($_GET['category_id']) ? '&category_id=' . urlencode($_GET['category_id']) : ''); ?>">1</a>
                    </li>
                    <?php if ($startPage > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?><?php echo (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?><?php echo (!empty($_GET['category_id']) ? '&category_id=' . urlencode($_GET['category_id']) : ''); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
                
                <?php if ($endPage < $totalPages): ?>
                    <?php if ($endPage < $totalPages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $totalPages; ?><?php echo (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?><?php echo (!empty($_GET['category_id']) ? '&category_id=' . urlencode($_GET['category_id']) : ''); ?>">
                            <?php echo $totalPages; ?>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo (!empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?><?php echo (!empty($_GET['category_id']) ? '&category_id=' . urlencode($_GET['category_id']) : ''); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
<?php endif; ?>

<script>
function confirmDelete(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mô hình Gundam này?')) {
        window.location.href = '<?= UrlHelper::url('Product/delete') ?>/' + id;
    }
}

function updateQuantity(productId, type) {
    const quantity = document.getElementById('quantity_' + productId).value;
    if (type === 'cart') {
        document.getElementById('cart_quantity_' + productId).value = quantity;
    } else {
        document.getElementById('buy_quantity_' + productId).value = quantity;
    }
}

function goToProductDetail(productId, event) {
    // Prevent navigation if clicking on buttons/forms
    if (event.target.closest('.btn-group') || 
        event.target.closest('.form-row') || 
        event.target.closest('button') ||
        event.target.closest('form') || 
        event.target.closest('input')) {
        return;
    }
    
    window.location.href = '/Product/show/' + productId;
}
</script>

<?php include_once 'app/views/layout/footer.php'; ?>
