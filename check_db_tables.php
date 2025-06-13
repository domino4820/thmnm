<?php
// Kết nối đến cơ sở dữ liệu
require_once('app/config/database.php');

// Khởi tạo kết nối
$database = new Database();
$conn = $database->getConnection();

// Lấy danh sách tất cả các bảng
$tables_query = "SHOW TABLES FROM my_store";
$stmt = $conn->prepare($tables_query);
$stmt->execute();
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo "<h1>Danh sách các bảng trong cơ sở dữ liệu 'my_store'</h1>";
echo "<ul>";
foreach ($tables as $table) {
    echo "<li>$table</li>";
    
    // Hiển thị cấu trúc của bảng
    $columns_query = "DESCRIBE $table";
    $columns_stmt = $conn->prepare($columns_query);
    $columns_stmt->execute();
    $columns = $columns_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>{$column['Field']} - {$column['Type']}</li>";
    }
    echo "</ul>";
}
echo "</ul>";
?> 