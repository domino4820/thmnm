<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/helpers/UrlHelper.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // New method to handle routing
    public function handleRequest($action, $params = [])
    {
        // Check if session is not already active before starting
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 检查所有类别管理操作都需要管理员或员工权限
        if ($action !== 'index' && $action !== 'list') {
            // 这些操作需要产品管理权限
            try {
                SessionHelper::requireProductManager();
            } catch (Exception $e) {
                $_SESSION['error'] = "Bạn không có quyền thực hiện chức năng này";
                header('Location: ' . UrlHelper::url(''));
                exit();
            }
        }

        switch ($action) {
            case 'index':
                $this->list();
                break;
            case 'list':
                $this->list();
                break;
            case 'create':
                $this->create();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                // Check if an ID is provided
                if (isset($params[0])) {
                    $this->edit($params[0]);
                } else {
                    $_SESSION['error'] = "ID danh mục không hợp lệ";
                    header('Location: ' . UrlHelper::url('Category/list'));
                    exit();
                }
                break;
            case 'update':
                // Check if an ID is provided
                if (isset($params[0])) {
                    $this->update($params[0]);
                } else {
                    $_SESSION['error'] = "ID danh mục không hợp lệ";
                    header('Location: ' . UrlHelper::url('Category/list'));
                    exit();
                }
                break;
            case 'delete':
                // Check if an ID is provided
                if (isset($params[0])) {
                    $this->delete($params[0]);
                } else {
                    $_SESSION['error'] = "ID danh mục không hợp lệ";
                    header('Location: ' . UrlHelper::url('Category/list'));
                    exit();
                }
                break;
            default:
                // If no matching action is found
                $_SESSION['error'] = "Chức năng không tồn tại";
                header('Location: ' . UrlHelper::url('Category/list'));
                exit();
        }
    }

    // List all categories
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // Show create category form
    public function create()
    {
        include 'app/views/category/create.php';
    }

    // Process category creation
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $_SESSION['error'] = "Tên danh mục không được để trống";
                header('Location: ' . UrlHelper::url('Category/create'));
                exit();
            }

            $result = $this->categoryModel->createCategory($name, $description);

            if ($result) {
                $_SESSION['success'] = "Danh mục đã được tạo thành công";
                header('Location: ' . UrlHelper::url('Category/list'));
                exit();
            } else {
                $_SESSION['error'] = "Không thể tạo danh mục";
                header('Location: ' . UrlHelper::url('Category/create'));
                exit();
            }
        }
    }

    // Show edit category form
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            $_SESSION['error'] = "Danh mục không tồn tại";
            header('Location: ' . UrlHelper::url('Category/list'));
            exit();
        }

        include 'app/views/category/edit.php';
    }

    // Process category update
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $_SESSION['error'] = "Tên danh mục không được để trống";
                header('Location: ' . UrlHelper::url('Category/edit/' . $id));
                exit();
            }

            $result = $this->categoryModel->updateCategory($id, $name, $description);

            if ($result) {
                $_SESSION['success'] = "Danh mục đã được cập nhật thành công";
                header('Location: ' . UrlHelper::url('Category/list'));
                exit();
            } else {
                $_SESSION['error'] = "Không thể cập nhật danh mục";
                header('Location: ' . UrlHelper::url('Category/edit/' . $id));
                exit();
            }
        }
    }

    // Delete a category
    public function delete($id)
    {
        $result = $this->categoryModel->deleteCategory($id);

        if ($result) {
            $_SESSION['success'] = "Danh mục đã được xóa thành công";
        } else {
            $_SESSION['error'] = "Không thể xóa danh mục";
        }

        header('Location: ' . UrlHelper::url('Category/list'));
        exit();
    }
} 