-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 10:15 AM
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
-- Database: `banbanh`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(2, 'Hao', '123', '2024-11-10 13:44:47', '2024-11-10 14:04:52'),
(3, 'admin', '123', '2024-11-10 13:53:35', '2024-11-10 14:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `cartitems`
--

CREATE TABLE `cartitems` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cartitems`
--

INSERT INTO `cartitems` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `price`) VALUES
(5, 3, 22, 2, 200000.00),
(39, 1, 4, 2, 17000.00),
(41, 2, 11, 1, 40000.00);

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`cart_id`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-10-15 15:00:33', '2024-10-15 15:00:33'),
(2, 2, '2024-10-15 15:00:33', '2024-10-15 15:00:33'),
(3, 3, '2024-10-15 15:00:33', '2024-10-15 15:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_image`, `created_at`, `updated_at`) VALUES
(1, 'Bánh ngọt', 'cat001.jpg', '2024-10-15 14:59:06', '2024-11-11 03:52:27'),
(2, 'Bánh mỳ', 'cat002.jpg', '2024-10-15 14:59:06', '2024-10-15 14:59:06'),
(3, 'Bánh kem', 'cat003.jpg', '2024-10-15 14:59:06', '2024-10-15 14:59:06'),
(4, 'Bánh tươi', 'cat004.jpg', '2024-10-15 14:59:06', '2024-10-15 14:59:06'),
(5, 'Bánh quy', 'cat005.jpg', '2024-10-15 14:59:06', '2024-10-15 14:59:06');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `first_name`, `last_name`, `email`, `phone`, `address`, `created_at`, `updated_at`, `password`) VALUES
(1, 'Nguyen', 'Van A', 'nguyenvana@example.com', '0987654321', '123 Đường ABC, Hà Nội', '2024-10-15 15:00:33', '2024-10-29 17:18:00', '827ccb0eea8a706c4c34a16891f84e7b'),
(2, 'Tran', 'Thi B', 'tranthib@example.com', '0977654321', '456 Đường XYZ, TP.HCM', '2024-10-15 15:00:33', '2024-11-12 08:56:39', '123456'),
(3, 'Le', 'Van C', 'levanc@example.com', '0967654321', '789 Đường DEF, Đà Nẵng', '2024-10-15 15:00:33', '2024-10-29 17:18:00', '827ccb0eea8a706c4c34a16891f84e7b');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `discount_id` int(11) NOT NULL,
  `discount_name` varchar(255) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`discount_id`, `discount_name`, `discount_type`, `discount_value`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(2, 'Giảm 20% cho bánh kem', 'percentage', 20.00, '2024-11-01', '2024-11-15', '2024-10-15 15:03:10', '2024-10-15 15:03:10'),
(3, 'Giảm 5.000 VND cho tất cả bánh mì', 'fixed', 5000.00, '2024-10-15', '2024-10-30', '2024-10-15 15:03:10', '2024-10-29 13:13:55'),
(4, 'Giảm 10% ', 'percentage', 10.00, '2024-11-10', '2024-11-22', '2024-11-10 15:12:57', '2024-11-10 15:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 250000.00),
(2, 1, 2, 1, 250000.00),
(3, 2, 3, 3, 100000.00),
(4, 2, 4, 2, 50000.00),
(5, 3, 5, 5, 150000.00),
(6, 3, 6, 1, 60000.00),
(7, 4, 7, 2, 75000.00),
(8, 4, 8, 3, 25000.00),
(9, 5, 9, 4, 60000.00),
(10, 6, 10, 2, 300000.00),
(11, 7, 11, 3, 83000.00),
(12, 8, 12, 1, 950000.00),
(13, 9, 13, 4, 30000.00),
(14, 10, 14, 2, 400000.00),
(15, 11, 50, 1, 22000.00),
(16, 11, 49, 1, 20000.00),
(17, 11, 45, 1, 28000.00),
(18, 13, 50, 1, 22000.00),
(19, 14, 50, 1, 22000.00),
(20, 16, 1, 1, 20000.00),
(21, 17, 50, 1, 22000.00),
(22, 17, 49, 1, 20000.00),
(23, 17, 48, 2, 44000.00),
(24, 17, 45, 3, 28000.00),
(25, 18, 11, 1, 50000.00),
(26, 18, 12, 1, 20000.00),
(27, 18, 13, 1, 20000.00),
(28, 19, 50, 1, 22000.00),
(29, 19, 49, 1, 20000.00),
(30, 20, 9, 1, 25000.00),
(31, 21, 3, 1, 15000.00),
(32, 22, 1, 1, 20000.00),
(33, 23, 2, 4, 25000.00),
(34, 23, 3, 3, 15000.00),
(35, 23, 5, 2, 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'Thanh Toán Khi Nhận Hàng',
  `shipping_address` varchar(255) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `total_amount`, `order_status`, `order_date`, `payment_method`, `shipping_address`, `shipping_city`, `shipping_phone`, `notes`) VALUES
(1, 1, 500000.00, 'completed', '2024-10-01 03:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(2, 2, 300000.00, 'processing', '2024-10-02 04:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(3, 3, 750000.00, 'pending', '2024-10-03 05:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(4, 1, 150000.00, 'completed', '2024-10-04 06:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(5, 2, 450000.00, 'cancelled', '2024-10-05 07:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(6, 3, 600000.00, 'completed', '2024-10-06 08:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(7, 1, 250000.00, 'processing', '2024-10-07 09:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(8, 2, 950000.00, 'completed', '2024-10-08 10:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(9, 3, 120000.00, 'pending', '2024-10-09 11:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(10, 1, 800000.00, 'processing', '2024-10-10 12:00:00', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(11, 1, 70000.00, 'processing', '2024-11-12 05:58:40', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(12, 1, 0.00, 'processing', '2024-11-12 05:58:53', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(13, 1, 22000.00, 'processing', '2024-11-12 06:00:21', 'Thanh Toán Khi Nhận Hàng', '', '', '', NULL),
(14, 1, 22000.00, 'processing', '2024-11-12 06:14:23', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', NULL),
(15, 1, 0.00, 'processing', '2024-11-12 06:14:38', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', NULL),
(16, 1, 20000.00, 'processing', '2024-11-12 06:15:03', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', NULL),
(17, 1, 214000.00, 'processing', '2024-11-12 06:37:31', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', NULL),
(18, 1, 90000.00, 'processing', '2024-11-12 06:42:57', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', ''),
(19, 1, 42000.00, 'processing', '2024-11-12 06:43:27', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', 'Buổi chiều'),
(20, 1, 25000.00, 'processing', '2024-11-12 07:08:46', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', 'Buổi chiều'),
(21, 1, 15000.00, 'processing', '2024-11-12 07:12:07', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', 'Buổi chiều'),
(22, 1, 20000.00, 'processing', '2024-11-12 07:14:34', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', 'Buổi chiều'),
(23, 2, 175000.00, 'processing', '2024-11-12 10:10:22', 'Thanh Toán Khi Nhận Hàng', '22 đường só 5 ấp 1 xã tân thạnh tây ', 'Hcm', '0899414692', 'Buổi chiều');

-- --------------------------------------------------------

--
-- Table structure for table `productdiscounts`
--

CREATE TABLE `productdiscounts` (
  `product_discount_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `productdiscounts`
--

INSERT INTO `productdiscounts` (`product_discount_id`, `product_id`, `discount_id`) VALUES
(6, 11, 2),
(7, 12, 2),
(8, 13, 2),
(9, 14, 2),
(10, 15, 2),
(11, 6, 3),
(12, 7, 3),
(13, 8, 3),
(14, 9, 3),
(15, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `product_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock_quantity`, `product_image`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Bánh ngọt 001', 'Mô tả bánh ngọt 001', 20000.00, 99, 'sp001.jpg', 1, '2024-10-15 14:59:06', '2024-11-12 07:14:34'),
(2, 'Bánh ngọt 002', 'Mô tả bánh ngọt 002', 25000.00, 116, 'sp002.jpg', 1, '2024-10-15 14:59:06', '2024-11-12 10:10:22'),
(3, 'Bánh ngọt 003', 'Mô tả bánh ngọt 003', 15000.00, 146, 'sp003.jpg', 1, '2024-10-15 14:59:06', '2024-11-12 10:10:22'),
(4, 'Bánh ngọt 004', 'Mô tả bánh ngọt 004', 17000.00, 200, 'sp004.jpg', 1, '2024-10-15 14:59:06', '2024-10-29 13:03:24'),
(5, 'Bánh ngọt 005', 'Mô tả bánh ngọt 005', 15000.00, 88, 'sp005.jpg', 1, '2024-10-15 14:59:06', '2024-11-12 10:10:22'),
(6, 'Bánh mỳ 001', 'Mô tả bánh mỳ 001', 18000.00, 100, 'sp006.jpg', 2, '2024-10-15 14:59:06', '2024-10-29 13:04:58'),
(7, 'Bánh mỳ 002', 'Mô tả bánh mỳ 002', 33000.00, 80, 'sp007.jpg', 2, '2024-10-15 14:59:06', '2024-10-29 13:05:02'),
(8, 'Bánh mỳ 003', 'Mô tả bánh mỳ 003', 25000.00, 60, 'sp008.jpg', 2, '2024-10-15 14:59:06', '2024-10-29 13:05:17'),
(9, 'Bánh mỳ 004', 'Mô tả bánh mỳ 004', 25000.00, 0, 'sp009.jpg', 2, '2024-10-15 14:59:06', '2024-11-12 07:06:59'),
(10, 'Bánh mỳ 005', 'Mô tả bánh mỳ 005', 10000.00, 70, 'sp010.jpg', 2, '2024-10-15 14:59:06', '2024-10-29 13:05:28'),
(11, 'Bánh kem 001', 'Mô tả bánh kem 001', 50000.00, 30, 'sp011.jpg', 3, '2024-10-15 14:59:06', '2024-10-29 13:05:33'),
(12, 'Bánh kem 002', 'Mô tả bánh kem 002', 20000.00, 25, 'sp012.jpg', 3, '2024-10-15 14:59:06', '2024-10-29 13:05:38'),
(13, 'Bánh kem 003', 'Mô tả bánh kem 003', 20000.00, 20, 'sp013.jpg', 3, '2024-10-15 14:59:06', '2024-10-29 13:05:42'),
(14, 'Bánh kem 004', 'Mô tả bánh kem 004', 25000.00, 15, 'sp014.jpg', 3, '2024-10-15 14:59:06', '2024-10-29 13:05:47'),
(15, 'Bánh kem 005', 'Mô tả bánh kem 005', 15000.00, 10, 'sp015.jpg', 3, '2024-10-15 14:59:06', '2024-10-29 13:05:52'),
(16, 'Bánh tươi 001', 'Mô tả bánh tươi 001', 15000.00, 80, 'sp016.jpg', 4, '2024-10-15 14:59:06', '2024-10-29 13:05:55'),
(17, 'Bánh tươi 002', 'Mô tả bánh tươi 002', 15000.00, 75, 'sp017.jpg', 4, '2024-10-15 14:59:06', '2024-10-29 13:05:58'),
(18, 'Bánh tươi 003', 'Mô tả bánh tươi 003', 20000.00, 90, 'sp018.jpg', 4, '2024-10-15 14:59:06', '2024-10-29 13:06:05'),
(19, 'Bánh tươi 004', 'Mô tả bánh tươi 004', 20000.00, 70, 'sp019.jpg', 4, '2024-10-15 14:59:06', '2024-10-29 13:06:08'),
(20, 'Bánh tươi 005', 'Mô tả bánh tươi 005', 18000.00, 65, 'sp020.jpg', 4, '2024-10-15 14:59:06', '2024-10-29 13:06:18'),
(21, 'Bánh quy 001', 'Mô tả bánh quy 001', 50000.00, 100, 'sp021.jpg', 5, '2024-10-15 14:59:06', '2024-10-29 13:06:25'),
(22, 'Bánh quy 002', 'Mô tả bánh quy 002', 20000.00, 120, 'sp022.jpg', 5, '2024-10-15 14:59:06', '2024-10-29 13:06:31'),
(23, 'Bánh quy 003', 'Mô tả bánh quy 003', 18000.00, 110, 'sp023.jpg', 5, '2024-10-15 14:59:06', '2024-10-29 13:06:34'),
(24, 'Bánh quy 004', 'Mô tả bánh quy 004', 15000.00, 130, 'sp024.jpg', 5, '2024-10-15 14:59:06', '2024-10-29 13:06:37'),
(25, 'Bánh quy 005', 'Mô tả bánh quy 005', 30000.00, 140, 'sp025.jpg', 5, '2024-10-15 14:59:06', '2024-10-29 13:06:40'),
(26, 'Bánh ngọt 001', 'Mô tả bánh ngọt 001', 15000.00, 100, 'sp001.jpg', 1, '2024-10-15 15:00:33', '2024-10-29 16:13:55'),
(27, 'Bánh ngọt 002', 'Mô tả bánh ngọt 002', 20000.00, 120, 'sp002.jpg', 1, '2024-10-15 15:00:33', '2024-10-29 16:14:00'),
(28, 'Bánh ngọt 003', 'Mô tả bánh ngọt 003', 18000.00, 150, 'sp003.jpg', 1, '2024-10-15 15:00:33', '2024-10-29 16:14:08'),
(29, 'Bánh ngọt 004', 'Mô tả bánh ngọt 004', 22000.00, 200, 'sp004.jpg', 1, '2024-10-15 15:00:33', '2024-10-29 16:14:28'),
(30, 'Bánh ngọt 005', 'Mô tả bánh ngọt 005', 15000.00, 90, 'sp005.jpg', 1, '2024-10-15 15:00:33', '2024-10-29 16:14:32'),
(31, 'Bánh mỳ 001', 'Mô tả bánh mỳ 001', 20000.00, 100, 'sp006.jpg', 2, '2024-10-15 15:00:33', '2024-10-29 16:14:36'),
(32, 'Bánh mỳ 002', 'Mô tả bánh mỳ 002', 20000.00, 80, 'sp007.jpg', 2, '2024-10-15 15:00:33', '2024-10-29 16:14:38'),
(33, 'Bánh mỳ 003', 'Mô tả bánh mỳ 003', 18000.00, 60, 'sp008.jpg', 2, '2024-10-15 15:00:33', '2024-10-29 16:14:41'),
(34, 'Bánh mỳ 004', 'Mô tả bánh mỳ 004', 33000.00, 50, 'sp009.jpg', 2, '2024-10-15 15:00:33', '2024-10-29 16:14:44'),
(35, 'Bánh mỳ 005', 'Mô tả bánh mỳ 005', 50000.00, 70, 'sp010.jpg', 2, '2024-10-15 15:00:33', '2024-10-29 16:14:47'),
(36, 'Bánh kem 001', 'Mô tả bánh kem 001', 10000.00, 30, 'sp011.jpg', 3, '2024-10-15 15:00:33', '2024-10-29 16:14:50'),
(37, 'Bánh kem 002', 'Mô tả bánh kem 002', 22000.00, 25, 'sp012.jpg', 3, '2024-10-15 15:00:33', '2024-10-29 16:14:59'),
(38, 'Bánh kem 003', 'Mô tả bánh kem 003', 22000.00, 20, 'sp013.jpg', 3, '2024-10-15 15:00:33', '2024-10-29 16:15:04'),
(39, 'Bánh kem 004', 'Mô tả bánh kem 004', 15000.00, 15, 'sp014.jpg', 3, '2024-10-15 15:00:33', '2024-10-29 16:15:29'),
(40, 'Bánh kem 005', 'Mô tả bánh kem 005', 50000.00, 10, 'sp015.jpg', 3, '2024-10-15 15:00:33', '2024-10-29 16:15:36'),
(41, 'Bánh tươi 001', 'Mô tả bánh tươi 001', 20000.00, 80, 'sp016.jpg', 4, '2024-10-15 15:00:33', '2024-10-29 16:15:39'),
(42, 'Bánh tươi 002', 'Mô tả bánh tươi 002', 15000.00, 75, 'sp017.jpg', 4, '2024-10-15 15:00:33', '2024-10-29 16:15:43'),
(43, 'Bánh tươi 003', 'Mô tả bánh tươi 003', 18000.00, 90, 'sp018.jpg', 4, '2024-10-15 15:00:33', '2024-10-29 16:15:46'),
(44, 'Bánh tươi 004', 'Mô tả bánh tươi 004', 22000.00, 70, 'sp019.jpg', 4, '2024-10-15 15:00:33', '2024-10-29 16:15:48'),
(45, 'Bánh tươi 005', 'Mô tả bánh tươi 005', 28000.00, 65, 'sp020.jpg', 4, '2024-10-15 15:00:33', '2024-10-29 16:15:54'),
(46, 'Bánh quy 001', 'Mô tả bánh quy 001', 35000.00, 100, 'sp021.jpg', 5, '2024-10-15 15:00:33', '2024-10-29 16:15:59'),
(47, 'Bánh quy 002', 'Mô tả bánh quy 002', 35000.00, 120, 'sp022.jpg', 5, '2024-10-15 15:00:33', '2024-10-29 16:16:04'),
(48, 'Bánh quy 003', 'Mô tả bánh quy 003', 44000.00, 110, 'sp023.jpg', 5, '2024-10-15 15:00:33', '2024-10-29 16:16:08'),
(49, 'Bánh quy 004', 'Mô tả bánh quy 004', 20000.00, 130, 'sp024.jpg', 5, '2024-10-15 15:00:33', '2024-10-29 16:16:11'),
(50, 'Bánh quy 005', 'Mô tả bánh quy 005', 22000.00, 140, 'sp025.jpg', 5, '2024-10-15 15:00:33', '2024-10-29 16:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `customer_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 11, 1, 5, 'lỏ', '2024-11-12 07:45:53'),
(2, 11, 1, 5, 'lỏ', '2024-11-12 07:46:46'),
(3, 11, 1, 4, 'lỏ', '2024-11-12 07:47:07'),
(4, 11, 2, 4, 'khá ok', '2024-11-12 07:47:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `productdiscounts`
--
ALTER TABLE `productdiscounts`
  ADD PRIMARY KEY (`product_discount_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `discount_id` (`discount_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cartitems`
--
ALTER TABLE `cartitems`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `productdiscounts`
--
ALTER TABLE `productdiscounts`
  MODIFY `product_discount_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cartitems`
--
ALTER TABLE `cartitems`
  ADD CONSTRAINT `cartitems_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cartitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE SET NULL;

--
-- Constraints for table `productdiscounts`
--
ALTER TABLE `productdiscounts`
  ADD CONSTRAINT `productdiscounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productdiscounts_ibfk_2` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`discount_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
