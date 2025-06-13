-- Chọn cơ sở dữ liệu
USE my_store;
 
-- Thêm cột product_name và product_image vào bảng order_details nếu chưa tồn tại
ALTER TABLE order_details 
ADD COLUMN IF NOT EXISTS product_name VARCHAR(255) NOT NULL DEFAULT 'Sản phẩm',
ADD COLUMN IF NOT EXISTS product_image VARCHAR(255) NULL; 