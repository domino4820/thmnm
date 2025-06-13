<?php
// 数据库连接配置
require_once('app/config/database.php');

try {
    // 创建数据库连接
    $db = (new Database())->getConnection();
    
    // 检查列是否已存在
    $stmt = $db->prepare("SHOW COLUMNS FROM orders LIKE 'user_id'");
    $stmt->execute();
    $column_exists = $stmt->rowCount() > 0;
    
    if (!$column_exists) {
        // 添加user_id列
        $stmt = $db->prepare("ALTER TABLE orders ADD COLUMN user_id INT NULL");
        $stmt->execute();
        echo "<h2>成功添加user_id列到orders表</h2>";
    } else {
        echo "<h2>user_id列已存在于orders表中</h2>";
    }
    
    // 显示orders表结构
    $stmt = $db->prepare("DESCRIBE orders");
    $stmt->execute();
    $tableStructure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Orders表结构</h2>";
    echo "<pre>";
    print_r($tableStructure);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}
?> 