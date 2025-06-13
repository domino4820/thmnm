<?php
// Require database configuration
require_once('app/config/database.php');

// Initialize connection
$database = new Database();
$conn = $database->getConnection();

try {
    echo "<h1>Executing SQL to update database structure</h1>";
    
    // SQL statements from update_table.sql
    $sql = "
    -- Thêm cột product_name và product_image vào bảng order_details nếu chưa tồn tại
    ALTER TABLE order_details 
    ADD COLUMN IF NOT EXISTS product_name VARCHAR(255) NOT NULL DEFAULT 'Sản phẩm',
    ADD COLUMN IF NOT EXISTS product_image VARCHAR(255) NULL;
    ";
    
    // Execute SQL
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute();
    
    if ($result) {
        echo "<p style='color:green'>SQL executed successfully!</p>";
    } else {
        echo "<p style='color:red'>Failed to execute SQL. Check for errors.</p>";
    }
    
    // Verify columns were added
    $stmt = $conn->prepare("DESCRIBE order_details");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Updated table structure:</h2>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "<td>{$column['Extra']}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database Error: " . $e->getMessage() . "</p>";
}
?> 