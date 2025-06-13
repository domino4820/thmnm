<?php
// Require database configuration
require_once('app/config/database.php');

// Initialize connection
$database = new Database();
$conn = $database->getConnection();

function columnExists($conn, $table, $column) {
    $stmt = $conn->prepare("SHOW COLUMNS FROM $table LIKE ?");
    $stmt->execute([$column]);
    return $stmt->rowCount() > 0;
}

try {
    echo "<h1>Database Table Fix Tool</h1>";
    
    // Check if columns exist
    $productNameExists = columnExists($conn, 'order_details', 'product_name');
    $productImageExists = columnExists($conn, 'order_details', 'product_image');
    
    echo "<h2>Checking columns:</h2>";
    echo "- product_name column exists: " . ($productNameExists ? "<span style='color:green'>Yes</span>" : "<span style='color:red'>No</span>") . "<br>";
    echo "- product_image column exists: " . ($productImageExists ? "<span style='color:green'>Yes</span>" : "<span style='color:red'>No</span>") . "<br>";
    
    $changes = false;
    
    // Add product_name if it doesn't exist
    if (!$productNameExists) {
        echo "<h3>Adding product_name column...</h3>";
        try {
            $conn->exec("ALTER TABLE order_details ADD COLUMN product_name VARCHAR(255) NOT NULL DEFAULT 'Sản phẩm'");
            echo "<p style='color:green'>Successfully added product_name column!</p>";
            $changes = true;
        } catch (PDOException $e) {
            echo "<p style='color:red'>Error adding product_name column: " . $e->getMessage() . "</p>";
        }
    }
    
    // Add product_image if it doesn't exist
    if (!$productImageExists) {
        echo "<h3>Adding product_image column...</h3>";
        try {
            $conn->exec("ALTER TABLE order_details ADD COLUMN product_image VARCHAR(255) NULL");
            echo "<p style='color:green'>Successfully added product_image column!</p>";
            $changes = true;
        } catch (PDOException $e) {
            echo "<p style='color:red'>Error adding product_image column: " . $e->getMessage() . "</p>";
        }
    }
    
    if (!$changes) {
        echo "<p style='color:green'>All required columns already exist. No changes needed.</p>";
    }
    
    // Show complete table structure after changes
    $stmt = $conn->prepare("DESCRIBE order_details");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Current order_details table structure:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default'] ?? ''}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Show a test form to confirm everything works
    echo "<h2>Test form</h2>";
    echo "<p>Use this button to test if the checkout process works now:</p>";
    echo "<form action='/Product/checkout' method='GET'>";
    echo "<input type='submit' value='Go to checkout page'>";
    echo "</form>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
} 