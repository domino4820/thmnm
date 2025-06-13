<?php
// Require necessary files
require_once('app/config/database.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/helpers/UrlHelper.php');

class ApiManagementController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    // New method to handle routing
    public function handleRequest($action, $params = [])
    {
        // Check if session is not already active before starting
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra quyền quản lý sản phẩm
        try {
            SessionHelper::requireProductManager();
        } catch (Exception $e) {
            $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này";
            header('Location: ' . UrlHelper::url(''));
            exit();
        }

        switch ($action) {
            case 'index':
                $this->productsIndex();
                break;
            case 'products':
                $this->productsIndex();
                break;
            case 'categories':
                $this->categoriesIndex();
                break;
            default:
                // If no matching action is found
                $_SESSION['error'] = "Chức năng không tồn tại";
                header('Location: ' . UrlHelper::url(''));
                exit();
        }
    }

    // Products API management page
    public function productsIndex()
    {
        include 'app/views/api_management/products/index.php';
    }

    // Categories API management page
    public function categoriesIndex()
    {
        include 'app/views/api_management/categories/index.php';
    }
} 