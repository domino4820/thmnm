<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $requestMethod;
    private $categoryId;

    public function __construct($requestMethod, $categoryId = null)
    {
        $this->requestMethod = $requestMethod;
        $this->categoryId = $categoryId;
        
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->categoryId) {
                    $response = $this->getCategory($this->categoryId);
                } else {
                    $response = $this->getAllCategories();
                }
                break;
            case 'POST':
                $response = $this->createCategory();
                break;
            case 'PUT':
                $response = $this->updateCategory($this->categoryId);
                break;
            case 'DELETE':
                $response = $this->deleteCategory($this->categoryId);
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

    private function getAllCategories()
    {
        $result = $this->categoryModel->getCategories();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getCategory($id)
    {
        $result = $this->categoryModel->getCategoryById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createCategory()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!$this->validateCategoryInput($input)) {
            return $this->unprocessableEntityResponse();
        }
        
        $name = $input['name'];
        $description = $input['description'] ?? '';
        
        $id = $this->categoryModel->createCategory($name, $description);
        if ($id) {
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Danh mục đã được tạo thành công',
                'id' => $id
            ]);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => 'Không thể tạo danh mục'
            ]);
        }
        
        return $response;
    }

    private function updateCategory($id)
    {
        $result = $this->categoryModel->getCategoryById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!$this->validateCategoryInput($input)) {
            return $this->unprocessableEntityResponse();
        }
        
        $name = $input['name'];
        $description = $input['description'] ?? '';
        
        $result = $this->categoryModel->updateCategory($id, $name, $description);
        if ($result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Danh mục đã được cập nhật thành công'
            ]);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => 'Không thể cập nhật danh mục'
            ]);
        }
        
        return $response;
    }

    private function deleteCategory($id)
    {
        $result = $this->categoryModel->getCategoryById($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        
        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'status' => 'success',
                'message' => 'Danh mục đã được xóa thành công'
            ]);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode([
                'status' => 'error',
                'message' => 'Không thể xóa danh mục'
            ]);
        }
        
        return $response;
    }

    private function validateCategoryInput($input)
    {
        if (!isset($input['name']) || empty($input['name'])) {
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
            'message' => 'Danh mục không tồn tại'
        ]);
        return $response;
    }
}
