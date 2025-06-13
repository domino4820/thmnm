<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');

class ProductApiController
{
    private $productModel;
    private $db;
    private $requestMethod;
    private $productId;

    public function __construct($requestMethod, $productId = null)
    {
        $this->requestMethod = $requestMethod;
        $this->productId = $productId;
        
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->productId) {
                    $response = $this->getProduct($this->productId);
                } else {
                    $response = $this->getAllProducts();
                }
                break;
            case 'POST':
                $response = $this->createProduct();
                break;
            case 'PUT':
                $response = $this->updateProduct($this->productId);
                break;
            case 'DELETE':
                $response = $this->deleteProduct($this->productId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllProducts()
    {
        // Get query parameters
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $categoryId = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        
        // Get products
        $result = $this->productModel->getProducts($limit, $offset, $categoryId, $search);
        
        // Get total count for pagination
        $totalCount = $this->productModel->countProducts($categoryId, $search);
        
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'data' => $result,
            'metadata' => [
                'total' => $totalCount,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($result)
            ]
        ]);
        
        return $response;
    }

    private function getProduct($id)
    {
        $result = $this->productModel->getProductById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createProduct()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!$this->validateProductInput($input)) {
            return $this->unprocessableEntityResponse();
        }
        
        try {
            // Xử lý đường dẫn ảnh
            $imageToSave = '';
            
            if (isset($input['image']) && !empty($input['image'])) {
                $inputImage = $input['image'];
                
                // Nếu ảnh không có đường dẫn uploads/products/, thêm vào
                if (strpos($inputImage, 'uploads/products/') === false) {
                    $imageToSave = 'uploads/products/' . $inputImage;
                } else {
                    $imageToSave = $inputImage;
                }
            }
            
            $id = $this->productModel->addProduct(
                $input['name'],
                $input['description'],
                $input['price'],
                $input['category_id'],
                $imageToSave
            );
            
            // Lấy thông tin sản phẩm vừa tạo
            $newProduct = $this->productModel->getProductById($id);
            
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Sản phẩm đã được tạo thành công',
                'id' => $id,
                'product' => [
                    'id' => $id,
                    'name' => $input['name'],
                    'description' => $input['description'],
                    'price' => $input['price'],
                    'category_id' => $input['category_id'],
                    'image' => $imageToSave,
                    'image_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/public/" . $imageToSave
                ]
            ]);
            
        } catch (Exception $e) {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
        return $response;
    }

    private function updateProduct($id)
    {
        $result = $this->productModel->getProductById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!$this->validateProductInput($input)) {
            return $this->unprocessableEntityResponse();
        }
        
        try {
            // Xử lý đường dẫn ảnh
            $imageToUpdate = '';
            
            if (isset($input['image']) && !empty($input['image'])) {
                // Nếu ảnh mới được gửi lên
                $inputImage = $input['image'];
                
                // Nếu ảnh không có đường dẫn uploads/products/, thêm vào
                if (strpos($inputImage, 'uploads/products/') === false) {
                    $imageToUpdate = 'uploads/products/' . $inputImage;
                } else {
                    $imageToUpdate = $inputImage;
                }
            } else {
                // Giữ ảnh cũ nếu không có ảnh mới
                $imageToUpdate = $result->image;
            }
            
            $result = $this->productModel->updateProduct(
                $id,
                $input['name'],
                $input['description'],
                $input['price'],
                $input['category_id'],
                $imageToUpdate
            );
            
            // Lấy thông tin sản phẩm mới nhất sau khi cập nhật
            $updatedProduct = $this->productModel->getProductById($id);
            
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Sản phẩm đã được cập nhật thành công',
                'product' => [
                    'id' => $id,
                    'name' => $input['name'],
                    'description' => $input['description'],
                    'price' => $input['price'],
                    'category_id' => $input['category_id'],
                    'image' => $imageToUpdate,
                    'image_url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/public/" . $imageToUpdate
                ]
            ]);
            
        } catch (Exception $e) {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
        return $response;
    }

    private function deleteProduct($id)
    {
        $result = $this->productModel->getProductById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        
        try {
            $result = $this->productModel->deleteProduct($id);
            
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Sản phẩm đã được xóa thành công'
            ]);
            
        } catch (Exception $e) {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        
        return $response;
    }

    private function validateProductInput($input)
    {
        if (!isset($input['name']) || empty($input['name']) || 
            !isset($input['description']) || empty($input['description']) ||
            !isset($input['price']) || !is_numeric($input['price']) ||
            !isset($input['category_id']) || !is_numeric($input['category_id'])) {
            return false;
        }
        
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'status' => 'error',
            'message' => 'Dữ liệu không hợp lệ'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'status' => 'error',
            'message' => 'Sản phẩm không tồn tại'
        ]);
        return $response;
    }
}
