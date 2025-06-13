<?php
// 数据库连接配置
require_once('app/config/database.php');

try {
    // 创建数据库连接
    $db = (new Database())->getConnection();
    
    // 查询orders表结构
    $stmt = $db->prepare("DESCRIBE orders");
    $stmt->execute();
    $tableStructure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Orders表结构</h2>";
    echo "<pre>";
    print_r($tableStructure);
    echo "</pre>";
    
    // 查询orders表中的一条记录
    $stmt = $db->prepare("SELECT * FROM orders LIMIT 1");
    $stmt->execute();
    $orderSample = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h2>Orders表示例数据</h2>";
    echo "<pre>";
    print_r($orderSample);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}
?> 