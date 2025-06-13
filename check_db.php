<?php
// 数据库连接配置
require_once('app/config/database.php');

try {
    // 创建数据库连接
    $db = (new Database())->getConnection();
    
    // 查询account表结构
    $stmt = $db->prepare("DESCRIBE account");
    $stmt->execute();
    $tableStructure = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h2>Account表结构</h2>";
    echo "<pre>";
    print_r($tableStructure);
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}
?> 