<?php 
// Check if session is not already active before starting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once 'app/views/layout/header.php'; 

// Fetch categories and featured products
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/ProductModel.php');
$db = (new Database())->getConnection();
$categoryModel = new CategoryModel($db);
$productModel = new ProductModel($db);
$categories = $categoryModel->getCategories();
$featuredProducts = $productModel->getFeaturedProducts(9); // Change limit to 9
?>

<style>
    /* Banner Redesign */
    .banner-section {
        position: relative;
        background-color: #000033;
        overflow: hidden;
        color: white;
        margin-bottom: 30px;
        min-height: 400px;
    }

    .banner-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/public/uploads/banner-gundam.jpg');
        background-position: center center;
        background-size: cover;
        opacity: 0.5;
        z-index: 1;
    }

    .banner-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
        padding: 20px;
        text-align: center;
    }

    .banner-text {
        position: relative;
        z-index: 3;
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
        background-color: rgba(0,0,0,0.5);
        padding: 30px;
        border-radius: 15px;
    }

    .banner-title {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 20px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    .banner-subtitle {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .banner-cta {
        display: inline-block;
        padding: 12px 30px;
        font-size: 1.1rem;
        background-color: #4a6cf7;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(74,108,247,0.3);
    }

    .banner-cta:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(74,108,247,0.4);
    }

    @media (max-width: 768px) {
        .banner-title {
            font-size: 2rem;
        }

        .banner-subtitle {
            font-size: 1rem;
        }

        .banner-text {
            padding: 20px;
        }
    }

    /* Product Card Category Badge */
    .product-category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #4a6cf7;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
        z-index: 10;
    }

    .gundam-card {
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .gundam-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 25px rgba(0,0,0,0.1);
    }

    .product-img-container {
        height: 250px;
        overflow: hidden;
        display: flex;
        align-items: flex-start; /* Changed to top alignment */
        justify-content: center;
    }

    .product-img-container img {
        width: 100%;
        object-fit: cover;
        object-position: top; /* Ensure image starts from top */
    }

    .category-list {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .category-badge {
        margin: 5px;
        background-color: #4a6cf7;
        color: white;
    }

    /* Responsive Grid for Featured Products */
    .featured-products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    @media (max-width: 992px) {
        .featured-products {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .featured-products {
            grid-template-columns: 1fr;
        }
    }

    /* Product Card Styles */
    .product-card-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        background-color: #4a6cf7;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.8rem;
    }

    .product-card-image {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }
</style>

<!-- Banner Section -->
<div class="banner-section scroll-reveal">
    <div class="banner-background"></div>
    <div class="banner-content">
        <div class="banner-text">
            <h1 class="banner-title">Thế Giới Gundam</h1>
            <p class="banner-subtitle">
                Khám phá bộ sưu tập mô hình Gundam độc đáo và chất lượng cao. 
                Những mô hình chi tiết, đam mê chân thực từng chi tiết.
            </p>
            <a href="/Product" class="banner-cta">
                <i class="fas fa-robot"></i> Khám Phá Ngay
            </a>
        </div>
    </div>
</div>

<div class="container">
    <!-- Category Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="category-list text-center">
                <h4 class="mb-3">Danh Mục Sản Phẩm</h4>
                <?php foreach ($categories as $category): ?>
                    <span class="badge category-badge">
                        <?php echo htmlspecialchars($category->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="row scroll-reveal">
        <div class="col-12 text-center mb-4">
            <h2>Mô Hình Nổi Bật</h2>
            <p class="text-muted">Những mô hình Gundam mới nhất và được yêu thích nhất</p>
        </div>
    </div>

    <div class="featured-products scroll-reveal">
        <?php if (empty($featuredProducts)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-robot"></i> Chưa có mô hình Gundam nào
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="card h-100 gundam-card position-relative">
                    <?php 
                    // Find category for this product
                    $productCategory = null;
                    foreach ($categories as $category) {
                        if ($category->id == $product->category_id) {
                            $productCategory = $category;
                            break;
                        }
                    }
                    ?>
                    
                    <?php if ($productCategory): ?>
                        <span class="product-card-badge">
                            <?php echo htmlspecialchars($productCategory->name); ?>
                        </span>
                    <?php endif; ?>

                    <div class="product-img-container">
                        <?php if ($product->image): ?>
                            <img src="/public/<?php echo htmlspecialchars($product->image); ?>" 
                                 class="card-img-top product-card-image" 
                                 alt="<?php echo htmlspecialchars($product->name); ?>">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center w-100 h-100">
                                <i class="fas fa-robot text-muted" style="font-size: 5rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product->name); ?></h5>
                        <p class="card-text text-muted mb-3">
                            <?php echo htmlspecialchars(substr($product->description, 0, 100) . '...'); ?>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 text-primary mb-0">
                                <?php echo number_format($product->price, 0, ',', '.') . ' VNĐ'; ?>
                            </span>
                            <a href="/Product/show/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?> 