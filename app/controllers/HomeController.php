<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class HomeController
{
    private $productModel;
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function handleRequest($action, $params = [])
    {
        switch ($action) {
            case 'index':
                $this->index();
                break;
            default:
                $this->index();
        }
    }

    public function index()
    {
        try {
            // Lấy danh sách danh mục
            $categories = $this->categoryModel->getCategories();
            
            // Lấy 6 sản phẩm nổi bật
            $featuredProducts = $this->productModel->getFeaturedProducts(6);
            
            // Debug thông tin
            error_log('Categories: ' . count($categories));
            error_log('Featured Products: ' . count($featuredProducts));
            
            if (empty($featuredProducts)) {
                error_log('No featured products found, getting regular products');
                $featuredProducts = $this->productModel->getProducts(6);
            }
            
            $pageTitle = 'Trang Chủ Gundam Store';
            
            include 'app/views/home/index.php';
        } catch (Exception $e) {
            // Ghi log lỗi
            error_log('Error in HomeController::index: ' . $e->getMessage());
            
            // Xử lý lỗi
            $_SESSION['error'] = 'Lỗi khi tải trang chủ: ' . $e->getMessage();
            include 'app/views/errors/error.php';
            exit();
        }
    }
}
?> 