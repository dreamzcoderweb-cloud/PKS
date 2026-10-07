-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2026 at 09:20 AM
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
(1, 'KGs', '2026-07-07 05:36:54', '2026-07-07 05:38:02'),
(14, '35 kg', '2026-09-15 08:02:27', '2026-09-15 08:02:27'),
(15, 'kadalai moodai', '2026-09-15 08:05:20', '2026-09-15 08:05:20');

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
(10, 'Dindigul', 1, '2026-07-06 09:39:04', '2026-07-06 09:43:40'),
(22, 'Pollachi', 1, '2026-09-15 07:52:09', '2026-09-15 07:52:09'),
(23, 'wanaparthy', 1, '2026-09-15 07:52:28', '2026-09-15 07:52:28');

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
(8, '94abd622-b4dc-45d6-9ab4-0e028f5674ca', '2', 'demo 2', 'demo2@gmail.com', '7098765432', '$2y$12$/GMRjQNzZU513QQN9Jy/9.kt5/9DwPAjoMpg1gFlzjNhu0LgJuLPu', 10, NULL, NULL, NULL, NULL, 1, 20, '2026-07-22 10:40:54', '2026-07-22 10:40:54'),
(10, '79a82b7e-e882-4cc5-8544-8ed49d7c517d', '3', 'Pollachi', 'pollachi@gmail.com', '9098765432', '$2y$12$lQh5FLHkSZ.6wr5KIv.IpOz2ykuPDsKOLfgxOyH9RcFLXFadrAN/.', 22, NULL, NULL, NULL, NULL, 1, 20, '2026-10-05 19:49:46', '2026-10-05 19:52:36');

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
  `business_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
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
(21, '42f1f78e-c410-4eb1-b705-61cc3ce9445b', '1', 10, 'Dgl sunderrajan', 'delaers', '9443704277', 'Dindigul', 1, 20, '2026-09-21 06:54:51', '2026-09-21 06:54:51'),
(22, 'f3139b3d-ba89-463a-a137-a084642d7459', '2', 10, 'lorry bhaii', 'Party', '9486208269', 'Dindigul', 1, 20, '2026-09-21 06:56:17', '2026-09-21 07:10:18'),
(23, '7fccff0c-1f63-430a-aa5e-87c6bbdfa75f', '3', 10, 'Bill ganeshan', 'party', '9443331683', 'Dindigul', 1, 20, '2026-09-21 06:58:46', '2026-09-21 06:58:46'),
(24, '0e718fb6-347e-4d3e-82c9-e8f31b2a4eb7', '4', 10, 'varuval kannan', 'varuval', '8148732057', 'Dindigul', 1, 20, '2026-09-21 06:59:52', '2026-09-21 07:10:03'),
(25, '7283534a-d083-40ff-9814-6ef20fa92266', '5', 10, 'Varuval moorthy', 'Varuval', '1234567890', 'Dindigul', 1, 20, '2026-09-21 07:00:23', '2026-09-21 07:10:38'),
(26, '44a52c4a-6f95-4ee7-a5d2-a114084d93ee', '6', 10, 'Kaliswaran', 'Arupukottai', '9865056181', 'Arupukottai', 1, 20, '2026-09-21 07:02:11', '2026-09-21 07:02:11'),
(27, 'cda0fdae-fd11-4bc8-9f29-d3071208f493', '7', 10, 'Anand raj', 'Party', '9876543210', 'Aruppu kottai', 1, 20, '2026-09-21 07:03:55', '2026-09-21 07:03:55'),
(28, 'f2f292ff-8416-4507-9dce-7b17da4dcbd2', '8', 10, 'pori sakthi', 'groundnut', '9875421301', 'Dindigul', 1, 20, '2026-09-21 07:15:00', '2026-09-21 07:15:00'),
(29, '9657964c-160d-47d3-bf91-70919403413f', '9', 10, 'SJ Traders', 'Groundnut', '8072354460', 'Anjal', 1, 20, '2026-09-23 07:02:30', '2026-09-23 07:02:30'),
(51, 'ef217025-cc9a-47a6-814d-da4e39243665', '10', 22, 'vel murugan', 'Ground nut', NULL, 'Pollachi', 1, 20, '2026-09-25 08:26:13', '2026-09-25 08:26:13'),
(52, 'd62b76cd-2e6f-441b-a061-b9103ac855a0', '11', 22, 'Dipali Enterprises', 'Other state', NULL, 'Andhra', 1, 20, '2026-09-25 08:27:11', '2026-09-25 08:27:11'),
(53, 'a78764d4-3e66-442e-939e-b9921f912992', '12', 22, 'BABA', NULL, NULL, 'Others state', 1, 20, '2026-09-25 08:27:46', '2026-09-25 08:27:46'),
(54, 'd42e2109-6fd7-4012-83d6-2c89cae9cac4', '13', 22, 'Laxmi Venkateswara', NULL, NULL, 'Other state', 1, 20, '2026-09-25 08:28:18', '2026-09-25 08:28:18'),
(55, '20d2e0b5-c469-4a54-b95b-c4fa6b935124', '14', 10, 'Laxmi Venkateswara', NULL, NULL, 'Telungana', 1, 20, '2026-09-25 08:28:36', '2026-09-25 08:28:36'),
(56, 'ef594c99-2ebd-47f6-94c2-6020c1c1203b', '15', 22, 'RK mohan', NULL, NULL, 'Pollachi', 1, 20, '2026-09-25 08:29:17', '2026-09-25 08:29:17'),
(57, '7f6f6de2-4505-4c24-b91a-65f0981bf22a', '16', 10, 'Anand reddy', NULL, NULL, 'Telungana', 1, 20, '2026-09-25 08:29:45', '2026-09-25 08:29:45'),
(58, '07a1d657-4526-4007-a669-a1bfd457280c', '17', 10, 'siva', NULL, NULL, 'chennai', 1, 20, '2026-09-30 04:54:43', '2026-09-30 04:54:43'),
(60, '809b7e4d-94cc-4a93-b707-57e414f0f519', '19', 10, 'Luckoo', 'groundnut seeds', NULL, 'palakad', 1, 20, '2026-10-05 07:17:16', '2026-10-05 07:17:16'),
(61, '57e42da1-15ad-41ef-800f-58792dd167b3', '20', 10, 'TMT SENTHIL', 'GROUNDNUT', NULL, 'DINDIGUL', 1, 20, '2026-10-05 13:28:07', '2026-10-05 13:28:07'),
(62, '03ea23af-0f78-4b2d-b67e-5aefffd7e871', '21', 10, 'ASVI FOODS', 'ROASTED', NULL, 'Dgl', 1, 20, '2026-10-05 13:30:40', '2026-10-05 13:30:40'),
(63, '4ee79cc5-f820-46e0-8d85-4b44ccb89eab', '22', 10, 'Meeran', 'OTC', '1234567890', 'OTC', 1, 20, '2026-10-05 13:32:34', '2026-10-06 08:05:48'),
(64, 'f31bc350-b8be-40a3-8bb6-a007d593b764', '23', 22, 'musthafa', 'bismillah traders', '9344341088', 'Pollachi', 1, 20, '2026-10-06 07:01:00', '2026-10-06 07:01:00'),
(65, '0617ad56-1dc3-44ed-8e40-63c85f55f810', '24', 22, 'Nagamanickam', 'nagamanickam', '9443023713', 'Pollachi', 1, 20, '2026-10-06 07:02:49', '2026-10-06 07:02:49'),
(66, '5a5b69d9-a155-46f9-ba00-fd32a449a4a9', '25', 22, 'Radhsh', 'Groundnut', NULL, 'vannamada', 1, 20, '2026-10-06 07:05:40', '2026-10-06 07:05:40'),
(67, '2155f443-ade0-4262-b4d6-dcdc3ed52161', '26', 22, 'Luckoo (60)', 'Luckoo (60)', '', '', 1, 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(70, 'c822a0a2-83e1-4b57-89ae-82efa9084891', '27', 22, 'gama', 'groundnut', NULL, 'clt', 1, 20, '2026-10-06 12:10:15', '2026-10-06 12:10:15'),
(71, 'f8ad45da-5cb4-4c57-bbe3-5b00e9636fae', '28', 22, 'AFSA', 'groundnut', NULL, 'CLT', 1, 20, '2026-10-06 12:10:41', '2026-10-06 12:10:41'),
(72, '30fe5861-fc7c-4a7f-b527-7dc1aad24fda', '29', 22, 'gama (70)', 'gama (70)', '', '', 1, 20, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(73, 'ec6bff42-7c53-49d5-93c2-6c00fdca434d', '30', 22, 'Nagercoil', 'Nagercoil', '', '', 1, 20, '2026-10-06 23:34:15', '2026-10-06 23:34:15');

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
(6, '2026_06_30_050053_create_stocks_table', 1);

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
(32, 'App\\Models\\User', 21, 'auth_token', '1b48d8369057b7e32942e6cb01d5c6a982ecf1b4cbfed7aa000e0008b34a6279', '[\"*\"]', '2026-10-01 07:07:47', NULL, '2026-07-07 04:38:45', '2026-10-01 07:07:47'),
(148, 'App\\Models\\Customer', 9, 'auth_token', '281176b478bc514625fff3d3d0666956dc29ad8a7a647bfe533c2d9e25b7811e', '[\"*\"]', '2026-08-11 07:54:22', NULL, '2026-08-11 05:30:26', '2026-08-11 07:54:22'),
(176, 'App\\Models\\Customer', 7, 'auth_token', 'aa52f7e2940b38d6daf01ef95ce02cf8bc88f5499a002a5e12f11b1fbd7b84b8', '[\"*\"]', '2026-09-29 08:03:02', NULL, '2026-09-24 06:41:55', '2026-09-29 08:03:02'),
(177, 'App\\Models\\Customer', 7, 'auth_token', '49da8ed174a08d5346eac70bdf0f8ee22ba0acf26babba28b86de4ccd20619af', '[\"*\"]', '2026-09-25 05:57:19', NULL, '2026-09-24 06:58:54', '2026-09-25 05:57:19'),
(181, 'App\\Models\\Customer', 7, 'auth_token', '370f6e3afd8a3c8678e3d24780fdce9a99dbe1cf714b561c25adba273d46b5ee', '[\"*\"]', '2026-09-25 05:57:52', NULL, '2026-09-25 05:08:44', '2026-09-25 05:57:52'),
(186, 'App\\Models\\Customer', 7, 'auth_token', '6a1f13c353d599a2b2b29aa94c5ba42d42014c5291f4f7e3bbda6236b1d50e23', '[\"*\"]', '2026-09-29 08:53:43', NULL, '2026-09-29 05:05:46', '2026-09-29 08:53:43'),
(187, 'App\\Models\\Customer', 7, 'auth_token', '8bfc71041890754d82efdb46ef98592bd91e734ddbb722d6f2c1f659801a30cb', '[\"*\"]', '2026-09-29 05:44:45', NULL, '2026-09-29 05:31:08', '2026-09-29 05:44:45'),
(188, 'App\\Models\\Customer', 7, 'auth_token', '9b5b69cfc28678f260037a35d9d39f5aed5d34ba530de2ccecd8085942adba61', '[\"*\"]', '2026-09-29 06:52:06', NULL, '2026-09-29 05:32:27', '2026-09-29 06:52:06'),
(190, 'App\\Models\\Customer', 7, 'auth_token', '49c2c377e7d9dbc8d2df6b93ade7ade6fac90f89e3b67fb51d09004323a11ade', '[\"*\"]', '2026-09-29 09:42:55', NULL, '2026-09-29 09:08:12', '2026-09-29 09:42:55'),
(192, 'App\\Models\\Customer', 7, 'auth_token', '80275db9fc6c04f6780f483c21459cbbc2309a07de4573c4f48c4ca93fc79539', '[\"*\"]', '2026-10-07 04:39:43', NULL, '2026-09-29 10:31:34', '2026-10-07 04:39:43'),
(193, 'App\\Models\\Customer', 7, 'auth_token', '56d475d2cbd9f10943aca9273f48b28e9c815679ef5c7ee4e77171d45d3d9cab', '[\"*\"]', '2026-09-30 05:52:03', NULL, '2026-09-30 05:51:44', '2026-09-30 05:52:03'),
(194, 'App\\Models\\Customer', 7, 'auth_token', 'e1d77f4a22837766403adc164c0645d3f766e9c77abd12503c49f6129812bd43', '[\"*\"]', '2026-09-30 07:44:25', NULL, '2026-09-30 06:01:50', '2026-09-30 07:44:25'),
(195, 'App\\Models\\Customer', 7, 'auth_token', '7c379fd28e91517c26baef16fcfd60f753ceebaa6fb34cba6901d46abf5316b8', '[\"*\"]', '2026-10-01 07:08:08', NULL, '2026-10-01 07:07:54', '2026-10-01 07:08:08'),
(200, 'App\\Models\\Customer', 8, 'auth_token', 'f119bc2ad46966a057f2971c53566ca444fa924b0c3a7fe78f1d7edcdbe73bb6', '[\"*\"]', '2026-10-06 10:31:24', NULL, '2026-10-05 08:34:57', '2026-10-06 10:31:24'),
(201, 'App\\Models\\Customer', 8, 'auth_token', 'eb7c5042ba18b9e9785f83c2e82cab6db229c8bff6398f4dadab251515c0338d', '[\"*\"]', '2026-10-06 13:25:42', NULL, '2026-10-05 09:04:00', '2026-10-06 13:25:42'),
(202, 'App\\Models\\Customer', 8, 'auth_token', '11f0e87dd17801a310acf1ee63a070d4e2b44c2610fe49fff759c0112440c100', '[\"*\"]', '2026-10-07 02:24:51', NULL, '2026-10-05 09:42:19', '2026-10-07 02:24:51'),
(203, 'App\\Models\\User', 20, 'auth_token', 'f013d83bf3ae175cedb2dab0e12b9c24b0b7215fb69e451c42b3ffd620df6e30', '[\"*\"]', '2026-10-06 10:30:58', NULL, '2026-10-05 19:49:06', '2026-10-06 10:30:58'),
(204, 'App\\Models\\User', 20, 'auth_token', '8cc4b197ef5e2cda3013aa8231a84f4526489a2859dd32b91b563b1fcc8b5d85', '[\"*\"]', '2026-10-06 04:39:19', NULL, '2026-10-06 04:38:38', '2026-10-06 04:39:19'),
(211, 'App\\Models\\Customer', 7, 'auth_token', '4f07ff9c5e5d79a8cee669232a1ae0af161e83c9ddeb35735c5ec0e6d379a78f', '[\"*\"]', '2026-10-06 11:04:31', NULL, '2026-10-06 11:04:28', '2026-10-06 11:04:31'),
(212, 'App\\Models\\User', 20, 'auth_token', '5a07cd588ac4519683a1d4a96404f76c41f811be1af87185a3d228b09a2400fc', '[\"*\"]', '2026-10-06 13:45:56', NULL, '2026-10-06 13:41:19', '2026-10-06 13:45:56'),
(213, 'App\\Models\\User', 20, 'auth_token', 'dfa894e54c55582493ec3295922758a9508c61e159af8f0e6a9513440f4c6cba', '[\"*\"]', '2026-10-07 04:39:34', NULL, '2026-10-07 04:39:31', '2026-10-07 04:39:34');

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
(1, 'b9736d5e-9a91-4b2d-b732-91dabd0f45a7', 10, 6, '200', 9, 18, '987654332', '[\"purchases\\/6a718b4189005_1785826113.jpg\"]', 20, '2026-08-04 06:48:33', '2026-08-04 06:48:33'),
(2, '94f7d97e-c75c-4d77-9057-18fd4592e422', 10, 12, '7', 8, 18, '9876554333', '[\"purchases\\/6a770946b9e86_1786186054.jpg\"]', 20, '2026-08-08 10:47:34', '2026-08-08 10:47:34'),
(3, '43e47c04-e8c0-4c1d-bf19-53457c5be5f1', 10, 16, '258', 9, 22, '9876543210', '[]', 20, '2026-08-11 10:51:38', '2026-08-11 10:51:38'),
(4, '09fbc82c-504b-439f-b25e-6b6dbb06a656', 10, 1, '258', 9, 23, '9876543210', '[]', 20, '2026-08-11 11:30:20', '2026-08-11 11:30:20'),
(5, '82d08d86-2924-41be-ac2d-50838516e73d', 10, 18, '258', 9, 24, '9876543210', '[\"purchases\\/6a7c25102ae08_1786520848.jpg\"]', 20, '2026-08-12 07:47:28', '2026-08-12 07:47:28'),
(6, 'd5429276-47a1-4aa2-b43f-f01ddc0fe75f', 10, 21, '25', 11, 25, '772727', '[\"purchases\\/6ab63bd9c074b_1790327769.jpg\"]', 20, '2026-09-25 09:16:09', '2026-09-25 09:16:09'),
(7, '4bbbb0fc-ec34-4839-8391-b38cc03330fc', 10, 21, '255', 11, 25, '9876543210', '[]', 20, '2026-09-25 09:20:14', '2026-09-25 09:20:14'),
(8, 'fb74c4d9-4431-40be-9379-f0bfa7586763', 10, 58, '9999', 9, 31, '8807777778', '[]', 20, '2026-09-30 05:00:45', '2026-09-30 05:00:45'),
(9, '2297fba3-6f9a-4648-b48f-d0985fa35564', 10, 58, '22222', 9, 32, '355678898776', '[]', 20, '2026-09-30 05:36:27', '2026-09-30 05:36:27');

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
  `rate` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_details`
--

INSERT INTO `purchase_details` (`id`, `purchase_id`, `brand_name`, `stock_name`, `lot_number`, `unit_value`, `unit_type`, `alter_unit_value`, `alter_unit_type`, `rate`, `created_at`, `updated_at`) VALUES
(1, 1, 'ram laxmanan', 'ram laxmanan', '300', 299.00, 'Bags', 14950.00, 'KGs', 100.00, '2026-08-04 06:48:33', '2026-08-04 06:48:33'),
(2, 2, 'kadalai', 'கடலை', '7', 100.00, 'Bags', 5000.00, 'KGs', 100.00, '2026-08-08 10:47:34', '2026-08-08 10:47:34'),
(3, 3, 'Kadai', 'Kadai', '250', 500.00, 'Bags', 25000.00, 'KGs', 10.00, '2026-08-11 10:51:38', '2026-08-11 10:51:38'),
(5, 4, 'Wheat', 'wheat', '580', 250.00, 'Bags', 12500.00, 'KGs', 500.00, '2026-08-11 11:30:57', '2026-08-11 11:30:57'),
(6, 5, 'skuu', 'skuu', '258', 58.00, 'Bags', 2900.00, 'KGs', NULL, '2026-08-12 07:47:28', '2026-08-12 07:47:28'),
(7, 6, 'kgl', 'kgl', '102', 2.00, '1 per kg', 100.00, '35 kg', NULL, '2026-09-25 09:16:09', '2026-09-25 09:16:09'),
(8, 7, 'kgk', '250', '255', 25.00, '1 per kg', 1250.00, 'kadalai moodai', NULL, '2026-09-25 09:20:14', '2026-09-25 09:20:14'),
(9, 8, 'guru', 'Surya', '1234', 20.00, 'Bags', 1000.00, 'KGs', NULL, '2026-09-30 05:00:45', '2026-09-30 05:00:45'),
(10, 9, 'Muthu', 'guru', '2222', 10.00, 'Bags', 500.00, 'KGs', NULL, '2026-09-30 05:36:27', '2026-09-30 05:36:27');

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
  `saletype` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = Sale, 1 = Decorticate,2 = cash sale',
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

INSERT INTO `sales` (`id`, `sale_id`, `branch_id`, `dealer_id`, `vehicle_id`, `saletype`, `invoice_number`, `driver_name`, `driver_number`, `sale_date`, `sale_images`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '9e80fcc9-11d5-4b44-923b-f24eba1bb400', 10, 8, 18, 0, 'fhhsh7755', 'fggshhsj', '9876543210', '2026-08-04 10:39:17', '[\"sales\\/6a71982f5392d_1785829423.png\"]', 20, '2026-08-04 10:39:17', '2026-08-04 07:43:43', '2026-08-04 10:39:17'),
(2, '572c37c3-75d6-4907-b7f4-fbcace4f0450', 10, 6, 12, 0, '12334', 'naveen', '9632587410', '2026-08-04 10:12:43', '[]', 20, '2026-08-04 10:12:43', '2026-08-04 10:12:08', '2026-08-04 10:12:43'),
(3, '7352157b-1863-4575-8e5d-d5ac94a2a287', 10, 6, 15, 0, '677', 'naveen', '9666664552', '2026-08-04 10:34:30', '[]', 20, '2026-08-04 10:34:30', '2026-08-04 10:33:21', '2026-08-04 10:34:30'),
(4, '50b0bb10-5c72-47a4-a966-12f528b656aa', 10, 5, 15, 0, '77777', 'naveen', '6464545545', '2026-08-04 10:34:26', '[]', 20, '2026-08-04 10:34:26', '2026-08-04 10:34:01', '2026-08-04 10:34:26'),
(5, 'bf042319-c6ba-4ce7-9261-c6a344ac36dc', 10, 6, 15, 0, '2677', 'naveen', '9496464644', '2026-08-04 12:23:21', '[]', 20, '2026-08-04 12:23:21', '2026-08-04 10:38:59', '2026-08-04 12:23:21'),
(6, '7946a4b3-13ad-42e8-852a-0c35a56fd370', 10, 8, 15, 0, '6667', 'naveen', '6464646646', '2026-08-04 12:23:16', '[]', 20, '2026-08-04 12:23:16', '2026-08-04 10:49:56', '2026-08-04 12:23:16'),
(7, '3c14cf40-c9fa-4a15-a033-d24e337adb75', 10, 8, 16, 1, '5677', 'naveen', '6969663251', '2026-08-04 12:23:13', '[]', 20, '2026-08-04 12:23:13', '2026-08-04 11:02:59', '2026-08-04 12:23:13'),
(8, '4561c8fb-fbef-4c26-9c86-b4702a8ead4e', 10, 6, 15, 0, '46754', 'naveen', '6555578877', '2026-08-04 12:23:10', '[]', 20, '2026-08-04 12:23:10', '2026-08-04 11:08:10', '2026-08-04 12:23:10'),
(9, 'ea0bdc9f-2103-4544-98b2-ca494ee4f3ec', 10, 13, 15, 1, 't6777', 'naveen', '3194949949', '2026-08-04 12:23:07', '[]', 20, '2026-08-04 12:23:07', '2026-08-04 12:07:03', '2026-08-04 12:23:07'),
(10, '88920549-69e4-464e-b2d6-4e8dd6b36357', 10, 8, 18, 0, '7778', 'naveen', '3431616466', '2026-08-05 07:54:52', '[]', 20, '2026-08-05 07:54:52', '2026-08-05 04:52:11', '2026-08-05 07:54:52'),
(11, '68f20b54-58ac-44ff-92db-447b3bf2ed7f', 10, 1, 16, 1, '5666', 'naveen', '9855588888', '2026-08-05 07:54:49', '[\"sales\\/6a72e116de576_1785913622.jpg\"]', 20, '2026-08-05 07:54:49', '2026-08-05 07:06:04', '2026-08-05 07:54:49'),
(12, '99e13b8f-494a-4f92-b76c-73f0aa76c8ba', 10, 6, 16, 0, '567766', 'naveen', '4948565885', '2026-08-05 07:54:47', '[]', 20, '2026-08-05 07:54:47', '2026-08-05 07:09:31', '2026-08-05 07:54:47'),
(13, 'c074e478-b227-4bb4-9f27-9fae2b94b039', 10, 6, 15, 1, '6666', 'naveen', '9494949494', '2026-08-05 07:54:44', '[]', 20, '2026-08-05 07:54:44', '2026-08-05 07:37:12', '2026-08-05 07:54:44'),
(14, '38b93b2f-92ea-46e0-a44c-4e71ba0b3efb', 10, 12, 16, 0, '1334556', 'dj', '9876543210', '2026-10-06 13:42:18', '[\"sales\\/6a74094c60fed_1785989452.jpg\"]', 20, '2026-10-06 13:42:18', '2026-08-06 04:10:52', '2026-10-06 13:42:18'),
(15, '1684f822-8724-48d4-bc5e-3c1c73dac4a2', 10, 8, 16, 0, '100', 'raj', '9876543210', '2026-10-06 13:44:52', '[\"sales\\/6a76cc01ddb81_1786170369.png\"]', 20, '2026-10-06 13:44:52', '2026-08-08 06:26:09', '2026-10-06 13:44:52'),
(16, '9efe4e31-5b0c-405b-aa9b-8fef0cf2a5e0', 10, 6, 16, 0, '122333', 'rrraaa', '9876543212', '2026-10-06 13:42:23', '[\"sales\\/6a77099801bb0_1786186136.jpg\"]', 20, '2026-10-06 13:42:23', '2026-08-08 10:48:56', '2026-10-06 13:42:23'),
(17, 'cbf09845-bf3c-416f-8dd4-6c81eb2b0f0d', 10, 3, 13, 0, '4555', 'sara', '9638527410', '2026-10-06 13:42:26', '[\"sales\\/6a7709e3e38b0_1786186211.jpg\"]', 20, '2026-10-06 13:42:26', '2026-08-08 10:50:11', '2026-10-06 13:42:26'),
(18, 'c8d522b8-8919-4dc1-8852-b30b93f8c9d8', 10, 6, 13, 1, '5555', 'naveen', '9855454556', '2026-10-06 13:42:29', '[]', 20, '2026-10-06 13:42:29', '2026-08-11 06:10:29', '2026-10-06 13:42:29'),
(19, '7660373e-0e50-41d6-b709-1bfe2d858877', 10, 8, 16, 1, '667', 'naveen', '9464646545', '2026-08-11 10:15:36', '[]', 20, '2026-08-11 10:15:36', '2026-08-11 10:13:54', '2026-08-11 10:15:36'),
(20, '187e6a79-3bd5-4959-b78a-03460f139f07', 10, 3, 16, 1, '677', 'naveen', '9454554545', '2026-08-11 11:06:26', '[]', 20, '2026-08-11 11:06:26', '2026-08-11 10:16:31', '2026-08-11 11:06:26'),
(21, 'd405afb5-0a13-42da-900a-f16b5b948c1a', 10, 16, 18, 1, '6778', 'naveen', '9494646464', '2026-08-11 11:06:22', '[]', 20, '2026-08-11 11:06:22', '2026-08-11 11:05:48', '2026-08-11 11:06:22'),
(22, '51581c89-2a67-4bff-a92a-33c0ce66f065', 10, 16, 15, 0, '566', 'naveen', '9555528282', '2026-10-06 13:42:33', '[]', 20, '2026-10-06 13:42:33', '2026-08-11 11:07:15', '2026-10-06 13:42:33'),
(23, '40b92cc1-2242-4e2a-8e18-78c29b909a2d', 10, 17, 23, 0, '8555855', 'Vignesh', '9876543210', '2026-10-06 13:42:36', '[]', 20, '2026-10-06 13:42:36', '2026-08-11 11:21:32', '2026-10-06 13:42:36'),
(24, '3ed64371-6020-4992-b118-fb11dc2e9410', 10, 17, 13, 1, '6677', 'naveen', '7994964646', '2026-10-06 13:42:39', '[]', 20, '2026-10-06 13:42:39', '2026-08-12 06:32:16', '2026-10-06 13:42:39'),
(25, 'c815d645-4e40-4bb8-b995-b6631df2aa1e', 10, 17, 23, 1, '5666', 'naveen', '9464664646', '2026-08-12 07:47:11', '[]', 20, '2026-08-12 07:47:11', '2026-08-12 07:33:07', '2026-08-12 07:47:11'),
(26, '23e51ba0-6b10-4a04-bfed-bba55a727337', 10, 18, 24, 1, '4443', 'naveen', '9411584447', '2026-10-06 13:42:44', '[]', 20, '2026-10-06 13:42:44', '2026-08-12 07:50:53', '2026-10-06 13:42:44'),
(27, '2549b0f1-ea33-4270-840f-16b5772d5140', 10, 18, 24, 0, '7654', 'rajesh', '9876543210', '2026-10-06 13:42:48', '[\"sales\\/6a7c270568bfd_1786521349.jpg\"]', 20, '2026-10-06 13:42:48', '2026-08-12 07:55:49', '2026-10-06 13:42:48'),
(28, '29372f3a-c287-41fe-b44f-113274b35ce7', 10, 12, 25, 0, '122334', 'fr', '9876543210', '2026-10-06 13:42:50', '[\"sales\\/6a7ead2e9e582_1786686766.jpg\"]', 20, '2026-10-06 13:42:50', '2026-08-14 05:52:46', '2026-10-06 13:42:50'),
(29, '987d2515-dc64-4189-8bd3-c60d247b1838', 10, 19, 23, 0, '12345', 'qwerty', '9876543210', '2026-10-06 13:42:53', '[\"sales\\/6a829fe70e281_1786945511.png\"]', 20, '2026-10-06 13:42:53', '2026-08-17 05:45:11', '2026-10-06 13:42:53'),
(30, '33b4407f-8b61-4dda-b805-c53da3412d03', 10, 24, 26, 0, '593/600/595/1', 'muthu', '7418252729', '2026-10-06 13:42:56', '[\"sales\\/6ab0d8ef91fc9_1789974767.jpg\"]', 20, '2026-10-06 13:42:56', '2026-09-21 07:12:47', '2026-10-06 13:42:56'),
(31, 'd0e62154-5e0f-4e40-9434-3f17d59e478b', 10, 28, 27, 0, '9/3', 'Jeeva', '3258965214', '2026-10-06 13:42:59', '[]', 20, '2026-10-06 13:42:59', '2026-09-21 07:18:21', '2026-10-06 13:42:59'),
(32, '3ad54d53-49bd-4792-ad27-f9e0e0aa45f8', 10, 29, 28, 0, '67', 'pandi', '7418252729', '2026-10-06 13:43:02', '[]', 20, '2026-10-06 13:43:02', '2026-09-23 07:04:45', '2026-10-06 13:43:02'),
(33, '4eaba5b9-39a7-4336-bb88-b2a74d229355', 10, 28, 27, 0, '22', 'jeeva', '7418252729', '2026-10-06 13:43:06', '[]', 20, '2026-10-06 13:43:06', '2026-09-24 09:10:47', '2026-10-06 13:43:06'),
(34, '1beecce8-95af-4d72-a0dd-650e149af9b0', 10, 21, 23, 0, '636363', 'muthu', '9876543210', '2026-10-06 13:43:09', '[\"sales\\/6ab63c1a3b31d_1790327834.jpg\"]', 20, '2026-10-06 13:43:09', '2026-09-25 09:17:14', '2026-10-06 13:43:09'),
(35, '4b9a85c3-f3e1-4440-bc29-4070a6645fdd', 10, 21, 24, 1, '6363', 'muthu', '9877654310', '2026-10-06 13:43:13', '[\"sales\\/6ab63c6dc4031_1790327917.jpg\"]', 20, '2026-10-06 13:43:13', '2026-09-25 09:18:37', '2026-10-06 13:43:13'),
(36, 'ed0f978e-898d-4c0d-8f4e-6dd41be192b8', 10, 55, 26, 0, '2233', 'naveen', '9638524710', '2026-09-25 11:38:07', '[]', 20, '2026-09-25 11:38:07', '2026-09-25 09:32:28', '2026-09-25 11:38:07'),
(37, '69b21639-0f9e-4f46-bad8-fc9e49fcd96d', 10, 55, 26, 0, '5677', 'naveen', '9638527410', '2026-09-25 11:38:03', '[]', 20, '2026-09-25 11:38:03', '2026-09-25 09:34:33', '2026-09-25 11:38:03'),
(38, '12ec8252-eafe-40c5-aac2-972506069857', 10, 27, 29, 0, '123455', 'siva', '9876543210', '2026-10-06 13:43:20', '[\"sales\\/6abb4156a7f5a_1790656854.jpg\"]', 20, '2026-10-06 13:43:20', '2026-09-29 04:40:54', '2026-10-06 13:43:20'),
(39, '2c68edec-8c78-4ea8-9f04-19ed9746ae3a', 10, 57, 30, 0, '12344dd', 'vj', '1234567890', '2026-10-06 13:43:17', '[\"sales\\/6abb43af014a4_1790657455.jpg\"]', 20, '2026-10-06 13:43:17', '2026-09-29 04:50:55', '2026-10-06 13:43:17'),
(40, 'd67b9aaf-4d29-4353-9cf7-d1cf8f9cf87b', 10, 57, 31, 0, '1344r', 'aj', '9876543210', '2026-10-06 13:43:25', '[\"sales\\/6abb43ff053cd_1790657535.png\"]', 20, '2026-10-06 13:43:25', '2026-09-29 04:52:15', '2026-10-06 13:43:25'),
(41, 'f2ce9d10-973f-4d55-9b45-217e1d2268df', 10, 55, 28, 0, '666', 'naveen', '9638527410', '2026-10-06 13:43:28', '[]', 20, '2026-10-06 13:43:28', '2026-09-29 05:27:38', '2026-10-06 13:43:28'),
(42, '153a9941-fc15-4b34-995e-549ef2e1df78', 10, 55, 28, 0, '666', 'naveen', '9638527410', '2026-10-06 13:43:31', '[]', 20, '2026-10-06 13:43:31', '2026-09-29 05:28:10', '2026-10-06 13:43:31'),
(43, '484b373f-5fe3-4108-a14c-cf3ad496005a', 10, 55, 28, 0, '666', 'naveen', '9638527410', '2026-10-06 13:43:35', '[]', 20, '2026-10-06 13:43:35', '2026-09-29 05:28:41', '2026-10-06 13:43:35'),
(44, '28c7ca0c-484e-416b-b8e1-908352223d1b', 10, 21, 29, 0, '098777', 'raja', '9876543210', '2026-10-06 13:43:39', '[]', 20, '2026-10-06 13:43:39', '2026-09-29 05:29:51', '2026-10-06 13:43:39'),
(45, 'ae3cafcb-4fb9-4e06-aafe-5ee349f3d3d3', 10, 21, 29, 1, '7777', 'muthu', '9876543210', '2026-10-06 13:43:47', '[]', 20, '2026-10-06 13:43:47', '2026-09-29 05:30:45', '2026-10-06 13:43:47'),
(46, '5cb036d5-e09f-4131-b004-4d1b502a44bb', 10, 55, 31, 0, '6666', 'naveen', '9638527410', '2026-10-06 13:43:58', '[]', 20, '2026-10-06 13:43:58', '2026-09-29 05:31:09', '2026-10-06 13:43:58'),
(47, '90f33d91-f1d1-4aba-adab-0597ad1a1685', 10, 57, 31, 0, '5666', 'naveen', '123456789', '2026-10-06 13:44:50', '[]', 20, '2026-10-06 13:44:50', '2026-09-29 05:42:28', '2026-10-06 13:44:50'),
(48, '413cecd2-edf5-423d-9c4c-4ac2fe14d9b3', 10, 29, 31, 1, '1111', 'naveen', '9638527410', '2026-10-06 13:44:48', '[]', 20, '2026-10-06 13:44:48', '2026-09-29 05:44:14', '2026-10-06 13:44:48'),
(49, '0c371c78-8ff1-4f25-96bc-b6a9c2d3ba39', 10, 55, 31, 1, '6666', 'Siva', '9638521470', '2026-10-06 13:44:46', '[]', 20, '2026-10-06 13:44:46', '2026-09-29 06:07:36', '2026-10-06 13:44:46'),
(50, '09ae64a8-7361-49b1-b537-6da05c539897', 10, 55, 31, 1, '3333', 'naveen', '1234567890', '2026-10-06 13:44:44', '[]', 20, '2026-10-06 13:44:44', '2026-09-29 06:18:08', '2026-10-06 13:44:44'),
(51, 'aafe1bd2-7c7f-4534-b3d4-f13134775202', 10, 55, 30, 1, '2222', 'naveen', '9638527110', '2026-10-06 13:44:42', '[]', 20, '2026-10-06 13:44:42', '2026-09-29 06:19:07', '2026-10-06 13:44:42'),
(52, 'bd079701-6bcf-4f78-a985-1d9f31105a86', 10, 57, 30, 0, '9999', 'siva', '1472583690', '2026-10-06 13:44:40', '[]', 20, '2026-10-06 13:44:40', '2026-09-29 06:50:55', '2026-10-06 13:44:40'),
(53, 'cd287cc9-6d3e-46b1-a960-eda7d4c3aa91', 10, 57, 30, 1, '4444', 'naveen', '9638527410', '2026-10-06 13:44:38', '[]', 20, '2026-10-06 13:44:38', '2026-09-29 07:39:10', '2026-10-06 13:44:38'),
(54, '70764697-1858-4c7c-b2fb-877d35d7e249', 10, 55, 30, 1, '77777', 'naveen', '1234567890', '2026-10-06 13:44:36', '[]', 20, '2026-10-06 13:44:36', '2026-09-29 07:58:11', '2026-10-06 13:44:36'),
(55, 'fd89dc1e-e766-4ca3-866e-cf06e707e50e', 10, 21, 31, 0, '890', 'muthu', '9876543210', '2026-10-06 13:44:34', '[]', 20, '2026-10-06 13:44:34', '2026-09-29 08:26:15', '2026-10-06 13:44:34'),
(58, '3810eb46-d31c-45f9-907f-01d46de798c6', 10, 57, 28, 1, '7777', 'Siva', '1234567852', '2026-10-06 13:44:32', '[]', 20, '2026-10-06 13:44:32', '2026-09-29 08:34:58', '2026-10-06 13:44:32'),
(59, '1796e890-b74c-4b20-b25a-94b01a11d3ac', 10, 58, 31, 1, '55555', 'naveen', '9638527410', '2026-10-06 13:44:30', '[]', 20, '2026-10-06 13:44:30', '2026-09-30 04:55:41', '2026-10-06 13:44:30'),
(60, '877d48be-7efa-4120-936b-fe3492f735bf', 10, 58, 31, 0, '00000', 'naveen', '9638524100', '2026-10-06 13:44:28', '[]', 20, '2026-10-06 13:44:28', '2026-09-30 05:03:28', '2026-10-06 13:44:28'),
(61, '526e0402-d6be-468e-b490-c2e292d79644', 10, 59, 31, 1, '6666', 'naveen', '1234587677', '2026-10-06 13:44:26', '[]', 20, '2026-10-06 13:44:26', '2026-09-30 05:13:04', '2026-10-06 13:44:26'),
(62, 'ea9ba34c-0ba1-4363-9f50-a9fada9581c4', 10, 59, 31, 1, '45666', 'naveen', '1236584100', '2026-10-06 13:44:24', '[]', 20, '2026-10-06 13:44:24', '2026-09-30 05:14:23', '2026-10-06 13:44:24'),
(63, '13eab315-8803-4855-801f-c92af61867e2', 10, 59, 31, 1, '67667', 'naveen', '0000000000', '2026-10-06 13:44:22', '[]', 20, '2026-10-06 13:44:22', '2026-09-30 05:17:05', '2026-10-06 13:44:22'),
(64, '13cfebd3-9c0a-4b3c-8f33-76ea287f3e42', 10, 59, 30, 1, '566666', 'naveen', '9638520741', '2026-10-06 13:44:20', '[]', 20, '2026-10-06 13:44:20', '2026-09-30 05:18:16', '2026-10-06 13:44:20'),
(65, '42c25cc7-05ed-4837-8542-bc4e9abb37b1', 10, 26, 32, 0, '15627f', 'dd', '1234567890', '2026-10-06 13:44:18', '[\"sales\\/6abc9f847fafa_1790746500.jpg\"]', 20, '2026-10-06 13:44:18', '2026-09-30 05:35:00', '2026-10-06 13:44:18'),
(66, '9d070555-faed-4460-8698-06f603688eae', 10, 59, 31, 0, '555555', 'naveen', '1234588477', '2026-10-06 13:44:16', '[]', 20, '2026-10-06 13:44:16', '2026-09-30 05:37:38', '2026-10-06 13:44:16'),
(67, '07f50045-d243-420c-a0d6-f405a24a6a58', 10, 59, 31, 1, '5556', 'naveen', '963821770', '2026-10-06 13:44:14', '[]', 20, '2026-10-06 13:44:14', '2026-09-30 05:38:39', '2026-10-06 13:44:14'),
(68, '621f9bd3-33bd-4093-a516-5e972069867a', 10, 28, 27, 0, '151', 'jeeva', '9003993864', '2026-10-06 13:44:11', '[]', 20, '2026-10-06 13:44:11', '2026-10-05 06:27:46', '2026-10-06 13:44:11'),
(69, '6970e806-694c-42df-9aa8-f7b3fcb607c0', 10, 60, 33, 0, '124', 'luckoo driver', '1234567890', '2026-10-06 13:44:06', '[\"sales\\/6ac34f90ab961_1791184784.jpg\"]', 20, '2026-10-06 13:44:06', '2026-10-05 07:19:44', '2026-10-06 13:44:06'),
(70, '331b3bcc-fbff-4feb-8f1f-bac815d3a60e', 10, 61, 34, 0, '184/ 180', 'PRRIYASAMY THAMPI', '123456', '2026-10-05 00:00:00', '[]', 20, NULL, '2026-10-05 13:29:47', '2026-10-05 13:29:47'),
(71, '6004a6d6-a846-425e-89f0-9647e51b7e52', 10, 63, 35, 0, '181', 'PARTY OWNER', '123456', '2026-10-05 00:00:00', '[]', 20, NULL, '2026-10-05 13:33:35', '2026-10-05 13:33:35'),
(72, 'ba951005-7119-4b19-a9a8-01498bb6d7ab', 22, 67, 36, 0, '-', 'shibhu', '9207983281', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(73, '3d097bf8-5357-4e91-b694-8f8317176bef', 22, 67, 37, 0, '-', 'Rajesh', '8078469634', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 07:21:48', '2026-10-06 07:21:48'),
(74, '7fee694a-18cd-4079-bcf6-4957b86ef4d2', 22, 68, 38, 0, '-', 'boopathi', '9677597427', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 07:27:00', '2026-10-06 07:27:00'),
(75, '5b4d64d7-ae0a-460f-8499-76b578317122', 22, 65, 39, 0, '-', 'pavitharan', '8940455589', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 07:29:05', '2026-10-06 07:29:05'),
(76, 'd0ba9343-b44b-4f27-8e5a-4ad4ff2137e5', 22, 69, 40, 0, '-', 'marees', '7907715492', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 07:30:30', '2026-10-06 07:30:30'),
(77, '8d0bdcdd-1daf-44b5-89d3-50d4cb2221c3', 22, 72, 41, 0, '144', 'jeeva', '9497740380', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(78, 'e744d0cb-c6bb-4714-8425-837f9d70c03b', 22, 70, 41, 0, '144', 'jeeva', '9497740380', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 12:15:58', '2026-10-06 12:15:58'),
(79, '6a884607-e232-4302-9e61-6807a5878b96', 22, 71, 41, 0, '145', 'jeeva', '9497740380', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 12:17:00', '2026-10-06 12:17:00'),
(80, '8d97df75-f6be-426b-87d6-1be118552677', 22, 67, 42, 0, '146', 'shibhu', '9207983281', '2026-10-06 00:00:00', '[]', 20, NULL, '2026-10-06 12:47:23', '2026-10-06 12:47:23'),
(81, '05e1f491-3d80-45f9-88ae-a37dae3ee0af', 22, 73, 8, 2, '11183069', 'ajis', '9876543210', '2026-07-16 05:45:00', '[]', 20, NULL, '2026-10-06 23:34:15', '2026-10-06 23:34:15'),
(82, 'cf784dee-91b9-4d42-8ec1-2586243b18ae', 22, 73, 8, 2, '11183070', 'ajis', '9876543210', '2026-07-16 05:45:00', '[]', 20, NULL, '2026-10-06 23:36:53', '2026-10-06 23:36:53');

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
  `rate` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sale_id`, `stock_id`, `lot_number`, `unit_value`, `unit_id`, `alternate_unit_value`, `alternate_unit_id`, `rate`, `created_at`, `updated_at`) VALUES
(1, 1, 27, '240', 10.00, 1, '500.0', 1, 100.00, '2026-08-04 07:43:43', '2026-08-04 07:43:43'),
(2, 1, 26, '330', 10.00, 1, '500.0', 1, 100.00, '2026-08-04 07:43:43', '2026-08-04 07:43:43'),
(3, 1, 25, '263', 15.00, 1, '750.0', 1, 150.00, '2026-08-04 07:43:43', '2026-08-04 07:43:43'),
(4, 2, 19, '42', 2.00, 1, '100.0', 1, 500.00, '2026-08-04 10:12:08', '2026-08-04 10:12:08'),
(5, 3, 29, '17', 2.00, 1, '100.0', 1, 500.00, '2026-08-04 10:33:21', '2026-08-04 10:33:21'),
(6, 4, 20, '417', 2.00, 1, '100.0', 1, 20.00, '2026-08-04 10:34:01', '2026-08-04 10:34:01'),
(7, 5, 29, '17', 2.00, 1, '100.0', 1, 200.00, '2026-08-04 10:38:59', '2026-08-04 10:38:59'),
(8, 6, 26, '330', 2.00, 1, '100.0', 1, 200.00, '2026-08-04 10:49:56', '2026-08-04 10:49:56'),
(9, 7, 29, '17', 2.00, 1, '100.0', 1, 200.00, '2026-08-04 11:02:59', '2026-08-04 11:02:59'),
(10, 8, 26, '330', 2.00, 1, '100.0', 1, 200.00, '2026-08-04 11:08:10', '2026-08-04 11:08:10'),
(11, 9, 26, '330', 2.00, 1, '100.0', 1, 2000.00, '2026-08-04 12:07:03', '2026-08-04 12:07:03'),
(12, 10, 27, '240', 2.00, 1, '100.0', 1, 200.00, '2026-08-05 04:52:11', '2026-08-05 04:52:11'),
(14, 11, 3, '520', 2.00, 1, '100.0', 1, 2000.00, '2026-08-05 07:07:02', '2026-08-05 07:07:02'),
(15, 12, 27, '240', 2.00, 1, '100.0', 1, 500.00, '2026-08-05 07:09:31', '2026-08-05 07:09:31'),
(16, 13, 26, '330', 2.00, 1, '100.0', 1, 200.00, '2026-08-05 07:37:12', '2026-08-05 07:37:12'),
(17, 14, 9, '1', 20.00, 1, '1000.0', 1, 100.00, '2026-08-06 04:10:52', '2026-08-06 04:10:52'),
(18, 14, 16, '400', 30.00, 1, '1500.0', 1, 10.00, '2026-08-06 04:10:52', '2026-08-06 04:10:52'),
(19, 14, 17, '499', 5.00, 1, '250.0', 1, 10.00, '2026-08-06 04:10:52', '2026-08-06 04:10:52'),
(20, 15, 29, '10', 10.00, 1, '500.0', 1, 100.00, '2026-08-08 06:26:09', '2026-08-08 06:26:09'),
(21, 15, 16, '400', 20.00, 1, '1000.0', 1, 100.00, '2026-08-08 06:26:09', '2026-08-08 06:26:09'),
(22, 16, 30, '7', 20.00, 1, '1000.0', 1, 100.00, '2026-08-08 10:48:56', '2026-08-08 10:48:56'),
(23, 17, 30, '7', 10.00, 1, '500.0', 1, 100.00, '2026-08-08 10:50:11', '2026-08-08 10:50:11'),
(24, 18, 28, '160', 2.00, 1, '100.0', 1, 10.00, '2026-08-11 06:10:29', '2026-08-11 06:10:29'),
(25, 19, 29, '17', 2.00, 1, '100.0', 1, 500.00, '2026-08-11 10:13:54', '2026-08-11 10:13:54'),
(26, 20, 29, '17', 2.00, 1, '100.0', 1, 450.00, '2026-08-11 10:16:31', '2026-08-11 10:16:31'),
(27, 21, 29, '17', 2.00, 1, '100.0', 1, 500.00, '2026-08-11 11:05:48', '2026-08-11 11:05:48'),
(28, 22, 31, '250', 2.00, 1, '100.0', 1, 200.00, '2026-08-11 11:07:15', '2026-08-11 11:07:15'),
(29, 23, 31, '250', 50.00, 1, '2500.0', 1, 100.00, '2026-08-11 11:21:32', '2026-08-11 11:21:32'),
(30, 24, 32, '255', 2.00, 1, '100.0', 1, NULL, '2026-08-12 06:32:16', '2026-08-12 06:32:16'),
(31, 25, 32, '255', 2.00, 1, '100.0', 1, NULL, '2026-08-12 07:33:07', '2026-08-12 07:33:07'),
(32, 26, 33, '258', 2.00, 1, '100.0', 1, 10.00, '2026-08-12 07:50:53', '2026-08-12 07:50:53'),
(33, 27, 35, '258', 58.00, 1, '2900.0', 1, 25.00, '2026-08-12 07:55:49', '2026-08-12 07:55:49'),
(34, 28, 28, '160', 10.00, 1, '500.0', 1, NULL, '2026-08-14 05:52:46', '2026-08-14 05:52:46'),
(35, 28, 17, '499', 2.00, 1, '100.0', 1, NULL, '2026-08-14 05:52:46', '2026-08-14 05:52:46'),
(36, 28, 21, '34', 5.00, 1, '250.0', 1, NULL, '2026-08-14 05:52:46', '2026-08-14 05:52:46'),
(38, 29, 28, '160', 5.00, 1, '250.0', 1, 100.00, '2026-08-17 05:59:59', '2026-08-17 05:59:59'),
(39, 30, 76, '200 lot', 50.00, 1, '2500.0', 1, NULL, '2026-09-21 07:12:47', '2026-09-21 07:12:47'),
(40, 31, 76, '200 lot', 25.00, 1, '1250.0', 1, NULL, '2026-09-21 07:18:21', '2026-09-21 07:18:21'),
(41, 32, 50, '600 lot', 280.00, 1, '14000.0', 1, NULL, '2026-09-23 07:04:45', '2026-09-23 07:04:45'),
(42, 33, 77, '200 lot', 10.00, 1, '500.0', 1, NULL, '2026-09-24 09:10:47', '2026-09-24 09:10:47'),
(43, 34, 84, '102', 1.00, 17, '50.0', 14, NULL, '2026-09-25 09:17:14', '2026-09-25 09:17:14'),
(44, 35, 84, '102', 1.00, 16, '50.0', 14, NULL, '2026-09-25 09:18:37', '2026-09-25 09:18:37'),
(45, 36, 83, '320 lot', 2.00, 1, '100.0', 1, NULL, '2026-09-25 09:32:28', '2026-09-25 09:32:28'),
(46, 37, 82, '182 lot', 1.00, 1, '50.0', 1, NULL, '2026-09-25 09:34:33', '2026-09-25 09:34:33'),
(47, 37, 81, '600 lot', 2.00, 1, '100.0', 1, NULL, '2026-09-25 09:34:33', '2026-09-25 09:34:33'),
(48, 38, 81, '600 lot', 1.00, 1, '50.0', 1, 1.00, '2026-09-29 04:40:54', '2026-09-29 04:40:54'),
(49, 39, 80, 'All Tj', 5.00, 1, '250.0', 1, 0.00, '2026-09-29 04:50:55', '2026-09-29 04:50:55'),
(50, 39, 81, '600 lot', 5.00, 1, '250.0', 1, 1.00, '2026-09-29 04:50:55', '2026-09-29 04:50:55'),
(51, 40, 81, '600 lot', 25.00, 1, '1250.0', 1, 1.00, '2026-09-29 04:52:15', '2026-09-29 04:52:15'),
(52, 41, 80, 'All Tj', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 05:27:38', '2026-09-29 05:27:38'),
(53, 42, 80, 'All Tj', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 05:28:10', '2026-09-29 05:28:10'),
(54, 43, 80, 'All Tj', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 05:28:41', '2026-09-29 05:28:41'),
(55, 44, 88, '6362', 1.00, 17, '50.0', 15, NULL, '2026-09-29 05:29:51', '2026-09-29 05:29:51'),
(56, 45, 83, '320 lot', 1.00, 1, '50.0', 1, 0.00, '2026-09-29 05:30:45', '2026-09-29 05:30:45'),
(57, 46, 80, 'All Tj', 2.00, 1, '100.0', 1, NULL, '2026-09-29 05:31:09', '2026-09-29 05:31:09'),
(58, 47, 80, 'All Tj', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 05:42:28', '2026-09-29 05:42:28'),
(59, 48, 88, '6362', 2.00, 17, '100.0', 15, NULL, '2026-09-29 05:44:14', '2026-09-29 05:44:14'),
(60, 48, 83, '320 lot', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 05:44:14', '2026-09-29 05:44:14'),
(61, 49, 87, '636', 2.00, 17, '100.0', 15, 500.00, '2026-09-29 06:07:36', '2026-09-29 06:07:36'),
(62, 50, 80, 'All Tj', 2.00, 1, '100.0', 1, 0.00, '2026-09-29 06:18:08', '2026-09-29 06:18:08'),
(63, 51, 87, '636', 2.00, 17, '100.0', 15, 500.00, '2026-09-29 06:19:07', '2026-09-29 06:19:07'),
(64, 52, 83, '320 lot', 1.00, 1, '50.0', 1, NULL, '2026-09-29 06:50:55', '2026-09-29 06:50:55'),
(65, 53, 82, '182 lot', 2.00, 1, '100.0', 1, 1.00, '2026-09-29 07:39:10', '2026-09-29 07:39:10'),
(66, 54, 82, '182 lot', 2.00, 1, '100.0', 1, NULL, '2026-09-29 07:58:11', '2026-09-29 07:58:11'),
(67, 55, 83, '320 lot', 1.00, 1, '50.0', 1, NULL, '2026-09-29 08:26:15', '2026-09-29 08:26:15'),
(68, 58, 83, '320 lot', 5.00, 1, '250.0', 1, NULL, '2026-09-29 08:34:58', '2026-09-29 08:34:58'),
(69, 59, 89, '000000', 2.00, 1, '100.0', 1, NULL, '2026-09-30 04:55:41', '2026-09-30 04:55:41'),
(70, 60, 90, '1234', 8.00, 1, '400.0', 1, NULL, '2026-09-30 05:03:28', '2026-09-30 05:03:28'),
(71, 61, 89, '000000', 3.00, 1, '150.0', 1, NULL, '2026-09-30 05:13:04', '2026-09-30 05:13:04'),
(72, 62, 90, '1234', 2.00, 1, '100.0', 1, NULL, '2026-09-30 05:14:23', '2026-09-30 05:14:23'),
(73, 63, 90, '1234', 2.00, 1, '100.0', 1, NULL, '2026-09-30 05:17:05', '2026-09-30 05:17:05'),
(74, 64, 89, '000000', 2.00, 1, '100.0', 1, NULL, '2026-09-30 05:18:16', '2026-09-30 05:18:16'),
(75, 65, 90, '1234', 5.00, 1, '250.0', 1, NULL, '2026-09-30 05:35:00', '2026-09-30 05:35:00'),
(76, 66, 91, '55555', 5.00, 1, '250.0', 1, 10.00, '2026-09-30 05:37:38', '2026-09-30 05:37:38'),
(77, 67, 92, '2222', 5.00, 1, '250.0', 1, 10.00, '2026-09-30 05:38:39', '2026-09-30 05:38:39'),
(78, 68, 93, '500', 35.00, 1, '1750.0', 1, NULL, '2026-10-05 06:27:46', '2026-10-05 06:27:46'),
(79, 69, 104, '488 lot', 40.00, 1, '2000.0', 1, NULL, '2026-10-05 07:19:44', '2026-10-05 07:19:44'),
(80, 70, 110, '667', 20.00, 1, '1000.0', 1, NULL, '2026-10-05 13:29:47', '2026-10-05 13:29:47'),
(81, 71, 108, '182', 1.00, 1, '50.0', 1, NULL, '2026-10-05 13:33:35', '2026-10-05 13:33:35'),
(82, 72, 69, '210 lot', 4.00, 1, '200.0', 1, NULL, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(83, 72, 68, '500 lot', 5.00, 1, '250.0', 1, NULL, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(84, 72, 136, '488', 60.00, 1, '3000.0', 1, NULL, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(85, 73, 133, '600', 130.00, 1, '6500.0', 1, NULL, '2026-10-06 07:21:48', '2026-10-06 07:21:48'),
(86, 74, 133, '600', 15.00, 1, '750.0', 1, NULL, '2026-10-06 07:27:00', '2026-10-06 07:27:00'),
(87, 75, 133, '600', 75.00, 1, '3750.0', 1, NULL, '2026-10-06 07:29:05', '2026-10-06 07:29:05'),
(88, 76, 137, '112', 21.00, 1, '1050.0', 1, NULL, '2026-10-06 07:30:30', '2026-10-06 07:30:30'),
(89, 77, 134, '600', 50.00, 1, '2500.0', 1, NULL, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(90, 78, 133, '600', 5.00, 1, '250.0', 1, NULL, '2026-10-06 12:15:58', '2026-10-06 12:15:58'),
(91, 79, 133, '600', 5.00, 1, '250.0', 1, NULL, '2026-10-06 12:17:00', '2026-10-06 12:17:00'),
(92, 80, 136, '488', 10.00, 1, '500.0', 1, NULL, '2026-10-06 12:47:23', '2026-10-06 12:47:23'),
(93, 81, 58, 'LOT001', 50.00, 1, '10', 1, 150.00, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(94, 81, 58, 'LOT002', 50.00, 1, '10', 1, 180.00, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(95, 82, 58, 'LOT001', 50.00, 1, '10', 1, 150.00, '2026-10-06 23:36:53', '2026-10-06 23:36:53'),
(96, 82, 58, 'LOT002', 50.00, 1, '10', 1, 180.00, '2026-10-06 23:36:53', '2026-10-06 23:36:53');

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
('G3jfWSXxw6HYRt1k0arwC5Q5biOO3C78ItfPD738', NULL, '103.24.232.25', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjA5N1lPcDJ2T0xTcjFIRXdDblJxb3FOSFJzQXNnOGdKSm5EaDhndiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1787065545),
('h3IbSs8UAq7Pp2nOEu2qY1IwnvArku8KCgr02Ygh', NULL, '106.51.25.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHJZY25BNFhUdWhsb3N1YWtFd3pCYmJ3S3FrUVFCQmR1MGJuNDg1RyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783339088),
('HDmpmIlMnek2e932teGC3dBnNXaM1vBGCn64ww6E', NULL, '106.51.26.85', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzA3WTlQNXpsdFozQVZWWGtOU0d5VDdnODF2WDZYMVhOWFRMbURZeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4vc2VydmVyLWNvbW1hbmRzL2NsZWFyLWNhY2hlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1784892402),
('MolAzttZMSsrgckRAJcgaySSEsnxiVwkqhtjCOKk', NULL, '23.27.145.64', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmNra0xZbzdGT01wVlBsTDFQdngzQm9MVFhIa0dlam5vVEJNYVN3aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784394612),
('SUzE8L6IcPOl3pkTJaiBFqhEIct5UJYNuXPM9zCV', NULL, '106.51.26.62', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3A0ZGVCT290MWlqS3ZUUVJjRWJIakV2YzFxTW5QcXRoMzRHWktJOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1783141781),
('TqJOrWGtrP0KcWONIOnPfl7KTtKmXflP5r9Kh90c', NULL, '158.222.113.189', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiajA3a043bHBQR3VibExvWERjVGdJcm5MS0xqZzd2V0ZLWVRiZ2F0SiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1785251062),
('xJBFm2nqhx1OncvDJwvrot568u7TFesA8oQGBKuh', NULL, '98.84.1.175', 'RecordedFuture Global Inventory Crawler', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidDZDYjQ1R3VraTdIUUtHZHBKdEF5YzAxdTNSYlVWekZyaUF2WXJ1WSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1784375870),
('xRT2E7BbP8swx5wyb1mubKG8EfblFfvkR7fDUSEK', NULL, '106.51.25.224', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ09CMkJVSjNVcVpHN1QxUkJvOVFUQTgwNUpKOUJqTnRNblc3Y2ZEdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4vc2VydmVyLWNvbW1hbmRzL2NsZWFyLWNhY2hlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1783330916),
('xsfkjGx3qyvSWLCtXlRlPlYQxfJ7APxFhc1FewJn', NULL, '209.147.81.7', 'Mozilla/5.0 (X11; Linux i686; rv:109.0) Gecko/20100101 Firefox/120.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiblNTYkdHZVFxS3R6YzZRdDVwUklGWDhpRUp6c015dDFCcno1MjFkdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vYmtzLmRlYWxvdXMuaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1785596574);

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
  `rate` decimal(15,2) DEFAULT NULL,
  `rate_stock` decimal(15,2) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `stock_id`, `brand_name`, `stock_name`, `lott_number`, `units`, `mt`, `stock_code`, `branch_id`, `unit_id`, `alter_unit_id`, `unit_value`, `alter_unit_value`, `rate`, `rate_stock`, `created_by`, `created_at`, `updated_at`) VALUES
(58, 'b42fe612-bd14-4395-ae96-c42bf6457018', 'TJ', 'Dipali', '650 lot', 10, 10460.00, '58', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:17:20', '2026-10-06 23:36:53'),
(60, 'b9fee279-f62d-4687-8d57-2182b1453a5a', '80-90', 'Sri Laxmi Venkateswara', '500 lot', 38, 1900.00, '60', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:18:41', '2026-10-06 06:21:06'),
(61, '44e27712-3d43-4930-a8aa-3b3ab7793084', '70-80', 'Chappan', '600 lot', 3, 150.00, '61', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:19:31', '2026-10-06 06:21:53'),
(62, '369402ed-21a4-450e-b225-caf1d2e9335c', '80-90', 'BABA', '263 lot', 64, 3200.00, '62', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:20:07', '2026-10-06 06:22:30'),
(63, 'a4398ca0-3d5f-4f1f-858d-75b63ee34315', '70-80', 'BABA', '37 lot', 9, 450.00, '63', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:23:36', '2026-09-21 06:23:36'),
(65, '1f1560d5-4379-4741-bd52-5e2cc218a869', '50-60', 'Kandiyar (PkS DLG)', '240', 240, 12000.00, '65', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:25:49', '2026-10-06 06:48:16'),
(67, '85a34803-debc-45bd-b2a2-66cf8b43c962', '60-70', 'Anand retty', '49 lot', 49, 2450.00, '67', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:27:38', '2026-09-21 06:27:38'),
(68, '3aa282d5-ee2e-4197-8199-6fa8140d1b1c', '80-90', 'Kandiyar New', '500 lot', 84, 4200.00, '68', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:48:16', '2026-10-06 07:17:59'),
(69, 'fc40feb5-ffe1-40e8-8663-1ce6fec4bd63', '80-90', 'Ganapathi', '210 lot', 55, 2750.00, '69', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:48:56', '2026-10-06 07:17:59'),
(71, 'dd314a14-a40c-4750-85e5-994f635508c1', '90-100', 'Baba', '312', 212, 10600.00, '71', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:50:08', '2026-10-06 06:54:58'),
(73, '394791e8-3d1c-476d-a8f4-d25f130d9f51', '80-90', 'Baba', '31 lot', 31, 1550.00, '73', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-09-21 06:51:26', '2026-09-21 06:51:26'),
(107, '391481a8-d0bc-4493-815a-436d9e5e9e93', '50-60', 'KANDIYAR', '329', 46, 2300.00, '76', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:08:44', '2026-10-05 08:08:44'),
(108, 'bb2ad3ea-d630-4e75-9771-390da80a6021', '50-60', 'THIRUVANNAMALAI', '182', 79, 3950.00, '77', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:09:27', '2026-10-05 13:33:35'),
(109, 'f13a2cda-d275-4e5a-b073-acfb4322f658', '50-60', 'SAMINATHAN', '320', 26, 1300.00, '78', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:10:03', '2026-10-05 08:10:03'),
(110, '467510a8-8df2-45a0-b8f1-8a0c436c77cc', '50-60', 'KANDIYAR (PLASTIC)', '667', 621, 31050.00, '79', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:11:57', '2026-10-05 13:29:47'),
(111, '06997d3b-9dac-46a9-9dfe-f419b73afe74', 'Sp mill', 'AC GUDOWN', '500 lot', 142, 7100.00, '80', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:12:36', '2026-10-05 08:12:36'),
(112, '0179b4ee-af4a-45ec-8029-5b0e75ddaad5', 'sp mill', 'KANDIYAR', '500 lot', 218, 10900.00, '81', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:13:26', '2026-10-05 08:13:26'),
(113, 'd311b6af-004a-4154-84af-d0857d1c979d', '50-60', 'SKR VINOTH', '200 lot', 93, 4650.00, '82', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:13:50', '2026-10-05 08:13:50'),
(114, '5694d248-3d63-480f-bed9-09485470c864', 'Return', 'ply', '67 lot', 49, 2450.00, '83', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:14:23', '2026-10-05 08:14:23'),
(115, '233f2123-2214-44e3-924d-42d7c5b3c010', 'return', 'ply', '20 lot', 10, 500.00, '84', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:14:52', '2026-10-05 08:14:52'),
(116, 'b3818aac-8741-4597-adbf-a9c7a58b19d7', 'TJ', 'CHAPPAN', '15', 15, 750.00, '85', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:15:24', '2026-10-05 08:15:24'),
(117, 'b14c8e52-818b-4825-8abd-256b72cd10de', '80-90', 'AC GUDOWN', '600 lot', 214, 10700.00, '86', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:17:46', '2026-10-05 08:17:46'),
(118, '3055cd87-b019-41f0-8b88-0f0079f8959c', '80-90', 'RAM LAXMAN', '200 lot', 70, 3500.00, '87', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:18:24', '2026-10-05 08:18:24'),
(119, '70ba0f5d-9718-4ec8-820d-d02b39558aa8', 'No brand', 'ANAND REDDY', '151 lot', 41, 2050.00, '88', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:19:25', '2026-10-05 08:19:25'),
(120, '66a83abe-f511-42b5-9589-3e6faaf09b34', 'No brand', 'KANDIYAR', '333', 97, 4850.00, '89', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:20:25', '2026-10-06 08:04:59'),
(121, '81d8d3c4-d846-460d-a74e-893569b4da94', '80-90', 'NARASIMA SWAMY', '496 lot', 71, 3550.00, '90', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:21:27', '2026-10-05 08:21:27'),
(122, '60e1d311-469e-47a6-bee3-9f8c2d5dfd59', '80-90', 'GOPINATH', '600 lot', 180, 9000.00, '91', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:22:03', '2026-10-05 08:22:03'),
(123, '3902ffb2-5520-4dbf-9e8b-c89fd79a9ab1', '80-90', 'VENKATESWARA', '500 lot', 108, 5400.00, '92', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:22:37', '2026-10-05 08:22:37'),
(124, '9f35f89d-a075-4abb-a968-9f140fd478c8', 'THELUNGANA', 'THEJASHREE', '600 lot', 326, 16300.00, '93', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:23:25', '2026-10-05 08:23:25'),
(125, '9823751c-351d-4e32-8211-6b1cbc010a98', 'THELUNGANA', 'VANAPARUTHI', '204', 180, 9000.00, '94', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:25:42', '2026-10-06 08:04:40'),
(126, '5bee6f3f-c9c6-45b7-bbcb-49c9bae08472', 'OTHER', 'AC GUDOWN', '200 lot', 172, 8600.00, '95', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:26:26', '2026-10-05 08:26:26'),
(128, '1ebc05b9-cdc6-4ae7-8f53-e4db3dc581df', 'ALANGUDI', 'GANAPATHI', '240', 140, 7000.00, '97', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:29:33', '2026-10-05 08:29:33'),
(129, '64a5c6b7-b808-4332-8958-5c17c01cb20c', '80-90', 'BAVANI (1)', '500', 382, 19100.00, '98', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:30:57', '2026-10-06 08:03:09'),
(130, '16d1c927-0d75-4934-91cd-2148215bb77a', '80-90', 'BAVANI 2', '500', 500, 25000.00, '99', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:31:32', '2026-10-06 08:03:25'),
(131, '2a32606a-c92b-40c6-b1d5-d04f1f9d60af', 'sp mill', 'GANAPATHI', '132', 132, 6600.00, '100', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-05 08:57:48', '2026-10-06 08:02:29'),
(133, '45eddb1d-d377-4aca-8a10-ebc50a89c27d', '60-70', 'sri Sainath industries', '600', 286, 14300.00, '101', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 06:07:03', '2026-10-06 12:17:00'),
(134, '924bdad6-37e9-433d-9fd5-e24c96228856', '80-90', 'The Jasree trading company', '600', 176, 8800.00, '102', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 06:26:05', '2026-10-06 12:14:15'),
(135, '7dd79650-900b-4f45-bfe9-e40bf7f00afa', '90-100', 'ganapathy', '200', 183, 9150.00, '103', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 06:44:59', '2026-10-06 06:44:59'),
(136, 'e6e21a82-8d81-45f8-96ad-73e356a3a50a', '90-100', 'Bhavani sankar&co', '488', 88, 4400.00, '104', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 06:50:29', '2026-10-06 12:47:23'),
(137, '389cc3d4-7f6f-421c-b4fc-cfaea89a223a', '90-100', 'Bhavani sankar &co', '112', 0, 0.00, '105', 22, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 06:51:45', '2026-10-06 07:30:30'),
(138, '14cc7548-be99-42ac-b50c-e91cff3e44dc', 'THELUNGANA', 'VANAPARUTHI', '313 LOT', 313, 15650.00, '106', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 08:08:49', '2026-10-06 08:08:49'),
(139, '8a37833c-b9c1-4cb7-8889-a780c4fdb52b', '50-60', 'GANAPATHI', '68 LOT', 68, 3400.00, '107', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 08:09:48', '2026-10-06 08:09:48'),
(140, 'faa3e5c2-b3e1-498f-9414-fe0e07a57a3b', 'MEDIUM', 'old', '85', 85, 4250.00, '108', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 08:11:42', '2026-10-06 08:11:42'),
(141, '17c3fe9a-2ae3-4ace-af31-4d42452683ce', 'MEDIUM', 'New', '91', 91, 4550.00, '109', 10, 1, 1, 'Bags', 'KGs', NULL, NULL, 20, '2026-10-06 08:12:12', '2026-10-06 08:12:12');

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
(159, 110, 70, 20.00, 'Bags', 'sale', '2026-10-05 00:00:00', 20, '2026-10-05 13:29:47', '2026-10-05 13:29:47'),
(160, 110, 70, 1000.00, 'KGs', 'sale', '2026-10-05 00:00:00', 20, '2026-10-05 13:29:47', '2026-10-05 13:29:47'),
(161, 108, 71, 1.00, 'Bags', 'sale', '2026-10-05 00:00:00', 20, '2026-10-05 13:33:35', '2026-10-05 13:33:35'),
(162, 108, 71, 50.00, 'KGs', 'sale', '2026-10-05 00:00:00', 20, '2026-10-05 13:33:35', '2026-10-05 13:33:35'),
(163, 69, 72, 4.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(164, 69, 72, 200.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(165, 68, 72, 5.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(166, 68, 72, 250.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(167, 136, 72, 60.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(168, 136, 72, 3000.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(169, 133, 73, 130.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:21:48', '2026-10-06 07:21:48'),
(170, 133, 73, 6500.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:21:48', '2026-10-06 07:21:48'),
(171, 133, 74, 15.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:27:00', '2026-10-06 07:27:00'),
(172, 133, 74, 750.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:27:00', '2026-10-06 07:27:00'),
(173, 133, 75, 75.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:29:05', '2026-10-06 07:29:05'),
(174, 133, 75, 3750.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:29:05', '2026-10-06 07:29:05'),
(175, 137, 76, 21.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:30:30', '2026-10-06 07:30:30'),
(176, 137, 76, 1050.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 07:30:30', '2026-10-06 07:30:30'),
(177, 134, 77, 50.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(178, 134, 77, 2500.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(179, 133, 78, 5.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:15:58', '2026-10-06 12:15:58'),
(180, 133, 78, 250.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:15:58', '2026-10-06 12:15:58'),
(181, 133, 79, 5.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:17:00', '2026-10-06 12:17:00'),
(182, 133, 79, 250.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:17:00', '2026-10-06 12:17:00'),
(183, 136, 80, 10.00, 'Bags', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:47:23', '2026-10-06 12:47:23'),
(184, 136, 80, 500.00, 'KGs', 'sale', '2026-10-06 00:00:00', 20, '2026-10-06 12:47:23', '2026-10-06 12:47:23'),
(185, 58, 81, 50.00, 'Bags', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(186, 58, 81, 10.00, 'KGs', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(187, 58, 81, 50.00, 'Bags', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(188, 58, 81, 10.00, 'KGs', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:34:16', '2026-10-06 23:34:16'),
(189, 58, 82, 50.00, 'Bags', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:36:53', '2026-10-06 23:36:53'),
(190, 58, 82, 10.00, 'KGs', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:36:53', '2026-10-06 23:36:53'),
(191, 58, 82, 50.00, 'Bags', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:36:53', '2026-10-06 23:36:53'),
(192, 58, 82, 10.00, 'KGs', 'sale', '2026-07-16 05:45:00', 20, '2026-10-06 23:36:53', '2026-10-06 23:36:53');

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
(8, 'pks transport', 10, '2026-07-07 05:21:38', '2026-07-07 05:39:54'),
(9, 'Rpt transport', 10, '2026-08-01 12:24:05', '2026-08-04 05:55:05'),
(10, 'testing', 10, '2026-08-10 05:24:22', '2026-08-10 05:24:22'),
(11, 'testing', 10, '2026-08-10 05:41:00', '2026-08-10 05:41:00');

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
(1, 'Bags', '2026-07-07 05:35:10', '2026-07-30 07:27:25'),
(16, '1 per kg', '2026-09-15 09:37:57', '2026-09-15 09:37:57'),
(17, '1 per kg', '2026-09-15 09:37:58', '2026-09-15 09:37:58');

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
(20, 'admin', 'testinguser@gmail.com', NULL, '$2y$12$xzum.J1zfLcrB6z5E3JN2eW2GxdJ4NMbx/A6GRBrT1LQsq94ybQW2', 'admin', '9876543210', NULL, 1, NULL, '2026-06-30 02:40:29', '2026-06-30 02:40:29'),
(21, 'ajis', 'ajis@gmail.com', NULL, '$2y$12$LwK9/E04geGiZ9b6SpeIgeAMoNVbTDTw2Kg8hqUlXeOetAgN8UvUC', 'user', '9489042187', NULL, 1, NULL, '2026-06-30 02:40:59', '2026-06-30 02:40:59'),
(22, 'test001', 'test001@gmail.com', NULL, '$2y$12$OpgizbwUZ4G/tmum.K5fKev/KHuCelqoXLVoNEghQ0BplwmkBFGgm', 'user', '8523692581', NULL, 1, NULL, '2026-07-04 04:27:59', '2026-07-04 04:27:59');

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
(18, 'lorry', 'TN57BP0206', 1, '2026-07-09 11:01:50', '2026-07-09 11:01:50'),
(22, 'lorry', 'TN58NN67', 1, '2026-08-11 10:51:38', '2026-08-11 10:51:38'),
(23, 'lorry', 'TN56GgF5', 1, '2026-08-11 11:21:32', '2026-08-11 11:21:32'),
(24, 'lorry', 'TN57NN67', 1, '2026-08-12 07:47:28', '2026-08-12 07:47:28'),
(25, 'lorry', 'tn57bq0294', 1, '2026-08-14 05:52:46', '2026-08-14 05:52:46'),
(26, 'local', 'muthu thattu vandi', 1, '2026-09-21 07:12:47', '2026-09-21 07:12:47'),
(27, 'local', 'jeeva vandi', 1, '2026-09-21 07:18:21', '2026-09-21 07:18:21'),
(28, 'local', 'SOR lorry', 1, '2026-09-23 07:04:45', '2026-09-23 07:04:45'),
(29, 'lorry', 'tn57ee3421', 1, '2026-09-29 04:40:54', '2026-09-29 04:40:54'),
(30, 'lorry', 'th571233', 1, '2026-09-29 04:50:54', '2026-09-29 04:50:54'),
(31, 'lorry', 'tn 57ee3421', 1, '2026-09-29 04:52:15', '2026-09-29 04:52:15'),
(32, 'lorry', 'tn57aa2021', 1, '2026-09-30 05:35:00', '2026-09-30 05:35:00'),
(33, 'local', 'no', 1, '2026-10-05 07:19:44', '2026-10-05 07:19:44'),
(34, 'local', 'IP thampi vandi', 1, '2026-10-05 13:29:47', '2026-10-05 13:29:47'),
(35, 'local', 'CAR', 1, '2026-10-05 13:33:35', '2026-10-05 13:33:35'),
(36, 'lorry', 'KL70G2280', 1, '2026-10-06 07:17:59', '2026-10-06 07:17:59'),
(37, 'lorry', 'KL70H2874', 1, '2026-10-06 07:21:48', '2026-10-06 07:21:48'),
(38, 'lorry', 'Tn67F4945', 1, '2026-10-06 07:27:00', '2026-10-06 07:27:00'),
(39, 'lorry', 'TN41U1450', 1, '2026-10-06 07:29:05', '2026-10-06 07:29:05'),
(40, 'lorry', 'KL70H9505', 1, '2026-10-06 07:30:30', '2026-10-06 07:30:30'),
(41, 'lorry', 'KA19AB2796', 1, '2026-10-06 12:14:15', '2026-10-06 12:14:15'),
(42, 'lorry', 'KL70J2399', 1, '2026-10-06 12:47:23', '2026-10-06 12:47:23');

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
  MODIFY `alter_unit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `branch_prices`
--
ALTER TABLE `branch_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `purchase_details`
--
ALTER TABLE `purchase_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `sale_details`
--
ALTER TABLE `sale_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `transporters`
--
ALTER TABLE `transporters`
  MODIFY `transporter_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `vehicle_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
