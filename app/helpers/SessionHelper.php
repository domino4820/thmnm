<?php
class SessionHelper {
// Khởi động session nếu chưa bắt đầu
public static function start() {
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
}
// Kiểm tra người dùng đã đăng nhập chưa
public static function isLoggedIn() {
self::start();
return isset($_SESSION['username']);
}
// Kiểm tra người dùng có phải admin không
public static function isAdmin() {
self::start();
return isset($_SESSION['username']) && isset($_SESSION['role']) &&
$_SESSION['role'] === 'admin';
}
// Kiểm tra người dùng có phải employee không
public static function isEmployee() {
self::start();
return isset($_SESSION['username']) && isset($_SESSION['role']) &&
$_SESSION['role'] === 'employee';
}
// Kiểm tra người dùng có phải user không
public static function isUser() {
self::start();
return isset($_SESSION['username']) && isset($_SESSION['role']) &&
$_SESSION['role'] === 'user';
}
// Kiểm tra xem người dùng có quyền quản lý sản phẩm không (admin và employee)
public static function canManageProducts() {
return self::isAdmin() || self::isEmployee();
}
// Kiểm tra xem người dùng có quyền quản lý người dùng không (chỉ admin)
public static function canManageUsers() {
return self::isAdmin();
}
// Lấy vai trò của người dùng, mặc định là 'guest'
public static function getRole() {
self::start();
return $_SESSION['role'] ?? null;
}
// Lấy username của người dùng đang đăng nhập
public static function getUsername() {
self::start();
return $_SESSION['username'] ?? null;
}
// Lấy user ID của người dùng hiện tại
public static function getUserId() {
self::start();
return $_SESSION['user_id'] ?? null;
}

// 获取当前用户的头像
public static function getUserAvatar() {
    self::start();
    if (!self::isLoggedIn()) {
        return null;
    }
    
    $userId = self::getUserId();
    if (!$userId) {
        return null;
    }
    
    try {
        require_once 'app/config/database.php';
        $conn = (new Database())->getConnection();
        $stmt = $conn->prepare("SELECT avatar FROM account WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    } catch (Exception $e) {
        error_log("Error fetching user avatar: " . $e->getMessage());
        return null;
    }
}
// Kiểm tra quyền truy cập và chuyển hướng nếu không có quyền
public static function requireLogin() {
self::start();
if (!self::isLoggedIn()) {
$_SESSION['error'] = "Bạn cần đăng nhập để truy cập trang này!";

// Sử dụng UrlHelper nếu có
if (class_exists('UrlHelper')) {
header('Location: ' . UrlHelper::url('account/login'));
} else {
header('Location: /account/login');
}
exit;
}
}
// Yêu cầu quyền admin để truy cập
public static function requireAdmin() {
self::requireLogin();
if (!self::isAdmin()) {
$_SESSION['error'] = "Bạn không có quyền truy cập trang này!";

// Sử dụng UrlHelper nếu có
if (class_exists('UrlHelper')) {
header('Location: ' . UrlHelper::url(''));
} else {
header('Location: /');
}
exit;
}
}
// Yêu cầu quyền quản lý sản phẩm (admin hoặc employee)
public static function requireProductManager() {
self::requireLogin();
if (!self::canManageProducts()) {
$_SESSION['error'] = "Bạn không có quyền quản lý sản phẩm!";

// Sử dụng UrlHelper nếu có
if (class_exists('UrlHelper')) {
header('Location: ' . UrlHelper::url(''));
} else {
header('Location: /');
}
exit;
}
}
// Thiết lập thông báo flash
public static function setFlash($type, $message) {
self::start();
$_SESSION[$type] = $message;
}
// Lấy và xóa thông báo flash
public static function getFlash($type) {
self::start();
$message = $_SESSION[$type] ?? null;
unset($_SESSION[$type]);
return $message;
}
}
?>