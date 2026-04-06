-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 06, 2026 lúc 11:41 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `jewelrywebsite`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `admin`:
--   `userId`
--       `user` -> `userid`
--

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT IGNORE INTO `admin` (`userId`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `articleId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `authorId` int(11) DEFAULT NULL,
  `cateId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`articleId`),
  UNIQUE KEY `authorId` (`authorId`),
  KEY `contentId` (`contentId`),
  KEY `cateId` (`cateId`),
  KEY `authorId_2` (`authorId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `article`:
--   `articleId`
--       `category` -> `cateId`
--   `contentId`
--       `content` -> `contentId`
--   `authorId`
--       `admin` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `article`
--

INSERT IGNORE INTO `article` (`articleId`, `title`, `content`, `thumbnail`, `published_at`, `slug`, `authorId`, `cateId`, `contentId`) VALUES
(1, 'How to Choose Diamonds', 'Guide for beginners...', NULL, NULL, '', 1, 5, 6),
(2, 'Jewelry Care 101', 'Cleaning tips for gold...', NULL, NULL, '', 2, 5, 7),
(3, '2026 Wedding Trends', 'What is hot this year...', NULL, NULL, '', 3, 5, 8),
(4, 'Understanding Carats', 'Technical guide to carats...', NULL, NULL, '', 4, 5, 9),
(5, 'Investing in Gold', 'Market analysis 2026...', NULL, NULL, '', 5, 5, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cartId` int(11) NOT NULL AUTO_INCREMENT,
  `memberId` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cartId`),
  KEY `memberId` (`memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `cart`:
--   `memberId`
--       `member` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT IGNORE INTO `cart` (`cartId`, `memberId`, `updated_at`) VALUES
(1, 6, '2026-04-06 09:34:05'),
(2, 7, '2026-04-06 09:34:05'),
(3, 8, '2026-04-06 09:34:05'),
(4, 9, '2026-04-06 09:34:05'),
(5, 10, '2026-04-06 09:34:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
CREATE TABLE IF NOT EXISTS `cart_item` (
  `itemId` int(11) NOT NULL AUTO_INCREMENT,
  `cartId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `productId` int(11) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `cartId` (`cartId`),
  KEY `productId` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `cart_item`:
--   `cartId`
--       `cart` -> `cartId`
--   `productId`
--       `product` -> `productId`
--

--
-- Đang đổ dữ liệu cho bảng `cart_item`
--

INSERT IGNORE INTO `cart_item` (`itemId`, `cartId`, `quantity`, `productId`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 3),
(3, 3, 1, 5),
(4, 4, 1, 2),
(5, 5, 3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cateId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `parentCateId` int(11) DEFAULT NULL,
  PRIMARY KEY (`cateId`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parentCateId` (`parentCateId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `category`:
--   `parentCateId`
--       `category` -> `cateId`
--

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT IGNORE INTO `category` (`cateId`, `name`, `type`, `slug`, `parentCateId`) VALUES
(1, 'Engagement Rings', 'product', 'engagement-rings', NULL),
(2, 'Diamond Necklaces', 'product', 'diamond-necklaces', NULL),
(3, 'Luxury Watches', 'product', 'luxury-watches', NULL),
(4, 'Gold Bracelets', 'product', 'gold-bracelets', NULL),
(5, 'Educational Blog', 'article', 'blog', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `status` varchar(20) NOT NULL DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `memberId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`commentId`),
  KEY `memberId` (`memberId`),
  KEY `contentId` (`contentId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `comment`:
--   `contentId`
--       `content` -> `contentId`
--   `memberId`
--       `member` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `comment`
--

INSERT IGNORE INTO `comment` (`commentId`, `guest_name`, `guest_email`, `content`, `rating`, `status`, `created_at`, `memberId`, `contentId`) VALUES
(1, NULL, NULL, 'Beautiful ring!', 5, 'approved', '2026-04-06 09:34:05', 6, 1),
(2, NULL, NULL, 'The watch is stunning.', 5, 'approved', '2026-04-06 09:34:05', 7, 2),
(3, NULL, NULL, 'Shipping was slow.', 3, 'approved', '2026-04-06 09:34:05', 8, 3),
(4, NULL, NULL, 'Average quality.', 4, 'approved', '2026-04-06 09:34:05', 9, 4),
(5, NULL, NULL, 'Best customer service.', 5, 'approved', '2026-04-06 09:34:05', 10, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `contactId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `reply_content` text DEFAULT NULL,
  `adminId` int(11) DEFAULT NULL,
  `memberId` int(11) DEFAULT NULL,
  PRIMARY KEY (`contactId`),
  KEY `adminId` (`adminId`),
  KEY `memberId` (`memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `contact`:
--   `adminId`
--       `admin` -> `userId`
--   `memberId`
--       `member` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `contact`
--

INSERT IGNORE INTO `contact` (`contactId`, `name`, `email`, `phone`, `subject`, `message`, `status`, `reply_content`, `adminId`, `memberId`) VALUES
(1, 'Robert Wilson', 'robert@gmail.com', NULL, 'Size Check', 'Is size 7 available for product 1?', 'pending', NULL, 1, 6),
(2, 'Linda Garcia', 'linda@gmail.com', NULL, 'Return', 'How to return my order?', 'replied', NULL, 2, 7),
(3, 'James Martinez', 'james@gmail.com', NULL, 'Feedback', 'Lovely shop!', 'pending', NULL, 3, 8),
(4, 'Barbara White', 'barbara@gmail.com', NULL, 'Bulk Order', 'Discount for 10 items?', 'pending', NULL, 4, 9),
(5, 'William Taylor', 'william@gmail.com', NULL, 'Repair', 'Do you fix broken clasps?', 'replied', NULL, 5, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `contentId` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`contentId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `content`:
--

--
-- Đang đổ dữ liệu cho bảng `content`
--

INSERT IGNORE INTO `content` (`contentId`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq` (
  `faqId` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `priority` int(5) NOT NULL DEFAULT 0,
  `creatorId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`faqId`),
  KEY `creatorId` (`creatorId`),
  KEY `contentId` (`contentId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `faq`:
--   `contentId`
--       `content` -> `contentId`
--   `creatorId`
--       `admin` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `faq`
--

INSERT IGNORE INTO `faq` (`faqId`, `question`, `answer`, `status`, `priority`, `creatorId`, `contentId`) VALUES
(1, 'Do you ship to EU?', 'Yes, we ship to all EU countries.', 'active', 1, 1, 1),
(2, 'Warranty period?', '2 years international warranty.', 'active', 2, 2, 2),
(3, 'Custom designs?', 'Contact us for bespoke jewelry.', 'active', 3, 3, 3),
(4, 'Gift wrapping?', 'Free premium gift box included.', 'active', 4, 4, 4),
(5, 'Safe payment?', 'SSL encrypted transactions.', 'active', 5, 5, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
  `userId` int(11) NOT NULL,
  `phonenum` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `rewardPoint` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `phonenum` (`phonenum`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `member`:
--   `userId`
--       `user` -> `userid`
--

--
-- Đang đổ dữ liệu cho bảng `member`
--

INSERT IGNORE INTO `member` (`userId`, `phonenum`, `address`, `rewardPoint`) VALUES
(6, '555-0101', '742 Evergreen Terrace', 150),
(7, '555-0102', '123 Maple Street', 200),
(8, '555-0103', '456 Oak Lane', 50),
(9, '555-0104', '789 Pine Road', 300),
(10, '555-0105', '101 Cedar Blvd', 120);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `orderId` int(11) NOT NULL AUTO_INCREMENT,
  `memberId` int(11) NOT NULL,
  `shipping_addr` text NOT NULL,
  `payment` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`orderId`),
  KEY `memberId` (`memberId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `order`:
--   `memberId`
--       `member` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `order`
--

INSERT IGNORE INTO `order` (`orderId`, `memberId`, `shipping_addr`, `payment`, `status`, `created_at`) VALUES
(1, 6, '742 Evergreen Terrace', 'CC', 'delivered', '2026-04-06 09:35:27'),
(2, 7, '123 Maple Street', 'Paypal', 'pending', '2026-04-06 09:35:27'),
(3, 8, '456 Oak Lane', 'COD', 'shipping', '2026-04-06 09:35:27'),
(4, 9, '789 Pine Road', 'CC', 'cancelled', '2026-04-06 09:35:27'),
(5, 10, '101 Cedar Blvd', 'Paypal', 'pending', '2026-04-06 09:35:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item`
--

DROP TABLE IF EXISTS `order_item`;
CREATE TABLE IF NOT EXISTS `order_item` (
  `itemId` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `productId` int(11) NOT NULL,
  PRIMARY KEY (`itemId`),
  KEY `orderId` (`orderId`),
  KEY `productId` (`productId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `order_item`:
--   `orderId`
--       `order` -> `orderId`
--   `productId`
--       `product` -> `productId`
--

--
-- Đang đổ dữ liệu cho bảng `order_item`
--

INSERT IGNORE INTO `order_item` (`itemId`, `orderId`, `purchase_price`, `quantity`, `productId`) VALUES
(1, 1, 12000.00, 1, 1),
(2, 2, 1200.00, 2, 3),
(3, 3, 3200.00, 1, 5),
(4, 4, 8500.00, 1, 2),
(5, 5, 450.00, 3, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `page_content`
--

DROP TABLE IF EXISTS `page_content`;
CREATE TABLE IF NOT EXISTS `page_content` (
  `pageId` int(11) NOT NULL,
  `page_key` varchar(100) NOT NULL,
  `section` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cateId` int(11) DEFAULT NULL,
  `managerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`pageId`),
  UNIQUE KEY `page_key` (`page_key`),
  KEY `cateId` (`cateId`),
  KEY `manageId` (`managerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `page_content`:
--   `cateId`
--       `category` -> `cateId`
--   `managerId`
--       `admin` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `page_content`
--

INSERT IGNORE INTO `page_content` (`pageId`, `page_key`, `section`, `content`, `updated_at`, `cateId`, `managerId`) VALUES
(1, 'home_hero', 'Hero Section', 'Welcome to our Luxury Jewelry Store', '2026-04-06 09:34:05', 1, 1),
(2, 'about_story', 'History', 'Founded in 1990 by Alex Johnson', '2026-04-06 09:34:05', 1, 2),
(3, 'policy_return', 'Support', '30-day money back guarantee', '2026-04-06 09:34:05', 1, 3),
(4, 'shipping_info', 'Logistics', 'Worldwide express delivery available', '2026-04-06 09:34:05', 1, 4),
(5, 'terms_service', 'Legal', 'Privacy policy and user terms', '2026-04-06 09:34:05', 1, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `productId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cateId` int(11) NOT NULL,
  `contentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`productId`),
  KEY `cateId` (`cateId`),
  KEY `contentId` (`contentId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `product`:
--   `cateId`
--       `category` -> `cateId`
--   `contentId`
--       `content` -> `contentId`
--

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT IGNORE INTO `product` (`productId`, `name`, `description`, `price`, `stock_quantity`, `created_at`, `cateId`, `contentId`) VALUES
(1, 'Eternal Hope Diamond', '18K White Gold with 2ct Diamond', 12000.00, 5, '2026-04-06 09:34:05', 1, 1),
(2, 'Royal Sapphire Ring', 'Deep blue sapphire with silver band', 8500.00, 3, '2026-04-06 09:34:05', 1, 2),
(3, 'Golden Sun Pendant', '24K Solid Gold sun-shaped necklace', 1200.00, 15, '2026-04-06 09:34:05', 2, 3),
(4, 'Ocean Breeze Bracelet', 'Aqua blue pearls and sterling silver', 450.00, 20, '2026-04-06 09:34:05', 4, 4),
(5, 'Night Sky Watch', 'Limited edition titanium casing', 3200.00, 10, '2026-04-06 09:34:05', 3, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `site_image`
--

DROP TABLE IF EXISTS `site_image`;
CREATE TABLE IF NOT EXISTS `site_image` (
  `imageId` int(11) NOT NULL AUTO_INCREMENT,
  `image_key` varchar(100) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `location_tag` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `managerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`imageId`),
  UNIQUE KEY `image_key` (`image_key`),
  KEY `managerId` (`managerId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `site_image`:
--   `managerId`
--       `admin` -> `userId`
--

--
-- Đang đổ dữ liệu cho bảng `site_image`
--

INSERT IGNORE INTO `site_image` (`imageId`, `image_key`, `filepath`, `location_tag`, `created_at`, `managerId`) VALUES
(1, 'logo_main', '/assets/logo.png', 'header', '2026-04-06 09:34:05', 1),
(2, 'banner_promo', '/assets/summer.jpg', 'homepage', '2026-04-06 09:34:05', 2),
(3, 'bg_footer', '/assets/bg.jpg', 'footer', '2026-04-06 09:34:05', 3),
(4, 'icon_fb', '/assets/facebook.png', 'social', '2026-04-06 09:34:05', 4),
(5, 'icon_ig', '/assets/insta.png', 'social', '2026-04-06 09:34:05', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `pwd_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- RELATIONSHIPS FOR TABLE `user`:
--

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT IGNORE INTO `user` (`userId`, `username`, `fullname`, `email`, `pwd_hash`, `avatar`, `role`, `created_at`, `last_login`) VALUES
(1, 'alex_admin', 'Alex Johnson', 'alex@jewelry.com', 'hash1', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(2, 'sarah_mgr', 'Sarah Williams', 'sarah@jewelry.com', 'hash2', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(3, 'mike_staff', 'Mike Brown', 'mike@jewelry.com', 'hash3', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(4, 'emily_editor', 'Emily Davis', 'emily@jewelry.com', 'hash4', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(5, 'john_boss', 'John Miller', 'john@jewelry.com', 'hash5', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(6, 'customer_01', 'Robert Wilson', 'robert@gmail.com', 'pass1', NULL, 'member', '2026-04-06 09:34:05', NULL),
(7, 'customer_02', 'Linda Garcia', 'linda@gmail.com', 'pass2', NULL, 'member', '2026-04-06 09:34:05', NULL),
(8, 'customer_03', 'James Martinez', 'james@gmail.com', 'pass3', NULL, 'member', '2026-04-06 09:34:05', NULL),
(9, 'customer_04', 'Barbara White', 'barbara@gmail.com', 'pass4', NULL, 'member', '2026-04-06 09:34:05', NULL),
(10, 'customer_05', 'William Taylor', 'william@gmail.com', 'pass5', NULL, 'member', '2026-04-06 09:34:05', NULL);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`articleId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_3` FOREIGN KEY (`authorId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parentCateId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`adminId`) REFERENCES `admin` (`userId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_ibfk_1` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faq_ibfk_2` FOREIGN KEY (`creatorId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `order` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `page_content`
--
ALTER TABLE `page_content`
  ADD CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`cateId`) REFERENCES `category` (`cateId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_2` FOREIGN KEY (`managerId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cateId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `site_image`
--
ALTER TABLE `site_image`
  ADD CONSTRAINT `site_image_ibfk_1` FOREIGN KEY (`managerId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
