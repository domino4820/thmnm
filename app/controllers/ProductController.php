<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

// Hàm ghi log tùy chỉnh
function custom_error_log($message) {
    $logFile = 'app/logs/product_errors.log';
    $timestamp = date('[Y-m-d H:i:s]');
    $logMessage = "$timestamp $message\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

class ProductController
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

    // Main entry point for routing
    public function handleRequest($action, $params = [])
    {
        try {
            switch ($action) {
                case 'index':
                    $this->index();
                    break;
                case 'show':
                    $this->show($params[0] ?? null);
                    break;
                case 'add':
                    $this->add();
                    break;
                case 'save':
                    $this->save();
                    break;
                case 'edit':
                    $this->edit($params[0] ?? null);
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete($params[0] ?? null);
                    break;
                default:
                    $this->showError('Không tìm thấy chức năng');
            }
        } catch (Exception $e) {
            $this->showError($e->getMessage());
        }
    }

    // Display error message
    private function showError($message, $redirectUrl = '/Product')
    {
        // Ghi log lỗi chi tiết
        custom_error_log("ProductController Error: $message");
        
        $_SESSION['error'] = $message;
        header("Location: $redirectUrl");
        exit();
    }

    // Validate input data
    private function validateProductInput($name, $description, $price, $category_id)
    {
        $errors = [];

        if (empty($name) || strlen($name) < 3) {
            $errors['name'] = 'Tên sản phẩm phải có ít nhất 3 ký tự';
        }

        if (empty($description) || strlen($description) < 10) {
            $errors['description'] = 'Mô tả phải có ít nhất 10 ký tự';
        }

        if (!is_numeric($price) || $price <= 0) {
            $errors['price'] = 'Giá sản phẩm phải là số dương';
        }

        if (empty($category_id)) {
            $errors['category_id'] = 'Vui lòng chọn danh mục';
        }

        return $errors;
    }

    public function index()
    {
        try {
            $products = $this->productModel->getProducts();
            $pageTitle = 'Danh sách sản phẩm';
            include 'app/views/product/list.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi tải danh sách sản phẩm: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        if (!$id) {
            $this->showError('ID sản phẩm không hợp lệ');
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $this->showError('Không tìm thấy sản phẩm');
        }

        $pageTitle = 'Chi tiết sản phẩm';
        include 'app/views/product/show.php';
    }

    public function add()
    {
        try {
            $categories = $this->categoryModel->getCategories();
            $pageTitle = 'Thêm sản phẩm mới';
            include 'app/views/product/add.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi tải danh mục: ' . $e->getMessage());
        }
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            // Ghi log toàn bộ dữ liệu POST
            custom_error_log("POST Data: " . json_encode($_POST));
            
            // Ghi log thông tin file upload
            if (isset($_FILES['image'])) {
                custom_error_log("Image Upload Info: " . json_encode($_FILES['image']));
            }

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = '';

            // Validate input
            $errors = $this->validateProductInput($name, $description, $price, $category_id);
            if (!empty($errors)) {
                // Ghi log lỗi validate
                custom_error_log("Product Validation Errors: " . json_encode($errors));
                
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
                header('Location: /Product/add');
                exit();
            }

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            }

            // Ghi log thông tin sản phẩm trước khi thêm
            custom_error_log("Adding Product - Name: $name, Description: $description, Price: $price, Category: $category_id, Image: $image");

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if ($result === false) {
                throw new Exception('Không thể thêm sản phẩm');
            }

            $_SESSION['success'] = 'Thêm sản phẩm thành công';
            header('Location: /Product');
            exit();
        } catch (Exception $e) {
            // Ghi log lỗi chi tiết
            custom_error_log("Error in save method: " . $e->getMessage());
            $this->showError('Lỗi khi lưu sản phẩm: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        if (!$id) {
            $this->showError('ID sản phẩm không hợp lệ');
        }

        try {
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                $this->showError('Không tìm thấy sản phẩm');
            }

            $categories = $this->categoryModel->getCategories();
            $pageTitle = 'Chỉnh sửa sản phẩm';
            include 'app/views/product/edit.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi tải thông tin sản phẩm: ' . $e->getMessage());
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = $_POST['existing_image'] ?? '';

            // Validate input
            $errors = $this->validateProductInput($name, $description, $price, $category_id);
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['form_data'] = $_POST;
                header("Location: /Product/edit/$id");
                exit();
            }

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            }

            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            
            if (!$edit) {
                throw new Exception('Không thể cập nhật sản phẩm');
            }

            $_SESSION['success'] = 'Cập nhật sản phẩm thành công';
            header('Location: /Product');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!$id) {
            $this->showError('ID sản phẩm không hợp lệ');
        }

        try {
            $delete = $this->productModel->deleteProduct($id);
            
            if (!$delete) {
                throw new Exception('Không thể xóa sản phẩm');
            }

            $_SESSION['success'] = 'Xóa sản phẩm thành công';
            header('Location: /Product');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }

    // Hàm upload ảnh
    private function uploadImage($file)
    {
        $uploadDir = 'public/uploads/products/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Tạo tên file duy nhất
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadPath = $uploadDir . $fileName;

        // Di chuyển file tải lên
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Trả về đường dẫn tương đối từ thư mục gốc
            custom_error_log("Image uploaded successfully: $uploadPath");
            return str_replace('public/', '', $uploadPath);
        }

        custom_error_log("Image upload failed for file: " . $file['name']);
        return '';
    }
}
?>