<?php
session_start(); // Start session for flash messages

// Bật hiển thị lỗi phát triển
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
            break;
        }
    }
});

// Load UrlHelper trước tiên để có thể sử dụng trong toàn bộ ứng dụng
require_once 'app/helpers/UrlHelper.php';

// Xử lý routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestUri = strtok($requestUri, '?'); // Loại bỏ query string

// Loại bỏ dấu gạch chéo đầu tiên
$requestUri = ltrim($requestUri, '/');

// Xác định base path
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);
$baseDir = '';

// Nếu script không nằm ở thư mục gốc
if ($scriptDir != '/' && $scriptDir != '\\') {
    // Đảm bảo scriptDir có dấu gạch chéo ở đầu nhưng không có ở cuối
    $scriptDir = '/' . trim($scriptDir, '/');
    
    // Nếu requestUri bắt đầu bằng scriptDir, loại bỏ scriptDir
    if (strpos($requestUri, ltrim($scriptDir, '/')) === 0) {
        $baseDir = ltrim($scriptDir, '/');
        $requestUri = substr($requestUri, strlen($baseDir));
    }
}

// Ghi log để debug
error_log("Request URI: " . $requestUri);
error_log("Script Name: " . $scriptName);
error_log("Script Dir: " . $scriptDir);
error_log("Base Dir: " . $baseDir);

// Loại bỏ các dấu gạch chéo đầu tiên lần nữa sau khi xử lý baseDir
$requestUri = ltrim($requestUri, '/');

// Tách các phần của URL
$parts = explode('/', $requestUri);

// Mặc định là trang chủ
$controller = !empty($parts[0]) ? ucfirst(strtolower($parts[0])) : 'Home';
$action = $parts[1] ?? 'index';

// 确保action的第一个字符小写，以匹配驼峰式命名规则 (camelCase)
if (!empty($action)) {
    $action = lcfirst($action);
}

$params = array_slice($parts, 2);

// Debug
error_log("Controller: $controller, Action: $action");

try {
    // Kiểm tra và khởi tạo controller
    $controllerName = $controller . 'Controller';
    
    if (!class_exists($controllerName)) {
        // Nếu không tìm thấy controller, chuyển về trang chủ
        error_log("Controller not found: $controllerName");
        $controllerName = 'HomeController';
        $action = 'index';
    }

    $controllerInstance = new $controllerName();

    // Kiểm tra phương thức
    if (!method_exists($controllerInstance, 'handleRequest')) {
        throw new Exception("Phương thức 'handleRequest' không tồn tại trong $controllerName");
    }

    // Gọi phương thức xử lý yêu cầu
    $controllerInstance->handleRequest($action, $params);

} catch (Exception $e) {
    // Xử lý lỗi
    error_log("Routing Error: " . $e->getMessage());
    
    // Hiển thị trang lỗi với thông tin chi tiết
    echo "<div style='margin: 100px auto; max-width: 800px; padding: 20px; background: #f8d7da; border-radius: 5px; border: 1px solid #f5c6cb; color: #721c24;'>";
    echo "<h1>Lỗi Ứng Dụng</h1>";
    echo "<p><strong>Chi tiết:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . " (dòng " . $e->getLine() . ")</p>";
    
    // Sử dụng UrlHelper để tạo liên kết về trang chủ nếu có
    if (class_exists('UrlHelper')) {
        echo "<p><a href='" . UrlHelper::url('') . "' style='color: #721c24; font-weight: bold;'>Quay lại trang chủ</a></p>";
    } else {
        echo "<p><a href='/' style='color: #721c24; font-weight: bold;'>Quay lại trang chủ</a></p>";
    }
    
    echo "</div>";
    exit();
}
?>