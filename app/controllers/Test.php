<?php
// app/controllers/Test.php

class Test extends Controller {
    public function index() {
        // Load model
        $testModel = $this->model('TestDbModel');
        $tables = $testModel->getSampleData();
        
        echo "<h2>Kết nối Database thành công! Các bảng hiện có:</h2>";
        echo "<pre>";
        print_r($tables);
        echo "</pre>";
    }

    public function fixImages() {
        $db = new Database();
        $db->query("UPDATE article SET thumbnail = REPLACE(thumbnail, 'assets/uploads/news/', 'assets/news/')");
        $db->execute();
        $db->query("UPDATE article SET thumbnail = REPLACE(thumbnail, 'assets/uploads/articles/', 'assets/news/')");
        $db->execute();
        echo "Đã sửa lại đường dẫn ảnh trong DB thành công!";
    }

    public function import() {
        // Import data from skymond_results.csv
        $csvFile = APPROOT . '/database/skymond_results.csv';
        if (!file_exists($csvFile)) {
            die("Không tìm thấy file CSV tại: " . $csvFile);
        }

        $db = new Database();
        
        // Sửa lỗi Database (khóa ngoại bị sai trong script ban đầu)
        try {
            $db->query("ALTER TABLE article DROP FOREIGN KEY article_ibfk_1");
            $db->execute();
        } catch(PDOException $e) {} // Bỏ qua nếu đã drop
        
        try {
            $db->query("ALTER TABLE article ADD CONSTRAINT article_ibfk_1 FOREIGN KEY (cateId) REFERENCES category (cateId) ON UPDATE CASCADE");
            $db->execute();
        } catch(PDOException $e) {} // Bỏ qua nếu đã add

        // Xóa index UNIQUE sai của authorId
        try {
            $db->query("ALTER TABLE article DROP INDEX authorId");
            $db->execute();
        } catch(PDOException $e) {} // Bỏ qua nếu đã drop
        
        $articleModel = $this->model('ArticleModel');

        // Xoá dữ liệu cũ trước khi import dữ liệu mới
        try {
            $db->query("DELETE FROM article");
            $db->execute();
            $db->query("DELETE FROM content");
            $db->execute();
        } catch(PDOException $e) {}

        echo "<h2>Đang tiến hành Import dữ liệu từ CSV...</h2>";
        echo "<ul>";

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            // Đọc dòng tiêu đề (Header)
            $header = fgetcsv($handle, 0, ",");
            
            $count = 0;
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                // Kiểm tra xem dòng có đủ 6 cột như định dạng không
                if (count($data) < 6) continue;
                
                $title = trim($data[0]);
                $content = trim($data[1]);
                $thumbnail = trim($data[2]);
                $slug = trim($data[3]);
                $authorId = (int)trim($data[4]);
                $cateId = (int)trim($data[5]);
                
                // Gắn thêm đường dẫn upload cho thumbnail (nếu có)
                $thumbnailPath = empty($thumbnail) ? '' : 'assets/news/' . $thumbnail;
                
                // 1. Tạo một record trong bảng `content` để lấy `contentId`
                $db->query("INSERT INTO content () VALUES ()");
                $db->execute();
                $contentId = $db->lastInsertId();
                
                // 2. Thêm vào bảng `article`
                $articleData = [
                    'title' => $title,
                    'content' => $content,
                    'thumbnail' => $thumbnailPath,
                    'published_at' => date('Y-m-d H:i:s'),
                    'slug' => $slug,
                    'authorId' => $authorId,
                    'cateId' => $cateId,
                    'contentId' => $contentId
                ];
                
                if ($articleModel->addArticle($articleData)) {
                    $count++;
                    echo "<li>Đã thêm thành công: <strong>" . htmlspecialchars($title) . "</strong></li>";
                } else {
                    echo "<li><span style='color:red;'>Lỗi khi thêm: " . htmlspecialchars($title) . "</span></li>";
                }
            }
            fclose($handle);
            echo "</ul>";
            echo "<h3>=> Hoàn tất! Đã import thành công $count bài viết vào Database.</h3>";
            echo "<br><a href='" . URLROOT . "/News'>Nhấn vào đây để xem trang Tin tức</a>";
        } else {
            echo "Không thể mở file CSV.";
        }
    }
}
