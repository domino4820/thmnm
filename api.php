<?php
// api.php - Endpoint cho API

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Ghi log request để debug
$logFile = 'api_debug.log';
$logData = [
    'time' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'query' => $_GET,
];
file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND);

// Xử lý OPTIONS request (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

// Bật hiển thị lỗi khi phát triển
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Tự động load các class
spl_autoload_register(function($class) {
    $paths = [
        'app/controllers/' . $class . '.php',
        'app/models/' . $class . '.php',
        'app/config/' . $class . '.php',
        'app/helpers/' . $class . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load các file cần thiết
require_once 'app/config/database.php';

// Xử lý routing cho API
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?'); // Loại bỏ query string

// Log the original request URI for debugging
file_put_contents($logFile, "Original URI: $requestUri\n", FILE_APPEND);

// Loại bỏ dấu gạch chéo đầu tiên và đường dẫn đến api.php
$requestUri = ltrim($requestUri, '/');
$apiPosition = strpos($requestUri, 'api.php');
if ($apiPosition !== false) {
    $requestUri = substr($requestUri, $apiPosition + 8); // 8 là độ dài của "api.php/"
}

// Loại bỏ các dấu gạch chéo đầu tiên lần nữa
$requestUri = ltrim($requestUri, '/');

// Log the processed URI for debugging
file_put_contents($logFile, "Processed URI: $requestUri\n", FILE_APPEND);

// Tách các phần của URL
$parts = explode('/', $requestUri);

// Xác định resource (product hoặc category)
$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

// Log the parsed resource and ID
file_put_contents($logFile, "Resource: $resource, ID: " . ($id ?? 'null') . "\n", FILE_APPEND);

// Lấy phương thức HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

try {
    // Route đến controller phù hợp
    switch (strtolower($resource)) {
        case 'product':
        case 'products':
            $controller = new ProductApiController($requestMethod, $id);
            $controller->processRequest();
            break;
            
        case 'category':
        case 'categories':
            $controller = new CategoryApiController($requestMethod, $id);
            $controller->processRequest();
            break;
            
        default:
            // 404 Not Found
            header("HTTP/1.1 404 Not Found");
            echo json_encode([
                'status' => 'error', 
                'message' => 'API Endpoint không tồn tại',
                'resource' => $resource,
                'id' => $id,
                'request_uri' => $requestUri
            ]);
            break;
    }
} catch (Exception $e) {
    // Xử lý lỗi
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'resource' => $resource,
        'id' => $id
    ]);
    
    // Log error for debugging
    file_put_contents($logFile, "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n", FILE_APPEND);
}
?> 