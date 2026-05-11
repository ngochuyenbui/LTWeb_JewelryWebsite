-- Dynamic data for the FAQ page.
-- `faq` already exists in the main database dump. This file refreshes its
-- sample content and adds the table used by the customer question form.

CREATE TABLE IF NOT EXISTS `faq` (
  `faqId` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `priority` int(5) NOT NULL DEFAULT 0,
  `creatorId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`faqId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `faq` (`faqId`, `question`, `answer`, `status`, `priority`, `creatorId`, `contentId`) VALUES
(1, 'Sản phẩm của AURELIA có chứng nhận chất lượng không?', 'Tất cả sản phẩm vàng bạc của AURELIA đều có giấy chứng nhận chất lượng từ Viện Đá Quý Quốc Tế (GIA) và Hiệp hội Vàng Việt Nam. Kim cương được kiểm định và cấp chứng nhận riêng biệt.', 'active', 1, NULL, NULL),
(2, 'Chính sách bảo hành như thế nào?', 'AURELIA cam kết bảo hành trọn đời cho tất cả sản phẩm vàng bạc. Bao gồm: đánh bóng, làm mới, sửa chữa miễn phí trong 1 năm đầu tiên. Sau 1 năm, phí bảo hành chỉ từ 100.000đ tùy loại sản phẩm.', 'active', 2, NULL, NULL),
(3, 'Tôi có thể đổi/trả sản phẩm không?', 'Có. AURELIA chấp nhận đổi trả trong vòng 30 ngày kể từ ngày mua, sản phẩm còn nguyên tem mác và hóa đơn. Sản phẩm khắc tên riêng không áp dụng đổi trả.', 'active', 3, NULL, NULL),
(4, 'Có hỗ trợ mua trả góp không?', 'AURELIA hỗ trợ trả góp 0% lãi suất qua thẻ tín dụng các ngân hàng liên kết cho đơn hàng từ 10.000.000đ. Thời hạn trả góp từ 3-12 tháng.', 'active', 4, NULL, NULL),
(5, 'Phí vận chuyển được tính như thế nào?', 'Miễn phí vận chuyển toàn quốc cho đơn hàng từ 5.000.000đ. Đơn hàng dưới 5.000.000đ phí ship chỉ 30.000đ. Giao hàng nhanh trong 2-3 ngày.', 'active', 5, NULL, NULL),
(6, 'Có thể thiết kế trang sức theo yêu cầu không?', 'Có! AURELIA nhận thiết kế và chế tác trang sức theo yêu cầu riêng. Vui lòng liên hệ hotline hoặc đến trực tiếp showroom để được tư vấn chi tiết. Thời gian chế tác từ 7-15 ngày làm việc.', 'active', 6, NULL, NULL)
ON DUPLICATE KEY UPDATE
  `question` = VALUES(`question`),
  `answer` = VALUES(`answer`),
  `status` = VALUES(`status`),
  `priority` = VALUES(`priority`);

CREATE TABLE IF NOT EXISTS `faq_question_submission` (
  `submissionId` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `question` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `answered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`submissionId`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
