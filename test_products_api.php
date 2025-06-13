<?php
// test_products_api.php - File test API sản phẩm

require_once 'app/config/database.php';
require_once 'app/controllers/ProductApiController.php';

// Simulate API request
$controller = new ProductApiController('GET');
$controller->processRequest();
?> 