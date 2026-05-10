-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 11:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jewelrywebsite`
--
CREATE DATABASE IF NOT EXISTS `jewelrywebsite` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `jewelrywebsite`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`userId`) VALUES
(1),
(2),
(3),
(4),
(5);

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `articleId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `authorId` int(11) DEFAULT NULL,
  `cateId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`articleId`, `title`, `content`, `thumbnail`, `published_at`, `slug`, `authorId`, `cateId`, `contentId`) VALUES
(1, 'How to Choose Diamonds', 'Guide for beginners...', NULL, NULL, '', 1, 5, 6),
(2, 'Jewelry Care 101', 'Cleaning tips for gold...', NULL, NULL, '', 2, 5, 7),
(3, '2026 Wedding Trends', 'What is hot this year...', NULL, NULL, '', 3, 5, 8),
(4, 'Understanding Carats', 'Technical guide to carats...', NULL, NULL, '', 4, 5, 9),
(5, 'Investing in Gold', 'Market analysis 2026...', NULL, NULL, '', 5, 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `memberId`, `updated_at`) VALUES
(1, 6, '2026-04-06 09:34:05'),
(2, 7, '2026-04-06 09:34:05'),
(3, 8, '2026-04-06 09:34:05'),
(4, 9, '2026-04-06 09:34:05'),
(5, 10, '2026-04-06 09:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

DROP TABLE IF EXISTS `cart_item`;
CREATE TABLE `cart_item` (
  `itemId` int(11) NOT NULL,
  `cartId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`itemId`, `cartId`, `quantity`, `productId`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 3),
(3, 3, 1, 5),
(4, 4, 1, 2),
(5, 5, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `cateId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `parentCateId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cateId`, `name`, `type`, `slug`, `parentCateId`) VALUES
(1, 'Engagement Rings', 'product', 'engagement-rings', NULL),
(2, 'Diamond Necklaces', 'product', 'diamond-necklaces', NULL),
(3, 'Luxury Watches', 'product', 'luxury-watches', NULL),
(4, 'Gold Bracelets', 'product', 'gold-bracelets', NULL),
(5, 'Educational Blog', 'article', 'blog', NULL),
(6, 'Bông Tai', 'product', 'earing-bongtai', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL,
  `guest_name` varchar(100) DEFAULT NULL,
  `guest_email` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` int(1) NOT NULL DEFAULT 5,
  `status` varchar(20) NOT NULL DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `memberId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentId`, `guest_name`, `guest_email`, `content`, `rating`, `status`, `created_at`, `memberId`, `contentId`) VALUES
(1, NULL, NULL, 'Beautiful ring!', 5, 'approved', '2026-04-06 09:34:05', 6, 1),
(2, NULL, NULL, 'The watch is stunning.', 5, 'approved', '2026-04-06 09:34:05', 7, 2),
(3, NULL, NULL, 'Shipping was slow.', 3, 'approved', '2026-04-06 09:34:05', 8, 3),
(4, NULL, NULL, 'Average quality.', 4, 'approved', '2026-04-06 09:34:05', 9, 4),
(5, NULL, NULL, 'Best customer service.', 5, 'approved', '2026-04-06 09:34:05', 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `contactId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `reply_content` text DEFAULT NULL,
  `adminId` int(11) DEFAULT NULL,
  `memberId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`contactId`, `name`, `email`, `phone`, `subject`, `message`, `status`, `reply_content`, `adminId`, `memberId`) VALUES
(1, 'Robert Wilson', 'robert@gmail.com', NULL, 'Size Check', 'Is size 7 available for product 1?', 'pending', NULL, 1, 6),
(2, 'Linda Garcia', 'linda@gmail.com', NULL, 'Return', 'How to return my order?', 'replied', NULL, 2, 7),
(3, 'James Martinez', 'james@gmail.com', NULL, 'Feedback', 'Lovely shop!', 'pending', NULL, 3, 8),
(4, 'Barbara White', 'barbara@gmail.com', NULL, 'Bulk Order', 'Discount for 10 items?', 'pending', NULL, 4, 9),
(5, 'William Taylor', 'william@gmail.com', NULL, 'Repair', 'Do you fix broken clasps?', 'replied', NULL, 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `contentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`contentId`) VALUES
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
-- Table structure for table `faq`
--

DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `faqId` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `priority` int(5) NOT NULL DEFAULT 0,
  `creatorId` int(11) DEFAULT NULL,
  `contentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`faqId`, `question`, `answer`, `status`, `priority`, `creatorId`, `contentId`) VALUES
(1, 'Do you ship to EU?', 'Yes, we ship to all EU countries.', 'active', 1, 1, 1),
(2, 'Warranty period?', '2 years international warranty.', 'active', 2, 2, 2),
(3, 'Custom designs?', 'Contact us for bespoke jewelry.', 'active', 3, 3, 3),
(4, 'Gift wrapping?', 'Free premium gift box included.', 'active', 4, 4, 4),
(5, 'Safe payment?', 'SSL encrypted transactions.', 'active', 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `userId` int(11) NOT NULL,
  `phonenum` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `rewardPoint` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`userId`, `phonenum`, `address`, `rewardPoint`) VALUES
(6, '555-0101', '742 Evergreen Terrace', 150),
(7, '555-0102', '123 Maple Street', 200),
(8, '555-0103', '456 Oak Lane', 50),
(9, '555-0104', '789 Pine Road', 300),
(10, '555-0105', '101 Cedar Blvd', 120);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `orderId` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `shipping_addr` text NOT NULL,
  `payment` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`orderId`, `memberId`, `shipping_addr`, `payment`, `status`, `created_at`) VALUES
(1, 6, '742 Evergreen Terrace', 'CC', 'delivered', '2026-04-06 09:35:27'),
(2, 7, '123 Maple Street', 'Paypal', 'pending', '2026-04-06 09:35:27'),
(3, 8, '456 Oak Lane', 'COD', 'shipping', '2026-04-06 09:35:27'),
(4, 9, '789 Pine Road', 'CC', 'cancelled', '2026-04-06 09:35:27'),
(5, 10, '101 Cedar Blvd', 'Paypal', 'pending', '2026-04-06 09:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

DROP TABLE IF EXISTS `order_item`;
CREATE TABLE `order_item` (
  `itemId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `productId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`itemId`, `orderId`, `purchase_price`, `quantity`, `productId`) VALUES
(1, 1, 12000.00, 1, 1),
(2, 2, 1200.00, 2, 3),
(3, 3, 3200.00, 1, 5),
(4, 4, 8500.00, 1, 2),
(5, 5, 450.00, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
CREATE TABLE `page_content` (
  `pageId` int(11) NOT NULL,
  `page_key` varchar(100) NOT NULL,
  `section` varchar(100) DEFAULT NULL,
  `content` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cateId` int(11) DEFAULT NULL,
  `managerId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_content`
--

INSERT INTO `page_content` (`pageId`, `page_key`, `section`, `content`, `updated_at`, `cateId`, `managerId`) VALUES
(1, 'home_hero', 'Hero Section', 'Welcome to our Luxury Jewelry Store', '2026-04-06 09:34:05', 1, 1),
(2, 'about_story', 'History', 'Founded in 1990 by Alex Johnson', '2026-04-06 09:34:05', 1, 2),
(3, 'policy_return', 'Support', '30-day money back guarantee', '2026-04-06 09:34:05', 1, 3),
(4, 'shipping_info', 'Logistics', 'Worldwide express delivery available', '2026-04-06 09:34:05', 1, 4),
(5, 'terms_service', 'Legal', 'Privacy policy and user terms', '2026-04-06 09:34:05', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `size` varchar(50) DEFAULT NULL,
  `size_dim` varchar(100) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `usage_info` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cateId` int(11) NOT NULL,
  `contentId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `name`, `sku`, `color`, `size`, `size_dim`, `material`, `usage_info`, `description`, `price`, `stock_quantity`, `created_at`, `cateId`, `contentId`) VALUES
(1, 'Eternal Hope Diamond', '', NULL, NULL, NULL, NULL, NULL, '18K White Gold with 2ct Diamond', 12000.00, 5, '2026-04-06 09:34:05', 1, 1),
(2, 'Royal Sapphire Ring', '', NULL, NULL, NULL, NULL, NULL, 'Deep blue sapphire with silver band', 8500.00, 3, '2026-04-06 09:34:05', 1, 2),
(3, 'Golden Sun Pendant', '', NULL, NULL, NULL, NULL, NULL, '24K Solid Gold sun-shaped necklace', 1200.00, 15, '2026-04-06 09:34:05', 2, 3),
(4, 'Ocean Breeze Bracelet', '', NULL, NULL, NULL, NULL, NULL, 'Aqua blue pearls and sterling silver', 450.00, 20, '2026-04-06 09:34:05', 4, 4),
(5, 'Night Sky Watch', '', NULL, NULL, NULL, NULL, NULL, 'Limited edition titanium casing', 3200.00, 10, '2026-04-06 09:34:05', 3, 5),
(6, 'Bông Tai - Lucent:Pe Mn Hoop Cry/Alu', 'swa-5696289-white', 'WHITE', 'ONESIZE', NULL, 'Crystals, Aluminum', 'Tránh va đập với các vật cứng nhọn bén - Tránh tiếp xúc với mỹ phẩm, hóa chất, thuốc tẩy rửa - Bảo quản cẩn thận trong hộp riêng có bông hoặc mút xốp', 'Đôi hoa tai vòng tròn nhỏ đầy mê hoặc này là một ví dụ tinh tế về sự thành thạo của Swarovski trong việc sử dụng ánh sáng. Mỗi vòng tròn pha lê trong suốt có chốt cài kín đáo và cơ chế bản lề dễ mở. Một tác phẩm đồ họa nổi bật với đường viền thể thao sang trọng.', 6890000.00, 5, '2026-05-08 20:47:00', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

DROP TABLE IF EXISTS `product_image`;
CREATE TABLE `product_image` (
  `imageId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL DEFAULT '0',
  `is_primary` tinyint(1) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_image`
--

DROP TABLE IF EXISTS `site_image`;
CREATE TABLE `site_image` (
  `imageId` int(11) NOT NULL,
  `image_key` varchar(100) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `location_tag` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `managerId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_image`
--

INSERT INTO `site_image` (`imageId`, `image_key`, `filepath`, `location_tag`, `created_at`, `managerId`) VALUES
(1, 'logo_main', '/assets/logo.png', 'header', '2026-04-06 09:34:05', 1),
(2, 'banner_promo', '/assets/summer.jpg', 'homepage', '2026-04-06 09:34:05', 2),
(3, 'bg_footer', '/assets/bg.jpg', 'footer', '2026-04-06 09:34:05', 3),
(4, 'icon_fb', '/assets/facebook.png', 'social', '2026-04-06 09:34:05', 4),
(5, 'icon_ig', '/assets/insta.png', 'social', '2026-04-06 09:34:05', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `pwd_hash` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'member',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `username`, `fullname`, `email`, `pwd_hash`, `avatar`, `role`, `created_at`, `last_login`) VALUES
(1, 'alex_admin', 'Alex Johnson', 'alex@jewelry.com', 'hash1', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(2, 'sarah_mgr', 'Sarah Williams', 'sarah@jewelry.com', 'hash2', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(3, 'mike_staff', 'Mike Brown', 'mike@jewelry.com', 'hash3', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(4, 'emily_editor', 'Emily Davis', 'emily@jewelry.com', 'hash4', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(5, 'john_boss', 'John Miller', 'john@jewelry.com', 'hash5', NULL, 'admin', '2026-04-06 09:34:05', NULL),
(6, 'customer_01', 'Robert Wilson', 'robert@gmail.com', 'pass1', NULL, 'member', '2026-04-06 09:34:05', NULL),
(7, 'customer_02', 'Linda Garcia', 'linda@gmail.com', 'pass2', NULL, 'member', '2026-04-06 09:34:05', NULL),
(8, 'customer_03', 'James Martinez', 'james@gmail.com', 'pass3', NULL, 'member', '2026-04-06 09:34:05', NULL),
(9, 'customer_04', 'Barbara White', 'barbara@gmail.com', 'pass4', NULL, 'member', '2026-04-06 09:34:05', NULL),
(10, 'customer_05', 'William Taylor', 'william@gmail.com', 'pass5', NULL, 'member', '2026-04-06 09:34:05', NULL),
(19, 'loantest', NULL, 'nguynloan0@gmail.com', '$2y$10$Z9.wcvU6LSgKKl6ICWjD7efJif.Br7jkGYSCnTXSQ1si.bBv1vbmO', NULL, 'member', '2026-04-15 16:09:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`articleId`),
  ADD UNIQUE KEY `authorId` (`authorId`),
  ADD KEY `contentId` (`contentId`),
  ADD KEY `cateId` (`cateId`),
  ADD KEY `authorId_2` (`authorId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`),
  ADD KEY `memberId` (`memberId`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `cartId` (`cartId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`cateId`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parentCateId` (`parentCateId`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `memberId` (`memberId`),
  ADD KEY `contentId` (`contentId`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contactId`),
  ADD KEY `adminId` (`adminId`),
  ADD KEY `memberId` (`memberId`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`contentId`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faqId`),
  ADD KEY `creatorId` (`creatorId`),
  ADD KEY `contentId` (`contentId`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `phonenum` (`phonenum`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `memberId` (`memberId`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`itemId`),
  ADD KEY `orderId` (`orderId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `page_content`
--
ALTER TABLE `page_content`
  ADD PRIMARY KEY (`pageId`),
  ADD UNIQUE KEY `page_key` (`page_key`),
  ADD KEY `cateId` (`cateId`),
  ADD KEY `manageId` (`managerId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`),
  ADD KEY `cateId` (`cateId`),
  ADD KEY `contentId` (`contentId`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`imageId`),
  ADD KEY `productId` (`productId`);

--
-- Indexes for table `site_image`
--
ALTER TABLE `site_image`
  ADD PRIMARY KEY (`imageId`),
  ADD UNIQUE KEY `image_key` (`image_key`),
  ADD KEY `managerId` (`managerId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `article`
--
ALTER TABLE `article`
  MODIFY `articleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `cateId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contactId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `contentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faqId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_image`
--
ALTER TABLE `site_image`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`articleId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `article_ibfk_3` FOREIGN KEY (`authorId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cartId`) REFERENCES `cart` (`cartId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`parentCateId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`adminId`) REFERENCES `admin` (`userId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON DELETE SET NULL;

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `faq_ibfk_1` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `faq_ibfk_2` FOREIGN KEY (`creatorId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`memberId`) REFERENCES `member` (`userId`) ON UPDATE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`orderId`) REFERENCES `order` (`orderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON UPDATE CASCADE;

--
-- Constraints for table `page_content`
--
ALTER TABLE `page_content`
  ADD CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`cateId`) REFERENCES `category` (`cateId`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `page_content_ibfk_2` FOREIGN KEY (`managerId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`cateId`) REFERENCES `category` (`cateId`) ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`contentId`) REFERENCES `content` (`contentId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_image`
--
ALTER TABLE `product_image`
  ADD CONSTRAINT `fk_product_image` FOREIGN KEY (`productId`) REFERENCES `product` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `site_image`
--
ALTER TABLE `site_image`
  ADD CONSTRAINT `site_image_ibfk_1` FOREIGN KEY (`managerId`) REFERENCES `admin` (`userId`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
