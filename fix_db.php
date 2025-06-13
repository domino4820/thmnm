<?php
// Require database configuration
require_once('app/config/database.php');

echo "Starting database update...\n";

try {
    // Initialize connection
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "Connected to database successfully.\n";
    
    // First, check if the columns exist
    $stmt = $conn->prepare("SHOW COLUMNS FROM order_details LIKE 'product_name'");
    $stmt->execute();
    $productNameExists = $stmt->rowCount() > 0;
    
    $stmt = $conn->prepare("SHOW COLUMNS FROM order_details LIKE 'product_image'");
    $stmt->execute();
    $productImageExists = $stmt->rowCount() > 0;
    
    echo "Column 'product_name' exists: " . ($productNameExists ? "Yes" : "No") . "\n";
    echo "Column 'product_image' exists: " . ($productImageExists ? "Yes" : "No") . "\n";
    
    // Add columns if they don't exist
    if (!$productNameExists) {
        echo "Adding product_name column...\n";
        $conn->exec("ALTER TABLE order_details ADD COLUMN product_name VARCHAR(255) NOT NULL DEFAULT 'Sản phẩm'");
        echo "product_name column added successfully.\n";
    }
    
    if (!$productImageExists) {
        echo "Adding product_image column...\n";
        $conn->exec("ALTER TABLE order_details ADD COLUMN product_image VARCHAR(255) NULL");
        echo "product_image column added successfully.\n";
    }
    
    // Verify the columns were added
    echo "\nFinal table structure:\n";
    $stmt = $conn->prepare("DESCRIBE order_details");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}\n";
    }
    
    echo "\nDatabase update completed successfully.\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
} 