# Project Bán Hàng

## Cài đặt

### Yêu cầu hệ thống

- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache, Nginx)

### Cài đặt cơ sở dữ liệu

1. Tạo cơ sở dữ liệu `my_store` trong MySQL
2. Import file SQL trong thư mục `sql/account_table.sql` để tạo bảng account và thêm tài khoản admin mặc định

### Cấu hình

1. Chỉnh sửa file `app/config/database.php` để cấu hình kết nối cơ sở dữ liệu
2. Đảm bảo đường dẫn base URL được cấu hình đúng (mặc định là `/webbanhang/`)

### Tài khoản mặc định

- Username: admin
- Password: password
- Role: admin

## Phân quyền

### Admin

- Quản lý sản phẩm (CRUD)
- Quản lý danh mục (CRUD)
- Quản lý người dùng (CRUD)
- Quản lý đơn hàng

### Employee (Nhân viên)

- Quản lý sản phẩm (CRUD)
- Quản lý đơn hàng

### User (Khách hàng)

- Xem sản phẩm
- Mua hàng
- Quản lý thông tin cá nhân
- Xem lịch sử đơn hàng

## Cấu trúc thư mục

- `app/`: Chứa mã nguồn chính của ứng dụng
  - `config/`: Cấu hình hệ thống
  - `controllers/`: Các controller
  - `models/`: Các model tương tác với cơ sở dữ liệu
  - `views/`: Giao diện người dùng
  - `helpers/`: Các lớp hỗ trợ
- `public/`: Thư mục công khai chứa tài nguyên tĩnh
  - `css/`: File CSS
  - `js/`: File JavaScript
  - `images/`: Hình ảnh
  - `uploads/`: Thư mục chứa file người dùng tải lên
- `sql/`: Chứa các script SQL
