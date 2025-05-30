<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Read: Get all categories
    public function getCategories()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Read: Get single category by ID
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Create: Add new category
    public function createCategory($name, $description)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);
        
        // Clean and bind data
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Update: Modify existing category
    public function updateCategory($id, $name, $description)
    {
        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Clean and bind data
        $id = htmlspecialchars(strip_tags($id));
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        
        return $stmt->execute();
    }

    // Delete: Remove a category
    public function deleteCategory($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $id = htmlspecialchars(strip_tags($id));
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
} 