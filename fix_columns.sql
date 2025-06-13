USE my_store;

-- Add product_name column
ALTER TABLE order_details ADD COLUMN product_name VARCHAR(255) NOT NULL DEFAULT 'Sản phẩm';

-- Add product_image column
ALTER TABLE order_details ADD COLUMN product_image VARCHAR(255) NULL;

-- Check if columns were added successfully
DESCRIBE order_details; 