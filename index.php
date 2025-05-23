<?php
session_start(); // Start session for flash messages

// Tự động load các class
spl_autoload_register(function($class) {
    $paths = [
        'app/controllers/' . $class . '.php',
        'app/models/' . $class . '.php',
        'app/config/' . $class . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// Xử lý routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?'); // Loại bỏ query string

// Loại bỏ dấu gạch chéo đầu tiên
$requestUri = ltrim($requestUri, '/');

// Tách các phần của URL
$parts = explode('/', $requestUri);

// Mặc định là trang chủ
$controller = !empty($parts[0]) ? ucfirst($parts[0]) : 'Home';
$action = $parts[1] ?? 'index';
$params = array_slice($parts, 2);

try {
    // Kiểm tra và khởi tạo controller
    $controllerName = $controller . 'Controller';
    
    if (!class_exists($controllerName)) {
        // Nếu không tìm thấy controller, chuyển về trang chủ
        $controllerName = 'HomeController';
        $action = 'index';
    }

    $controllerInstance = new $controllerName();

    // Kiểm tra phương thức
    if (!method_exists($controllerInstance, 'handleRequest')) {
        throw new Exception('Phương thức không tồn tại');
    }

    // Gọi phương thức xử lý yêu cầu
    $controllerInstance->handleRequest($action, $params);

} catch (Exception $e) {
    // Xử lý lỗi
    error_log("Routing Error: " . $e->getMessage());
    
    // Chuyển hướng về trang chủ với thông báo lỗi
    $_SESSION['error'] = $e->getMessage();
    header('Location: /');
    exit();
}
?>