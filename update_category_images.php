<?php
// Connect to the database
require_once('app/config/database.php');
$db = new Database();
$conn = $db->getConnection();

// Array of category names and their corresponding image files
$categoryImages = [
    'Perfect Grade (PG)' => 'PG.jpg',
    'High Grade (HG)' => 'HG.jpg',
    'SD' => 'sd.jpg',
    'Freedom Strike' => 'FREEDOM_STRVE.jpg',
    'NMP' => 'FREEDOM_STRVE.jpg'
];

try {
    // Start transaction
    $conn->beginTransaction();
    
    // Get all categories
    $stmt = $conn->query("SELECT id, name FROM category");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $updateStmt = $conn->prepare("UPDATE category SET image = :image WHERE id = :id");
    
    foreach ($categories as $category) {
        $imageName = 'placeholder-category.jpg'; // Default image
        
        // Check if we have a specific image for this category
        foreach ($categoryImages as $categoryName => $image) {
            if (stripos($category['name'], $categoryName) !== false || 
                strtolower($category['name']) == strtolower($categoryName)) {
                $imageName = $image;
                break;
            }
        }
        
        // Update the category
        $updateStmt->bindParam(':image', $imageName);
        $updateStmt->bindParam(':id', $category['id']);
        $updateStmt->execute();
        
        echo "Updated category '{$category['name']}' with image '{$imageName}'<br>";
    }
    
    // Commit transaction
    $conn->commit();
    
    echo "<br>All categories updated successfully!";
    
} catch(PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?> 