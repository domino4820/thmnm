<?php
class Database {
    private $host = "localhost";
    private $db_name = "my_store";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Kiểm tra các thông số kết nối
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            // Ghi log thông tin kết nối
            error_log("Attempting database connection:");
            error_log("DSN: " . $dsn);
            error_log("Username: " . $this->username);
            
            // Thử kết nối với timeout dài hơn
            $this->conn = new PDO(
                $dsn, 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_TIMEOUT => 10,  // Tăng timeout lên 10 giây
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                ]
            );
            
            // Ghi log kết nối thành công
            error_log("Database connection successful!");
        } catch(PDOException $exception) {
            // Ghi log lỗi chi tiết
            error_log("Database Connection Error Details:");
            error_log("Error Message: " . $exception->getMessage());
            error_log("Error Code: " . $exception->getCode());
            error_log("Host: " . $this->host);
            error_log("Database: " . $this->db_name);
            error_log("Username: " . $this->username);
            
            // Hiển thị thông báo lỗi thân thiện
            die("Lỗi kết nối CSDL. Chi tiết lỗi đã được ghi vào log. Vui lòng kiểm tra cấu hình kết nối.");
        }
        return $this->conn;
    }
}
