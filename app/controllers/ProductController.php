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
                case 'cart':
                    $this->cart();
                    break;
                case 'addToCart':
                    $this->addToCart();
                    break;
                case 'checkout':
                    $this->checkout();
                    break;
                case 'processCheckout':
                    $this->processCheckout();
                    break;
                case 'buyNow':
                    $this->buyNow();
                    break;
                case 'removeFromCart':
                    $this->removeFromCart();
                    break;
                case 'updateCart':
                    $this->updateCart();
                    break;
                case 'orderConfirmation':
                    $this->orderConfirmation($params[0] ?? null);
                    break;
                case 'searchOrders':
                    $this->searchOrders();
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
            // Get search parameters
            $search = isset($_GET['search']) ? $_GET['search'] : null;
            $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = 12; // Items per page
            $offset = ($page - 1) * $limit;
            
            // Get products with filters
            $products = $this->productModel->getProducts($limit, $offset, $category_id, $search);
            
            // Get total products count for pagination
            $totalProducts = $this->productModel->countProducts($category_id, $search);
            $totalPages = ceil($totalProducts / $limit);
            
            // Get all categories for filter dropdown
            $categories = $this->categoryModel->getCategories();
            
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

    // Hiển thị giỏ hàng
    public function cart() 
    {
        try {
            // Khởi tạo giỏ hàng nếu chưa tồn tại
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            
            // Tính tổng tiền
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            $pageTitle = 'Giỏ hàng';
            include 'app/views/product/cart.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi hiển thị giỏ hàng: ' . $e->getMessage());
        }
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            $product_id = $_POST['product_id'] ?? null;
            $quantity = intval($_POST['quantity'] ?? 1);
            
            if (!$product_id) {
                throw new Exception('ID sản phẩm không hợp lệ');
            }
            
            if ($quantity <= 0) {
                $quantity = 1;
            }
            
            // Lấy thông tin sản phẩm từ database
            $product = $this->productModel->getProductById($product_id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm');
            }
            
            // Khởi tạo giỏ hàng nếu chưa tồn tại
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['product_id'] == $product_id) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }
            
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
            if (!$found) {
                $_SESSION['cart'][] = [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity
                ];
            }
            
            $_SESSION['success'] = 'Đã thêm sản phẩm vào giỏ hàng';
            
            // Redirect về trang sản phẩm hoặc giỏ hàng
            header('Location: /Product/cart');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi thêm vào giỏ hàng: ' . $e->getMessage());
        }
    }

    // Hiển thị trang thanh toán
    public function checkout() 
    {
        try {
            // Kiểm tra giỏ hàng có trống không
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION['error'] = 'Giỏ hàng trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.';
                header('Location: /Product');
                exit();
            }
            
            $cart = $_SESSION['cart'];
            $totalAmount = 0;
            
            // Tính tổng tiền
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }
            
            $pageTitle = 'Thanh toán';
            include 'app/views/product/checkout.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi hiển thị trang thanh toán: ' . $e->getMessage());
        }
    }

    // Xử lý thanh toán
    public function processCheckout() 
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            // Kiểm tra giỏ hàng có trống không
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception('Giỏ hàng trống');
            }
            
            // Lấy thông tin khách hàng từ form
            $customer_name = $_POST['customer_name'] ?? '';
            $customer_email = $_POST['customer_email'] ?? '';
            $customer_phone = $_POST['customer_phone'] ?? '';
            $customer_address = $_POST['customer_address'] ?? '';
            
            // Validate thông tin khách hàng
            if (empty($customer_name) || empty($customer_phone) || empty($customer_address)) {
                throw new Exception('Vui lòng điền đầy đủ thông tin');
            }
            
            // Ghi log giỏ hàng để debug
            custom_error_log("Giỏ hàng trước khi thanh toán: " . json_encode($_SESSION['cart']));
            
            // Bắt đầu transaction
            $this->db->beginTransaction();
            
            try {
                // Tạo đơn hàng mới với thông tin khách hàng
                $stmt = $this->db->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, customer_address, order_date) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$customer_name, $customer_email, $customer_phone, $customer_address]);
                
                // Lấy ID đơn hàng vừa tạo
                $order_id = $this->db->lastInsertId();
                custom_error_log("Đơn hàng mới được tạo với ID: " . $order_id);
                
                // Lưu thông tin giỏ hàng trước khi thanh toán để hiển thị sau này
                $_SESSION['last_order_items'] = $_SESSION['cart'];
                $_SESSION['last_order_id'] = $order_id;
                
                // Thêm chi tiết đơn hàng với cả thông tin sản phẩm
                $stmt = $this->db->prepare("
                    INSERT INTO order_details 
                    (order_id, product_id, quantity, price, product_name, product_image) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                $totalAmount = 0;
                foreach ($_SESSION['cart'] as $item) {
                    // Log item details before insert
                    custom_error_log("Thêm vào order_details: order_id={$order_id}, product_id={$item['product_id']}, qty={$item['quantity']}, price={$item['price']}, name={$item['name']}, image=" . ($item['image'] ?? ''));
                    
                    $stmt->execute([
                        $order_id,
                        $item['product_id'],
                        $item['quantity'],
                        $item['price'],
                        $item['name'],
                        $item['image'] ?? ''
                    ]);
                    
                    $totalAmount += $item['price'] * $item['quantity'];
                }
                
                // Cập nhật tổng tiền cho đơn hàng
                $stmt = $this->db->prepare("UPDATE orders SET total_amount = ? WHERE id = ?");
                $stmt->execute([$totalAmount, $order_id]);
                
                // Commit transaction
                $this->db->commit();
                custom_error_log("Đơn hàng được xử lý thành công: " . $order_id);
                
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                
                // Chuyển hướng đến trang xác nhận đặt hàng thành công
                $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng của bạn là: ' . $order_id;
                header('Location: /Product/orderConfirmation/' . $order_id);
                exit();
                
            } catch (Exception $e) {
                // Rollback transaction nếu có lỗi
                $this->db->rollBack();
                custom_error_log("Lỗi xử lý đơn hàng (transaction rollback): " . $e->getMessage());
                throw $e;
            }
            
        } catch (Exception $e) {
            custom_error_log("Lỗi xử lý đơn hàng (outer catch): " . $e->getMessage());
            $this->showError('Lỗi khi xử lý đơn hàng: ' . $e->getMessage(), '/Product/checkout');
        }
    }

    // Mua ngay (đi thẳng đến trang thanh toán với một sản phẩm)
    public function buyNow()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            $product_id = $_POST['product_id'] ?? null;
            $quantity = intval($_POST['quantity'] ?? 1);
            
            if (!$product_id) {
                throw new Exception('ID sản phẩm không hợp lệ');
            }
            
            if ($quantity <= 0) {
                $quantity = 1;
            }
            
            // Lấy thông tin sản phẩm từ database
            $product = $this->productModel->getProductById($product_id);
            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm');
            }
            
            // Tạo giỏ hàng tạm thời chỉ với sản phẩm này
            $_SESSION['cart'] = [
                [
                    'product_id' => $product_id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => $quantity
                ]
            ];
            
            // Chuyển hướng đến trang thanh toán
            header('Location: /Product/checkout');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi xử lý mua ngay: ' . $e->getMessage());
        }
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeFromCart()
    {
        try {
            $product_id = $_GET['product_id'] ?? null;
            
            if (!$product_id) {
                throw new Exception('ID sản phẩm không hợp lệ');
            }
            
            // Kiểm tra giỏ hàng có tồn tại không
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception('Giỏ hàng trống');
            }
            
            // Tìm và xóa sản phẩm khỏi giỏ hàng
            foreach ($_SESSION['cart'] as $index => $item) {
                if ($item['product_id'] == $product_id) {
                    unset($_SESSION['cart'][$index]);
                    // Sắp xếp lại mảng
                    $_SESSION['cart'] = array_values($_SESSION['cart']);
                    break;
                }
            }
            
            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
            header('Location: /Product/cart');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi xóa sản phẩm khỏi giỏ hàng: ' . $e->getMessage());
        }
    }

    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showError('Phương thức không hợp lệ');
        }

        try {
            // Kiểm tra giỏ hàng có tồn tại không
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                throw new Exception('Giỏ hàng trống');
            }
            
            // Lấy dữ liệu từ form
            $quantities = $_POST['quantity'] ?? [];
            
            if (empty($quantities)) {
                throw new Exception('Không có dữ liệu cập nhật');
            }
            
            // Cập nhật số lượng cho từng sản phẩm
            foreach ($quantities as $product_id => $quantity) {
                $quantity = intval($quantity);
                if ($quantity <= 0) {
                    // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
                    foreach ($_SESSION['cart'] as $index => $item) {
                        if ($item['product_id'] == $product_id) {
                            unset($_SESSION['cart'][$index]);
                            break;
                        }
                    }
                } else {
                    // Cập nhật số lượng mới
                    foreach ($_SESSION['cart'] as &$item) {
                        if ($item['product_id'] == $product_id) {
                            $item['quantity'] = $quantity;
                            break;
                        }
                    }
                }
            }
            
            // Sắp xếp lại mảng giỏ hàng
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            
            $_SESSION['success'] = 'Đã cập nhật giỏ hàng';
            header('Location: /Product/cart');
            exit();
        } catch (Exception $e) {
            $this->showError('Lỗi khi cập nhật giỏ hàng: ' . $e->getMessage());
        }
    }

    // Hiển thị trang xác nhận đơn hàng
    public function orderConfirmation($order_id = null) 
    {
        try {
            if (!$order_id) {
                throw new Exception('Không tìm thấy đơn hàng');
            }
            
            // Kiểm tra xem có phải là đơn hàng vừa tạo không
            if (isset($_SESSION['last_order_id']) && $_SESSION['last_order_id'] == $order_id && isset($_SESSION['last_order_items'])) {
                // Sử dụng thông tin từ session
                $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
                $stmt->execute([$order_id]);
                $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$orderInfo) {
                    throw new Exception('Không tìm thấy đơn hàng');
                }
                
                $order = [
                    'id' => $orderInfo['id'],
                    'customer_name' => $orderInfo['customer_name'],
                    'customer_email' => $orderInfo['customer_email'],
                    'customer_phone' => $orderInfo['customer_phone'],
                    'customer_address' => $orderInfo['customer_address'],
                    'order_date' => $orderInfo['order_date'],
                    'total_amount' => $orderInfo['total_amount'],
                    'items' => array_map(function($item) {
                        return [
                            'product_name' => $item['name'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'image' => $item['image'] ?? ''
                        ];
                    }, $_SESSION['last_order_items'])
                ];
                
                // Xóa thông tin đơn hàng tạm thời từ session
                unset($_SESSION['last_order_items']);
                unset($_SESSION['last_order_id']);
            } else {
                // Lấy thông tin đơn hàng từ database bao gồm thông tin sản phẩm từ order_details
                $stmt = $this->db->prepare("
                    SELECT o.*, od.product_id, od.quantity, od.price, od.product_name, od.product_image
                    FROM orders o
                    JOIN order_details od ON o.id = od.order_id
                    WHERE o.id = ?
                ");
                $stmt->execute([$order_id]);
                $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($orderDetails)) {
                    throw new Exception('Không tìm thấy đơn hàng');
                }
                
                // Gom nhóm thông tin
                $order = [
                    'id' => $orderDetails[0]['id'],
                    'customer_name' => $orderDetails[0]['customer_name'],
                    'customer_email' => $orderDetails[0]['customer_email'],
                    'customer_phone' => $orderDetails[0]['customer_phone'],
                    'customer_address' => $orderDetails[0]['customer_address'],
                    'order_date' => $orderDetails[0]['order_date'],
                    'total_amount' => $orderDetails[0]['total_amount'],
                    'items' => array_map(function($detail) {
                        return [
                            'product_name' => $detail['product_name'] ?: 'Sản phẩm #' . $detail['product_id'],
                            'quantity' => $detail['quantity'],
                            'price' => $detail['price'],
                            'image' => $detail['product_image'] ?: ''
                        ];
                    }, $orderDetails)
                ];
            }
            
            $pageTitle = 'Xác nhận đơn hàng';
            include 'app/views/product/orderConfirmation.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi hiển thị trang xác nhận đơn hàng: ' . $e->getMessage());
        }
    }

    // Search for orders
    public function searchOrders()
    {
        try {
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
            $orders = [];
            
            if (!empty($searchTerm)) {
                $orders = $this->productModel->searchOrders($searchTerm);
            }
            
            $pageTitle = 'Tìm kiếm đơn hàng';
            include 'app/views/product/search_orders.php';
        } catch (Exception $e) {
            $this->showError('Lỗi khi tìm kiếm đơn hàng: ' . $e->getMessage());
        }
    }
}
?>