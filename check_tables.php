<?php
// Kết nối đến cơ sở dữ liệu
require_once('app/config/database.php');

// Khởi tạo kết nối
$database = new Database();
$conn = $database->getConnection();

try {
    // Lấy danh sách tất cả các bảng
    $tables_query = "SHOW TABLES FROM my_store";
    $stmt = $conn->prepare($tables_query);
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Database Structure</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            h1 {
                color: #333;
            }
            .table {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 10px;
            }
            .table-name {
                font-weight: bold;
                color: #007bff;
                font-size: 1.2em;
                margin-bottom: 10px;
            }
            .columns {
                margin-left: 20px;
            }
            .column {
                margin-bottom: 5px;
            }
            .column-name {
                font-weight: bold;
            }
            .column-type {
                color: #6c757d;
            }
            .primary-key {
                color: #dc3545;
            }
        </style>
    </head>
    <body>
        <h1>Danh sách các bảng trong cơ sở dữ liệu 'my_store'</h1>";
    
    if (empty($tables)) {
        echo "<p>Không có bảng nào trong cơ sở dữ liệu.</p>";
    } else {
        foreach ($tables as $table) {
            echo "<div class='table'>";
            echo "<div class='table-name'>$table</div>";
            
            // Hiển thị cấu trúc của bảng
            $columns_query = "DESCRIBE $table";
            $columns_stmt = $conn->prepare($columns_query);
            $columns_stmt->execute();
            $columns = $columns_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<div class='columns'>";
            foreach ($columns as $column) {
                $isPrimaryKey = ($column['Key'] == 'PRI') ? ' <span class="primary-key">(Primary Key)</span>' : '';
                echo "<div class='column'>
                    <span class='column-name'>{$column['Field']}</span> - 
                    <span class='column-type'>{$column['Type']}</span>
                    $isPrimaryKey
                </div>";
            }
            echo "</div>";
            echo "</div>";
        }
    }
    
    echo "</body></html>";
} catch (PDOException $e) {
    echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
}
?> 