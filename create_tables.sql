-- Chọn cơ sở dữ liệu
USE my_store;

-- Tạo bảng đơn hàng nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(255) NOT NULL,
  customer_email VARCHAR(255),
  customer_phone VARCHAR(20) NOT NULL,
  customer_address TEXT NOT NULL,
  order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10, 2) DEFAULT 0
);

-- Tạo bảng chi tiết đơn hàng nếu chưa tồn tại
CREATE TABLE IF NOT EXISTS order_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id)
  -- FOREIGN KEY (product_id) REFERENCES products(id)
); 