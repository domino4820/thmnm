<?php
class ApiFrontendController
{
    public function __construct()
    {
        // Không cần kết nối database vì chúng ta sử dụng API
    }

    // New method to handle routing
    public function handleRequest($action, $params = [])
    {
        // Check if session is not already active before starting
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'products':
                $this->products();
                break;
            case 'categories':
                $this->categories();
                break;
            default:
                // If no matching action is found
                $this->index();
                break;
        }
    }

    // Index page (API Documentation)
    public function index()
    {
        include 'app/views/api_frontend/index.php';
    }

    // Products management page
    public function products()
    {
        include 'app/views/api_frontend/products.php';
    }

    // Categories management page
    public function categories()
    {
        include 'app/views/api_frontend/categories.php';
    }
} 