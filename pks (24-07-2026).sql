-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2026 at 09:25 AM
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
-- Database: `pks`
--

-- --------------------------------------------------------

--
-- Table structure for table `alternate_units`
--

CREATE TABLE `alternate_units` (
  `alter_unit_id` bigint(20) UNSIGNED NOT NULL,
  `alter_unit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `alternate_units`
--

INSERT INTO `alternate_units` (`alter_unit_id`, `alter_unit`, `created_at`, `updated_at`) VALUES
(1, 'KGs', '2026-07-07 05:36:54', '2026-07-07 05:38:02');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `status`, `created_at`, `updated_at`) VALUES
(10, 'Dindigul', 1, '2026-07-06 09:39:04', '2026-07-06 09:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `branch_prices`
--

CREATE TABLE `branch_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `business` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `gst_number` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `added_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `customer_code`, `name`, `email`, `mobile_number`, `password`, `branch_id`, `business`, `address`, `location`, `gst_number`, `status`, `added_by`, `created_at`, `updated_at`) VALUES
(7, 'd8afae08-d111-450f-893e-0c2ea42cf3de', '1', 'demo', 'demo@gmail.com', '8098765432', '$2y$12$K7m7yjEe1FdNSyHJhknYyumgebPtSoXQE5Er4e5uyfmGOzoX/XDXO', 10, NULL, NULL, NULL, NULL, 1, 20, '2026-07-22 10:07:19', '2026-07-22 10:07:19'),
(8, '94abd622-b4dc-45d6-9ab4-0e028f5674ca', '2', 'demo 2', 'demo2@gmail.com', '7098765432', '$2y$12$/GMRjQNzZU513QQN9Jy/9.kt5/9DwPAjoMpg1gFlzjNhu0LgJuLPu', 10, NULL, NULL, NULL, NULL, 1, 20, '2026-07-22 10:40:54', '2026-07-22 10:40:54');

-- --------------------------------------------------------

--
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` varchar(255) NOT NULL,
  `dealer_code` varchar(20) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `dealer_id`, `dealer_code`, `branch_id`, `name`, `business_name`, `contact_number`, `address`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '12f783a8-fef3-4713-a9ca-6672059c1e15', '1', 10, 'pradeep', 'ac', '9655209482', 'chennai', 1, 20, '2026-07-16 05:21:47', '2026-07-16 10:20:28'),
(2, '27c5202f-df2c-4023-9f87-4f66329a480b', '2', 10, 'surya', 'guru', '9797949454', 'madurai', 1, 20, '2026-07-16 10:20:14', '2026-07-16 10:20:14'),
(3, '0ecee990-7836-4178-9457-356bba346c04', '3', 10, 'siva', 'dc', '8554212225', 'delhi', 1, 20, '2026-07-17 04:39:33', '2026-07-17 04:39:33'),
(5, 'bf34c204-7609-4657-ab4d-d05e72947a15', '4', 10, 'guru', 'tv', '9464649484', 'chennai', 1, 20, '2026-07-17 11:47:10', '2026-07-17 11:47:10'),
(6, 'f072e745-53b6-46aa-a8f5-72577e776112', '5', 10, 'santhosh', 'bike', '8989595655', 'chennai', 1, 20, '2026-07-17 12:15:03', '2026-07-17 12:15:14'),
(8, '3c00df25-6dfd-45bb-82a9-5999758e8156', '6', 10, 'Suresh', 'phone sales', '9499494949', 'chennai', 1, 20, '2026-07-20 14:07:50', '2026-07-20 14:07:50');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gatepasses`
--

CREATE TABLE `gatepasses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gatepass_number` varchar(255) NOT NULL,
  `gatepass_type` varchar(255) NOT NULL DEFAULT 'outward',
  `movement_type` varchar(255) NOT NULL DEFAULT 'sale',
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transporter_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `driver_number` varchar(255) DEFAULT NULL,
  `gatepass_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `gatepass_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gatepass_images`)),
  `remarks` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gatepasses`
--

INSERT INTO `gatepasses` (`id`, `gatepass_number`, `gatepass_type`, `movement_type`, `sale_id`, `purchase_id`, `branch_id`, `dealer_id`, `customer_id`, `transporter_id`, `vehicle_id`, `driver_name`, `driver_number`, `gatepass_date`, `gatepass_images`, `remarks`, `status`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'GP-20260724-TWMMR', 'outward', 'sale', 46, NULL, 10, 2, 7, 5, 15, 'kumar', '1233134346', '2026-07-24 01:41:21', '[]', 'Gatepass created for delivery', 'pending', 20, NULL, '2026-07-24 01:41:21', '2026-07-24 01:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `gatepass_details`
--

CREATE TABLE `gatepass_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gatepass_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `lot_number` varchar(255) DEFAULT NULL,
  `unit_value` varchar(255) NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `alternate_unit_value` varchar(255) DEFAULT NULL,
  `alternate_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gatepass_details`
--

INSERT INTO `gatepass_details` (`id`, `gatepass_id`, `stock_id`, `lot_number`, `unit_value`, `unit_id`, `alternate_unit_value`, `alternate_unit_id`, `remarks`, `created_at`, `updated_at`) VALUES
(3, 2, 12, NULL, 'bags', 1, 'kgs', 1, NULL, '2026-07-24 01:41:21', '2026-07-24 01:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_00_000000_create_branch_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_06_30_050028_create_personal_access_tokens_table', 1),
(6, '2026_06_30_050053_create_stocks_table', 1),
(8, '2026_07_21_000000_create_gatepasses_table', 2),
(9, '2026_07_21_000001_create_gatepass_details_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(16, 'App\\Models\\User', 21, 'auth_token', '5490d48824eaeff9aebdef4597ce89d9e78b099e902fc7486e141a2fb6754778', '[\"*\"]', '2026-06-30 02:48:01', NULL, '2026-06-30 02:42:28', '2026-06-30 02:48:01'),
(19, 'App\\Models\\User', 22, 'auth_token', '49efc521d7abac6a1bc7b2872dfac857be6cbeffcf799e9aa5857c3823e6f390', '[\"*\"]', NULL, NULL, '2026-07-04 10:37:52', '2026-07-04 10:37:52'),
(21, 'App\\Models\\User', 21, 'auth_token', '97039c7bb0f96b9735c8a1d53c47e6dc19d2aa068985c12db3f12d0ebda61f1a', '[\"*\"]', NULL, NULL, '2026-07-06 05:34:30', '2026-07-06 05:34:30'),
(27, 'App\\Models\\User', 21, 'auth_token', '40cba418875ba28ff3397db5994bc75d20be20a787ac6f7f7f9d8c60fb9d6bcd', '[\"*\"]', '2026-07-06 07:46:02', NULL, '2026-07-06 07:42:26', '2026-07-06 07:46:02'),
(32, 'App\\Models\\User', 21, 'auth_token', '1b48d8369057b7e32942e6cb01d5c6a982ecf1b4cbfed7aa000e0008b34a6279', '[\"*\"]', '2026-07-07 04:42:40', NULL, '2026-07-07 04:38:45', '2026-07-07 04:42:40'),
(83, 'App\\Models\\User', 20, 'auth_token', '0a21b4050ba1de6bca20a3bfebbff62b2238fa59a940541bec22788ba780f8e8', '[\"*\"]', '2026-07-24 05:46:09', NULL, '2026-07-23 09:40:41', '2026-07-24 05:46:09'),
(84, 'App\\Models\\Customer', 7, 'auth_token', '04d860f618c6a0cee0137a36cfc458f56fbd42d06a014e5bcb643d23f498e460', '[\"*\"]', '2026-07-23 09:42:16', NULL, '2026-07-23 09:41:31', '2026-07-23 09:42:16'),
(85, 'App\\Models\\User', 20, 'auth_token', 'ded29a3d584922f3477bfc713970443d59a6c41e79da7604d57502b49eec4c55', '[\"*\"]', '2026-07-23 10:07:18', NULL, '2026-07-23 10:01:59', '2026-07-23 10:07:18'),
(86, 'App\\Models\\User', 20, 'auth_token', 'fc09c4242868c49641d3364d684ef2ccaf29d88f58c7cb26a381d0585e632c57', '[\"*\"]', '2026-07-24 05:45:39', NULL, '2026-07-24 05:25:19', '2026-07-24 05:45:39'),
(87, 'App\\Models\\User', 20, 'auth_token', 'd3e1f97491e5750922af1dd91b6f795be3fe27decd6132e5e1d33edb52948f56', '[\"*\"]', '2026-07-24 01:41:56', NULL, '2026-07-24 00:30:04', '2026-07-24 01:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `transporter_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `driver_number` varchar(255) NOT NULL,
  `purchase_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`purchase_images`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `purchase_id`, `branch_id`, `dealer_id`, `lot_number`, `transporter_id`, `vehicle_id`, `driver_number`, `purchase_images`, `created_by`, `created_at`, `updated_at`) VALUES
(7, 'd28e308f-504d-4b0f-b35e-f1242531b9f3', 10, 5, '66464', 8, 15, '777777778', '[\"purchases\\/6a5e12a86ced9_1784550056.jpg\",\"purchases\\/6a5e12a86cfbe_1784550056.jpg\"]', 20, '2026-07-20 12:20:56', '2026-07-20 12:20:56'),
(8, '5699295b-18a5-4962-927f-646e8926fba1', 10, 8, '6464', 8, 18, '7766655444', '[\"purchases\\/6a5e2c3e4c464_1784556606.jpg\",\"purchases\\/6a5e2c3e4c4cc_1784556606.jpg\"]', 20, '2026-07-20 14:10:06', '2026-07-20 14:10:06'),
(9, '856de671-b34f-4fe7-9eca-eea9f08c13c8', 10, 3, '2528', 8, 18, '6786434477', '[\"purchases\\/6a5ef1cc84947_1784607180.jpg\",\"purchases\\/6a5ef1cc84b23_1784607180.jpg\"]', 20, '2026-07-21 04:13:00', '2026-07-21 04:13:00'),
(10, '4015f323-3e8b-4516-87bc-9d0ddf21516a', 10, 8, '5255', 8, 18, '5677866798', '[\"purchases\\/6a5ef22042d07_1784607264.jpg\",\"purchases\\/6a5ef22042d84_1784607264.jpg\"]', 20, '2026-07-21 04:14:24', '2026-07-21 04:14:24'),
(13, 'd0ee43be-10d0-4239-8317-631279e990e3', 10, 2, '1222', 5, 18, '6678998655', '[\"purchases\\/6a5efd0c30eeb_1784610060.jpg\",\"purchases\\/6a5efd0c31059_1784610060.jpg\"]', 20, '2026-07-21 05:01:00', '2026-07-21 05:01:00'),
(14, '7679a5f1-8ee8-4ea5-958f-d8a6ece50f3b', 10, 2, '31331', 8, 18, '778899899', '[\"purchases\\/6a5f02600b776_1784611424.jpg\",\"purchases\\/6a5f02600b7ea_1784611424.jpg\"]', 20, '2026-07-21 05:23:44', '2026-07-21 05:23:44'),
(15, 'a154d43e-d208-4ecd-8c36-fc20757da8d5', 10, 1, '6464', 5, 18, '8888999999', '[\"purchases\\/6a5f34b0c8261_1784624304.jpg\",\"purchases\\/6a5f34b0c84dd_1784624304.jpg\"]', 20, '2026-07-21 08:58:24', '2026-07-21 08:58:24'),
(16, '2c18cab6-31c7-4e18-872e-ee147d32c7f7', 10, 6, '2000', 5, 16, '566727222', '[\"purchases\\/6a5f38738f1ce_1784625267.jpg\",\"purchases\\/6a5f38738f61c_1784625267.jpg\"]', 20, '2026-07-21 09:14:27', '2026-07-21 09:14:27'),
(18, 'b5a74dc4-4cb8-43ea-b157-93acde2bbce2', 10, 3, '11111', 8, 18, '788888999', '[\"purchases\\/6a5f467f2ee31_1784628863.jpg\",\"purchases\\/6a5f467f2ee96_1784628863.jpg\"]', 20, '2026-07-21 10:14:23', '2026-07-21 10:14:23'),
(20, '038f7728-c5cc-4a9a-953f-0ddd0edffaf5', 10, 6, '2000', 5, 16, '66666666666', '[\"purchases\\/6a5f4f86d68bc_1784631174.jpg\",\"purchases\\/6a5f4f86d6923_1784631174.jpg\"]', 20, '2026-07-21 10:52:54', '2026-07-21 10:52:54'),
(23, '0d61e96a-c681-403a-b14b-2de82cfd247e', 10, 2, '3333', 5, 13, '7788998282', '[\"purchases\\/6a61d26712858_1784795751.jpg\",\"purchases\\/6a61d26712aa6_1784795751.jpg\"]', 20, '2026-07-23 05:40:59', '2026-07-23 08:35:51'),
(28, '7af9b657-bb79-4dda-9678-2ab3a066561a', 10, 6, '55855', 8, 16, '567776655', '[\"purchases\\/6a61ed5a76f85_1784802650.jpg\",\"purchases\\/6a61ed5a773ac_1784802650.jpg\"]', 20, '2026-07-23 10:30:50', '2026-07-23 10:30:50'),
(29, 'eba461ee-3642-4336-833a-247e582acca2', 10, 6, '24542', 8, 16, '7377373737', '[\"purchases\\/6a62066e92a9f_1784809070.jpg\",\"purchases\\/6a62066e92b3e_1784809070.jpg\"]', 20, '2026-07-23 12:17:50', '2026-07-23 12:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `stock_name` varchar(255) NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `unit_value` decimal(15,2) NOT NULL,
  `unit_type` varchar(255) NOT NULL,
  `alter_unit_value` decimal(15,2) NOT NULL,
  `alter_unit_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `brand_name`, `stock_name`, `lot_number`, `unit_value`, `unit_type`, `alter_unit_value`, `alter_unit_type`, `created_at`, `updated_at`) VALUES
(10, 7, 'tata', 'tv', '66464', 1.00, 'Bags', 50.00, 'KGs', '2026-07-20 12:20:56', '2026-07-20 12:20:56'),
(11, 8, 'redmi', 'nortce5', '6464', 2.00, 'Bags', 100.00, 'KGs', '2026-07-20 14:10:06', '2026-07-20 14:10:06'),
(12, 9, 'tata', 'tv', '2528', 2.00, 'Bags', 100.00, 'KGs', '2026-07-21 04:13:00', '2026-07-21 04:13:00'),
(13, 10, 'tata', 'tv', '5255', 5.00, 'Bags', 250.00, 'KGs', '2026-07-21 04:14:24', '2026-07-21 04:14:24'),
(16, 13, 'tata', 'tv', '1222', 10.00, 'Bags', 500.00, 'KGs', '2026-07-21 05:01:00', '2026-07-21 05:01:00'),
(17, 14, 'dell', 'laptop', '31331', 1.00, 'Bags', 50.00, 'KGs', '2026-07-21 05:23:44', '2026-07-21 05:23:44'),
(18, 15, 'tata', 'tv', '6464', 1.00, 'Bags', 50.00, 'KGs', '2026-07-21 08:58:24', '2026-07-21 08:58:24'),
(19, 16, 'tata', 'tv', '2000', 2.00, 'Bags', 100.00, 'KGs', '2026-07-21 09:14:27', '2026-07-21 09:14:27'),
(21, 18, 'Dell', 'laptop', '000000', 1.00, 'Bags', 50.00, 'KGs', '2026-07-21 10:14:23', '2026-07-21 10:14:23'),
(23, 20, 'tata', 'tv', '2000', 2.00, 'Bags', 100.00, 'KGs', '2026-07-21 10:52:54', '2026-07-21 10:52:54'),
(33, 23, 'Dell', 'Laptop', '3333', 3.00, 'Bags', 150.00, 'KGs', '2026-07-23 08:35:51', '2026-07-23 08:35:51'),
(34, 28, 'dell', 'laptop', '55855', 2.00, 'Bags', 100.00, 'KGs', '2026-07-23 10:30:50', '2026-07-23 10:30:50'),
(35, 28, 'tata', 'tv', '55855', 1.00, 'Bags', 50.00, 'KGs', '2026-07-23 10:30:50', '2026-07-23 10:30:50'),
(36, 28, 'redmi', 'nortce5', '55855', 5.00, 'Bags', 250.00, 'KGs', '2026-07-23 10:30:50', '2026-07-23 10:30:50'),
(37, 29, 'Dell', 'laptop', '24542', 1.00, 'Bags', 50.00, 'KGs', '2026-07-23 12:17:50', '2026-07-23 12:17:50'),
(38, 29, 'redmi', 'nortce5', '24542', 1.00, 'Bags', 50.00, 'KGs', '2026-07-23 12:17:50', '2026-07-23 12:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `driver_name` varchar(255) NOT NULL,
  `driver_number` varchar(255) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sale_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`sale_images`)),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `sale_id`, `branch_id`, `dealer_id`, `vehicle_id`, `invoice_number`, `driver_name`, `driver_number`, `sale_date`, `sale_images`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '8230775c-3555-4deb-af0b-fd11ae980951', 10, 6, 18, '4566', 'surya', '9484545434', '2026-07-23 06:06:52', '[\"sales\\/6a5f5efd1b2b5_1784635133.jpg\",\"sales\\/6a5f5efd1b326_1784635133.jpg\"]', 20, '2026-07-23 06:06:52', '2026-07-20 11:24:19', '2026-07-23 06:06:52'),
(45, '7622d015-e9cb-4b92-a642-10e0ebbb7e62', 10, 2, 16, '4555', 'kumar', '0000000000', '2026-07-23 07:40:03', '[\"sales\\/6a61b26f8a840_1784787567.jpg\",\"sales\\/6a61b26f8a8c8_1784787567.jpg\"]', 20, '2026-07-23 07:40:03', '2026-07-23 05:53:51', '2026-07-23 07:40:03'),
(46, 'db619ae5-44d3-4c02-a911-406bfe4d4045', 10, 2, 15, '66677', 'Kumar', '9464644444', '2026-07-23 07:40:10', '[\"sales\\/6a61acde57fe3_1784786142.jpg\",\"sales\\/6a61acde58063_1784786142.jpg\"]', 20, '2026-07-23 07:40:10', '2026-07-23 05:55:42', '2026-07-23 07:40:10'),
(47, '88a9db0f-c199-4b5f-8d2e-05a617453d36', 10, 3, 17, '6777', 'kumar', '1233134346', '2026-07-23 07:40:06', '[\"sales\\/6a61c3a66357c_1784791974.jpg\",\"sales\\/6a61c3a6635f2_1784791974.jpg\"]', 20, '2026-07-23 07:40:06', '2026-07-23 07:22:37', '2026-07-23 07:40:06'),
(48, '2f2bcb8a-0a35-48a1-a707-4ae3153eabd4', 10, 2, 16, '5667', 'naveen', '9494949449', '2026-07-23 00:00:00', '[\"sales\\/6a61c5c31a20d_1784792515.jpg\",\"sales\\/6a61c5c31a294_1784792515.jpg\"]', 20, NULL, '2026-07-23 07:41:55', '2026-07-23 07:41:55'),
(51, '4731eb4a-f6de-44bf-b580-ead9910e2603', 10, 2, 18, '33222', 'naveen', '9494949496', '2026-07-24 05:45:35', '[\"sales\\/6a62fbd4a9d4e_1784871892.jpg\",\"sales\\/6a62fbd4a9ddf_1784871892.jpg\"]', 20, '2026-07-24 05:45:35', '2026-07-24 05:42:31', '2026-07-24 05:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

CREATE TABLE `sale_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `lot_number` varchar(255) NOT NULL,
  `unit_value` decimal(15,2) NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `alternate_unit_value` varchar(255) DEFAULT NULL,
  `alternate_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sale_id`, `stock_id`, `lot_number`, `unit_value`, `unit_id`, `alternate_unit_value`, `alternate_unit_id`, `created_at`, `updated_at`) VALUES
(3, 1, 1, '000000', 1.00, 1, NULL, NULL, '2026-07-21 11:58:53', '2026-07-21 11:58:53'),
(5, 46, 12, '6677', 3.00, 1, '150.0', 1, '2026-07-23 05:55:42', '2026-07-23 05:55:42'),
(6, 45, 10, '234', 1.00, 1, '50.0', 1, '2026-07-23 06:19:27', '2026-07-23 06:19:27'),
(9, 47, 12, '677777', 1.00, 1, '50.0', 1, '2026-07-23 07:32:54', '2026-07-23 07:32:54'),
(10, 48, 12, '56677', 19.00, 1, '950.0', 1, '2026-07-23 07:41:55', '2026-07-23 07:41:55'),
(13, 51, 16, '1234', 2.00, 1, '100.0', 1, '2026-07-24 05:44:52', '2026-07-24 05:44:52'),
(14, 51, 12, '1234', 50.00, 1, '2500.0', 1, '2026-07-24 05:44:52', '2026-07-24 05:44:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4g8WBDGubYVHIcT2UwKPgOznaMiejZPxCLjGTFex', NULL, '106.51.27.192', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiem1VcUlSV3I2ckxRTGRSYk9xSWMwd1ZnYWVsWDJ4MUtMVjZoU1dEdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782889250),
('5fVZ3P9Ro9HwqZ1wAoBlluR0nZL7CO4VsCVQsBTl', NULL, '173.211.16.52', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieExUNG1QQkF3RktBSjg1aGlhNldlU0w1NEVSQUNJVFFMY0lCTU5raCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784733288),
('9spav2JSJNcGHMVCilITuGIUdG1Dz9lWr3cvPT4f', NULL, '106.51.26.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWtBS2Q4dThYSWc3V3ptNEJLMkJTdERCNkRPU3NlOFF0cWxMR0R2aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783151850),
('E5qpa3Puq892rgKTTaC5WhhajtaIUKVKtnChmNA2', NULL, '106.51.25.215', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjdnbHE0UUl6UUdQaFAyRk54VVhtMWc3NWNnWWFvbW5MRVZscGVzNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782889158),
('FBuczVqoA3Ml8Hw1V00VO0YkyOKSYWJNJRImPVWQ', NULL, '45.153.159.12', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieW9oeVRUME5McXFKSUtGMnFIT1hOclZxY1VmYzlETHVaZ2VYMmg0WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784568200),
('FNyo1DdrE3mT66hb3GIrWVDhrxFGG5CPV3pNzPGm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUtNRnV1OW9ncUJqbVpNSldsUEZoTks2WDZuVTdtbHMzV2l6MUZQMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782811890),
('h3IbSs8UAq7Pp2nOEu2qY1IwnvArku8KCgr02Ygh', NULL, '106.51.25.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHJZY25BNFhUdWhsb3N1YWtFd3pCYmJ3S3FrUVFCQmR1MGJuNDg1RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783339088),
('MolAzttZMSsrgckRAJcgaySSEsnxiVwkqhtjCOKk', NULL, '23.27.145.64', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmNra0xZbzdGT01wVlBsTDFQdngzQm9MVFhIa0dlam5vVEJNYVN3aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784394612),
('SUzE8L6IcPOl3pkTJaiBFqhEIct5UJYNuXPM9zCV', NULL, '106.51.26.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3A0ZGVCT290MWlqS3ZUUVJjRWJIakV2YzFxTW5QcXRoMzRHWktJOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783141781),
('xJBFm2nqhx1OncvDJwvrot568u7TFesA8oQGBKuh', NULL, '98.84.1.175', 'RecordedFuture Global Inventory Crawler', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidDZDYjQ1R3VraTdIUUtHZHBKdEF5YzAxdTNSYlVWekZyaUF2WXJ1WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784375870),
('xRT2E7BbP8swx5wyb1mubKG8EfblFfvkR7fDUSEK', NULL, '106.51.25.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ09CMkJVSjNVcVpHN1QxUkJvOVFUQTgwNUpKOUJqTnRNblc3Y2ZEdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4vc2VydmVyLWNvbW1hbmRzL2NsZWFyLWNhY2hlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783330916);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` varchar(255) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `stock_name` varchar(255) NOT NULL,
  `lott_number` varchar(255) NOT NULL,
  `units` int(11) DEFAULT NULL,
  `mt` decimal(15,2) DEFAULT NULL,
  `stock_code` varchar(20) NOT NULL,
  `branch_id` bigint(20) NOT NULL,
  `unit_id` bigint(20) NOT NULL,
  `alter_unit_id` bigint(20) NOT NULL,
  `unit_value` varchar(255) NOT NULL,
  `alter_unit_value` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_id`, `brand_name`, `stock_name`, `lott_number`, `units`, `mt`, `stock_code`, `branch_id`, `unit_id`, `alter_unit_id`, `unit_value`, `alter_unit_value`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'cc5b8100-e9b2-4ba2-90cb-7813ac78b480', 'wdc', 'pokm', '1235285', 1, 50.00, 'STOCK_A001', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-16 10:23:22', '2026-07-23 06:06:52'),
(2, '42612181-6c34-4c33-980d-78f78ac24ef2', 'wdc', 'pokm', '1235285', 22, 5.00, '1', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-16 11:05:49', '2026-07-16 11:05:49'),
(3, 'b209acb0-8783-4441-aa8e-b0c4ecd3d0dd', 'wdc', 'pokm', '1235285', 22, 5.00, '2', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-17 06:21:01', '2026-07-17 06:21:01'),
(4, '8f90fbef-226c-4f2f-be36-2f69c8878c42', 'wdc', 'pokm', '1235285', 22, 5.00, '3', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-17 07:06:05', '2026-07-17 11:09:30'),
(5, '7927cb5b-3e98-491a-8c66-d39f188d9bcb', 'wdc', 'pokm', '1235285', 22, 1100.00, '4', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-17 10:16:39', '2026-07-20 05:48:41'),
(10, '880db78f-eaf3-413e-a39e-914282c19301', 'tata', 'tv', '1111', 29, 1450.00, '9', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-20 04:47:22', '2026-07-23 07:40:03'),
(11, 'b5a4f8eb-d3c7-4f02-9fd0-db73cca8680c', 'redmi', 'nortce5', '44455', 5, 250.00, '10', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-20 14:05:35', '2026-07-20 14:09:07'),
(12, '3fc98fe2-1c63-474b-8236-df63193aa364', 'Dell', 'laptop', '1234', 100, 5000.00, '11', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-21 05:22:35', '2026-07-24 05:45:35'),
(14, '85e2021e-3544-4fb9-a411-8409e9572551', 'msk', 'tata', '6667', 10, 500.00, '12', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-23 10:02:53', '2026-07-23 10:02:53'),
(16, '59a6e829-1e65-488a-b2b5-0214f6c643aa', 'oppo', 'phone', '1234', 50, 2500.00, '13', 10, 1, 1, 'Bags', 'KGs', 20, '2026-07-24 05:28:02', '2026-07-24 05:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(15,2) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `movement_type` varchar(255) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `stock_id`, `sale_id`, `quantity`, `unit`, `movement_type`, `transaction_date`, `user_id`, `created_at`, `updated_at`) VALUES
(16, 12, 48, 19.00, 'Bags', 'sale', '2026-07-23 00:00:00', 20, '2026-07-23 07:41:55', '2026-07-23 07:41:55'),
(17, 12, 48, 950.00, 'KGs', 'sale', '2026-07-23 00:00:00', 20, '2026-07-23 07:41:55', '2026-07-23 07:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `transporters`
--

CREATE TABLE `transporters` (
  `transporter_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transporters`
--

INSERT INTO `transporters` (`transporter_id`, `name`, `branch_id`, `created_at`, `updated_at`) VALUES
(5, 'my transports', 10, '2026-07-07 05:02:39', '2026-07-07 05:04:18'),
(8, 'pks transport', 10, '2026-07-07 05:21:38', '2026-07-07 05:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `unit` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit`, `created_at`, `updated_at`) VALUES
(1, 'Bags', '2026-07-07 05:35:10', '2026-07-07 05:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `mobile_number` varchar(255) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `mobile_number`, `branch_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(20, 'testuseree', 'testinguser@gmail.com', NULL, '$2y$12$xzum.J1zfLcrB6z5E3JN2eW2GxdJ4NMbx/A6GRBrT1LQsq94ybQW2', 'admin', '9876543210', NULL, 1, NULL, '2026-06-30 02:40:29', '2026-06-30 02:40:29'),
(21, 'ajis', 'ajis@gmail.com', NULL, '$2y$12$LwK9/E04geGiZ9b6SpeIgeAMoNVbTDTw2Kg8hqUlXeOetAgN8UvUC', 'user', '9489042187', 5, 1, NULL, '2026-06-30 02:40:59', '2026-06-30 02:40:59'),
(22, 'test001', 'test001@gmail.com', NULL, '$2y$12$OpgizbwUZ4G/tmum.K5fKev/KHuCelqoXLVoNEghQ0BplwmkBFGgm', 'user', '8523692581', 5, 1, NULL, '2026-07-04 04:27:59', '2026-07-04 04:27:59');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_type` enum('lorry','local') NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`vehicle_id`, `vehicle_type`, `name`, `status`, `created_at`, `updated_at`) VALUES
(8, 'lorry', 'TN98383', 1, '2026-07-06 06:47:11', '2026-07-06 06:47:11'),
(12, 'lorry', 'tn58ac2019', 1, '2026-07-06 10:41:58', '2026-07-06 10:41:58'),
(13, 'local', 'tn64ac7121', 1, '2026-07-06 10:50:53', '2026-07-06 10:58:54'),
(15, 'lorry', 'TN57NNN', 1, '2026-07-06 10:58:27', '2026-07-06 11:06:15'),
(16, 'lorry', 'TN56NNN', 1, '2026-07-06 11:04:38', '2026-07-06 11:06:05'),
(17, 'local', 'NNNLR', 1, '2026-07-06 11:06:58', '2026-07-06 11:07:06'),
(18, 'lorry', 'TN57BP0206', 1, '2026-07-09 11:01:50', '2026-07-09 11:01:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alternate_units`
--
ALTER TABLE `alternate_units`
  ADD PRIMARY KEY (`alter_unit_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `branch_prices`
--
ALTER TABLE `branch_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_prices_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_id_unique` (`customer_id`),
  ADD UNIQUE KEY `customers_customer_code_unique` (`customer_code`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD UNIQUE KEY `customers_mobile_number_unique` (`mobile_number`),
  ADD KEY `customers_branch_id_foreign` (`branch_id`),
  ADD KEY `customers_added_by_foreign` (`added_by`);

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dealers_dealer_id_unique` (`dealer_id`),
  ADD UNIQUE KEY `dealers_dealer_code_unique` (`dealer_code`),
  ADD KEY `dealers_branch_id_foreign` (`branch_id`),
  ADD KEY `dealers_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gatepasses`
--
ALTER TABLE `gatepasses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gatepasses_gatepass_number_unique` (`gatepass_number`),
  ADD KEY `gatepasses_sale_id_foreign` (`sale_id`),
  ADD KEY `gatepasses_purchase_id_foreign` (`purchase_id`),
  ADD KEY `gatepasses_branch_id_foreign` (`branch_id`),
  ADD KEY `gatepasses_dealer_id_foreign` (`dealer_id`),
  ADD KEY `gatepasses_customer_id_foreign` (`customer_id`),
  ADD KEY `gatepasses_transporter_id_foreign` (`transporter_id`),
  ADD KEY `gatepasses_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `gatepasses_created_by_foreign` (`created_by`);

--
-- Indexes for table `gatepass_details`
--
ALTER TABLE `gatepass_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gatepass_details_gatepass_id_foreign` (`gatepass_id`),
  ADD KEY `gatepass_details_stock_id_foreign` (`stock_id`),
  ADD KEY `gatepass_details_unit_id_foreign` (`unit_id`),
  ADD KEY `gatepass_details_alternate_unit_id_foreign` (`alternate_unit_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_purchase_id_unique` (`purchase_id`),
  ADD KEY `purchases_branch_id_foreign` (`branch_id`),
  ADD KEY `purchases_dealer_id_foreign` (`dealer_id`),
  ADD KEY `purchases_transporter_id_foreign` (`transporter_id`),
  ADD KEY `purchases_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `purchases_created_by_foreign` (`created_by`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_details_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_sale_id_unique` (`sale_id`),
  ADD KEY `sales_branch_id_foreign` (`branch_id`),
  ADD KEY `sales_dealer_id_foreign` (`dealer_id`),
  ADD KEY `sales_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `sales_created_by_foreign` (`created_by`);

--
-- Indexes for table `sale_details`
--
ALTER TABLE `sale_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_details_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_details_stock_id_foreign` (`stock_id`),
  ADD KEY `sale_details_unit_id_foreign` (`unit_id`),
  ADD KEY `sale_details_alternate_unit_id_foreign` (`alternate_unit_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_stock_id_unique` (`stock_id`),
  ADD UNIQUE KEY `stocks_stock_code_unique` (`stock_code`),
  ADD KEY `stocks_created_by_foreign` (`created_by`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `unit_id` (`unit_id`,`alter_unit_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_stock_id_foreign` (`stock_id`),
  ADD KEY `stock_movements_sale_id_foreign` (`sale_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`);

--
-- Indexes for table `transporters`
--
ALTER TABLE `transporters`
  ADD PRIMARY KEY (`transporter_id`),
  ADD KEY `transporters_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alternate_units`
--
ALTER TABLE `alternate_units`
  MODIFY `alter_unit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `branch_prices`
--
ALTER TABLE `branch_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gatepasses`
--
ALTER TABLE `gatepasses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gatepass_details`
--
ALTER TABLE `gatepass_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `transporters`
--
ALTER TABLE `transporters`
  MODIFY `transporter_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;

--
-- Constraints for table `dealers`
--
ALTER TABLE `dealers`
  ADD CONSTRAINT `dealers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dealers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gatepasses`
--
ALTER TABLE `gatepasses`
  ADD CONSTRAINT `gatepasses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_dealer_id_foreign` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_transporter_id_foreign` FOREIGN KEY (`transporter_id`) REFERENCES `transporters` (`transporter_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepasses_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`vehicle_id`) ON DELETE CASCADE;

--
-- Constraints for table `gatepass_details`
--
ALTER TABLE `gatepass_details`
  ADD CONSTRAINT `gatepass_details_alternate_unit_id_foreign` FOREIGN KEY (`alternate_unit_id`) REFERENCES `alternate_units` (`alter_unit_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `gatepass_details_gatepass_id_foreign` FOREIGN KEY (`gatepass_id`) REFERENCES `gatepasses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepass_details_stock_id_foreign` FOREIGN KEY (`stock_id`) REFERENCES `stocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gatepass_details_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `purchase_details_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transporters`
--
ALTER TABLE `transporters`
  ADD CONSTRAINT `transporters_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
