-- Optional dynamic data for the About page.
-- Import this after database/jewelrywebsite.sql if you want to manage these
-- About sections from MySQL instead of using the PHP fallback data.

CREATE TABLE IF NOT EXISTS `about_company_image` (
  `imageId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`imageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `about_company_image` (`title`, `description`, `image_url`, `status`, `sort_order`) VALUES
('Showroom flagship Quận 1', 'Không gian sang trọng tại trung tâm TP.HCM', 'https://images.unsplash.com/photo-1581090700227-1e8e6e3e2c1d?w=1200&h=700&fit=crop', 'active', 1),
('Xưởng chế tác thủ công', 'Hơn 40 công đoạn chế tác bởi nghệ nhân lành nghề', 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=1200&h=700&fit=crop', 'active', 2),
('Bộ sưu tập độc bản', 'Mỗi tác phẩm là một câu chuyện riêng', 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=1200&h=700&fit=crop', 'active', 3),
('Phòng tư vấn VIP', 'Trải nghiệm dịch vụ cá nhân hoá đẳng cấp', 'https://images.unsplash.com/photo-1573408301185-9146fe634ad0?w=1200&h=700&fit=crop', 'active', 4);

CREATE TABLE IF NOT EXISTS `customer_testimonial` (
  `testimonialId` int(11) NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(120) NOT NULL,
  `customer_role` varchar(150) DEFAULT NULL,
  `avatar_url` varchar(500) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`testimonialId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_testimonial` (`customer_name`, `customer_role`, `avatar_url`, `content`, `rating`, `status`, `sort_order`) VALUES
('Chị Mai Linh', 'Doanh nhân, Hà Nội', 'https://i.pravatar.cc/150?img=47', 'Tôi đã mua trang sức tại AURELIA hơn 5 năm. Chất lượng vàng và thiết kế luôn vượt mong đợi, dịch vụ thì tận tâm hiếm có.', 5, 'active', 1),
('Anh Quang Huy', 'Khách hàng VIP', 'https://i.pravatar.cc/150?img=12', 'Bộ nhẫn cưới đặt riêng tại AURELIA là kỷ vật quý giá nhất của vợ chồng tôi. Đội ngũ tư vấn cực kỳ chuyên nghiệp.', 5, 'active', 2),
('Chị Thu Hà', 'Stylist', 'https://i.pravatar.cc/150?img=32', 'Mỗi món trang sức của AURELIA đều mang phong cách riêng, dễ dàng phối với nhiều outfit từ cổ điển đến hiện đại.', 5, 'active', 3),
('Cô Bích Ngọc', 'Khách hàng thân thiết', 'https://i.pravatar.cc/150?img=45', 'Giá vàng minh bạch, chính sách thu đổi rõ ràng. AURELIA là nơi tôi gửi gắm niềm tin trong nhiều năm qua.', 5, 'active', 4);

CREATE TABLE IF NOT EXISTS `branch` (
  `branchId` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(120) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`branchId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `branch` (`city`, `address`, `phone`, `status`, `sort_order`) VALUES
('TP. Hồ Chí Minh', '123 Lê Lợi, Quận 1', '028 3822 xxxx', 'active', 1),
('Hà Nội', '45 Tràng Tiền, Hoàn Kiếm', '024 3936 xxxx', 'active', 2),
('Đà Nẵng', '88 Bạch Đằng, Hải Châu', '0236 3823 xxxx', 'active', 3),
('Cần Thơ', '12 Hoà Bình, Ninh Kiều', '0292 3765 xxxx', 'active', 4),
('Nha Trang', '56 Trần Phú, Lộc Thọ', '0258 3527 xxxx', 'active', 5),
('Hải Phòng', '21 Điện Biên Phủ, Hồng Bàng', '0225 3845 xxxx', 'active', 6);

CREATE TABLE IF NOT EXISTS `recruitment_job` (
  `jobId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(180) NOT NULL,
  `location` varchar(120) NOT NULL,
  `job_type` varchar(80) NOT NULL DEFAULT 'Full-time',
  `experience_level` varchar(180) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'open',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`jobId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `recruitment_job` (`title`, `location`, `job_type`, `experience_level`, `status`, `sort_order`) VALUES
('Nghệ nhân chế tác trang sức', 'TP.HCM', 'Full-time', '3-5 năm kinh nghiệm', 'open', 1),
('Tư vấn viên cao cấp', 'Hà Nội', 'Full-time', 'Có kinh nghiệm bán lẻ', 'open', 2),
('Thiết kế trang sức 3D', 'TP.HCM', 'Full-time', 'Thành thạo Rhino/Matrix', 'open', 3),
('Chuyên viên Marketing', 'TP.HCM', 'Full-time', '2+ năm kinh nghiệm', 'open', 4),
('Quản lý chi nhánh', 'Đà Nẵng', 'Full-time', '5+ năm kinh nghiệm', 'open', 5),
('Nhân viên kho vận', 'TP.HCM', 'Full-time', 'Không yêu cầu kinh nghiệm', 'open', 6);

CREATE TABLE IF NOT EXISTS `job_application` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `about_stat` (
  `statId` int(11) NOT NULL AUTO_INCREMENT,
  `stat_value` varchar(30) NOT NULL,
  `label` varchar(120) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`statId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `about_stat` (`stat_value`, `label`, `status`, `sort_order`) VALUES
('18+', 'Năm kinh nghiệm', 'active', 1),
('50K+', 'Khách hàng', 'active', 2),
('10K+', 'Sản phẩm', 'active', 3),
('15', 'Chi nhánh', 'active', 4);

CREATE TABLE IF NOT EXISTS `about_value` (
  `valueId` int(11) NOT NULL AUTO_INCREMENT,
  `icon` varchar(50) NOT NULL DEFAULT 'sparkles',
  `title` varchar(120) NOT NULL,
  `description` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`valueId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `about_value` (`icon`, `title`, `description`, `status`, `sort_order`) VALUES
('award', 'Chất lượng', 'Cam kết sử dụng nguyên liệu cao cấp nhất với chứng nhận quốc tế.', 'active', 1),
('heart', 'Tận tâm', 'Phục vụ khách hàng bằng sự chân thành và chuyên nghiệp.', 'active', 2),
('users', 'Uy tín', 'Gần 20 năm xây dựng thương hiệu và niềm tin khách hàng.', 'active', 3),
('sparkles', 'Sáng tạo', 'Không ngừng đổi mới thiết kế, dẫn đầu xu hướng trang sức.', 'active', 4);
