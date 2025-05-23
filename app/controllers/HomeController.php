<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');

class HomeController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
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
            // Lấy 6 sản phẩm mới nhất
            $featuredProducts = $this->productModel->getProducts(6);
            $pageTitle = 'Trang Chủ Gundam Store';
            
            include 'app/views/home/index.php';
        } catch (Exception $e) {
            // Xử lý lỗi
            $_SESSION['error'] = 'Lỗi khi tải trang chủ: ' . $e->getMessage();
            header('Location: /');
            exit();
        }
    }
}
?> 