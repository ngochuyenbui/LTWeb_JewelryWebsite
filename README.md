# Note: Có một README về XAMPP ở trong thư mục database
# Hướng Dẫn Điều Hướng Router
## 1. Nguyên lý hoạt động của Router
Thay vì truy cập trực tiếp vào từng file (ví dụ: about.php), tất cả mọi yêu cầu từ trình duyệt đều phải đi qua "cánh cửa duy nhất" là file public/index.php. Tại đây, lớp App (Router) sẽ phân tích URL để quyết định gọi Controller nào và Hàm (Action) nào.

## 2. Quy tắc đặt tên và Cấu trúc URL
Hệ thống sẽ hoạt động theo định dạng URL sạch (Pretty URL) nhờ vào cấu hình .htaccess:
localhost/jewelryweb/[Tên_Controller]/[Tên_Hàm]/[Tham_số]

Ví dụ thực tế:

localhost/jewelryweb/Home: Chạy hàm index() trong HomeController.php.

localhost/jewelryweb/Products/detail/5: Chạy hàm detail() với tham số $id = 5 trong ProductsController.php.

## 3. Cách thêm một Trang/Tính năng mới cho các thành viên
Mỗi thành viên khi được giao một nhiệm vụ (ví dụ: Thành viên #4 làm trang Tin tức) cần thực hiện 3 bước sau:

### Bước 1: Tạo Controller (Xử lý Logic)
Tạo file mới trong app/controllers/ (ví dụ: News.php). File này phải kế thừa từ lớp Controller cốt lõi.

<?php
class News extends Controller {
    // Hàm mặc định khi truy cập /news
    public function index() {
        // 1. Gọi Model để lấy dữ liệu từ MySQL (nếu cần) [cite: 62]
        // 2. Gọi View để hiển thị giao diện [cite: 42]
        $this->view("client/news_list"); 
    }

    // Hàm đọc chi tiết bài viết /news/detail/ID
    public function detail($id) {
        $this->view("client/news_detail", ["id" => $id]);
    }
}

### Bước 2: Tạo View (Giao diện)
Tạo file tương ứng trong app/views/client/ (ví dụ: news_list.php). Tại đây, bạn sử dụng HTML5/CSS3 (Tailwind) để dựng giao diện. Đừng quên nhúng Header/Footer dùng chung để đảm bảo tính nhất nhất.

### Bước 3: Tạo Model (Dữ liệu - Nếu có)
Nếu trang cần lấy dữ liệu từ MySQL (như danh sách sản phẩm hoặc bài viết), hãy tạo file trong app/models/ để viết các câu lệnh SQL.
