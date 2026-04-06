# Hướng Dẫn Cài Đặt và Test Database Cho Team
(Để dễ kiểm tra lại thui)
Tài liệu này hướng dẫn các thành viên trong nhóm cách khởi chạy dự án trên máy cá nhân và thiết lập cấu hình Database với XAMPP để cùng nhau code MVC.

---

## Bước 1: Chuẩn bị Môi trường XAMPP
1. Chắc chắn rằng bạn đã cài đặt **XAMPP** trên máy.
2. Mở trình điều khiển kiểm soát **XAMPP Control Panel**.
3. Bấm **Start** cho 2 dịch vụ là **Apache** và **MySQL**. *Đảm bảo cả 2 đều sáng màu xanh lục không có lỗi.*

## Bước 2: Đặt Source Code vào Htdocs
Để dự án có thể chạy được, bạn bắt buộc phải đưa thư mục dự án vào thư mục của máy chủ nội bộ.
1. Copy toàn bộ thư mục dự án `LTWeb_JewelryWebsite` (chứa các thư mục *app, core, database, public...*).
2. Dán thư mục này vào đường dẫn:
   - Nếu dùng Windows: `C:\xampp\htdocs\`
   - Nếu dùng Mac: `Applications/XAMPP/xamppfiles/htdocs/`
3. Cuối cùng, đường dẫn đầy đủ trên máy của bạn sẽ trông giống như thế này: `C:\xampp\htdocs\LTWeb_JewelryWebsite`.

## Bước 3: Import Database (Cơ sở dữ liệu)
Bây giờ, chúng ta sẽ nạp cấu trúc và dữ liệu có sẵn vào MySQL.
1. Mở trình duyệt web của bạn lên và truy cập đường dẫn: `http://localhost/phpmyadmin/`
2. Ở cột bên trái, bấm vào **Mới** (New) để tạo database.
3. Ở khung bên phải màn hình:
   - Nhập Tên cơ sở dữ liệu (Database name) là: `jewelrywebsite`
   - Bảng mã (Collation) chọn: `utf8mb4_general_ci`
   - Bấm **Tạo** (Create).
4. Sau khi tạo xong, click chọn database `jewelrywebsite` vừa tạo ở cột bên trái.
5. Nhìn lên thanh menu trên cùng, bấm vào nút **Nhập** (Import).
6. Bấm nút **Chọn tệp** (Choose File) và trỏ tới tệp SQL đã được lưu thẳng trong source code của tụi mình: `htdocs/LTWeb_JewelryWebsite/database/jewelrywebsite.sql`.
7. Cuộn xuống dưới cùng và bấm **Nhập** (Go) để hoàn tất. Thành công là khi xuất hiện các thông báo màu xanh lá.

## Bước 4: Chạy Thử (Test Kết Nối)
Mọi thiết lập về code đã được xử lý ở file `core/Database.php` và `core/config.php`. Không cần phải code thêm để gọi SQL.
1. Để kiểm tra trên máy bạn đã nhận kết nối hay chưa, hãy mở trình duyệt lên và đi tới:
    `http://localhost/LTWeb_JewelryWebsite/Test`
2. **Thành công:** Màn hình in ra một "Array" chứa khoảng 16 danh sách các Tables mà bạn vừa import. 
3. **Thất bại:** Màn hình báo lỗi kết nối. Xem Bước 5.

## Bước 5: Cấu hình cá nhân (Nếu bị lỗi)
Nếu máy của thư viện/thành viên có dùng cấu hình mật khẩu MySQL riêng cho XAMPP (mặc định XAMPP không có mật khẩu gốc), hãy đổi nó trong project:
1. Mở file `core/config.php`.
2. Sửa thông số mật khẩu `DB_PASS` cho khớp với mật khẩu phpMyAdmin của máy bạn.
3. **Lưu ý:** Trước khi commit (đẩy code lên Github), HÃY XÓA pass đi để không làm lỗi máy của các bạn khác! 
