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

// Load manually if no categories
if (!$categoryModel->getCategories()) {
    $manualCategories = [
        (object)['id' => 1, 'name' => 'Perfect Grade (PG)', 'image' => 'PG.jpg'],
        (object)['id' => 2, 'name' => 'High Grade (HG)', 'image' => 'HG.jpg'],
        (object)['id' => 3, 'name' => 'SD', 'image' => 'sd.jpg'],
        (object)['id' => 4, 'name' => 'Freedom Strike', 'image' => 'FREEDOM_STRVE.jpg'],
    ];
    $categories = $manualCategories;
} else {
    $categories = $categoryModel->getCategories();
}

// Get featured products
$featuredProducts = $productModel->getFeaturedProducts(6);
if (empty($featuredProducts)) {
    $featuredProducts = $productModel->getProducts(6);
}
?>

<style>
    /* Google Font Import */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
    }

    /* Hero Section Styles */
    .hero-section {
        position: relative;
        background: linear-gradient(135deg, #050c2c 0%, #1e3799 100%);
        color: white;
        overflow: hidden;
        border-radius: 0;
        margin-top: -1.5rem;
        height: 500px;
        display: flex;
        align-items: center;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('<?= UrlHelper::asset('uploads/banner-gundam.jpg') ?>');
        background-position: center;
        background-size: cover;
        opacity: 0.35;
        z-index: 1;
    }

    .hero-content {
        position: relative;
        z-index: 2;
        padding: 0 15px;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -0.5px;
    }

    .hero-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        font-weight: 300;
        max-width: 600px;
        line-height: 1.8;
    }

    .hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        font-weight: 600;
        padding: 12px 30px;
        border-radius: 50px;
        font-size: 1.1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3);
    }

    .hero-cta:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 242, 254, 0.4);
        color: white;
    }

    /* Category Section */
    .section-title {
        position: relative;
        margin-bottom: 2rem;
        font-weight: 700;
        color: #333;
        padding-bottom: 10px;
    }

    .section-title:after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        border-radius: 2px;
    }

    .section-title.text-center:after {
        left: 50%;
        transform: translateX(-50%);
    }

    .category-card {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 1.5rem;
        cursor: pointer;
        height: 180px;
    }
    
    .category-card .image-container {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }
    
    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    
    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .category-card:hover img {
        transform: scale(1.1);
    }
    
    .category-card-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 15px;
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
        z-index: 1;
    }

    .category-card-title {
        color: white;
        font-weight: 600;
        margin: 0;
        font-size: 1.2rem;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.6);
    }

    /* Featured Products */
    .product-section {
        background-color: #f8f9fa;
        padding: 3rem 0;
        margin: 3rem 0;
        border-radius: 20px;
    }

    .product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        height: 100%;
        background-color: white;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .product-image-container {
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        font-weight: 500;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.8rem;
        z-index: 2;
    }

    .product-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-top: 1rem;
        color: #333;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .product-price {
        font-weight: 700;
        color: #4facfe;
        font-size: 1.3rem;
    }

    .btn-view {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 8px 20px;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        color: white;
    }
    
    /* Testimonials Styles */
    .testimonials-section {
        padding: 5rem 0;
        background-color: #fff;
    }
    
    .testimonial-card {
        background-color: #fff;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        position: relative;
    }
    
    .testimonial-card:before {
        content: "\201C";
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 5rem;
        font-family: Georgia, serif;
        color: rgba(79, 172, 254, 0.1);
        line-height: 0;
    }
    
    .testimonial-content {
        padding-top: 30px;
        font-style: italic;
        color: #555;
        line-height: 1.6;
    }
    
    .testimonial-author {
        display: flex;
        align-items: center;
        margin-top: 1.5rem;
    }
    
    .testimonial-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 3px solid #f0f0f0;
    }
    
    .testimonial-info h5 {
        margin: 0;
        font-weight: 600;
        color: #333;
    }
    
    .testimonial-info span {
        font-size: 0.85rem;
        color: #777;
    }
    
    /* Stats Section */
    .stats-section {
        padding: 4rem 0;
        background: linear-gradient(135deg, #050c2c 0%, #1e3799 100%);
        color: white;
        text-align: center;
    }
    
    .stats-card {
        padding: 1.5rem;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #4facfe;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stats-text {
        font-size: 1.1rem;
        opacity: 0.9;
    }
</style>

<!-- Hero Section with Banner -->
<div class="hero-section">
    <div class="hero-background"></div>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">Gundam Store <br>Mô hình Gunpla chính hãng</h1>
                    <p class="hero-subtitle">Cửa hàng chuyên cung cấp các mô hình Gundam chính hãng, đa dạng mẫu mã và phụ kiện. Sẵn sàng phục vụ nhu cầu sưu tầm của bạn.</p>
                    <a href="<?= UrlHelper::url('Product') ?>" class="hero-cta">
                        Xem sản phẩm <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Section -->
<div class="container-fluid mt-5">
    <h2 class="section-title">Danh mục sản phẩm</h2>
    <div class="row">
        <?php foreach($categories as $category): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="<?= UrlHelper::url('Product?category_id='.$category->id) ?>" class="text-decoration-none">
                    <div class="category-card">
                        <div class="image-container">
                            <?php
                            $imagePath = 'uploads/categories/'.(!empty($category->image) ? $category->image : 'placeholder-category.jpg');
                            // Kiểm tra nếu file NMP thì sử dụng thư mục products
                            if (strpos($category->image, '68301e39bc8fa_818cXcaog9L.jpg') !== false) {
                                $imagePath = 'uploads/products/68301e39bc8fa_818cXcaog9L.jpg';
                            }
                            ?>
                            <img src="<?= UrlHelper::asset($imagePath) ?>" 
                                 alt="<?= htmlspecialchars($category->name) ?>"
                                 onerror="this.src='<?= UrlHelper::asset('uploads/placeholder-category.jpg') ?>'">
                        </div>
                        <div class="category-card-overlay">
                            <h3 class="category-card-title"><?= htmlspecialchars($category->name) ?></h3>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- "Xem Thêm Danh Mục" -->
<div class="container-fluid text-center mb-5">
    <a href="<?= UrlHelper::url('Product') ?>" class="btn btn-outline-primary">
        Xem Thêm Danh Mục <i class="fas fa-arrow-right ms-2"></i>
    </a>
</div>

<!-- Featured Products Section -->
<div class="container-fluid product-section">
    <div class="container-fluid">
        <h2 class="section-title text-center">Sản phẩm nổi bật</h2>
        <p class="text-center mb-4 text-muted">Khám phá những mô hình được yêu thích nhất tại cửa hàng</p>
        
        <div class="row">
            <?php if (empty($featuredProducts)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Hiện chưa có sản phẩm nào.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($featuredProducts as $product): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="product-card h-100">
                            <div class="product-image-container">
                                <img src="<?= UrlHelper::asset((!empty($product->image) ? $product->image : 'uploads/products/default.jpg')) ?>" 
                                     class="product-image" alt="<?= htmlspecialchars($product->name) ?>"
                                     onerror="this.src='<?= UrlHelper::asset('uploads/products/default.jpg') ?>'">
                                <?php if (isset($product->category_name)): ?>
                                    <div class="product-badge"><?= htmlspecialchars($product->category_name) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="product-title"><?= htmlspecialchars($product->name) ?></h5>
                                <div class="mt-auto pt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="product-price"><?= number_format($product->price, 0, ',', '.') ?> ₫</span>
                                        <a href="<?= UrlHelper::url('Product/show/'.$product->id) ?>" class="btn btn-view">
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= UrlHelper::url('Product') ?>" class="btn btn-lg btn-primary">
                <i class="fas fa-th-list me-2"></i> Xem tất cả sản phẩm
            </a>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="stats-section">
    <div class="container-fluid">
        <h2 class="section-title text-center text-white mb-5">Gundam Store bằng con số</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="stats-number">500+</div>
                    <div class="stats-text">Mô hình Gundam</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">1000+</div>
                    <div class="stats-text">Khách hàng hài lòng</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stats-number">63/63</div>
                    <div class="stats-text">Tỉnh thành giao hàng</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stats-number">4.9/5</div>
                    <div class="stats-text">Đánh giá trung bình</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Testimonials Section -->
<div class="testimonials-section">
    <div class="container">
        <h2 class="section-title text-center mb-5">Khách hàng nói gì về chúng tôi</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        Tôi rất hài lòng với chất lượng mô hình Gundam tại đây. Sản phẩm chính hãng, đóng gói cẩn thận và giao hàng nhanh chóng. Nhân viên tư vấn rất nhiệt tình và am hiểu sản phẩm.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Nguyễn Văn A" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h5>Nguyễn Văn A</h5>
                            <span>Khách hàng thường xuyên</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        Shop có đa dạng các loại mô hình từ HG, RG đến PG, giá cả hợp lý hơn so với thị trường. Đặc biệt là dịch vụ bảo hành và hỗ trợ sau bán hàng rất tốt. Sẽ ủng hộ shop dài lâu!
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Trần Thị B" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h5>Trần Thị B</h5>
                            <span>Nhà sưu tập Gundam</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        Lần đầu mua Gundam tại shop và cực kỳ ấn tượng. Đặt hàng online rất dễ dàng, thanh toán tiện lợi và nhận được hàng chỉ sau 2 ngày. Mô hình đẹp và chính xác như mô tả trên website.
                    </div>
                    <div class="testimonial-author">
                        <img src="https://randomuser.me/api/portraits/men/62.jpg" alt="Lê Văn C" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h5>Lê Văn C</h5>
                            <span>Khách hàng mới</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <h2 class="mb-4">Khám phá thế giới Gundam ngay hôm nay</h2>
            <p class="lead mb-4">Sưu tầm ngay những mô hình Gundam chất lượng cao với giá cả hợp lý</p>
            <a href="<?= UrlHelper::url('Product') ?>" class="btn btn-lg btn-primary">Mua sắm ngay</a>
        </div>
    </div>
</div>

<?php include_once 'app/views/layout/footer.php'; ?> 