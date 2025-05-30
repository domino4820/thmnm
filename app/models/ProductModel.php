<?php

class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProducts($limit = 100, $offset = 0)
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
        FROM " . $this->table_name . " p
        LEFT JOIN category c ON p.category_id = c.id
        LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function getProductById($id)
    {
        if (!is_numeric($id)) {
            return null;
        }

        $query = "SELECT p.*, c.name as category_name 
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function addProduct($name, $description, $price, $category_id, $image = "")
    {
        // Validate input
        $name = trim($name);
        $description = trim($description);
        $price = floatval($price);
        $category_id = intval($category_id);

        // Kiểm tra tính hợp lệ của dữ liệu
        if (empty($name) || strlen($name) < 3) {
            throw new Exception('Tên sản phẩm phải có ít nhất 3 ký tự');
        }

        if (empty($description) || strlen($description) < 10) {
            throw new Exception('Mô tả phải có ít nhất 10 ký tự');
        }

        if ($price <= 0) {
            throw new Exception('Giá sản phẩm phải là số dương');
        }

        if (empty($category_id)) {
            throw new Exception('Vui lòng chọn danh mục');
        }

        // Kiểm tra danh mục có tồn tại không
        $checkCategoryQuery = "SELECT id FROM category WHERE id = :category_id";
        $checkStmt = $this->conn->prepare($checkCategoryQuery);
        $checkStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->rowCount() == 0) {
            throw new Exception('Danh mục không tồn tại');
        }

        // Chuẩn hóa dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $image = htmlspecialchars(strip_tags($image));

        // Thực hiện truy vấn thêm sản phẩm
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, description, price, category_id, image) 
                  VALUES (:name, :description, :price, :category_id, :image)";
        
        $stmt = $this->conn->prepare($query);

        // Bind các tham số
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        // Thực thi và trả về kết quả
        try {
            $result = $stmt->execute();
            
            // Trả về ID của sản phẩm vừa được thêm
            return $this->conn->lastInsertId();
        } catch(PDOException $e) {
            // Ghi log lỗi chi tiết
            error_log("Lỗi thêm sản phẩm: " . $e->getMessage());
            throw new Exception('Không thể thêm sản phẩm. Vui lòng thử lại.');
        }
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image = "")
    {
        // Validate input
        $id = intval($id);
        $name = trim($name);
        $description = trim($description);
        $price = floatval($price);
        $category_id = intval($category_id);

        // Kiểm tra tính hợp lệ của dữ liệu
        if ($id <= 0) {
            throw new Exception('ID sản phẩm không hợp lệ');
        }

        if (empty($name) || strlen($name) < 3) {
            throw new Exception('Tên sản phẩm phải có ít nhất 3 ký tự');
        }

        if (empty($description) || strlen($description) < 10) {
            throw new Exception('Mô tả phải có ít nhất 10 ký tự');
        }

        if ($price <= 0) {
            throw new Exception('Giá sản phẩm phải là số dương');
        }

        if (empty($category_id)) {
            throw new Exception('Vui lòng chọn danh mục');
        }

        // Kiểm tra sản phẩm có tồn tại không
        $checkProductQuery = "SELECT id FROM " . $this->table_name . " WHERE id = :id";
        $checkStmt = $this->conn->prepare($checkProductQuery);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->rowCount() == 0) {
            throw new Exception('Sản phẩm không tồn tại');
        }

        // Kiểm tra danh mục có tồn tại không
        $checkCategoryQuery = "SELECT id FROM category WHERE id = :category_id";
        $checkStmt = $this->conn->prepare($checkCategoryQuery);
        $checkStmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->rowCount() == 0) {
            throw new Exception('Danh mục không tồn tại');
        }

        // Chuẩn hóa dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $image = htmlspecialchars(strip_tags($image));

        // Truy vấn cập nhật sản phẩm
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, 
                      description = :description, 
                      price = :price, 
                      category_id = :category_id, 
                      image = :image 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);

        // Bind các tham số
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);

        // Thực thi và trả về kết quả
        try {
            $result = $stmt->execute();
            
            // Kiểm tra số dòng bị ảnh hưởng
            if ($stmt->rowCount() > 0) {
                return true;
            }
            
            throw new Exception('Không có thay đổi nào được thực hiện');
        } catch(PDOException $e) {
            // Ghi log lỗi chi tiết
            error_log("Lỗi cập nhật sản phẩm: " . $e->getMessage());
            throw new Exception('Không thể cập nhật sản phẩm. Vui lòng thử lại.');
        }
    }

    public function deleteProduct($id)
    {
        // Validate input
        $id = intval($id);

        if ($id <= 0) {
            throw new Exception('ID sản phẩm không hợp lệ');
        }

        // Kiểm tra sản phẩm có tồn tại không
        $checkProductQuery = "SELECT id FROM " . $this->table_name . " WHERE id = :id";
        $checkStmt = $this->conn->prepare($checkProductQuery);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        if ($checkStmt->rowCount() == 0) {
            throw new Exception('Sản phẩm không tồn tại');
        }

        // Truy vấn xóa sản phẩm
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        // Thực thi và trả về kết quả
        try {
            $result = $stmt->execute();
            
            // Kiểm tra số dòng bị ảnh hưởng
            if ($stmt->rowCount() > 0) {
                return true;
            }
            
            throw new Exception('Không thể xóa sản phẩm');
        } catch(PDOException $e) {
            // Ghi log lỗi chi tiết
            error_log("Lỗi xóa sản phẩm: " . $e->getMessage());
            throw new Exception('Không thể xóa sản phẩm. Vui lòng thử lại.');
        }
    }

    public function getFeaturedProducts($limit = 6)
    {
        // Retrieve featured products, typically the newest or most viewed products
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.category_id, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  ORDER BY p.id DESC  
                  LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}

?>