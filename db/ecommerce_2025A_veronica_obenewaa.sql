-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 05:59 PM
-- Server version: 8.0.44-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_2025A_veronica_obenewaa`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_notifications`
--

CREATE TABLE `booking_notifications` (
  `notification_id` int NOT NULL,
  `booking_id` int NOT NULL,
  `patient_id` int NOT NULL,
  `physician_id` int NOT NULL,
  `notification_type` varchar(50) NOT NULL DEFAULT 'cancellation',
  `message` text,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int NOT NULL,
  `brand_name` varchar(100) NOT NULL COMMENT 'Brand name (text)',
  `created_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `created_by`) VALUES
(10, 'panadols', 2),
(11, 'Prosac', 2),
(12, 'Amoxil', 2),
(13, 'Flagyl', 2),
(14, 'Augmentin', 2),
(15, 'Zoloft', 2),
(16, 'Lexapro', 2),
(18, 'Cozaar', 2),
(19, 'Diovan', 2),
(20, 'Glucophage', 2),
(21, 'Actos', 2),
(22, 'Humulin', 2),
(23, 'Tylenol', 2),
(24, 'Advil', 2),
(25, 'Benylin', 2),
(26, 'Claritin', 2),
(27, 'Zyrtec', 2),
(28, 'Redoxon', 2),
(29, 'Centrum', 2),
(30, 'Wellman', 2),
(31, 'Seven seas', 2),
(32, 'Hydrocortisone', 2),
(33, 'Canesten', 2),
(34, 'Betnovate', 2),
(35, 'Deep heatt', 2),
(36, 'Tiger balm', 2),
(37, 'Biofreeze', 2),
(38, 'Icy hot', 2),
(39, 'Thermacare', 2),
(40, 'Detol', 2),
(41, 'Band-aiid', 2),
(42, 'Elastoplast', 2),
(43, 'bandages', 2),
(44, 'ointment', 2),
(45, 'Sterile gloves', 2),
(46, 'First aid kits', 2),
(47, 'Omron', 2),
(48, 'OneTouch', 2),
(49, 'Microlife', 2),
(50, 'Clearblue', 2),
(51, 'Dr Morepen', 2),
(52, 'Omeprazole', 2),
(53, 'Pepto-Bismol', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `p_id` int NOT NULL,
  `c_id` int DEFAULT NULL,
  `qty` int NOT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`p_id`, `c_id`, `qty`, `added_at`) VALUES
(8, 2, 1, '2025-11-13 00:36:46'),
(11, 6, 2, '2025-11-24 16:03:34'),
(2, 10, 1, '2025-11-24 19:36:19'),
(9, 10, 1, '2025-11-25 22:08:42'),
(12, 10, 1, '2025-11-26 00:13:17'),
(11, 11, 2, '2025-11-27 15:54:56'),
(9, 11, 1, '2025-11-27 17:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int NOT NULL,
  `cat_name` varchar(100) NOT NULL COMMENT 'Category name (text)',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `created_by`, `created_at`) VALUES
(1, 'Vitamins & Minerals', 2, '2025-10-16 18:27:11'),
(4, 'Pain Relievers', 2, '2025-10-16 18:35:35'),
(6, 'First Aid', 2, '2025-10-24 17:17:50'),
(7, 'Food supplements & vitamins', 2, '2025-10-24 17:18:13'),
(8, 'Antibiotics', 2, '2025-10-26 00:14:43'),
(9, 'Antivirals', 2, '2025-10-26 00:15:02'),
(10, 'Antimalarias', 2, '2025-10-26 00:15:16'),
(11, 'Antifungals', 2, '2025-10-26 00:15:30'),
(12, 'Antidepressants', 2, '2025-10-26 00:15:55'),
(13, 'Antihypertensives', 2, '2025-10-26 00:16:26'),
(14, 'Cardiovascular drugs', 2, '2025-10-26 00:16:52'),
(15, 'Antidiabetics drugs', 2, '2025-10-26 00:17:13'),
(16, 'Antihistamines', 2, '2025-10-26 00:17:29'),
(17, 'Gastrointestinal drugs', 2, '2025-10-26 00:17:52'),
(18, 'Dermatological agents', 2, '2025-10-26 00:18:11'),
(19, 'Musculoskeletal drugs', 2, '2025-10-26 00:18:34'),
(20, 'Dietary supplements', 2, '2025-10-26 00:19:46'),
(21, 'Antiparasitics', 2, '2025-10-26 00:20:08'),
(22, 'Chemotherapy drugs', 2, '2025-10-26 00:20:31'),
(24, 'Diagnostic & medical devices', 2, '2025-10-26 00:22:06'),
(25, 'Therapeutic items', 2, '2025-10-26 00:22:37'),
(26, 'Antacids & Antiulcer Drugs', 2, '2025-11-13 00:33:45'),
(27, 'Antidiarrheal / Antacid', 2, '2025-11-13 00:47:27');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(50) NOT NULL,
  `customer_pass` varchar(150) NOT NULL,
  `customer_country` varchar(30) NOT NULL,
  `customer_city` varchar(30) NOT NULL,
  `customer_contact` varchar(15) NOT NULL,
  `customer_image` varchar(100) DEFAULT NULL,
  `user_role` int NOT NULL,
  `role_id` int DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `pharmaceutical_registration_number` varchar(100) DEFAULT NULL,
  `hospital_name` varchar(255) DEFAULT NULL,
  `hospital_registration_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_email`, `customer_pass`, `customer_country`, `customer_city`, `customer_contact`, `customer_image`, `user_role`, `role_id`, `company_name`, `pharmaceutical_registration_number`, `hospital_name`, `hospital_registration_number`) VALUES
(1, 'Doris Korantemaa', 'doriskorantemaa@gmail.com', '$2y$10$JwoAGvowdMkgyf5VGaYS4unqEZNsa6SaosripTcLjurk49V7Nidy.', 'GH', 'Tamale', '0559950155', NULL, 1, 1, NULL, NULL, NULL, NULL),
(2, 'Ashaka Praprara', 'ashaka@gmail.com', '$2y$10$bOUAVyJKIZmb3BfWUaMDg.zY5MFPlLNDhHGsB.fDK7bwprvR/x3aW', 'GH', 'Accra', '0202973025', NULL, 1, 1, NULL, NULL, NULL, NULL),
(3, 'kwaku Yeboah', 'yeboah@gmail.com', '$2y$10$53eLOlQ1OE5JlID7rjEglulHCwmbtPPtDk8AMZfhbxn7QJY34cT4i', 'GH', 'Tamale', '0559650438', NULL, 2, 2, NULL, NULL, NULL, NULL),
(4, 'Doris Korantemaa', 'doris@gmail.com', '$2y$10$hdErSj9od794XPHSHdX0.OhU6ijuFYbRHX3kB0grsVToPZb10vIqe', 'GH', 'Nkawkaw', '0245786890', NULL, 2, 2, NULL, NULL, NULL, NULL),
(5, 'Alex Owoahene', 'kina@gmail.com', '$2y$10$uukC3HOan6CtO065vBSIxuyVJkaBl8juvYYnW5VONDgtdIrPuSoca', 'GH', 'Madina Accra', '0556789765', NULL, 1, 1, 'Kinapharma Ltd', 'GHS-FDA-DR-2025-00417', NULL, NULL),
(6, 'Dr Akosua Nkunim', 'akosua@gmail.com', '$2y$10$U/kt5XIHcGfchsSxSotfL.NxWAnlzu0AD/fDE8eBLgXcyJJIZiWd.', 'GH', 'Madina, Accra', '0207867890', NULL, 3, 3, NULL, NULL, 'Albany Clinic', 'GHS-HR-2025-08341'),
(9, 'Blessing Yeboah', 'ernest@gmail.com', '$2y$10$GleztDneNp/fQHA8gZtNl.Z4aF2/kVltltrW1W6KtAD1dLibCWjPG', 'GH', 'Adenta, Accra', '0547962861', NULL, 1, 1, 'Ernest Chemist Ltd', 'GHS-FDA-DR-2024-00419', NULL, NULL),
(10, 'Obenewaa Clara', 'obenewaa@gmail.com', '$2y$10$XdM/Z5.T79/ya1tj.GUIPenbDulWgrEaSsZmLjkGzo52n42RmFHnO', 'GH', 'Dodowa', '0245875609', NULL, 2, 2, NULL, NULL, NULL, NULL),
(11, 'Rose Biamah Ampadu', 'rose@gmail.com', '$2y$10$tTVOtQRdxaTSWc/xxOwxJOStTx2QCIAmgrGTuroVIHfWYgUFOvd0G', 'GH', 'Lashibi, Tema', '0558923654', NULL, 2, 2, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_role_backup`
--

CREATE TABLE `customer_role_backup` (
  `customer_id` int NOT NULL DEFAULT '0',
  `customer_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `customer_email` varchar(50) CHARACTER SET latin1 NOT NULL,
  `customer_pass` varchar(150) CHARACTER SET latin1 NOT NULL,
  `customer_country` varchar(30) CHARACTER SET latin1 NOT NULL,
  `customer_city` varchar(30) CHARACTER SET latin1 NOT NULL,
  `customer_contact` varchar(15) CHARACTER SET latin1 NOT NULL,
  `customer_image` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `user_role` int NOT NULL,
  `role_id` int DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `pharmaceutical_registration_number` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `hospital_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `hospital_registration_number` varchar(100) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_role_backup`
--

INSERT INTO `customer_role_backup` (`customer_id`, `customer_name`, `customer_email`, `customer_pass`, `customer_country`, `customer_city`, `customer_contact`, `customer_image`, `user_role`, `role_id`, `company_name`, `pharmaceutical_registration_number`, `hospital_name`, `hospital_registration_number`) VALUES
(1, 'Doris Korantemaa', 'doriskorantemaa@gmail.com', '$2y$10$JwoAGvowdMkgyf5VGaYS4unqEZNsa6SaosripTcLjurk49V7Nidy.', 'GH', 'Tamale', '0559950155', NULL, 1, 1, NULL, NULL, NULL, NULL),
(2, 'Ashaka Praprara', 'ashaka@gmail.com', '$2y$10$bOUAVyJKIZmb3BfWUaMDg.zY5MFPlLNDhHGsB.fDK7bwprvR/x3aW', 'GH', 'Accra', '0202973025', NULL, 1, 1, NULL, NULL, NULL, NULL),
(3, 'kwaku Yeboah', 'yeboah@gmail.com', '$2y$10$53eLOlQ1OE5JlID7rjEglulHCwmbtPPtDk8AMZfhbxn7QJY34cT4i', 'GH', 'Tamale', '0559650438', NULL, 2, 2, NULL, NULL, NULL, NULL),
(4, 'Doris Korantemaa', 'doris@gmail.com', '$2y$10$hdErSj9od794XPHSHdX0.OhU6ijuFYbRHX3kB0grsVToPZb10vIqe', 'GH', 'Nkawkaw', '0245786890', NULL, 2, 2, NULL, NULL, NULL, NULL),
(5, 'Alex Owoahene', 'kina@gmail.com', '$2y$10$uukC3HOan6CtO065vBSIxuyVJkaBl8juvYYnW5VONDgtdIrPuSoca', 'GH', 'Madina Accra', '0556789765', NULL, 3, 3, 'Kinapharma Ltd', 'GHS-FDA-DR-2025-00417', NULL, NULL),
(6, 'Dr Akosua Nkunim', 'akosua@gmail.com', '$2y$10$U/kt5XIHcGfchsSxSotfL.NxWAnlzu0AD/fDE8eBLgXcyJJIZiWd.', 'GH', 'Madina, Accra', '0207867890', NULL, 4, 4, NULL, NULL, 'Albany Clinic', 'GHS-HR-2025-08341');

-- --------------------------------------------------------

--
-- Table structure for table `customer_specializations`
--

CREATE TABLE `customer_specializations` (
  `customer_id` int NOT NULL,
  `specialization_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_specializations`
--

INSERT INTO `customer_specializations` (`customer_id`, `specialization_id`) VALUES
(6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE `orderdetails` (
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`order_id`, `product_id`, `qty`, `price`) VALUES
(1, 9, 1, 0.00),
(1, 11, 2, 0.00),
(2, 9, 1, 0.00),
(2, 11, 2, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `invoice_no` varchar(255) DEFAULT NULL,
  `order_date` date NOT NULL,
  `order_status` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `invoice_no`, `order_date`, `order_status`, `total_amount`) VALUES
(1, 11, 'INV-20251127-06D3B8', '2025-11-27', 'Paid', 0.00),
(2, 11, 'INV-20251127-D675B4', '2025-11-27', 'Paid', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int NOT NULL,
  `order_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'GHS',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_status` varchar(50) DEFAULT 'Success',
  `payment_method` varchar(50) DEFAULT NULL COMMENT 'Payment method: paystack, cash, bank_transfer,etc.',
  `transaction_ref` varchar(100) DEFAULT NULL COMMENT 'Paystack transaction reference',
  `authorization_code` varchar(100) DEFAULT NULL COMMENT 'Authorization code from payment gateway',
  `payment_channel` varchar(50) DEFAULT NULL COMMENT 'Payment channel: card, mobile_money, etc.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `physician_bookings`
--

CREATE TABLE `physician_bookings` (
  `booking_id` int NOT NULL,
  `physician_id` int NOT NULL,
  `patient_id` int DEFAULT NULL,
  `appointment_datetime` datetime DEFAULT NULL,
  `reason_text` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_conditions` text COLLATE utf8mb4_unicode_ci COMMENT 'Patient health conditions',
  `status` enum('scheduled','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'scheduled',
  `zoom_meeting_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_join_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_start_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zoom_created_at` timestamp NULL DEFAULT NULL,
  `zoom_status` enum('pending','created','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `physician_bookings`
--

INSERT INTO `physician_bookings` (`booking_id`, `physician_id`, `patient_id`, `appointment_datetime`, `reason_text`, `health_conditions`, `status`, `zoom_meeting_id`, `zoom_join_url`, `zoom_start_url`, `zoom_password`, `zoom_created_at`, `zoom_status`, `created_at`) VALUES
(19, 6, 11, '2025-12-02 11:00:00', '', '', 'scheduled', '86311562311', 'https://us05web.zoom.us/j/86311562311?pwd=ubYLi8SEdiU7L2M5cLcxBBRrK4RNNH.1', 'https://us05web.zoom.us/s/86311562311?zak=eyJ0eXAiOiJKV1QiLCJzdiI6IjAwMDAwMiIsInptX3NrbSI6InptX28ybSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJ3ZWIiLCJjbHQiOjAsIm1udW0iOiI4NjMxMTU2MjMxMSIsImF1ZCI6ImNsaWVudHNtIiwidWlkIjoiWGo2UFNIcWVSdU82TXNnSFZkYkNodyIsInppZCI6IjY1NDZiZGQxNDBjNTRiNWJhM2MxYjgxMjIzYjRkMjM3Iiwic2siOiIwIiwic3R5IjoxLCJ3Y2QiOiJ1czA1IiwiZXhwIjoxNzY0MjU1NDg4LCJpYXQiOjE3NjQyNDgyODgsImFpZCI6IkVUa2E4RnRjUzhLc1NjWk4zRU9ISnciLCJjaWQiOiIifQ.PJZ8YDtGFKlL0-0n1moNmCbHTb55q-7BWMrZzNQZZIw', 'Euki3EPIYK', '2025-11-27 12:58:08', 'created', '2025-11-27 12:58:07'),
(20, 6, 11, '2025-11-27 13:00:00', 'Health Conditions:\nallergies\n\nAdditional Notes:\nheadache', 'allergies', 'scheduled', '85090293758', 'https://us05web.zoom.us/j/85090293758?pwd=AqMw57WHr9WHH4KHDMuFZkPCeAHvqa.1', 'https://us05web.zoom.us/s/85090293758?zak=eyJ0eXAiOiJKV1QiLCJzdiI6IjAwMDAwMiIsInptX3NrbSI6InptX28ybSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJ3ZWIiLCJjbHQiOjAsIm1udW0iOiI4NTA5MDI5Mzc1OCIsImF1ZCI6ImNsaWVudHNtIiwidWlkIjoiWGo2UFNIcWVSdU82TXNnSFZkYkNodyIsInppZCI6IjUwZjdkNTc4YjQ2YTQyZGRhZDIwZGViNjBjMjQ2NDk3Iiwic2siOiIwIiwic3R5IjoxLCJ3Y2QiOiJ1czA1IiwiZXhwIjoxNzY0MjU1NTc4LCJpYXQiOjE3NjQyNDgzNzgsImFpZCI6IkVUa2E4RnRjUzhLc1NjWk4zRU9ISnciLCJjaWQiOiIifQ.uMFGTU1JFAp0WJkiv0epUP5Pi3MieXlOmsaI3OjMuzc', 'PQqxjoO67/', '2025-11-27 12:59:38', 'created', '2025-11-27 12:59:36');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_cat` int NOT NULL,
  `product_brand` int NOT NULL,
  `product_title` varchar(200) NOT NULL,
  `product_price` double NOT NULL,
  `product_desc` varchar(500) DEFAULT NULL,
  `product_image` varchar(100) DEFAULT NULL,
  `product_keywords` varchar(100) DEFAULT NULL,
  `created_by` int NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_cat`, `product_brand`, `product_title`, `product_price`, `product_desc`, `product_image`, `product_keywords`, `created_by`, `updated_at`) VALUES
(2, 7, 10, 'Osteocare', 240, 'Food supplements for bones suitable for patients of all ages', 'uploads/u2/p2/1761422446_osteocare.jpg', 'supplement', 2, '2025-10-25 20:00:46'),
(8, 8, 12, 'Amoxicilin', 10.59, 'Treats bacterial infections, kills bacteria by preventing them from forming protective cell walls.', 'uploads/u2/p8/1761504884_amoxicillin.jpg', 'antibiotic', 2, '2025-10-26 18:54:44'),
(9, 4, 10, 'Paracetamol', 9.74, 'Relieves mild to moderate pain and reduces fever', 'uploads/u2/p9/1761521744_paracetamol.jpg', 'pain reliever', 2, '2025-10-26 23:35:44'),
(11, 25, 39, 'Heat Pad', 50.92, 'An electric heat pad is a flexible device that plugs into an electrical outlet and produces heat via internal resistive heating elements. It’s designed for application to parts of the body (back, shoulders, abdomen, legs, etc.) to provide warmth and therapeutic relief.', 'uploads/u2/p11/1762994116_heating_pad.jpg', 'pain reliever', 2, '2025-11-13 00:35:16'),
(12, 26, 52, 'Nugel-O', 10.57, 'Nugel-O is a proton-pump inhibitor (PPI) that reduces the amount of acid produced in the stomach. It’s used to treat conditions such as gastroesophageal reflux disease (GERD), stomach and duodenal ulcers, heartburn and indigestion', 'uploads/u2/p12/1762994968_Nugel_O_Susp_200ml.jpg', 'digestive', 2, '2025-11-13 00:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrator', '2025-11-16 14:33:53'),
(2, 'customer', 'Customer', '2025-11-16 14:33:53'),
(3, 'pharmaceutical_company', 'Pharmaceutical Company', '2025-11-16 14:33:53'),
(4, 'physician', 'Physician', '2025-11-16 14:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `specializations`
--

CREATE TABLE `specializations` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `specializations`
--

INSERT INTO `specializations` (`id`, `name`, `created_at`) VALUES
(1, 'Cardiology', '2025-11-16 14:33:53'),
(2, 'General Practice', '2025-11-16 14:33:53'),
(3, 'Internal Medicine', '2025-11-16 14:33:53'),
(4, 'Pediatrics', '2025-11-16 14:33:53'),
(5, 'Dermatology', '2025-11-16 14:33:53'),
(6, 'Psychiatry', '2025-11-16 14:33:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_notifications`
--
ALTER TABLE `booking_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `physician_id` (`physician_id`),
  ADD KEY `idx_patient_id` (`patient_id`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD UNIQUE KEY `uniq_customer_product` (`c_id`,`p_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`),
  ADD UNIQUE KEY `cat_name` (`cat_name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customer_email` (`customer_email`),
  ADD UNIQUE KEY `idx_customer_pharma_reg` (`pharmaceutical_registration_number`),
  ADD UNIQUE KEY `idx_customer_hospital_reg` (`hospital_registration_number`),
  ADD KEY `fk_customer_role` (`role_id`);

--
-- Indexes for table `customer_specializations`
--
ALTER TABLE `customer_specializations`
  ADD PRIMARY KEY (`customer_id`,`specialization_id`),
  ADD KEY `specialization_id` (`specialization_id`);

--
-- Indexes for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `physician_bookings`
--
ALTER TABLE `physician_bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `physician_id` (`physician_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `zoom_meeting_id` (`zoom_meeting_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `product_cat` (`product_cat`),
  ADD KEY `product_brand` (`product_brand`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `specializations`
--
ALTER TABLE `specializations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_notifications`
--
ALTER TABLE `booking_notifications`
  MODIFY `notification_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `physician_bookings`
--
ALTER TABLE `physician_bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `specializations`
--
ALTER TABLE `specializations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_notifications`
--
ALTER TABLE `booking_notifications`
  ADD CONSTRAINT `booking_notifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `physician_bookings` (`booking_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_notifications_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_notifications_ibfk_3` FOREIGN KEY (`physician_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`c_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `fk_customer_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `customer_specializations`
--
ALTER TABLE `customer_specializations`
  ADD CONSTRAINT `customer_specializations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_specializations_ibfk_2` FOREIGN KEY (`specialization_id`) REFERENCES `specializations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `physician_bookings`
--
ALTER TABLE `physician_bookings`
  ADD CONSTRAINT `fk_patient` FOREIGN KEY (`patient_id`) REFERENCES `customer` (`customer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_physician` FOREIGN KEY (`physician_id`) REFERENCES `customer` (`customer_id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`product_cat`) REFERENCES `categories` (`cat_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`product_brand`) REFERENCES `brands` (`brand_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
