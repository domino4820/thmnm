# Các thay đổi đã thực hiện

## 1. Sửa lỗi đường dẫn

- Đã thêm tiền tố `/webbanhang/` vào tất cả các đường dẫn để khắc phục lỗi "phương thức không tồn tại"
- Cập nhật tất cả các liên kết trong header, footer và các trang

## 2. Cải thiện form đăng ký và đăng nhập

- Đã gộp nút đăng ký và đăng nhập thành một nút trong header
- Thêm liên kết đăng ký trong trang đăng nhập
- Thêm các trường bổ sung trong form đăng ký:
  - Email
  - Số điện thoại
  - Địa chỉ (với các trường tỉnh/thành, quận/huyện, phường/xã)
  - Ảnh đại diện

## 3. Quản lý người dùng

- Thêm trang quản lý người dùng cho admin
- Thêm chức năng tạo, sửa, xóa người dùng
- Hiển thị thông tin chi tiết của người dùng (email, số điện thoại, địa chỉ, ảnh đại diện)

## 4. Phân quyền

- Cập nhật SessionHelper để kiểm tra quyền truy cập
- Phân quyền theo vai trò:
  - Admin: Quản lý sản phẩm, danh mục, người dùng
  - Employee: Quản lý sản phẩm
  - User: Mua sản phẩm, thanh toán

## 5. Cơ sở dữ liệu

- Cập nhật cấu trúc bảng account để hỗ trợ các trường mới:
  - email
  - sdt
  - dia_chi
  - avatar
  - created_at

## 6. Giao diện người dùng

- Thêm CSS tùy chỉnh cho các badge vai trò
- Cải thiện giao diện người dùng với Bootstrap 5
- Thêm thông báo flash cho các hành động thành công/thất bại
- Thêm xác nhận trước khi xóa người dùng

## 7. Quản lý file

- Thêm chức năng tải lên ảnh đại diện
- Tạo thư mục uploads để lưu trữ file người dùng tải lên
