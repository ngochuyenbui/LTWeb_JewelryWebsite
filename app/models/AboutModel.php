<?php

require_once 'BaseModel.php';

class AboutModel extends BaseModel {
    private function fetchRows(string $sql): array {
        try {
            $this->db->query($sql);
            $rows = $this->db->resultSet();
            return is_array($rows) ? $rows : [];
        } catch (Throwable $e) {
            return [];
        }
    }

    private function assetUrl(string $path, string $fallback = ''): string {
        $raw = trim($path);
        if ($raw === '') {
            return $fallback !== '' ? $this->assetUrl($fallback) : '';
        }

        if (str_starts_with($raw, URLROOT)) {
            return $raw;
        }

        if (preg_match('/^https?:\/\//i', $raw)) {
            return $fallback !== '' ? $this->assetUrl($fallback) : '';
        }

        return URLROOT . '/' . ltrim($raw, '/');
    }

    public function ensureJobApplicationTable(): void {
        $this->db->query("CREATE TABLE IF NOT EXISTS `job_application` (
            `applicationId` int(11) NOT NULL AUTO_INCREMENT,
            `fullname` varchar(150) NOT NULL,
            `email` varchar(150) NOT NULL,
            `phone` varchar(20) NOT NULL,
            `position` varchar(180) NOT NULL,
            `location` varchar(120) DEFAULT NULL,
            `cv_path` varchar(500) NOT NULL,
            `cover_letter` text NULL,
            `status` varchar(20) NOT NULL DEFAULT 'new',
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`applicationId`),
            KEY `status` (`status`),
            KEY `created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        $this->db->execute();
    }

    public function saveJobApplication(array $data): bool {
        $this->ensureJobApplicationTable();
        $this->db->query("INSERT INTO `job_application`
            (`fullname`, `email`, `phone`, `position`, `location`, `cv_path`, `cover_letter`)
            VALUES (:fullname, :email, :phone, :position, :location, :cv_path, :cover_letter)");
        $this->db->bind(':fullname', $data['fullname'] ?? '');
        $this->db->bind(':email', $data['email'] ?? '');
        $this->db->bind(':phone', $data['phone'] ?? '');
        $this->db->bind(':position', $data['position'] ?? '');
        $this->db->bind(':location', $data['location'] ?? '');
        $this->db->bind(':cv_path', $data['cv_path'] ?? '');
        $this->db->bind(':cover_letter', $data['cover_letter'] ?? '');
        return $this->db->execute();
    }

    public function getCompanyImages(): array {
        $rows = $this->fetchRows("SELECT image_url AS src, title, description AS `desc` FROM about_company_image WHERE status = 'active' ORDER BY sort_order ASC, imageId ASC");
        if (!empty($rows)) {
            $localRows = [];
            foreach ($rows as $row) {
                $row->src = $this->assetUrl((string)($row->src ?? ''));
                if ($row->src !== '') {
                    $localRows[] = $row;
                }
            }

            if (!empty($localRows)) {
                return $localRows;
            }
        }

        return [
            (object)['src' => $this->assetUrl('assets/images/about/store-1.jpg'), 'title' => 'Showroom flagship Quận 1', 'desc' => 'Không gian sang trọng tại trung tâm TP.HCM'],
            (object)['src' => $this->assetUrl('assets/images/about/store-2.png'), 'title' => 'Xưởng chế tác thủ công', 'desc' => 'Hơn 40 công đoạn chế tác bởi nghệ nhân lành nghề'],
            (object)['src' => $this->assetUrl('assets/images/about/store-3.jpg'), 'title' => 'Bộ sưu tập độc bản', 'desc' => 'Mỗi tác phẩm là một câu chuyện riêng'],
            (object)['src' => $this->assetUrl('assets/images/about/store-4.png'), 'title' => 'Phòng tư vấn VIP', 'desc' => 'Trải nghiệm dịch vụ cá nhân hoá đẳng cấp'],
        ];
    }

    public function getTestimonials(): array {
        $rows = $this->fetchRows("SELECT customer_name AS name, customer_role AS role, avatar_url AS avatar, content, rating FROM customer_testimonial WHERE status = 'active' ORDER BY sort_order ASC, testimonialId ASC");
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $row->avatar = $this->assetUrl((string)($row->avatar ?? ''), 'assets/images/about/logo.png');
            }
            return $rows;
        }

        $avatar = $this->assetUrl('assets/images/about/logo.png');
        return [
            (object)['name' => 'Chị Mai Linh', 'role' => 'Doanh nhân, Hà Nội', 'avatar' => $avatar, 'content' => 'Tôi đã mua trang sức tại AURELIA hơn 5 năm. Chất lượng vàng và thiết kế luôn vượt mong đợi, dịch vụ thì tận tâm hiếm có.', 'rating' => 5],
            (object)['name' => 'Anh Quang Huy', 'role' => 'Khách hàng VIP', 'avatar' => $avatar, 'content' => 'Bộ nhẫn cưới đặt riêng tại AURELIA là kỷ vật quý giá nhất của vợ chồng tôi. Đội ngũ tư vấn cực kỳ chuyên nghiệp.', 'rating' => 5],
            (object)['name' => 'Chị Thu Hà', 'role' => 'Stylist', 'avatar' => $avatar, 'content' => 'Mỗi món trang sức của AURELIA đều mang phong cách riêng, dễ dàng phối với nhiều outfit từ cổ điển đến hiện đại.', 'rating' => 5],
            (object)['name' => 'Cô Bích Ngọc', 'role' => 'Khách hàng thân thiết', 'avatar' => $avatar, 'content' => 'Giá vàng minh bạch, chính sách thu đổi rõ ràng. AURELIA là nơi tôi gửi gắm niềm tin trong nhiều năm qua.', 'rating' => 5],
        ];
    }

    public function getBranches(): array {
        $rows = $this->fetchRows("SELECT city, address, phone FROM branch WHERE status = 'active' ORDER BY sort_order ASC, branchId ASC");
        if (!empty($rows)) {
            return $rows;
        }

        return [
            (object)['city' => 'TP. Hồ Chí Minh', 'address' => '123 Lê Lợi, Quận 1', 'phone' => '028 3822 xxxx'],
            (object)['city' => 'Hà Nội', 'address' => '45 Tràng Tiền, Hoàn Kiếm', 'phone' => '024 3936 xxxx'],
            (object)['city' => 'Đà Nẵng', 'address' => '88 Bạch Đằng, Hải Châu', 'phone' => '0236 3823 xxxx'],
            (object)['city' => 'Cần Thơ', 'address' => '12 Hoà Bình, Ninh Kiều', 'phone' => '0292 3765 xxxx'],
            (object)['city' => 'Nha Trang', 'address' => '56 Trần Phú, Lộc Thọ', 'phone' => '0258 3527 xxxx'],
            (object)['city' => 'Hải Phòng', 'address' => '21 Điện Biên Phủ, Hồng Bàng', 'phone' => '0225 3845 xxxx'],
        ];
    }

    public function getJobs(): array {
        $rows = $this->fetchRows("SELECT jobId AS id, title, location, job_type AS type, experience_level AS level FROM recruitment_job WHERE status = 'open' ORDER BY sort_order ASC, jobId ASC");
        if (!empty($rows)) {
            return $rows;
        }

        return [
            (object)['title' => 'Nghệ nhân chế tác trang sức', 'location' => 'TP.HCM', 'type' => 'Full-time', 'level' => '3-5 năm kinh nghiệm'],
            (object)['title' => 'Tư vấn viên cao cấp', 'location' => 'Hà Nội', 'type' => 'Full-time', 'level' => 'Có kinh nghiệm bán lẻ'],
            (object)['title' => 'Thiết kế trang sức 3D', 'location' => 'TP.HCM', 'type' => 'Full-time', 'level' => 'Thành thạo Rhino/Matrix'],
            (object)['title' => 'Chuyên viên Marketing', 'location' => 'TP.HCM', 'type' => 'Full-time', 'level' => '2+ năm kinh nghiệm'],
            (object)['title' => 'Quản lý chi nhánh', 'location' => 'Đà Nẵng', 'type' => 'Full-time', 'level' => '5+ năm kinh nghiệm'],
            (object)['title' => 'Nhân viên kho vận', 'location' => 'TP.HCM', 'type' => 'Full-time', 'level' => 'Không yêu cầu kinh nghiệm'],
        ];
    }

    public function getStats(): array {
        $rows = $this->fetchRows("SELECT stat_value AS number, label FROM about_stat WHERE status = 'active' ORDER BY sort_order ASC, statId ASC");
        if (!empty($rows)) {
            return $rows;
        }

        return [
            (object)['number' => '18+', 'label' => 'Năm kinh nghiệm'],
            (object)['number' => '50K+', 'label' => 'Khách hàng'],
            (object)['number' => '10K+', 'label' => 'Sản phẩm'],
            (object)['number' => '15', 'label' => 'Chi nhánh'],
        ];
    }

    public function getValues(): array {
        $rows = $this->fetchRows("SELECT icon, title, description AS `desc` FROM about_value WHERE status = 'active' ORDER BY sort_order ASC, valueId ASC");
        if (!empty($rows)) {
            return $rows;
        }

        return [
            (object)['icon' => 'award', 'title' => 'Chất lượng', 'desc' => 'Cam kết sử dụng nguyên liệu cao cấp nhất với chứng nhận quốc tế.'],
            (object)['icon' => 'heart', 'title' => 'Tận tâm', 'desc' => 'Phục vụ khách hàng bằng sự chân thành và chuyên nghiệp.'],
            (object)['icon' => 'users', 'title' => 'Uy tín', 'desc' => 'Gần 20 năm xây dựng thương hiệu và niềm tin khách hàng.'],
            (object)['icon' => 'sparkles', 'title' => 'Sáng tạo', 'desc' => 'Không ngừng đổi mới thiết kế, dẫn đầu xu hướng trang sức.'],
        ];
    }
}
