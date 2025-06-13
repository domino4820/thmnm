<?php
// Connect to the database
require_once('app/config/database.php');
$db = new Database();
$conn = $db->getConnection();

// SQL query to add image column to category table
$sql = "ALTER TABLE `category` ADD COLUMN `image` VARCHAR(255) NULL AFTER `description`";

try {
    // Execute the query
    $conn->exec($sql);
    echo "Column 'image' added to 'category' table successfully!";
} catch(PDOException $e) {
    // If the column already exists or other error occurs
    if($e->getCode() == '42S21') {
        echo "Column 'image' already exists in 'category' table.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?> 