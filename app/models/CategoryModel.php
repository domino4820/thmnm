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
        
        // Add default image path if not specified
        foreach ($result as $category) {
            if (!isset($category->image)) {
                if (stripos($category->name, 'Perfect Grade') !== false || stripos($category->name, 'PG') !== false) {
                    $category->image = 'PG.jpg';
                } 
                else if (stripos($category->name, 'High Grade') !== false || stripos($category->name, 'HG') !== false) {
                    $category->image = 'HG.jpg';
                }
                else if (stripos($category->name, 'SD') !== false) {
                    $category->image = 'sd.jpg';
                }
                else if (stripos($category->name, 'Freedom Strike') !== false || stripos($category->name, 'Freedom') !== false) {
                    $category->image = 'FREEDOM_STRVE.jpg';
                }
                else if (stripos($category->name, 'NMP') !== false) {
                    $category->image = '68301e39bc8fa_818cXcaog9L.jpg';
                }
                else {
                    $category->image = 'placeholder-category.jpg';
                }
            }
        }
        
        return $result;
    }

    // Read: Get single category by ID
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Add default image if not specified
        if ($result) {
            if (!isset($result->image)) {
                if (stripos($result->name, 'Perfect Grade') !== false || stripos($result->name, 'PG') !== false) {
                    $result->image = 'PG.jpg';
                } 
                else if (stripos($result->name, 'High Grade') !== false || stripos($result->name, 'HG') !== false) {
                    $result->image = 'HG.jpg';
                }
                else if (stripos($result->name, 'SD') !== false) {
                    $result->image = 'sd.jpg';
                }
                else if (stripos($result->name, 'Freedom Strike') !== false || stripos($result->name, 'Freedom') !== false) {
                    $result->image = 'FREEDOM_STRVE.jpg';
                }
                else if (stripos($result->name, 'NMP') !== false) {
                    $result->image = '68301e39bc8fa_818cXcaog9L.jpg';
                }
                else {
                    $result->image = 'placeholder-category.jpg';
                }
            }
        }
        
        return $result;
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