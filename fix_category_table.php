<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Connect to the database
require_once('app/config/database.php');
$db = new Database();
$conn = $db->getConnection();

// Function to safely echo HTML
function output($message) {
    echo htmlspecialchars($message) . "<br>\n";
    flush();
    ob_flush();
}

// Check if the image column exists
function columnExists($conn, $table, $column) {
    try {
        $stmt = $conn->query("SELECT $column FROM $table LIMIT 1");
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// Start HTML output
echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Category Table</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
    </style>
</head>
<body>
<div class='container'>
    <h1>Fix Category Table</h1>";

try {
    echo "<h2>Step 1: Check if 'image' column exists</h2>";
    
    if (columnExists($conn, 'category', 'image')) {
        echo "<p class='info'>Column 'image' already exists in the 'category' table.</p>";
    } else {
        echo "<p class='info'>Column 'image' does not exist. Adding it now...</p>";
        
        // SQL query to add image column to category table
        $sql = "ALTER TABLE `category` ADD COLUMN `image` VARCHAR(255) NULL AFTER `description`";
        
        try {
            // Execute the query
            $conn->exec($sql);
            echo "<p class='success'>Column 'image' added to 'category' table successfully!</p>";
        } catch(PDOException $e) {
            echo "<p class='error'>Error adding column: " . $e->getMessage() . "</p>";
            throw $e;
        }
    }
    
    echo "<h2>Step 2: Update category images</h2>";
    
    // Array of category names and their corresponding image files
    $categoryImages = [
        'Perfect Grade (PG)' => 'PG.jpg',
        'High Grade (HG)' => 'HG.jpg',
        'SD' => 'sd.jpg',
        'Freedom Strike' => 'FREEDOM_STRVE.jpg',
        'NMP' => 'FREEDOM_STRVE.jpg'
    ];
    
    // Start transaction
    $conn->beginTransaction();
    
    // Get all categories
    $stmt = $conn->query("SELECT id, name FROM category");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($categories)) {
        echo "<p class='info'>No categories found in the database.</p>";
    } else {
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
            
            echo "<p>Updated category '" . htmlspecialchars($category['name']) . "' with image '" . htmlspecialchars($imageName) . "'</p>";
        }
        
        // Commit transaction
        $conn->commit();
        
        echo "<p class='success'>All categories updated successfully!</p>";
    }
    
    echo "<h2>Step 3: Verification</h2>";
    
    // Verify that the image column exists and has data
    $stmt = $conn->query("SELECT id, name, image FROM category");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($categories)) {
        echo "<p class='info'>No categories found for verification.</p>";
    } else {
        echo "<table border='1' cellpadding='5'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                </tr>";
        
        foreach ($categories as $category) {
            echo "<tr>
                    <td>" . htmlspecialchars($category['id']) . "</td>
                    <td>" . htmlspecialchars($category['name']) . "</td>
                    <td>" . htmlspecialchars($category['image']) . "</td>
                  </tr>";
        }
        
        echo "</table>";
    }
    
} catch(PDOException $e) {
    // Rollback transaction on error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    echo "<p class='error'>Database Error: " . $e->getMessage() . "</p>";
}

echo "<p><a href='index.php'>Return to Homepage</a></p>";
echo "</div></body></html>";
?> 