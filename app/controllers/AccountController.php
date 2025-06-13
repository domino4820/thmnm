<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/helpers/SessionHelper.php');

class AccountController {
    private $accountModel;
    private $db;
    
    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }
    
    // Main entry point for routing
    public function handleRequest($action, $params = []) {
        try {
            switch ($action) {
                case 'register':
                    $this->register();
                    break;
                case 'login':
                    $this->login();
                    break;
                case 'save':
                    $this->save();
                    break;
                case 'logout':
                    $this->logout();
                    break;
                case 'checkLogin':
                    $this->checkLogin();
                    break;
                case 'listusers':
                    $this->listusers();
                    break;
                case 'createuser':
                    $this->createuser();
                    break;
                case 'edituser':
                    $this->edituser($params[0] ?? null);
                    break;
                case 'updateuser':
                    $this->updateuser();
                    break;
                case 'deleteuser':
                    $this->deleteuser($params[0] ?? null);
                    break;
                case 'profile':
                    $this->profile();
                    break;
                case 'updateprofile':
                    $this->updateprofile();
                    break;
                default:
                    $this->login();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . UrlHelper::url('account/login'));
            exit;
        }
    }
    
    public function register() {
        include_once 'app/views/account/register.php';
    }
    
    public function login() {
        include_once 'app/views/account/login.php';
    }
    
    public function createuser() {
        // Require admin permissions
        SessionHelper::requireAdmin();
        
        $roles = ['admin', 'employee', 'user'];
        include_once 'app/views/account/create.php';
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $email = $_POST['email'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            
            // Handle address fields
            $address_parts = array();
            if (!empty($_POST['dia_chi'])) $address_parts[] = $_POST['dia_chi'];
            if (!empty($_POST['phuong_xa'])) $address_parts[] = $_POST['phuong_xa'];
            if (!empty($_POST['quan_huyen'])) $address_parts[] = $_POST['quan_huyen'];
            if (!empty($_POST['tinh_thanh'])) $address_parts[] = $_POST['tinh_thanh'];
            
            $dia_chi_full = implode(', ', $address_parts);
            
            $errors = [];
            
            if (empty($username)) $errors['username'] = "Vui lòng nhập username!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập fullname!";
            if (empty($password)) $errors['password'] = "Vui lòng nhập password!";
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            if (empty($email)) $errors['email'] = "Vui lòng nhập email!";
            if (empty($sdt)) $errors['sdt'] = "Vui lòng nhập số điện thoại!";
            
            // Email validation
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }
            
            // Phone number validation (basic)
            if ($sdt && !preg_match("/^[0-9]{10,11}$/", $sdt)) {
                $errors['sdt'] = "Số điện thoại không hợp lệ!";
            }
            
            // Validate role - only admin can set role to admin or employee
            if (SessionHelper::isLoggedIn() && !SessionHelper::isAdmin()) {
                $role = 'user'; // Force regular users to register other users as 'user'
            }
            
            if (!in_array($role, ['admin', 'employee', 'user'])) {
                $role = 'user'; // Default to user if invalid role
            }
            
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }
            
            // Handle avatar upload
            $avatar_path = '';
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($avatar['type'], $allowed_types)) {
                    $errors['avatar'] = "Chỉ chấp nhận file ảnh JPG, PNG!";
                } elseif ($avatar['size'] > $max_size) {
                    $errors['avatar'] = "Kích thước file không được vượt quá 2MB!";
                } else {
                    $filename = time() . '_' . $avatar['name'];
                    $upload_dir = 'public/uploads/avatars/';
                    
                    // Create directory if not exists
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $upload_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($avatar['tmp_name'], $upload_path)) {
                        $avatar_path = $upload_path;
                    } else {
                        $errors['avatar'] = "Có lỗi khi tải file lên!";
                    }
                }
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $result = $this->accountModel->saveWithDetails(
                    $username, 
                    $fullName, 
                    $password, 
                    $role, 
                    $email, 
                    $sdt, 
                    $dia_chi_full, 
                    $avatar_path
                );
                
                if ($result) {
                    $_SESSION['success'] = "Tài khoản đã được tạo thành công!";
                    
                    // Redirect based on origin
                    if (SessionHelper::isAdmin() && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'createuser') !== false) {
                        header('Location: ' . UrlHelper::url('account/listusers'));
                    } else {
                        header('Location: ' . UrlHelper::url('account/login'));
                    }
                    exit;
                } else {
                    $errors['general'] = "Có lỗi xảy ra khi đăng ký tài khoản.";
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }
    
    public function logout() {
        SessionHelper::start();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        unset($_SESSION['user_id']);
        
        $_SESSION['success'] = "Bạn đã đăng xuất thành công!";
        header('Location: ' . UrlHelper::url(''));
        exit;
    }
    
    public function checkLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $account = $this->accountModel->getAccountByUsername($username);
            
            if ($account && password_verify($password, $account->password)) {
                SessionHelper::start();
                $_SESSION['username'] = $account->username;
                $_SESSION['role'] = $account->role;
                $_SESSION['user_id'] = $account->id;
                
                $_SESSION['success'] = "Đăng nhập thành công!";
                header('Location: ' . UrlHelper::url(''));
                exit;
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }
    
    // ===== USER MANAGEMENT FEATURES (ADMIN ONLY) =====
    
    public function listusers() {
        // Require admin permissions
        SessionHelper::requireAdmin();
        
        $users = $this->accountModel->getAllUsers();
        include_once 'app/views/account/list.php';
    }
    
    public function edituser($id = null) {
        // Require admin permissions
        SessionHelper::requireAdmin();
        
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (!$id) {
            $_SESSION['error'] = "ID người dùng không hợp lệ!";
            header('Location: ' . UrlHelper::url('account/listusers'));
            exit;
        }
        
        $user = $this->accountModel->getUserById($id);
        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy người dùng!";
            header('Location: ' . UrlHelper::url('account/listusers'));
            exit;
        }
        
        $roles = ['admin', 'employee', 'user'];
        include_once 'app/views/account/edit.php';
    }
    
    public function updateuser() {
        // Require admin permissions
        SessionHelper::requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $role = $_POST['role'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = $_POST['email'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            
            // Handle address fields
            $address_parts = array();
            if (!empty($_POST['dia_chi'])) $address_parts[] = $_POST['dia_chi'];
            if (!empty($_POST['phuong_xa'])) $address_parts[] = $_POST['phuong_xa'];
            if (!empty($_POST['quan_huyen'])) $address_parts[] = $_POST['quan_huyen'];
            if (!empty($_POST['tinh_thanh'])) $address_parts[] = $_POST['tinh_thanh'];
            
            $dia_chi_full = implode(', ', $address_parts);
            
            $errors = [];
            
            if (empty($id)) $errors['id'] = "ID không hợp lệ!";
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ tên!";
            if (!in_array($role, ['admin', 'employee', 'user'])) $errors['role'] = "Vai trò không hợp lệ!";
            
            if ($password && strlen($password) < 6) {
                $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự!";
            }
            
            // Email validation
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }
            
            // Phone number validation (basic)
            if ($sdt && !preg_match("/^[0-9]{10,11}$/", $sdt)) {
                $errors['sdt'] = "Số điện thoại không hợp lệ!";
            }
            
            // Handle avatar upload
            $avatar_path = '';
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($avatar['type'], $allowed_types)) {
                    $errors['avatar'] = "Chỉ chấp nhận file ảnh JPG, PNG!";
                } elseif ($avatar['size'] > $max_size) {
                    $errors['avatar'] = "Kích thước file không được vượt quá 2MB!";
                } else {
                    $filename = time() . '_' . $avatar['name'];
                    $upload_dir = 'public/uploads/avatars/';
                    
                    // Create directory if not exists
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $upload_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($avatar['tmp_name'], $upload_path)) {
                        $avatar_path = $upload_path;
                    } else {
                        $errors['avatar'] = "Có lỗi khi tải file lên!";
                    }
                }
            }
            
            if (count($errors) > 0) {
                $user = $this->accountModel->getUserById($id);
                $roles = ['admin', 'employee', 'user'];
                include_once 'app/views/account/edit.php';
            } else {
                $result = $this->accountModel->updateUserWithDetails(
                    $id, 
                    $fullName, 
                    $role, 
                    $email, 
                    $sdt, 
                    $dia_chi_full, 
                    $avatar_path, 
                    $password
                );
                
                if ($result) {
                    $_SESSION['success'] = "Cập nhật người dùng thành công!";
                    header('Location: ' . UrlHelper::url('account/listusers'));
                    exit;
                } else {
                    $errors['general'] = "Có lỗi xảy ra khi cập nhật người dùng.";
                    $user = $this->accountModel->getUserById($id);
                    $roles = ['admin', 'employee', 'user'];
                    include_once 'app/views/account/edit.php';
                }
            }
        }
    }
    
    public function deleteuser($id = null) {
        // Require admin permissions
        SessionHelper::requireAdmin();
        
        if (!$id && isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        if (!$id) {
            $_SESSION['error'] = "ID người dùng không hợp lệ!";
            header('Location: ' . UrlHelper::url('account/listusers'));
            exit;
        }
        
        // Don't allow admins to delete themselves
        SessionHelper::start();
        $user = $this->accountModel->getUserById($id);
        
        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy người dùng!";
            header('Location: ' . UrlHelper::url('account/listusers'));
            exit;
        }
        
        if ($user->username === $_SESSION['username']) {
            $_SESSION['error'] = "Bạn không thể xóa tài khoản của chính mình!";
            header('Location: ' . UrlHelper::url('account/listusers'));
            exit;
        }
        
        $result = $this->accountModel->deleteUser($id);
        if ($result) {
            $_SESSION['success'] = "Xóa người dùng thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa người dùng.";
        }
        
        header('Location: ' . UrlHelper::url('account/listusers'));
        exit;
    }
    
    // Method to handle user profile
    public function profile() {
        SessionHelper::requireLogin();
        
        $username = SessionHelper::getUsername();
        $account = $this->accountModel->getAccountByUsername($username);
        
        include_once 'app/views/account/profile.php';
    }
    
    // Method to update the user's own profile
    public function updateprofile() {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullName = $_POST['fullname'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $email = $_POST['email'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $dia_chi = $_POST['dia_chi'] ?? '';
            
            // Handle address fields
            $address_parts = array();
            if (!empty($_POST['dia_chi'])) $address_parts[] = $_POST['dia_chi'];
            if (!empty($_POST['phuong_xa'])) $address_parts[] = $_POST['phuong_xa'];
            if (!empty($_POST['quan_huyen'])) $address_parts[] = $_POST['quan_huyen'];
            if (!empty($_POST['tinh_thanh'])) $address_parts[] = $_POST['tinh_thanh'];
            
            $dia_chi_full = implode(', ', $address_parts);
            
            $errors = [];
            $username = SessionHelper::getUsername();
            $account = $this->accountModel->getAccountByUsername($username);
            
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập họ tên!";
            
            // Email validation
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }
            
            // Phone number validation (basic)
            if ($sdt && !preg_match("/^[0-9]{10,11}$/", $sdt)) {
                $errors['sdt'] = "Số điện thoại không hợp lệ!";
            }
            
            // Only validate password if the user is trying to change it
            if (!empty($newPassword)) {
                if (!password_verify($currentPassword, $account->password)) {
                    $errors['current_password'] = "Mật khẩu hiện tại không đúng!";
                }
                
                if (strlen($newPassword) < 6) {
                    $errors['new_password'] = "Mật khẩu mới phải có ít nhất 6 ký tự!";
                }
                
                if ($newPassword !== $confirmPassword) {
                    $errors['confirm_password'] = "Mật khẩu mới và xác nhận không khớp!";
                }
            }
            
            // Handle avatar upload
            $avatar_path = '';
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($avatar['type'], $allowed_types)) {
                    $errors['avatar'] = "Chỉ chấp nhận file ảnh JPG, PNG!";
                } elseif ($avatar['size'] > $max_size) {
                    $errors['avatar'] = "Kích thước file không được vượt quá 2MB!";
                } else {
                    $filename = time() . '_' . $avatar['name'];
                    $upload_dir = 'public/uploads/avatars/';
                    
                    // Create directory if not exists
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $upload_path = $upload_dir . $filename;
                    
                    if (move_uploaded_file($avatar['tmp_name'], $upload_path)) {
                        $avatar_path = $upload_path;
                    } else {
                        $errors['avatar'] = "Có lỗi khi tải file lên!";
                    }
                }
            }
            
            if (count($errors) > 0) {
                include_once 'app/views/account/profile.php';
            } else {
                // Update user profile
                $password = !empty($newPassword) ? $newPassword : null;
                
                $result = $this->accountModel->updateUserWithDetails(
                    $account->id,
                    $fullName,
                    $account->role,
                    $email,
                    $sdt,
                    $dia_chi_full,
                    $avatar_path,
                    $password
                );
                
                if ($result) {
                    $_SESSION['success'] = "Cập nhật thông tin thành công!";
                    header('Location: ' . UrlHelper::url('account/profile'));
                    exit;
                } else {
                    $errors['general'] = "Có lỗi xảy ra khi cập nhật thông tin.";
                    include_once 'app/views/account/profile.php';
                }
            }
        }
    }
}
?>