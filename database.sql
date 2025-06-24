-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2020 at 08:10 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spothire_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_backgrounds`
--

CREATE TABLE `company_backgrounds` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `industry` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_data` text COLLATE utf8mb4_unicode_ci,
  `inception_date` date DEFAULT NULL,
  `is_enrolled` tinyint(1) NOT NULL,
  `annual_turnover` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_employees` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_backgrounds`
--

INSERT INTO `company_backgrounds` (`id`, `user_id`, `industry`, `location`, `location_data`, `inception_date`, `is_enrolled`, `annual_turnover`, `total_employees`, `created_at`, `updated_at`) VALUES
(1, 3, 'software Industry', 'Uttara, Dhaka, bangladesh', NULL, '2020-04-30', 0, '223', 4, '2020-04-10 20:11:43', '2020-05-19 08:13:40');

-- --------------------------------------------------------

--
-- Table structure for table `company_images`
--

CREATE TABLE `company_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_sm` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_images`
--

INSERT INTO `company_images` (`id`, `user_id`, `image`, `image_sm`, `description`, `created_at`, `updated_at`) VALUES
(1, 3, '042820202348235ea86c6777962.jpg', '042820202348245ea86c681e1e1.jpg', NULL, '2020-04-10 20:12:21', '2020-04-28 17:48:24'),
(2, 3, '042820202341575ea86ae5852a2.jpg', '042820202341575ea86ae5bb1a4.jpg', NULL, '2020-04-27 20:51:19', '2020-04-28 17:41:57'),
(3, 3, '042820202324465ea866de90190.jpg', '042820202324475ea866df9b224.jpg', NULL, '2020-04-28 17:24:47', '2020-04-28 17:24:47'),
(4, 3, '042920200007565ea870fc9a8c2.jpg', '042920200007565ea870fccc253.jpg', NULL, '2020-04-28 17:39:36', '2020-04-28 18:07:56');

-- --------------------------------------------------------

--
-- Table structure for table `degrees`
--

CREATE TABLE `degrees` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `degrees`
--

INSERT INTO `degrees` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bachelor of Architecture', 1, NULL, NULL),
(2, 'Bachelor of Business Administration (BBA)', 1, NULL, NULL),
(3, 'Bachelor of Arts in English', 1, NULL, NULL),
(4, 'Bachelor of Arts in Bengali', 1, NULL, NULL),
(5, 'Bachelor of Arts in Arabic', 1, NULL, NULL),
(6, 'Bachelor of Arts in Persian Language and Literature', 1, NULL, NULL),
(7, 'Bachelor of Arts in History', 1, NULL, NULL),
(8, 'Bachelor of Arts in Philosophy', 1, NULL, NULL),
(9, 'Bachelor of Arts in Islamic Studies', 1, NULL, NULL),
(10, 'Bachelor of Arts in Theatre and Performance Studies', 1, NULL, NULL),
(11, 'Bachelor of Arts in Linguistics', 1, NULL, NULL),
(12, 'Bachelor of Arts in Music', 1, NULL, NULL),
(13, 'Bachelor of Arts in Dance', 1, NULL, NULL),
(14, 'Bachelor of Arts in Biotechnology', 1, NULL, NULL),
(15, 'Bachelor of Social Science, Media Studies and Journalism', 1, NULL, NULL),
(16, 'Bachelor of Social Science in Economics', 1, NULL, NULL),
(17, 'Bachelor of Social Science in Political Science', 1, NULL, NULL),
(18, 'Bachelor of Social Science in International Relations', 1, NULL, NULL),
(19, 'Bachelor of Social Science in Sociology', 1, NULL, NULL),
(20, 'Bachelor of Social Science in Mass Communication & Journalism', 1, NULL, NULL),
(21, 'Bachelor of Social Science in Public Administration', 1, NULL, NULL),
(22, 'Bachelor of Social Science in Population Sciences', 1, NULL, NULL),
(23, 'Bachelor of Social Science in Peace and Conflict Studies', 1, NULL, NULL),
(24, 'Bachelor of Social Science in Women and Gender Studies', 1, NULL, NULL),
(25, 'Bachelor of Social Science in Development Studies', 1, NULL, NULL),
(26, 'Bachelor of Social Science in Television, Film and Photography', 1, NULL, NULL),
(27, 'Bachelor of Social Science in Criminology', 1, NULL, NULL),
(28, 'Bachelor of Social Science in Communication Disorders', 1, NULL, NULL),
(29, 'Bachelor of Social Science in Printing and Publication Studies', 1, NULL, NULL),
(30, 'Bachelor of Social Science in Anthropology', 1, NULL, NULL),
(31, 'Bachelor of Science in Computer Science & Engineering', 1, NULL, NULL),
(32, 'Bachelor of Science in Computer Science', 1, NULL, NULL),
(33, 'Bachelor of Science in Electronic And Communication Engineering', 1, NULL, NULL),
(34, 'Bachelor of Science in Electrical And Electronic Engineering', 1, NULL, NULL),
(35, 'Bachelor of Science in Civil Engineering', 1, NULL, NULL),
(36, 'Bachelor of Science in Textile Engineering', 1, NULL, NULL),
(37, 'Bachelor of Science in Industrial and Production Engineering', 1, NULL, NULL),
(38, 'Bachelor of Science in Mechanical Engineering', 1, NULL, NULL),
(39, 'Bachelor of Science in Water Resources Engineering', 1, NULL, NULL),
(40, 'Bachelor of Science in Naval Architecture and Marine Engineering', 1, NULL, NULL),
(41, 'Bachelor of Science in Applied Physics & Electronics', 1, NULL, NULL),
(42, 'Bachelor of Science in Applied Chemistry & Chemical Engineering\r\n', 1, NULL, NULL),
(43, 'Bachelor of Science in Nuclear Engineering', 1, NULL, NULL),
(44, 'Bachelor of Science in Robotics and Mechatronics Engineering', 1, NULL, NULL),
(45, 'Bachelor of Laws [LL. B. (Hons.)]', 1, NULL, NULL),
(46, 'Bachelor of Science in Mathematics', 1, NULL, NULL),
(47, 'Bachelor of Science in Microbiology', 1, NULL, NULL),
(48, 'Bachelor of Science in Nursing', 1, NULL, NULL),
(49, 'Bachelor of Science in Public Health Nursing', 1, NULL, NULL),
(50, 'Bachelor of Science in Physiotherapy', 1, NULL, NULL),
(51, 'Bachelor of Science in Health Technology', 1, NULL, NULL),
(52, 'Bachelor of Science in Leather Technology', 1, NULL, NULL),
(53, 'Bachelor of Science in Home-Economics', 1, NULL, NULL),
(54, 'Bachelor of Science in Technical Education', 1, NULL, NULL),
(55, 'Bachelor of Science In Physics', 1, NULL, NULL),
(56, 'Bachelor of Science In Chemistry', 1, NULL, NULL),
(57, 'Bachelor of Science In Statistics', 1, NULL, NULL),
(58, 'Bachelor of Science In Theoretical Physics', 1, NULL, NULL),
(59, 'Bachelor of Science In Biomedical Physics & Technology', 1, NULL, NULL),
(60, 'Bachelor of Science In Applied Mathematics', 1, NULL, NULL),
(61, 'Bachelor of Science In Theoretical and Computational Chemistry', 1, NULL, NULL),
(62, 'Bachelor of Science In Soil, Water & Environment', 1, NULL, NULL),
(63, 'Bachelor of Science In Botany', 1, NULL, NULL),
(64, 'Bachelor of Science In Zoology', 1, NULL, NULL),
(65, 'Bachelor of Science In Biochemistry and Molecular Biology', 1, NULL, NULL),
(66, 'Bachelor of Science In Psychology', 1, NULL, NULL),
(67, 'Bachelor of Science In Fisheries', 1, NULL, NULL),
(68, 'Bachelor of Science In Genetic Engineering and Biotechnology', 1, NULL, NULL),
(69, 'Bachelor of Science In Educational and Counselling Psychology', 1, NULL, NULL),
(70, 'Bachelor of Pharmacy', 1, NULL, NULL),
(71, 'Master of Architecture', 1, NULL, NULL),
(72, 'Master of Business Administration (MBA)', 1, NULL, NULL),
(73, 'Master of Arts in English ', 1, NULL, NULL),
(74, 'Master of Arts in Bengali', 1, NULL, NULL),
(75, 'Master of Arts in Arabic', 1, NULL, NULL),
(76, 'Master of Arts in Persian Language and Literature', 1, NULL, NULL),
(77, 'Master of Arts in History', 1, NULL, NULL),
(78, 'Master of Arts in Philosophy', 1, NULL, NULL),
(79, 'Master of Arts in Islamic Studies', 1, NULL, NULL),
(80, 'Master of Arts in Theatre and Performance Studies', 1, NULL, NULL),
(81, 'Master of Arts in Linguistics', 1, NULL, NULL),
(82, 'Master of Arts in Music', 1, NULL, NULL),
(83, 'Master of Arts in Dance', 1, NULL, NULL),
(84, 'Master of Arts in Biotechnology', 1, NULL, NULL),
(85, 'Master of Social Science, Media Studies and Journalism', 1, NULL, NULL),
(86, 'Master of Social Science in Economics', 1, NULL, NULL),
(87, 'Master of Social Science in Political Science', 1, NULL, NULL),
(88, 'Master of Social Science in International Relations', 1, NULL, NULL),
(89, 'Master of Social Science in Sociology', 1, NULL, NULL),
(90, 'Master of Social Science in Mass Communication & Journalism', 1, NULL, NULL),
(91, 'Master of Social Science in Public Administration', 1, NULL, NULL),
(92, 'Master of Social Science in Population Sciences', 1, NULL, NULL),
(93, 'Master of Social Science in Peace and Conflict Studies', 1, NULL, NULL),
(94, 'Master of Social Science in Women and Gender Studies', 1, NULL, NULL),
(95, 'Master of Social Science in Development Studies', 1, NULL, NULL),
(96, 'Master of Social Science in Television, Film and Photography', 1, NULL, NULL),
(97, 'Master of Social Science in Criminology', 1, NULL, NULL),
(98, 'Master of Social Science in Communication Disorders', 1, NULL, NULL),
(99, 'Master of Social Science in Printing and Publication Studies', 1, NULL, NULL),
(100, 'Master of Social Science in Anthropology', 1, NULL, NULL),
(101, 'Master of Science in Computer Science & Engineering', 1, NULL, NULL),
(102, 'Master of Science in Computer Science', 1, NULL, NULL),
(103, 'Master of Science in Electronic And Communication Engineering', 1, NULL, NULL),
(104, 'Master of Science in Electrical And Electronic Engineering', 1, NULL, NULL),
(105, 'Master of Science in Civil Engineering', 1, NULL, NULL),
(106, 'Master of Science in Textile Engineering', 1, NULL, NULL),
(107, 'Master of Science in Industrial and Production Engineering', 1, NULL, NULL),
(108, 'Master of Science in Mechanical Engineering', 1, NULL, NULL),
(109, 'Master of Science in Water Resources Engineering', 1, NULL, NULL),
(110, 'Master of Science in Naval Architecture and Marine Engineering', 1, NULL, NULL),
(111, 'Master of Science in Applied Physics & Electronics', 1, NULL, NULL),
(112, 'Master of Science in Applied Chemistry & Chemical Engineering', 1, NULL, NULL),
(113, 'Master of Science in Nuclear Engineering', 1, NULL, NULL),
(114, 'Master of Science in Robotics and Mechatronics Engineering', 1, NULL, NULL),
(115, 'Master of Laws [LL. B. (Hons.)]', 1, NULL, NULL),
(116, 'Master of Science in Mathematics', 1, NULL, NULL),
(117, 'Master of Science in Microbiology', 1, NULL, NULL),
(118, 'Master of Science in Nursing', 1, NULL, NULL),
(119, 'Master of Science in Public Health Nursing', 1, NULL, NULL),
(120, 'Master of Science in Physiotherapy', 1, NULL, NULL),
(121, 'Master of Science in Health Technology', 1, NULL, NULL),
(122, 'Master of Science in Leather Technology', 1, NULL, NULL),
(123, 'Master of Science in Home-Economics', 1, NULL, NULL),
(124, 'Master of Science in Technical Education', 1, NULL, NULL),
(125, 'Master of Science In Physics', 1, NULL, NULL),
(126, 'Master of Science In Chemistry', 1, NULL, NULL),
(127, 'Master of Science In Statistics', 1, NULL, NULL),
(128, 'Master of Science In Theoretical Physics', 1, NULL, NULL),
(129, 'Master of Science In Biomedical Physics & Technology', 1, NULL, NULL),
(130, 'Master of Science In Applied Mathematics', 1, NULL, NULL),
(131, 'Master of Science In Theoretical and Computational Chemistry', 1, NULL, NULL),
(132, 'Master of Science In Soil, Water & Environment', 1, NULL, NULL),
(133, 'Master of Science In Botany', 1, NULL, NULL),
(134, 'Master of Science In Zoology', 1, NULL, NULL),
(135, 'Master of Science In Biochemistry and Molecular Biology', 1, NULL, NULL),
(136, 'Master of Science In Psychology', 1, NULL, NULL),
(137, 'Master of Science In Fisheries', 1, NULL, NULL),
(138, 'Master of Science In Genetic Engineering and Biotechnology', 1, NULL, NULL),
(139, 'Master of Science In Educational and Counselling Psychology', 1, NULL, NULL),
(140, 'Master of Pharmacy', 1, NULL, NULL),
(141, 'Bachelor of Medicine (MBBS)', 1, NULL, NULL),
(142, 'Bachelor of Surgery', 1, NULL, NULL),
(143, 'Master of Clinical Medicine (MCM)', 1, NULL, NULL),
(144, 'Master of Medical Science (MMSc, MMedSc)', 1, NULL, NULL),
(145, 'Master of Medicine (MM, MMed)', 1, NULL, NULL),
(146, 'Master of Philosophy (MPhil)', 1, NULL, NULL),
(147, 'Master of Surgery (MS, MSurg, MChir, MCh, ChM, CM)', 1, NULL, NULL),
(148, 'Master of Science in Medicine or Surgery (MSc)', 1, NULL, NULL),
(149, 'Doctor of Philosophy (PhD, DPhil)', 1, NULL, NULL),
(150, 'Doctor of Clinical Medicine (DCM)', 1, NULL, NULL),
(151, 'Doctor of Clinical Surgery (DClinSurg)', 1, NULL, NULL),
(152, 'Doctor of Medical Science (DMSc, DMedSc)', 1, NULL, NULL),
(153, 'Doctor of Surgery (DS, DSurg)', 1, NULL, NULL),
(154, 'Secondary School Certificate (SSC)', 1, NULL, NULL),
(155, 'Secondary School Certificate (Dakhil)', 1, NULL, NULL),
(156, 'Secondary School Certificate (Vocational)', 1, NULL, NULL),
(157, 'Higher Secondary Certificate (HSC)', 1, NULL, NULL),
(158, 'Higher Secondary Certificate (Alim)', 1, NULL, NULL),
(159, 'Higher Secondary Certificate (Vocational)', 1, NULL, NULL),
(160, 'Junior School Certificate (JSC)', 1, NULL, NULL),
(161, 'Primary School Certificate (PSC)', 1, NULL, NULL),
(162, 'O Level', 1, NULL, NULL),
(163, 'A Level', 1, NULL, NULL),
(166, 'Other', 1, NULL, NULL),
(168, 'higher secondary school certificate', 1, '2018-09-30 18:17:24', '2018-09-30 18:17:24'),
(169, '', 1, '2018-12-26 13:14:46', '2018-12-26 13:14:46'),
(170, 'my favourite degree', 1, '2019-09-16 06:22:53', '2019-09-16 06:22:53'),
(171, 'my favrourite degree', 1, '2019-09-16 09:40:52', '2019-09-16 09:40:52'),
(172, 'sdlfsdlf', 1, '2019-09-19 11:19:17', '2019-09-19 11:19:17'),
(173, 'slfsld', 1, '2019-10-01 09:35:50', '2019-10-01 09:35:50');

-- --------------------------------------------------------

--
-- Table structure for table `employers`
--

CREATE TABLE `employers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employers`
--

INSERT INTO `employers` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(2, '', 0, '2020-04-17 21:13:45', '2020-04-17 21:13:45'),
(3, '', 0, '2020-04-17 21:14:06', '2020-04-17 21:14:06'),
(4, '', 0, '2020-04-17 21:14:18', '2020-04-17 21:14:18'),
(5, '', 0, '2020-04-17 21:29:34', '2020-04-17 21:29:34'),
(6, '', 0, '2020-04-21 16:16:34', '2020-04-21 16:16:34'),
(7, '', 0, '2020-04-21 16:16:35', '2020-04-21 16:16:35'),
(8, '', 0, '2020-04-21 16:40:43', '2020-04-21 16:40:43'),
(9, '', 0, '2020-04-21 16:40:43', '2020-04-21 16:40:43'),
(10, '', 0, '2020-05-03 12:10:59', '2020-05-03 12:10:59'),
(11, '', 0, '2020-05-03 12:24:25', '2020-05-03 12:24:25'),
(12, '', 0, '2020-05-03 12:25:09', '2020-05-03 12:25:09'),
(13, '', 0, '2020-05-03 12:26:36', '2020-05-03 12:26:36'),
(14, '', 0, '2020-05-03 12:27:33', '2020-05-03 12:27:33'),
(15, '', 0, '2020-05-03 12:28:54', '2020-05-03 12:28:54'),
(16, '', 0, '2020-05-03 12:34:04', '2020-05-03 12:34:04'),
(17, '', 0, '2020-05-03 14:17:36', '2020-05-03 14:17:36'),
(18, '', 0, '2020-05-03 14:17:57', '2020-05-03 14:17:57'),
(19, '', 0, '2020-05-03 15:39:29', '2020-05-03 15:39:29'),
(20, '', 0, '2020-05-03 18:07:45', '2020-05-03 18:07:45'),
(21, '', 0, '2020-05-03 20:39:01', '2020-05-03 20:39:01'),
(22, '', 0, '2020-05-03 22:52:54', '2020-05-03 22:52:54'),
(23, '', 0, '2020-05-03 23:09:42', '2020-05-03 23:09:42'),
(24, '', 0, '2020-05-03 23:31:59', '2020-05-03 23:31:59'),
(25, '', 0, '2020-05-03 23:33:41', '2020-05-03 23:33:41'),
(26, '', 0, '2020-05-04 09:25:03', '2020-05-04 09:25:03'),
(27, '', 0, '2020-05-04 09:25:08', '2020-05-04 09:25:08'),
(28, '', 0, '2020-05-04 15:58:49', '2020-05-04 15:58:49'),
(29, '', 0, '2020-05-04 15:59:18', '2020-05-04 15:59:18'),
(30, '', 0, '2020-05-04 16:08:14', '2020-05-04 16:08:14'),
(31, '', 0, '2020-05-04 16:08:31', '2020-05-04 16:08:31'),
(32, '', 0, '2020-05-04 16:19:02', '2020-05-04 16:19:02'),
(33, '', 0, '2020-05-04 16:34:27', '2020-05-04 16:34:27'),
(34, '', 0, '2020-05-04 16:34:42', '2020-05-04 16:34:42'),
(35, '', 0, '2020-05-04 16:40:17', '2020-05-04 16:40:17'),
(36, '', 0, '2020-05-04 17:12:23', '2020-05-04 17:12:23'),
(37, '', 0, '2020-05-04 17:24:06', '2020-05-04 17:24:06'),
(38, '', 0, '2020-05-04 17:26:03', '2020-05-04 17:26:03'),
(39, '', 0, '2020-05-04 17:28:23', '2020-05-04 17:28:23'),
(40, '', 0, '2020-05-04 17:33:14', '2020-05-04 17:33:14'),
(41, '', 0, '2020-05-05 08:08:59', '2020-05-05 08:08:59'),
(42, '', 0, '2020-05-05 08:13:03', '2020-05-05 08:13:03'),
(43, '', 0, '2020-05-05 08:13:35', '2020-05-05 08:13:35'),
(44, '', 0, '2020-05-05 08:13:55', '2020-05-05 08:13:55'),
(45, '', 0, '2020-05-05 08:36:35', '2020-05-05 08:36:35'),
(46, '', 0, '2020-05-05 08:41:23', '2020-05-05 08:41:23'),
(47, '', 0, '2020-05-05 08:59:58', '2020-05-05 08:59:58'),
(48, '', 0, '2020-05-05 14:20:56', '2020-05-05 14:20:56'),
(49, '', 0, '2020-05-07 16:17:20', '2020-05-07 16:17:20'),
(50, '', 0, '2020-05-09 06:39:29', '2020-05-09 06:39:29'),
(51, '', 0, '2020-05-09 06:39:32', '2020-05-09 06:39:32'),
(52, '', 0, '2020-05-09 06:39:36', '2020-05-09 06:39:36'),
(53, '', 0, '2020-05-09 19:09:06', '2020-05-09 19:09:06'),
(54, '', 0, '2020-05-09 19:09:53', '2020-05-09 19:09:53'),
(55, '', 0, '2020-05-09 19:10:35', '2020-05-09 19:10:35'),
(56, '', 0, '2020-05-09 19:11:09', '2020-05-09 19:11:09'),
(57, '', 0, '2020-05-09 19:13:20', '2020-05-09 19:13:20'),
(58, '', 0, '2020-05-09 19:26:59', '2020-05-09 19:26:59'),
(59, '', 0, '2020-05-09 19:27:12', '2020-05-09 19:27:12'),
(60, '', 0, '2020-05-09 19:32:14', '2020-05-09 19:32:14'),
(61, '', 0, '2020-05-09 19:32:37', '2020-05-09 19:32:37'),
(62, '', 0, '2020-05-09 19:32:55', '2020-05-09 19:32:55'),
(63, '', 0, '2020-05-09 19:34:11', '2020-05-09 19:34:11'),
(64, '', 0, '2020-05-09 19:34:27', '2020-05-09 19:34:27'),
(65, '', 0, '2020-05-09 19:34:50', '2020-05-09 19:34:50'),
(66, '', 0, '2020-05-09 19:37:40', '2020-05-09 19:37:40'),
(67, '', 0, '2020-05-11 23:18:24', '2020-05-11 23:18:24'),
(68, '', 0, '2020-05-12 14:00:10', '2020-05-12 14:00:10'),
(69, '', 0, '2020-05-15 11:26:53', '2020-05-15 11:26:53'),
(70, '', 0, '2020-05-15 11:38:14', '2020-05-15 11:38:14'),
(71, '', 0, '2020-05-15 11:38:18', '2020-05-15 11:38:18'),
(72, '', 0, '2020-05-15 11:38:19', '2020-05-15 11:38:19'),
(73, '', 0, '2020-05-15 11:40:07', '2020-05-15 11:40:07'),
(74, '', 0, '2020-05-15 11:41:42', '2020-05-15 11:41:42'),
(75, '', 0, '2020-05-15 11:41:45', '2020-05-15 11:41:45'),
(76, '', 0, '2020-05-15 11:45:36', '2020-05-15 11:45:36'),
(77, '', 0, '2020-05-15 11:45:38', '2020-05-15 11:45:38'),
(78, '', 0, '2020-05-15 11:45:41', '2020-05-15 11:45:41'),
(79, '', 0, '2020-05-15 11:45:42', '2020-05-15 11:45:42'),
(80, '', 0, '2020-05-15 11:45:44', '2020-05-15 11:45:44'),
(81, '', 0, '2020-05-15 11:53:14', '2020-05-15 11:53:14'),
(82, '', 0, '2020-05-15 11:54:39', '2020-05-15 11:54:39'),
(83, '', 0, '2020-05-15 11:54:45', '2020-05-15 11:54:45'),
(84, '', 0, '2020-05-15 11:54:48', '2020-05-15 11:54:48'),
(85, '', 0, '2020-05-15 11:55:29', '2020-05-15 11:55:29'),
(86, '', 0, '2020-05-15 11:55:31', '2020-05-15 11:55:31'),
(87, '', 0, '2020-05-15 11:55:36', '2020-05-15 11:55:36'),
(88, '', 0, '2020-05-15 11:55:37', '2020-05-15 11:55:37'),
(89, '', 0, '2020-05-15 11:55:39', '2020-05-15 11:55:39'),
(90, '', 0, '2020-05-15 11:55:41', '2020-05-15 11:55:41'),
(91, '', 0, '2020-05-15 11:55:43', '2020-05-15 11:55:43'),
(92, '', 0, '2020-05-15 11:55:44', '2020-05-15 11:55:44'),
(93, '', 0, '2020-05-15 11:55:46', '2020-05-15 11:55:46'),
(94, '', 0, '2020-05-15 11:55:48', '2020-05-15 11:55:48'),
(95, '', 0, '2020-05-15 12:04:26', '2020-05-15 12:04:26'),
(96, '', 0, '2020-05-15 12:05:46', '2020-05-15 12:05:46'),
(97, '', 0, '2020-05-15 12:05:48', '2020-05-15 12:05:48'),
(98, '', 0, '2020-05-15 12:05:50', '2020-05-15 12:05:50'),
(99, '', 0, '2020-05-15 12:22:28', '2020-05-15 12:22:28'),
(100, '', 0, '2020-05-15 12:22:30', '2020-05-15 12:22:30'),
(101, '', 0, '2020-05-15 12:22:32', '2020-05-15 12:22:32'),
(102, '', 0, '2020-05-15 12:22:35', '2020-05-15 12:22:35'),
(103, '', 0, '2020-05-15 12:22:37', '2020-05-15 12:22:37'),
(104, '', 0, '2020-05-15 12:22:38', '2020-05-15 12:22:38'),
(105, '', 0, '2020-05-15 12:22:40', '2020-05-15 12:22:40'),
(106, '', 0, '2020-05-15 12:22:41', '2020-05-15 12:22:41'),
(107, '', 0, '2020-05-15 12:22:43', '2020-05-15 12:22:43'),
(108, '', 0, '2020-05-15 12:22:44', '2020-05-15 12:22:44'),
(109, '', 0, '2020-05-15 12:22:46', '2020-05-15 12:22:46'),
(110, '', 0, '2020-05-15 12:22:47', '2020-05-15 12:22:47'),
(111, '', 0, '2020-05-15 12:22:49', '2020-05-15 12:22:49'),
(112, '', 0, '2020-05-15 12:22:50', '2020-05-15 12:22:50'),
(113, '', 0, '2020-05-15 12:22:52', '2020-05-15 12:22:52'),
(114, '', 0, '2020-05-15 12:22:53', '2020-05-15 12:22:53'),
(115, '', 0, '2020-05-15 12:22:55', '2020-05-15 12:22:55'),
(116, '', 0, '2020-05-15 12:33:47', '2020-05-15 12:33:47'),
(117, '', 0, '2020-05-15 14:20:16', '2020-05-15 14:20:16'),
(118, '', 0, '2020-05-15 21:49:59', '2020-05-15 21:49:59'),
(119, '', 0, '2020-05-15 21:59:57', '2020-05-15 21:59:57'),
(120, '', 0, '2020-05-15 21:59:59', '2020-05-15 21:59:59'),
(121, '', 0, '2020-05-15 22:00:02', '2020-05-15 22:00:02'),
(122, '', 0, '2020-05-15 22:00:04', '2020-05-15 22:00:04'),
(123, '', 0, '2020-05-16 09:03:07', '2020-05-16 09:03:07'),
(124, '', 0, '2020-05-17 19:35:42', '2020-05-17 19:35:42'),
(125, '', 0, '2020-05-17 19:35:51', '2020-05-17 19:35:51'),
(126, '', 0, '2020-05-17 19:36:05', '2020-05-17 19:36:05'),
(127, '', 0, '2020-05-17 20:48:02', '2020-05-17 20:48:02'),
(128, '', 0, '2020-05-17 20:48:06', '2020-05-17 20:48:06'),
(129, '', 0, '2020-05-17 20:48:10', '2020-05-17 20:48:10'),
(130, '', 0, '2020-05-17 20:48:52', '2020-05-17 20:48:52'),
(131, '', 0, '2020-05-19 08:05:51', '2020-05-19 08:05:51'),
(132, '', 0, '2020-05-19 08:07:42', '2020-05-19 08:07:42'),
(133, '', 0, '2020-05-19 08:08:03', '2020-05-19 08:08:03'),
(134, '', 0, '2020-05-19 08:10:42', '2020-05-19 08:10:42'),
(135, '', 0, '2020-05-19 08:10:45', '2020-05-19 08:10:45'),
(136, '', 0, '2020-05-19 08:10:48', '2020-05-19 08:10:48'),
(137, '', 0, '2020-05-19 08:10:55', '2020-05-19 08:10:55'),
(138, '', 0, '2020-05-19 09:47:11', '2020-05-19 09:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, 'database', 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\SendNotificationEmailJob\\\":5:{s:10:\\\"\\u0000*\\u0000details\\\";a:8:{s:15:\\\"receipientEmail\\\";s:18:\\\"rubab891@yahoo.com\\\";s:14:\\\"receipientName\\\";s:14:\\\"Muhammad Rubab\\\";s:7:\\\"subject\\\";s:16:\\\"Interview Alert!\\\";s:3:\\\"msg\\\";s:66:\\\"Rubab Hossain selected you for an interview for the alsdf position\\\";s:11:\\\"senderEmail\\\";s:19:\\\"rubab2020@gmail.com\\\";s:10:\\\"senderName\\\";s:13:\\\"Rubab Hossain\\\";s:9:\\\"alertType\\\";s:11:\\\"interviewed\\\";s:7:\\\"siteUrl\\\";s:21:\\\"http:\\/\\/localhost:4200\\\";}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";O:13:\\\"Carbon\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-04-20 13:58:26.678238\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}}\"}}', 'Swift_IoException: Unable to open file for reading [images/logo/logo_sm.jpg] in D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\ByteStream\\FileByteStream.php:144\nStack trace:\n#0 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\ByteStream\\FileByteStream.php(84): Swift_ByteStream_FileByteStream->_getReadHandle()\n#1 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(691): Swift_ByteStream_FileByteStream->read(8192)\n#2 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(348): Swift_Mime_SimpleMimeEntity->_readStream(Object(Swift_ByteStream_FileByteStream))\n#3 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(483): Swift_Mime_SimpleMimeEntity->getBody()\n#4 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(465): Swift_Mime_SimpleMimeEntity->_bodyToString()\n#5 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(492): Swift_Mime_SimpleMimeEntity->toString()\n#6 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMimeEntity.php(465): Swift_Mime_SimpleMimeEntity->_bodyToString()\n#7 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mime\\SimpleMessage.php(584): Swift_Mime_SimpleMimeEntity->toString()\n#8 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Message.php(148): Swift_Mime_SimpleMessage->toString()\n#9 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Transport\\MailgunTransport.php(92): Swift_Message->toString()\n#10 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Transport\\MailgunTransport.php(64): Illuminate\\Mail\\Transport\\MailgunTransport->payload(Object(Swift_Message), \'Muhammad Rubab ...\')\n#11 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\swiftmailer\\swiftmailer\\lib\\classes\\Swift\\Mailer.php(85): Illuminate\\Mail\\Transport\\MailgunTransport->send(Object(Swift_Message), Array)\n#12 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(443): Swift_Mailer->send(Object(Swift_Message), Array)\n#13 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(217): Illuminate\\Mail\\Mailer->sendSwiftMessage(Object(Swift_Message))\n#14 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(221): Illuminate\\Mail\\Mailer->send(\'email.notificat...\', Array, Object(Closure))\n#15 D:\\rubab\\xampp\\htdocs\\spothirebackend\\app\\Jobs\\SendNotificationEmailJob.php(51): Illuminate\\Support\\Facades\\Facade::__callStatic(\'send\', Array)\n#16 [internal function]: App\\Jobs\\SendNotificationEmailJob->handle()\n#17 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(29): call_user_func_array(Array, Array)\n#18 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#19 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(539): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(94): Illuminate\\Container\\Container->call(Array)\n#22 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(114): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendNotificationEmailJob))\n#23 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(102): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendNotificationEmailJob))\n#24 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(98): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(42): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendNotificationEmailJob), false)\n#26 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(69): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#27 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(317): Illuminate\\Queue\\Jobs\\Job->fire()\n#28 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(267): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#29 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(113): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#30 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(101): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(85): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#32 [internal function]: Illuminate\\Queue\\Console\\WorkCommand->fire()\n#33 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(29): call_user_func_array(Array, Array)\n#34 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#35 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#36 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(539): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#37 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Illuminate\\Container\\Container->call(Array)\n#38 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\symfony\\console\\Command\\Command.php(252): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#39 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(167): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#40 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\symfony\\console\\Application.php(946): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\symfony\\console\\Application.php(248): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\symfony\\console\\Application.php(148): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 D:\\rubab\\xampp\\htdocs\\spothirebackend\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(122): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\rubab\\xampp\\htdocs\\spothirebackend\\artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 {main}', '2020-04-20 07:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `favourites`
--

CREATE TABLE `favourites` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `describe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks_images`
--

CREATE TABLE `feedbacks_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `feedback_id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bangabandhu Sheikh Mujib Medical University', 1, NULL, NULL),
(2, 'Bangabandhu Sheikh Mujibur Rahman Agricultural University', 1, NULL, NULL),
(3, 'Bangabandhu Sheikh Mujibur Rahman Maritime University', 1, NULL, NULL),
(4, 'Bangabandhu Sheikh Mujibur Rahman Science & Technology University', 1, NULL, NULL),
(5, 'Bangladesh Agricultural University', 1, NULL, NULL),
(6, 'Bangladesh Open University', 1, NULL, NULL),
(7, 'Bangladesh University of Engineering & Technology', 1, NULL, NULL),
(8, 'Bangladesh University of Professionals', 1, NULL, NULL),
(9, 'Bangladesh University of Textiles', 1, NULL, NULL),
(10, 'Barisal University', 1, NULL, NULL),
(11, 'Begum Rokeya University', 1, NULL, NULL),
(12, 'Chittagong Medical University', 1, NULL, NULL),
(13, 'Chittagong University of Engineering & Technology', 1, NULL, NULL),
(14, 'Chittagong Veterinary and Animal Sciences University', 1, NULL, NULL),
(15, 'Comilla University', 1, NULL, NULL),
(16, 'Dhaka University of Engineering & Technology', 1, NULL, NULL),
(17, 'Hajee Mohammad Danesh Science & Technology University', 1, NULL, NULL),
(18, 'Islamic Arabic University', 1, NULL, NULL),
(19, 'Islamic University', 1, NULL, NULL),
(20, 'Jagannath University', 1, NULL, NULL),
(21, 'Jahangirnagar University', 1, NULL, NULL),
(22, 'Jatiya Kabi Kazi Nazrul Islam University', 1, NULL, NULL),
(23, 'Jessore University of Science & Technology', 1, NULL, NULL),
(24, 'Khulna University', 1, NULL, NULL),
(25, 'Khulna University of Engineering and Technology', 1, NULL, NULL),
(26, 'Mawlana Bhashani Science & Technology University', 1, NULL, NULL),
(27, 'National University', 1, NULL, NULL),
(28, 'Noakhali Science & Technology University', 1, NULL, NULL),
(29, 'Pabna University of Science and Technology', 1, NULL, NULL),
(30, 'Patuakhali Science And Technology University', 1, NULL, NULL),
(31, 'Rajshahi Medical University', 1, NULL, NULL),
(32, 'Rajshahi University of Engineering & Technology', 1, NULL, NULL),
(33, 'Rangamati Science and Technology University', 1, NULL, NULL),
(34, 'Shahjalal University of Science & Technology', 1, NULL, NULL),
(35, 'Sher-e-Bangla Agricultural University', 1, NULL, NULL),
(36, 'Sylhet Agricultural University', 1, NULL, NULL),
(37, 'University of Chittagong', 1, NULL, NULL),
(38, 'University of Dhaka', 1, NULL, NULL),
(39, 'University of Rajshahi', 1, NULL, NULL),
(40, 'Ahsanullah University of Science and Technology', 1, NULL, NULL),
(41, 'American International University Bangladesh', 1, NULL, NULL),
(42, 'Anwer Khan Modern University', 1, NULL, NULL),
(43, 'Army University of Engineering and Technology (BAUET), Qadirabad', 1, NULL, NULL),
(44, 'Army University of Science and Technology(BAUST), Saidpur', 1, NULL, NULL),
(45, 'ASA University Bangladesh', 1, NULL, NULL),
(46, 'Asian University of Bangladesh', 1, NULL, NULL),
(47, 'Atish Dipankar University of Science & Technology', 1, NULL, NULL),
(48, 'Bangladesh Army International University of Science & Technology, Comilla', 1, NULL, NULL),
(49, 'Bangladesh Islami University', 1, NULL, NULL),
(50, 'Bangladesh University', 1, NULL, NULL),
(51, 'Bangladesh University of Business & Technology (BUBT)', 1, NULL, NULL),
(52, 'Bangladesh University of Health Sciences', 1, NULL, NULL),
(53, 'BGC Trust University Bangladesh, Chittagong', 1, NULL, NULL),
(54, 'BGMEA University of Fashion & Technology', 1, NULL, NULL),
(55, 'BRAC University', 1, NULL, NULL),
(56, 'Britannia University', 1, NULL, NULL),
(57, 'Canadian University of Bangladesh', 1, NULL, NULL),
(58, 'CCN University of Science & Technology', 1, NULL, NULL),
(59, 'Central University of Science and Technology', 1, NULL, NULL),
(60, 'Central Women’s University', 1, NULL, NULL),
(61, 'Chittagong Independent University (CIU)', 1, NULL, NULL),
(62, 'City University', 1, NULL, NULL),
(63, 'Coxs Bazar International University', 1, NULL, NULL),
(64, 'Daffodil International University', 1, NULL, NULL),
(65, 'Dhaka International University', 1, NULL, NULL),
(66, 'East Delta University , Chittagong', 1, NULL, NULL),
(67, 'East West University', 1, NULL, NULL),
(68, 'Eastern University', 1, NULL, NULL),
(69, 'European University of Bangladesh', 1, NULL, NULL),
(70, 'Exim Bank Agricultural University, Bangladesh', 1, NULL, NULL),
(71, 'Fareast International University', 1, NULL, NULL),
(72, 'Feni University', 1, NULL, NULL),
(73, 'First Capital University of Bangladesh', 1, NULL, NULL),
(74, 'German University Bangladesh', 1, NULL, NULL),
(75, 'Global University Bangladesh', 1, NULL, NULL),
(76, 'Gono Bishwabidyalay', 1, NULL, NULL),
(77, 'Green University of Bangladesh', 1, NULL, NULL),
(78, 'Hamdard University Bangladesh', 1, NULL, NULL),
(79, 'IBAIS University', 1, NULL, NULL),
(80, 'Independent University, Bangladesh', 1, NULL, NULL),
(81, 'International Islamic University, Chittagong', 1, NULL, NULL),
(82, 'International University of Business Agriculture & Technology', 1, NULL, NULL),
(83, 'Ishakha International University, Bangladesh', 1, NULL, NULL),
(84, 'Khwaja Yunus Ali University', 1, NULL, NULL),
(85, 'Leading University, Sylhet', 1, NULL, NULL),
(86, 'Manarat International University', 1, NULL, NULL),
(87, 'Metropolitan University, Sylhet', 1, NULL, NULL),
(88, 'N.P.I University of Bangladesh', 1, NULL, NULL),
(89, 'North Bengal International University', 1, NULL, NULL),
(90, 'North East University Bangladesh', 1, NULL, NULL),
(91, 'North South University', 1, NULL, NULL),
(92, 'North Western University, Khulna', 1, NULL, NULL),
(93, 'Northern University Bangladesh', 1, NULL, NULL),
(94, 'Northern University of Business & Technology, Khulna', 1, NULL, NULL),
(95, 'Notre Dame University Bangladesh', 1, NULL, NULL),
(96, 'Port City International University', 1, NULL, NULL),
(97, 'Premier University, Chittagong', 1, NULL, NULL),
(98, 'Presidency University', 1, NULL, NULL),
(99, 'Prime University', 1, NULL, NULL),
(100, 'Primeasia University', 1, NULL, NULL),
(101, 'Pundro University of Science & Technology', 1, NULL, NULL),
(102, 'Queens University', 1, NULL, NULL),
(103, 'Rabindra Moitri University, Kushtia', 1, NULL, NULL),
(104, 'Rajshahi Science & Technology University (RSTU), Natore', 1, NULL, NULL),
(105, 'Ranada Prasad Shaha University', 1, NULL, NULL),
(106, 'Royal University of Dhaka', 1, NULL, NULL),
(107, 'Shanto Mariam University of Creative Technology', 1, NULL, NULL),
(108, 'Sheikh Fazilatunnesa Mujib University', 1, NULL, NULL),
(109, 'Sonargaon University', 1, NULL, NULL),
(110, 'Southeast University', 1, NULL, NULL),
(111, 'Southern University Bangladesh , Chittagong', 1, NULL, NULL),
(112, 'Stamford University, Bangladesh', 1, NULL, NULL),
(113, 'State University Of Bangladesh', 1, NULL, NULL),
(114, 'Sylhet International University, Sylhet', 1, NULL, NULL),
(115, 'The International University of Scholars', 1, NULL, NULL),
(116, 'The Millennium University', 1, NULL, NULL),
(117, 'The Peoples University of Bangladesh', 1, NULL, NULL),
(118, 'The University of Asia Pacific', 1, NULL, NULL),
(119, 'Times University Bangladesh', 1, NULL, NULL),
(120, 'United International University', 1, NULL, NULL),
(121, 'University of Creative Technology, Chittagong', 1, NULL, NULL),
(122, 'University of Development Alternative', 1, NULL, NULL),
(123, 'University of Global Village', 1, NULL, NULL),
(124, 'University of Information Technology & Sciences', 1, NULL, NULL),
(125, 'University of Liberal Arts Bangladesh', 1, NULL, NULL),
(126, 'University of Science & Technology, Chittagong', 1, NULL, NULL),
(127, 'University of South Asia', 1, NULL, NULL),
(128, 'Uttara University', 1, NULL, NULL),
(129, 'Varendra University', 1, NULL, NULL),
(130, 'Victoria University of Bangladesh', 1, NULL, NULL),
(131, 'World University of Bangladesh', 1, NULL, NULL),
(132, 'Z.H Sikder University of Science & Technology', 1, NULL, NULL),
(133, 'Abdul Malek Ukil Medical College', 1, NULL, NULL),
(134, 'Chittagong Medical College', 1, NULL, NULL),
(135, 'Colonel Malek Medical College', 1, NULL, NULL),
(136, 'Comilla Medical College', 1, NULL, NULL),
(137, 'Cox’s Bazar Medical College', 1, NULL, NULL),
(138, 'Dhaka Medical College', 1, NULL, NULL),
(139, 'Dinajpur Medical College', 1, NULL, NULL),
(140, 'Faridpur Medical College', 1, NULL, NULL),
(141, 'Jamalpur Medical College', 1, NULL, NULL),
(142, 'Jessore Medical College', 1, NULL, NULL),
(143, 'Khulna Medical College', 1, NULL, NULL),
(144, 'Kushtia Medical College', 1, NULL, NULL),
(145, 'Mugda Medical College', 1, NULL, NULL),
(146, 'Mymensingh Medical College', 1, NULL, NULL),
(147, 'Pabna Medical College', 1, NULL, NULL),
(148, 'Patuakhali Medical College', 1, NULL, NULL),
(149, 'Rajshahi Medical College', 1, NULL, NULL),
(150, 'Rangamati Medical College', 1, NULL, NULL),
(151, 'Rangpur Medical College', 1, NULL, NULL),
(152, 'Satkhira Medical College', 1, NULL, NULL),
(153, 'Shaheed M. Monsur Ali Medical College', 1, NULL, NULL),
(154, 'Shaheed Suhrawardy Medical College', 1, NULL, NULL),
(155, 'Shaheed Tajuddin Ahmad Medical College', 1, NULL, NULL),
(156, 'Shaheed Ziaur Rahman Medical College', 1, NULL, NULL),
(157, 'Shahid Syed Nazrul Islam Medical College', 1, NULL, NULL),
(158, 'Sheikh Sayera Khatun Medical College', 1, NULL, NULL),
(159, 'Sher-e-Bangla Medical College', 1, NULL, NULL),
(160, 'Sir Salimullah Medical College', 1, NULL, NULL),
(161, 'Sylhet MAG Osmani Medical College', 1, NULL, NULL),
(162, 'Tangail Medical College', 1, NULL, NULL),
(163, '', 1, NULL, NULL),
(164, 'Holy Cross College', 0, '2018-09-25 16:00:36', '2018-09-25 16:00:36'),
(165, 'Holy Cross Girls\' High School', 0, '2018-09-25 16:00:36', '2018-09-25 16:00:36'),
(166, 'Military Institute of Science and Technology', 1, NULL, NULL),
(167, 'Armed Forces Medical College', 1, NULL, NULL),
(168, 'Private', 0, '2018-09-27 22:41:46', '2018-09-27 22:41:46'),
(169, 'Play Pen', 0, '2018-09-27 22:41:46', '2018-09-27 22:41:46'),
(170, 'Central Govt. High School', 0, '2018-11-03 17:15:45', '2018-11-03 17:15:45'),
(171, 'Notre Dame College', 0, '2018-11-03 17:15:45', '2018-11-03 17:15:45'),
(172, 'Scholastica', 0, '2018-12-23 22:50:36', '2018-12-23 22:50:36'),
(173, 'Mohammadpur Govt. High School', 0, '2018-12-25 21:08:26', '2018-12-25 21:08:26'),
(174, 'Bir Shreshtha Noor Mohammad Public School and College', 0, '2018-12-25 21:08:26', '2018-12-25 21:08:26'),
(175, 'Bangladesh University of Engineering and Technology', 0, '2018-12-26 09:45:10', '2018-12-26 09:45:10'),
(176, 'Munshiganj Polytechnic Institute', 0, '2018-12-26 13:14:46', '2018-12-26 13:14:46'),
(177, 'Dhaka city college  under National University.', 0, '2019-03-15 23:34:31', '2019-03-15 23:34:31'),
(178, 'Daffodil International Universit', 0, '2019-03-16 13:04:09', '2019-03-16 13:04:09'),
(179, 'Dhaka University', 0, '2019-03-16 14:18:51', '2019-03-16 14:18:51'),
(180, 'A B C D DegreeCollege', 0, '2019-03-16 16:07:52', '2019-03-16 16:07:52'),
(181, 'Ahsanullah University of Science & Technology', 0, '2019-03-16 21:58:38', '2019-03-16 21:58:38'),
(182, 'Dhaka poly technic', 0, '2019-03-17 10:45:58', '2019-03-17 10:45:58'),
(183, 'Rajshahi board', 0, '2019-03-17 12:54:44', '2019-03-17 12:54:44'),
(184, 'Northern University College', 0, '2019-03-17 13:46:55', '2019-03-17 13:46:55'),
(185, 'Agradyut Bidyaniketon High School', 0, '2019-03-17 13:48:39', '2019-03-17 13:48:39'),
(186, 'Rangpur Polytechnic Institute', 0, '2019-03-17 16:30:53', '2019-03-17 16:30:53'),
(187, 'Badarganj High School and College', 0, '2019-03-17 16:30:53', '2019-03-17 16:30:53'),
(188, 'Jashore University of Science and Technology', 0, '2019-03-22 23:38:46', '2019-03-22 23:38:46'),
(189, 'BAF Shaheen College, Tejgaon, Dhaka', 0, '2019-03-22 23:38:46', '2019-03-22 23:38:46'),
(190, 'Kanutia Abdul Ala High School, Magura', 0, '2019-03-22 23:38:46', '2019-03-22 23:38:46'),
(191, 'Ideal School & College', 0, '2019-03-23 00:15:09', '2019-03-23 00:15:09'),
(192, 'American International University-Bangladesh', 0, '2019-03-23 00:29:40', '2019-03-23 00:29:40'),
(193, 'Govt. Sundarban Adorsha College, Khulna', 0, '2019-03-23 16:01:34', '2019-03-23 16:01:34'),
(194, 'H.R.H. Prince Agakhan High School, Khulna', 0, '2019-03-23 16:01:34', '2019-03-23 16:01:34'),
(195, 'Khilgaon Govt. High School', 0, '2019-03-31 21:53:37', '2019-03-31 21:53:37'),
(197, 'my favrouite instition', 0, '2019-09-16 09:17:34', '2019-09-16 09:17:34'),
(198, 'my favoruite insitituion', 0, '2019-09-16 09:40:52', '2019-09-16 09:40:52'),
(199, 'dfdfedf', 0, '2019-09-19 11:19:17', '2019-09-19 11:19:17'),
(200, 'sldlflf', 0, '2019-10-01 09:35:50', '2019-10-01 09:35:50'),
(201, 'Bangladesh University of Textiles asdf', 0, '2020-03-30 06:44:03', '2020-03-30 06:44:03'),
(202, 'Bangladesh University of Textiles asdf sfsdfsdffff', 0, '2020-03-30 07:15:02', '2020-03-30 07:15:02');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `looking_for` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `end_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_salary_negotiable` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_data` text COLLATE utf8mb4_unicode_ci,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_experience` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_qualification` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirements` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `advert_duration` int(11) NOT NULL,
  `advert_deadline` date NOT NULL,
  `currency_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `target_audience` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_payment_completed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `looking_for`, `slug`, `employment_type`, `start_date`, `start_time`, `end_date`, `end_time`, `salary_from`, `salary_to`, `salary_type`, `is_salary_negotiable`, `address`, `city`, `location_data`, `department`, `min_experience`, `min_qualification`, `description`, `requirements`, `advert_duration`, `advert_deadline`, `currency_type`, `target_audience`, `is_payment_completed`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'Software Engineer', 'software-engineer', 'Permanent (Full-Time)', '2020-04-13', '01:00', '2020-04-22', '01:00', '1234', '2389', 'Month', '0', 'dhaka, bangladesh', NULL, NULL, 'Accounting/Auditing', '1-3 years', 'Bechelor', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p><p>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p><p>quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo</p><p>consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse</p><p>cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non</p><p>proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod</p><p>tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,</p><p>quis nostrud exercitation ', 30, '2020-10-13', 'BDT', NULL, 1, NULL, '2020-04-13 13:58:03', '2020-04-13 13:58:03'),
(2, 1, 'sdlf', 'sdlf', 'Permanent (Full-Time)', '2020-04-05', '01:00', '2020-04-06', '01:00', NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>dfsd</p>', NULL, 30, '2020-10-14', 'BDT', NULL, 1, NULL, '2020-04-13 21:14:56', '2020-04-13 21:14:56'),
(3, 1, 'date', 'date', 'Permanent (Full-Time)', '2020-04-01', '01:00', '2020-04-02', '01:00', '4949', NULL, 'Month', '0', NULL, NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>kslfsdkf</p>', NULL, 30, '2020-10-14', 'BDT', NULL, 1, NULL, '2020-04-13 21:29:52', '2020-04-13 21:29:52'),
(4, 1, 'test disble circulate', 'test-disble-circulate', 'Permanent (Full-Time)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>sklsdfskfsf</p>', NULL, 30, '2020-10-15', 'BDT', NULL, 1, NULL, '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(5, 3, 'Good engineer', 'internal-engineer', 'Permanent (Full-Time)', '2020-4-17', '01:00', '2020-4-22', '13:00', '2999', '3999', 'Month', '0', 'dhaka, bangladesh', NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>klsfsf</p>', '<p>klawopeowp</p>', 30, '2020-10-17', 'BDT', NULL, 1, NULL, '2020-04-17 14:49:38', '2020-04-17 14:49:38'),
(6, 3, 'alsdf', 'alsdf', 'Permanent (Full-Time)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>sfsdfsdf</p>', NULL, 30, '2020-10-18', 'BDT', NULL, 1, '2020-04-21 06:37:04', '2020-04-17 19:04:36', '2020-04-21 06:37:04'),
(8, 3, 'job mail', 'internal-job-mail', 'Permanent (Full-Time)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', '1-3 years', 'Bechelor', '<p>sldfsf</p>', NULL, 30, '2020-10-21', 'BDT', NULL, 1, NULL, '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(9, 3, 'internal job mail 2', 'internal-job-mail-1', 'Permanent (Full-Time)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', '1-3 years', 'Bechelor', '<p>sldfsf</p>', NULL, 30, '2020-10-21', 'BDT', 'internal', 1, NULL, '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(10, 3, 'test discontine worker', 'test-discontine-worker', 'Permanent (Full-Time)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, NULL, NULL, 'Accounting/Auditing', 'Not required', 'Bechelor', '<p>klsksdlflsdf</p>', NULL, 30, '2020-10-23', 'BDT', 'university', 1, NULL, '2020-04-23 14:15:53', '2020-04-23 14:15:53');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(10) UNSIGNED NOT NULL,
  `applicant_id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `application_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `is_short_listed` tinyint(1) NOT NULL DEFAULT '0',
  `rating_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `applicant_id`, `job_id`, `application_status`, `interview_date`, `interview_time`, `is_short_listed`, `rating_status`, `rating`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 'withdrawn', NULL, NULL, 0, NULL, NULL, NULL, '2020-05-17 18:54:55', '2020-05-19 09:16:58'),
(2, 3, 2, 'applied', NULL, NULL, 0, NULL, NULL, NULL, '2020-05-18 11:14:16', '2020-05-18 11:14:16'),
(3, 3, 4, 'assigned', NULL, NULL, 0, NULL, NULL, NULL, '2020-05-18 11:14:37', '2020-05-19 19:09:01'),
(4, 1, 1, 'discontinued', NULL, NULL, 0, 'pending', NULL, NULL, '2020-05-19 09:16:23', '2020-05-19 19:02:33');

-- --------------------------------------------------------

--
-- Table structure for table `job_benefits`
--

CREATE TABLE `job_benefits` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_benefits`
--

INSERT INTO `job_benefits` (`id`, `job_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Friendly Environment', '2020-04-13 13:58:03', '2020-04-13 13:58:03'),
(2, 1, 'Bottomless Snacks', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(3, 5, 'Bottomless Snacks', '2020-04-17 14:49:38', '2020-04-17 14:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `job_skills`
--

CREATE TABLE `job_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_skills`
--

INSERT INTO `job_skills` (`id`, `job_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Integration software', '2020-04-13 13:58:03', '2020-04-13 13:58:03'),
(2, 1, 'Storage/Data Management', '2020-04-13 13:58:03', '2020-04-13 13:58:03'),
(3, 5, 'AWS', '2020-04-17 14:49:38', '2020-04-17 14:49:38');

-- --------------------------------------------------------

--
-- Table structure for table `job_workdays`
--

CREATE TABLE `job_workdays` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_workdays`
--

INSERT INTO `job_workdays` (`id`, `job_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Sat', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(2, 1, 'Sun', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(3, 1, 'Mon', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(4, 1, 'Tue', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(5, 1, 'Wed', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(6, 1, 'Thu', '2020-04-13 13:58:04', '2020-04-13 13:58:04'),
(7, 2, 'Sat', '2020-04-13 21:14:56', '2020-04-13 21:14:56'),
(8, 2, 'Sun', '2020-04-13 21:14:57', '2020-04-13 21:14:57'),
(9, 2, 'Mon', '2020-04-13 21:14:57', '2020-04-13 21:14:57'),
(10, 2, 'Tue', '2020-04-13 21:14:57', '2020-04-13 21:14:57'),
(11, 2, 'Wed', '2020-04-13 21:14:57', '2020-04-13 21:14:57'),
(12, 2, 'Thu', '2020-04-13 21:14:57', '2020-04-13 21:14:57'),
(13, 3, 'Sat', '2020-04-13 21:29:52', '2020-04-13 21:29:52'),
(14, 3, 'Sun', '2020-04-13 21:29:52', '2020-04-13 21:29:52'),
(15, 3, 'Mon', '2020-04-13 21:29:52', '2020-04-13 21:29:52'),
(16, 3, 'Tue', '2020-04-13 21:29:52', '2020-04-13 21:29:52'),
(17, 3, 'Wed', '2020-04-13 21:29:53', '2020-04-13 21:29:53'),
(18, 3, 'Thu', '2020-04-13 21:29:53', '2020-04-13 21:29:53'),
(19, 4, 'Sat', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(20, 4, 'Sun', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(21, 4, 'Mon', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(22, 4, 'Tue', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(23, 4, 'Wed', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(24, 4, 'Thu', '2020-04-15 16:47:52', '2020-04-15 16:47:52'),
(25, 5, 'Sat', '2020-04-17 14:49:38', '2020-04-17 14:49:38'),
(26, 5, 'Sun', '2020-04-17 14:49:38', '2020-04-17 14:49:38'),
(27, 5, 'Mon', '2020-04-17 14:49:38', '2020-04-17 14:49:38'),
(28, 5, 'Tue', '2020-04-17 14:49:38', '2020-04-17 14:49:38'),
(29, 5, 'Wed', '2020-04-17 14:49:39', '2020-04-17 14:49:39'),
(30, 5, 'Thu', '2020-04-17 14:49:39', '2020-04-17 14:49:39'),
(31, 6, 'Sat', '2020-04-17 19:04:36', '2020-04-17 19:04:36'),
(32, 6, 'Sun', '2020-04-17 19:04:36', '2020-04-17 19:04:36'),
(33, 6, 'Mon', '2020-04-17 19:04:37', '2020-04-17 19:04:37'),
(34, 6, 'Tue', '2020-04-17 19:04:37', '2020-04-17 19:04:37'),
(35, 6, 'Wed', '2020-04-17 19:04:37', '2020-04-17 19:04:37'),
(36, 6, 'Thu', '2020-04-17 19:04:37', '2020-04-17 19:04:37'),
(43, 8, 'Sat', '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(44, 8, 'Sun', '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(45, 8, 'Mon', '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(46, 8, 'Tue', '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(47, 8, 'Wed', '2020-04-21 06:44:13', '2020-04-21 06:44:13'),
(48, 8, 'Thu', '2020-04-21 06:44:14', '2020-04-21 06:44:14'),
(49, 9, 'Sat', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(50, 9, 'Sun', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(51, 9, 'Mon', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(52, 9, 'Tue', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(53, 9, 'Wed', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(54, 9, 'Thu', '2020-04-21 06:47:29', '2020-04-21 06:47:29'),
(55, 10, 'Sat', '2020-04-23 14:15:54', '2020-04-23 14:15:54'),
(56, 10, 'Sun', '2020-04-23 14:15:54', '2020-04-23 14:15:54'),
(57, 10, 'Mon', '2020-04-23 14:15:54', '2020-04-23 14:15:54'),
(58, 10, 'Tue', '2020-04-23 14:15:54', '2020-04-23 14:15:54'),
(59, 10, 'Wed', '2020-04-23 14:15:54', '2020-04-23 14:15:54'),
(60, 10, 'Thu', '2020-04-23 14:15:54', '2020-04-23 14:15:54');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_receiver_seen` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2017_11_26_113225_registration', 1),
(2, '2017_11_27_072215_qualification', 1),
(3, '2017_11_27_073048_work_experience', 1),
(4, '2017_11_27_090011_project', 1),
(6, '2017_11_27_092125_company_background', 1),
(7, '2017_12_11_074804_create_awards_table', 1),
(8, '2018_03_28_052313_create_user_verifications_table', 1),
(9, '2018_03_28_053755_create_user_tokens_table', 1),
(11, '2018_04_22_052348_create_institutions_table', 1),
(12, '2018_04_22_052550_create_employers_table', 1),
(17, '2018_05_09_110324_create_degree_list_table', 1),
(19, '2018_05_29_060107_create_rating_table', 1),
(20, '2018_06_21_071358_create_concentrations_table', 1),
(21, '2018_06_21_081918_create_project_institutions_table', 1),
(22, '2014_10_12_100000_create_password_resets_table', 2),
(23, '2018_06_24_062504_create_occupations_table', 3),
(24, '2018_05_09_110435_create_tags_table', 4),
(25, '2018_06_30_071652_create_splash_track_table', 5),
(26, '2018_07_29_113309_create_feeedback_table', 6),
(27, '2018_07_29_114012_create_images_for_feeedback_table', 6),
(28, '2018_08_01_115445_create_user_job_filters_track', 6),
(29, '2018_10_02_070531_create_user_concentrations_table', 1),
(30, '2018_10_15_060759_create_messages_table', 1),
(31, '2019_05_13_113117_create_bookmarks_table', 1),
(33, '2019_09_22_135138_create_work_experience_images', 7),
(34, '2017_11_27_091323_skill', 8),
(35, '2019_10_26_165745_create_company_images_table', 9),
(40, '2018_04_15_054718_create_write_offers_table', 10),
(41, '2018_05_07_092016_create_job_applications_table', 10),
(42, '2019_12_02_165923_create_job_skills', 10),
(43, '2019_12_02_170019_create_job_benefits', 10),
(44, '2019_12_02_170042_create_job_workdays', 10),
(46, '2020_02_10_124029_create_payments_table', 11),
(47, '2020_04_09_063811_create_target_universities_table', 12),
(48, '2020_04_18_204851_create_queue_jobs_table', 13),
(49, '2020_04_19_182712_create_failed_jobs_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

CREATE TABLE `occupations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `occupations`
--

INSERT INTO `occupations` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Software Enginner', 1, NULL, NULL),
(2, 'Software Engineer & Web Developer', 0, '2018-06-24 01:19:58', '2018-06-24 01:19:58'),
(3, 'Web Developer', 1, '2018-06-24 03:30:26', '2018-06-24 03:30:26'),
(4, 'Test', 0, '2018-07-01 00:21:46', '2018-07-01 00:21:46'),
(5, 'Youtuber', 0, '2018-07-01 00:38:17', '2018-07-01 00:38:17'),
(6, 'A Web Developer', 0, '2018-07-01 01:16:32', '2018-07-01 01:16:32'),
(7, 'Internship', 0, '2018-07-03 02:14:05', '2018-07-03 02:14:05'),
(8, 'Trainee Software Enginner', 0, '2018-07-03 02:14:05', '2018-07-03 02:14:05'),
(9, 'Software Engineer', 0, '2018-07-03 04:33:00', '2018-07-03 04:33:00'),
(10, 'System Administration', 0, '2018-07-03 04:42:13', '2018-07-03 04:42:13'),
(11, 'Executive', 0, '2018-07-10 02:17:52', '2018-07-10 02:17:52'),
(12, 'Content Writer', 0, '2018-07-10 23:27:10', '2018-07-10 23:27:10'),
(13, 'Support Engineer', 0, '2018-07-17 03:10:06', '2018-07-17 03:10:06'),
(14, 'IT Officer', 0, '2018-07-23 06:25:53', '2018-07-23 06:25:53'),
(15, 'IT Executive', 0, '2018-07-23 06:32:18', '2018-07-23 06:32:18'),
(16, 'Business Development Executive', 0, '2018-08-09 13:25:40', '2018-08-09 13:25:40'),
(17, 'System Administrator', 0, '2018-08-09 15:33:35', '2018-08-09 15:33:35'),
(18, 'Executive senior', 0, '2018-08-12 13:30:37', '2018-08-12 13:30:37'),
(19, 'Business Development Manager', 0, '2018-08-14 17:56:51', '2018-08-14 17:56:51'),
(20, 'Financial Analyst', 0, '2018-08-28 15:39:13', '2018-08-28 15:39:13'),
(21, 'Trainee Software Engineer', 0, '2018-09-13 16:35:40', '2018-09-13 16:35:40'),
(22, 'QA Engineer', 0, '2018-09-20 14:20:25', '2018-09-20 14:20:25'),
(23, 'Mobile Developer, Android', 0, '2018-09-20 16:29:16', '2018-09-20 16:29:16'),
(24, 'k', 0, '2018-09-21 10:53:35', '2018-09-21 10:53:35'),
(25, 'Graphic Engineer', 0, '2018-09-21 10:59:20', '2018-09-21 10:59:20'),
(26, 'Regional Capacity Building Officer', 0, '2018-09-24 21:12:43', '2018-09-24 21:12:43'),
(27, 'Senior Executive', 0, '2018-09-27 22:46:32', '2018-09-27 22:46:32'),
(28, 'Business Analyst', 0, '2018-09-28 20:36:26', '2018-09-28 20:36:26'),
(29, 'Senior Columnist', 0, '2018-09-28 21:31:02', '2018-09-28 21:31:02'),
(30, 'Intern', 0, '2018-09-30 17:43:15', '2018-09-30 17:43:15'),
(31, 'Architectural Drafter', 0, '2018-09-30 18:05:04', '2018-09-30 18:05:04'),
(32, 'Photographer', 0, '2018-10-20 12:55:11', '2018-10-20 12:55:11'),
(33, 'Photograph', 0, '2018-10-20 13:06:11', '2018-10-20 13:06:11'),
(34, 'Artist', 0, '2018-10-20 13:38:44', '2018-10-20 13:38:44'),
(35, 'Web Dev', 0, '2018-10-21 12:48:06', '2018-10-21 12:48:06'),
(36, 'Junior Lecturer', 0, '2018-11-03 17:17:08', '2018-11-03 17:17:08'),
(37, 'Senior Lecturer', 0, '2018-11-03 17:17:08', '2018-11-03 17:17:08'),
(38, 'gkj kjhk  lh', 0, '2018-12-11 16:01:28', '2018-12-11 16:01:28'),
(39, 'Software dev', 0, '2018-12-18 13:03:19', '2018-12-18 13:03:19'),
(40, 'Assistant Designer', 0, '2018-12-18 13:03:19', '2018-12-18 13:03:19'),
(41, 'Empty Space Test', 0, '2018-12-20 12:47:51', '2018-12-20 12:47:51'),
(42, 'Programmer Analyst', 0, '2018-12-24 13:27:01', '2018-12-24 13:27:01'),
(43, 'sfsdf', 0, '2018-12-24 18:24:18', '2018-12-24 18:24:18'),
(44, 'sdfgfsdg', 0, '2018-12-24 18:24:18', '2018-12-24 18:24:18'),
(45, 'He', 0, '2018-12-25 21:08:48', '2018-12-25 21:08:48'),
(46, 'Head of Staff', 0, '2018-12-25 21:12:10', '2018-12-25 21:12:10'),
(47, 'Computer Operator', 0, '2018-12-26 13:19:32', '2018-12-26 13:19:32'),
(48, 'Project Manager', 0, '2018-12-26 17:01:37', '2018-12-26 17:01:37'),
(49, 'UX/UI Design Intern', 0, '2019-01-05 21:33:10', '2019-01-05 21:33:10'),
(50, 'Graphic Designer', 0, '2019-01-05 21:34:55', '2019-01-05 21:34:55'),
(51, 'Jamuna bank limited', 0, '2019-03-15 23:38:33', '2019-03-15 23:38:33'),
(52, 'Junior Programmer', 0, '2019-03-16 12:20:39', '2019-03-16 12:20:39'),
(53, 'Manager', 0, '2019-03-16 16:10:13', '2019-03-16 16:10:13'),
(54, 'Junior Executive', 0, '2019-03-16 20:33:34', '2019-03-16 20:33:34'),
(55, 'Customer Executive.', 0, '2019-03-16 20:33:35', '2019-03-16 20:33:35'),
(56, 'Software Development Manager', 0, '2019-03-16 21:51:57', '2019-03-16 21:51:57'),
(57, 'Software Engineer (Intern)', 0, '2019-03-16 21:51:57', '2019-03-16 21:51:57'),
(58, 'Junior software engineer', 0, '2019-03-16 22:40:43', '2019-03-16 22:40:43'),
(59, 'Asst.Systems Engineer', 0, '2019-03-16 22:41:49', '2019-03-16 22:41:49'),
(60, 'Teaching Assistant', 0, '2019-03-16 23:39:14', '2019-03-16 23:39:14'),
(61, 'IT Manager', 0, '2019-03-17 09:30:13', '2019-03-17 09:30:13'),
(62, 'programmer', 0, '2019-03-17 10:38:21', '2019-03-17 10:38:21'),
(63, 'Sales Executive', 0, '2019-03-17 13:40:56', '2019-03-17 13:40:56'),
(64, 'Jr. Electrical Engineer', 0, '2019-03-17 16:39:50', '2019-03-17 16:39:50'),
(65, 'Distribution & Retail Sales & Service', 0, '2019-03-17 16:39:50', '2019-03-17 16:39:50'),
(66, 'risk', 0, '2019-03-20 21:09:19', '2019-03-20 21:09:19'),
(67, 'Software Developer', 0, '2019-03-22 22:26:12', '2019-03-22 22:26:12'),
(68, 'project director', 0, '2019-03-23 00:05:38', '2019-03-23 00:05:38'),
(69, 'Executive Engineer', 0, '2019-03-23 15:22:27', '2019-03-23 15:22:27'),
(70, 'Engineer Executive', 0, '2019-03-23 15:28:56', '2019-03-23 15:28:56'),
(71, 'jr. Web Developer', 0, '2019-03-23 16:10:13', '2019-03-23 16:10:13'),
(72, 'Web Application Developer', 0, '2019-03-23 21:27:07', '2019-03-23 21:27:07'),
(73, 'Web page Designer', 0, '2019-03-23 21:29:01', '2019-03-23 21:29:01'),
(74, 'Intern Software Engineer, Unity 3D', 0, '2019-03-25 00:38:12', '2019-03-25 00:38:12'),
(75, 'Software Analyst', 0, '2019-03-25 00:38:54', '2019-03-25 00:38:54'),
(76, 'QA', 0, '2019-03-29 13:52:14', '2019-03-29 13:52:14'),
(77, 'dss', 0, '2019-09-22 03:50:11', '2019-09-22 03:50:11'),
(78, 'Software Engineer(Full stack)', 0, '2020-01-02 08:59:10', '2020-01-02 08:59:10'),
(79, 'Full Stack Software Engineer', 0, '2020-02-27 10:04:58', '2020-02-27 10:04:58'),
(80, 'Software Architect', 0, '2020-03-02 05:54:43', '2020-03-02 05:54:43'),
(81, 'Web Developer fgg', 0, '2020-03-10 15:55:48', '2020-03-10 15:55:48'),
(82, 'erros public', 0, '2020-04-09 12:59:07', '2020-04-09 12:59:07'),
(83, 'free job post', 0, '2020-04-09 12:59:07', '2020-04-09 12:59:07'),
(84, '', 0, '2020-04-21 16:16:34', '2020-04-21 16:16:34'),
(85, '', 0, '2020-04-21 16:40:43', '2020-04-21 16:40:43'),
(86, '', 0, '2020-05-03 12:10:59', '2020-05-03 12:10:59'),
(87, '', 0, '2020-05-03 12:24:25', '2020-05-03 12:24:25'),
(88, '', 0, '2020-05-03 12:25:09', '2020-05-03 12:25:09'),
(89, '', 0, '2020-05-03 12:26:36', '2020-05-03 12:26:36'),
(90, '', 0, '2020-05-03 12:27:33', '2020-05-03 12:27:33'),
(91, '', 0, '2020-05-03 12:28:54', '2020-05-03 12:28:54'),
(92, '', 0, '2020-05-03 12:34:04', '2020-05-03 12:34:04'),
(93, '', 0, '2020-05-03 14:17:37', '2020-05-03 14:17:37'),
(94, '', 0, '2020-05-03 14:17:57', '2020-05-03 14:17:57'),
(95, '', 0, '2020-05-03 15:39:30', '2020-05-03 15:39:30'),
(96, '', 0, '2020-05-03 18:07:45', '2020-05-03 18:07:45'),
(97, '', 0, '2020-05-03 20:39:02', '2020-05-03 20:39:02'),
(98, '', 0, '2020-05-03 22:52:54', '2020-05-03 22:52:54'),
(99, '', 0, '2020-05-03 23:09:42', '2020-05-03 23:09:42'),
(100, '', 0, '2020-05-03 23:31:59', '2020-05-03 23:31:59'),
(101, '', 0, '2020-05-03 23:33:42', '2020-05-03 23:33:42'),
(102, '', 0, '2020-05-04 09:25:03', '2020-05-04 09:25:03'),
(103, '', 0, '2020-05-04 09:25:08', '2020-05-04 09:25:08'),
(104, '', 0, '2020-05-04 15:58:50', '2020-05-04 15:58:50'),
(105, '', 0, '2020-05-04 15:59:18', '2020-05-04 15:59:18'),
(106, '', 0, '2020-05-04 16:08:14', '2020-05-04 16:08:14'),
(107, '', 0, '2020-05-04 16:08:31', '2020-05-04 16:08:31'),
(108, '', 0, '2020-05-04 16:19:02', '2020-05-04 16:19:02'),
(109, '', 0, '2020-05-04 16:34:27', '2020-05-04 16:34:27'),
(110, '', 0, '2020-05-04 16:34:42', '2020-05-04 16:34:42'),
(111, '', 0, '2020-05-04 16:40:18', '2020-05-04 16:40:18'),
(112, '', 0, '2020-05-04 17:12:24', '2020-05-04 17:12:24'),
(113, '', 0, '2020-05-04 17:24:06', '2020-05-04 17:24:06'),
(114, '', 0, '2020-05-04 17:26:03', '2020-05-04 17:26:03'),
(115, '', 0, '2020-05-04 17:28:23', '2020-05-04 17:28:23'),
(116, '', 0, '2020-05-04 17:33:14', '2020-05-04 17:33:14'),
(117, '', 0, '2020-05-05 08:08:59', '2020-05-05 08:08:59'),
(118, '', 0, '2020-05-05 08:13:03', '2020-05-05 08:13:03'),
(119, '', 0, '2020-05-05 08:13:35', '2020-05-05 08:13:35'),
(120, '', 0, '2020-05-05 08:13:56', '2020-05-05 08:13:56'),
(121, '', 0, '2020-05-05 08:36:35', '2020-05-05 08:36:35'),
(122, '', 0, '2020-05-05 08:41:23', '2020-05-05 08:41:23'),
(123, '', 0, '2020-05-05 08:59:58', '2020-05-05 08:59:58'),
(124, '', 0, '2020-05-05 14:20:56', '2020-05-05 14:20:56'),
(125, '', 0, '2020-05-07 16:17:20', '2020-05-07 16:17:20'),
(126, '', 0, '2020-05-09 06:39:29', '2020-05-09 06:39:29'),
(127, '', 0, '2020-05-09 06:39:33', '2020-05-09 06:39:33'),
(128, '', 0, '2020-05-09 06:39:36', '2020-05-09 06:39:36'),
(129, '', 0, '2020-05-09 19:09:06', '2020-05-09 19:09:06'),
(130, '', 0, '2020-05-09 19:09:53', '2020-05-09 19:09:53'),
(131, '', 0, '2020-05-09 19:10:35', '2020-05-09 19:10:35'),
(132, '', 0, '2020-05-09 19:11:09', '2020-05-09 19:11:09'),
(133, '', 0, '2020-05-09 19:13:20', '2020-05-09 19:13:20'),
(134, '', 0, '2020-05-09 19:26:59', '2020-05-09 19:26:59'),
(135, '', 0, '2020-05-09 19:27:12', '2020-05-09 19:27:12'),
(136, '', 0, '2020-05-09 19:32:14', '2020-05-09 19:32:14'),
(137, '', 0, '2020-05-09 19:32:37', '2020-05-09 19:32:37'),
(138, '', 0, '2020-05-09 19:32:55', '2020-05-09 19:32:55'),
(139, '', 0, '2020-05-09 19:34:11', '2020-05-09 19:34:11'),
(140, '', 0, '2020-05-09 19:34:27', '2020-05-09 19:34:27'),
(141, '', 0, '2020-05-09 19:34:50', '2020-05-09 19:34:50'),
(142, '', 0, '2020-05-09 19:37:40', '2020-05-09 19:37:40'),
(143, '', 0, '2020-05-11 23:18:24', '2020-05-11 23:18:24'),
(144, '', 0, '2020-05-12 14:00:11', '2020-05-12 14:00:11'),
(145, '', 0, '2020-05-15 11:26:53', '2020-05-15 11:26:53'),
(146, '', 0, '2020-05-15 11:38:14', '2020-05-15 11:38:14'),
(147, '', 0, '2020-05-15 11:38:18', '2020-05-15 11:38:18'),
(148, '', 0, '2020-05-15 11:38:20', '2020-05-15 11:38:20'),
(149, '', 0, '2020-05-15 11:40:07', '2020-05-15 11:40:07'),
(150, '', 0, '2020-05-15 11:41:42', '2020-05-15 11:41:42'),
(151, '', 0, '2020-05-15 11:41:46', '2020-05-15 11:41:46'),
(152, '', 0, '2020-05-15 11:45:36', '2020-05-15 11:45:36'),
(153, '', 0, '2020-05-15 11:45:38', '2020-05-15 11:45:38'),
(154, '', 0, '2020-05-15 11:45:41', '2020-05-15 11:45:41'),
(155, '', 0, '2020-05-15 11:45:42', '2020-05-15 11:45:42'),
(156, '', 0, '2020-05-15 11:45:44', '2020-05-15 11:45:44'),
(157, '', 0, '2020-05-15 11:53:14', '2020-05-15 11:53:14'),
(158, '', 0, '2020-05-15 11:54:40', '2020-05-15 11:54:40'),
(159, '', 0, '2020-05-15 11:54:45', '2020-05-15 11:54:45'),
(160, '', 0, '2020-05-15 11:54:48', '2020-05-15 11:54:48'),
(161, '', 0, '2020-05-15 11:55:30', '2020-05-15 11:55:30'),
(162, '', 0, '2020-05-15 11:55:32', '2020-05-15 11:55:32'),
(163, '', 0, '2020-05-15 11:55:36', '2020-05-15 11:55:36'),
(164, '', 0, '2020-05-15 11:55:37', '2020-05-15 11:55:37'),
(165, '', 0, '2020-05-15 11:55:39', '2020-05-15 11:55:39'),
(166, '', 0, '2020-05-15 11:55:41', '2020-05-15 11:55:41'),
(167, '', 0, '2020-05-15 11:55:43', '2020-05-15 11:55:43'),
(168, '', 0, '2020-05-15 11:55:44', '2020-05-15 11:55:44'),
(169, '', 0, '2020-05-15 11:55:46', '2020-05-15 11:55:46'),
(170, '', 0, '2020-05-15 11:55:48', '2020-05-15 11:55:48'),
(171, '', 0, '2020-05-15 12:04:26', '2020-05-15 12:04:26'),
(172, '', 0, '2020-05-15 12:05:46', '2020-05-15 12:05:46'),
(173, '', 0, '2020-05-15 12:05:49', '2020-05-15 12:05:49'),
(174, '', 0, '2020-05-15 12:05:50', '2020-05-15 12:05:50'),
(175, '', 0, '2020-05-15 12:22:29', '2020-05-15 12:22:29'),
(176, '', 0, '2020-05-15 12:22:30', '2020-05-15 12:22:30'),
(177, '', 0, '2020-05-15 12:22:32', '2020-05-15 12:22:32'),
(178, '', 0, '2020-05-15 12:22:35', '2020-05-15 12:22:35'),
(179, '', 0, '2020-05-15 12:22:37', '2020-05-15 12:22:37'),
(180, '', 0, '2020-05-15 12:22:39', '2020-05-15 12:22:39'),
(181, '', 0, '2020-05-15 12:22:40', '2020-05-15 12:22:40'),
(182, '', 0, '2020-05-15 12:22:42', '2020-05-15 12:22:42'),
(183, '', 0, '2020-05-15 12:22:43', '2020-05-15 12:22:43'),
(184, '', 0, '2020-05-15 12:22:44', '2020-05-15 12:22:44'),
(185, '', 0, '2020-05-15 12:22:46', '2020-05-15 12:22:46'),
(186, '', 0, '2020-05-15 12:22:48', '2020-05-15 12:22:48'),
(187, '', 0, '2020-05-15 12:22:49', '2020-05-15 12:22:49'),
(188, '', 0, '2020-05-15 12:22:51', '2020-05-15 12:22:51'),
(189, '', 0, '2020-05-15 12:22:52', '2020-05-15 12:22:52'),
(190, '', 0, '2020-05-15 12:22:53', '2020-05-15 12:22:53'),
(191, '', 0, '2020-05-15 12:22:55', '2020-05-15 12:22:55'),
(192, '', 0, '2020-05-15 12:33:47', '2020-05-15 12:33:47'),
(193, '', 0, '2020-05-15 14:20:16', '2020-05-15 14:20:16'),
(194, '', 0, '2020-05-15 21:49:59', '2020-05-15 21:49:59'),
(195, '', 0, '2020-05-15 21:59:57', '2020-05-15 21:59:57'),
(196, '', 0, '2020-05-15 21:59:59', '2020-05-15 21:59:59'),
(197, '', 0, '2020-05-15 22:00:02', '2020-05-15 22:00:02'),
(198, '', 0, '2020-05-15 22:00:04', '2020-05-15 22:00:04'),
(199, '', 0, '2020-05-16 09:03:07', '2020-05-16 09:03:07'),
(200, '', 0, '2020-05-17 19:35:42', '2020-05-17 19:35:42'),
(201, '', 0, '2020-05-17 19:35:51', '2020-05-17 19:35:51'),
(202, '', 0, '2020-05-17 19:36:05', '2020-05-17 19:36:05'),
(203, '', 0, '2020-05-17 20:48:02', '2020-05-17 20:48:02'),
(204, '', 0, '2020-05-17 20:48:06', '2020-05-17 20:48:06'),
(205, '', 0, '2020-05-17 20:48:10', '2020-05-17 20:48:10'),
(206, '', 0, '2020-05-17 20:48:52', '2020-05-17 20:48:52'),
(207, '', 0, '2020-05-19 08:05:51', '2020-05-19 08:05:51'),
(208, '', 0, '2020-05-19 08:07:42', '2020-05-19 08:07:42'),
(209, '', 0, '2020-05-19 08:08:03', '2020-05-19 08:08:03'),
(210, '', 0, '2020-05-19 08:10:42', '2020-05-19 08:10:42'),
(211, '', 0, '2020-05-19 08:10:45', '2020-05-19 08:10:45'),
(212, '', 0, '2020-05-19 08:10:48', '2020-05-19 08:10:48'),
(213, '', 0, '2020-05-19 08:10:55', '2020-05-19 08:10:55'),
(214, '', 0, '2020-05-19 09:47:11', '2020-05-19 09:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reg_id` int(10) UNSIGNED NOT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `payment_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bkash_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bkash_transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `queue_jobs`
--

CREATE TABLE `queue_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `queue_jobs`
--

INSERT INTO `queue_jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\SendNotificationEmailJob\\\":5:{s:7:\\\"\\u0000*\\u0000data\\\";a:8:{s:15:\\\"receipientEmail\\\";s:18:\\\"rubab891@yahoo.com\\\";s:14:\\\"receipientName\\\";s:14:\\\"Muhammad Rubab\\\";s:7:\\\"subject\\\";s:12:\\\"Hired Alert!\\\";s:3:\\\"msg\\\";s:76:\\\"Congratulations! Rubab Hossain hired you for the Software Engineer position.\\\";s:11:\\\"senderEmail\\\";s:19:\\\"rubab2020@gmail.com\\\";s:10:\\\"senderName\\\";s:13:\\\"Rubab Hossain\\\";s:9:\\\"alertType\\\";s:5:\\\"hired\\\";s:7:\\\"siteUrl\\\";s:21:\\\"http:\\/\\/localhost:4200\\\";}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";O:13:\\\"Carbon\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-05-19 18:06:36.627664\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}}\"}}', 0, NULL, 1589889996, 1589889992),
(2, 'default', '{\"displayName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotificationEmailJob\",\"command\":\"O:33:\\\"App\\\\Jobs\\\\SendNotificationEmailJob\\\":5:{s:7:\\\"\\u0000*\\u0000data\\\";a:8:{s:15:\\\"receipientEmail\\\";s:19:\\\"rubab2020@gmail.com\\\";s:14:\\\"receipientName\\\";s:13:\\\"Rubab Hossain\\\";s:7:\\\"subject\\\";s:12:\\\"Hired Alert!\\\";s:3:\\\"msg\\\";s:81:\\\"Congratulations! Muhammad Rubab hired you for the test disble circulate position.\\\";s:11:\\\"senderEmail\\\";s:18:\\\"rubab891@yahoo.com\\\";s:10:\\\"senderName\\\";s:14:\\\"Muhammad Rubab\\\";s:9:\\\"alertType\\\";s:5:\\\"hired\\\";s:7:\\\"siteUrl\\\";s:21:\\\"http:\\/\\/localhost:4200\\\";}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";O:13:\\\"Carbon\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-05-20 01:09:06.660663\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:10:\\\"Asia\\/Dhaka\\\";}}\"}}', 0, NULL, 1589915346, 1589915342);

-- --------------------------------------------------------

--
-- Table structure for table `splash_tracks`
--

CREATE TABLE `splash_tracks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `section` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `feature_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cloud Computing', 1, NULL, NULL),
(3, 'AWS', 1, NULL, NULL),
(4, 'Storage/Data Management', 1, NULL, NULL),
(5, 'Data mining\r\n', 1, NULL, NULL),
(6, 'Integration software', 1, NULL, NULL),
(7, 'SQL', 1, NULL, NULL),
(8, 'NoSQL', 1, NULL, NULL),
(9, 'Oracle Database', 1, NULL, NULL),
(10, 'MongoDB', 1, NULL, NULL),
(11, 'ASP.NET', 1, NULL, NULL),
(12, 'JavaScript', 1, NULL, NULL),
(13, 'Web Architecture\r\n', 1, NULL, NULL),
(14, 'Angular', 1, NULL, NULL),
(15, 'AngularJs', 1, NULL, NULL),
(16, 'Node.js', 1, NULL, NULL),
(17, 'React', 1, NULL, NULL),
(18, 'UI Design', 1, NULL, NULL),
(19, 'UX Design', 1, NULL, NULL),
(20, 'Prototyping', 1, NULL, NULL),
(21, 'Git', 1, NULL, NULL),
(22, 'Java', 1, NULL, NULL),
(23, 'Python', 1, NULL, NULL),
(24, 'Data Visualization', 1, NULL, NULL),
(25, 'Data Analysis', 1, NULL, NULL),
(26, 'Progressive Web Application', 1, NULL, NULL),
(27, 'Java Spring', 1, NULL, NULL),
(28, 'Vue.js', 1, NULL, NULL),
(29, 'Android', 1, NULL, NULL),
(30, 'Kotlin', 1, NULL, NULL),
(31, 'SEM', 1, NULL, NULL),
(32, 'SEO', 1, NULL, NULL),
(33, 'Mobile App Development', 1, NULL, NULL),
(34, 'iOS Development', 1, NULL, NULL),
(35, 'IT Security', 1, NULL, NULL),
(36, 'Cybersecurity', 1, NULL, NULL),
(37, 'Marketing campaign', 1, NULL, NULL),
(38, 'Microsoft Azure', 1, NULL, NULL),
(39, 'Linux', 1, NULL, NULL),
(40, 'AutoCAD', 1, NULL, NULL),
(41, 'MATLAB', 1, NULL, NULL),
(42, 'Algorithmic Design', 1, NULL, NULL),
(43, 'Artificial Intelligence', 1, NULL, NULL),
(44, 'Perl', 1, NULL, NULL),
(45, 'Ruby', 1, NULL, NULL),
(46, 'Ruby on Rails', 1, NULL, NULL),
(47, 'Shell Scripting', 1, NULL, NULL),
(48, 'Big Data', 1, NULL, NULL),
(49, 'QA Engineer', 1, NULL, NULL),
(50, 'Analytics Experiments', 1, NULL, NULL),
(51, 'VMware', 1, NULL, NULL),
(52, 'Managerial Economics', 1, NULL, NULL),
(53, 'Macroeconometric Forecasting', 1, NULL, NULL),
(54, 'Microsoft SQL Server', 1, NULL, NULL),
(55, 'HTML/CSS', 1, NULL, NULL),
(56, 'jQuery', 1, NULL, NULL),
(57, 'Bootstrap', 1, NULL, NULL),
(58, 'Typography', 1, NULL, NULL),
(59, 'Adobe Acrobat', 1, NULL, NULL),
(60, 'Adobe Creative', 1, NULL, NULL),
(61, 'Adobe Flash', 1, NULL, NULL),
(62, 'Adobe Illustrator', 1, NULL, NULL),
(63, 'Adobe InDesign', 1, NULL, NULL),
(64, 'Adobe Photoshop', 1, NULL, NULL),
(65, 'Aesthetic sense', 1, NULL, NULL),
(66, 'Analytical', 1, NULL, NULL),
(67, 'MS Excel', 1, NULL, NULL),
(68, 'Flash', 1, NULL, NULL),
(69, 'Marketing', 1, NULL, NULL),
(70, 'Negotiation', 1, NULL, NULL),
(71, 'Networking', 1, NULL, NULL),
(72, 'Photography', 1, NULL, NULL),
(73, 'Photoshop', 1, NULL, NULL),
(74, 'Planning', 1, NULL, NULL),
(75, 'PowerPoint', 1, NULL, NULL),
(76, 'Presentation', 1, NULL, NULL),
(77, 'Print design', 1, NULL, NULL),
(78, 'Printing', 1, NULL, NULL),
(79, 'Prioritizing', 1, NULL, NULL),
(80, 'Problem-solving', 1, NULL, NULL),
(81, 'Production', 1, NULL, NULL),
(82, 'Project management', 1, NULL, NULL),
(83, 'Quark', 1, NULL, NULL),
(84, 'QuarkXpress', 1, NULL, NULL),
(85, 'Sales', 1, NULL, NULL),
(86, 'Sketching', 1, NULL, NULL),
(87, 'Spacing', 1, NULL, NULL),
(88, 'Storyboard creation', 1, NULL, NULL),
(89, 'Strategic thinking', 1, NULL, NULL),
(90, 'Teamwork', 1, NULL, NULL),
(91, 'Time management', 1, NULL, NULL),
(92, 'Microsoft Office', 1, NULL, NULL),
(93, 'Middleware software', 0, '2018-09-21 05:01:35', '2018-09-21 05:01:35'),
(94, 'Web Architecture', 0, '2018-09-21 05:23:16', '2018-09-21 05:23:16'),
(95, 'Forgery Detection', 0, '2018-09-24 15:12:43', '2018-09-24 15:12:43'),
(96, 'Impostor Recognition', 0, '2018-09-24 15:12:44', '2018-09-24 15:12:44'),
(98, 'Sales Report', 0, '2018-09-27 16:46:32', '2018-09-27 16:46:32'),
(99, 'Ms Word', 0, '2018-09-27 16:46:32', '2018-09-27 16:46:32'),
(100, 'Editing', 0, '2018-09-28 15:31:02', '2018-09-28 15:31:02'),
(101, 'Laravel', 0, '2018-10-21 07:20:07', '2018-10-21 07:20:07'),
(102, 'PHP', 0, '2018-10-21 07:20:07', '2018-10-21 07:20:07'),
(103, 'Graphics Design', 0, '2018-10-21 15:08:10', '2018-10-21 15:08:10'),
(104, 'ERP Solutions', 0, '2018-10-24 15:37:09', '2018-10-24 15:37:09'),
(105, 'Content Creation', 0, '2018-11-24 21:25:55', '2018-11-24 21:25:55'),
(106, 'Research', 0, '2018-11-24 21:25:55', '2018-11-24 21:25:55'),
(107, 'Financial Anaylst', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(108, 'Financial Budgetting', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(109, 'Cashflow Management', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(110, 'Risk Management', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(111, 'Financial Accounts', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(112, 'Final Accounts', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(113, 'Value for money', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(114, 'Laughing', 0, '2018-12-14 12:31:45', '2018-12-14 12:31:45'),
(115, 'Financial Analysis', 1, '2018-12-23 12:41:44', '2018-12-23 12:41:44'),
(116, 'Cost/Benefit Analysis', 1, '2018-12-23 12:41:44', '2018-12-23 12:41:44'),
(117, 'Corporate Accounting', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(118, 'Sales Projection', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(119, 'Internal Audit', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(120, 'Risk Assessment', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(121, 'Plant/Field Accounting', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(122, 'Financial Services ', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(123, 'Corporate Banking', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(124, 'Budgets/Forecasts', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(125, 'Statistical Analysis', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(126, 'Capital Project Analysis', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(127, 'Labor Analysis', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(128, 'Contribution Margin Analysis', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(129, 'Mergers and Acquisitions', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(130, 'Corporate Development', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(131, 'Strategic Planning', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(132, 'Scorecarding', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(133, 'Metric-Based Analysis', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(134, 'Financial Modeling', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(135, 'Customer Service', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(136, 'Interpersonal Skills', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(137, 'Word Processing', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(138, 'Spreadsheet', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(139, 'Leading & Communicating', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(140, 'Multitasking', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(141, 'Meet Deadlines', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(142, 'Handling Information', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(143, 'Data Protection', 1, '2018-12-23 12:41:45', '2018-12-23 12:41:45'),
(144, 'Public Relations', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(145, 'Communication', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(147, 'Decision Making', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(149, 'Conflict Resolution', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(150, 'Leadership', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(151, 'Adaptability', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(152, 'Creativity', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(153, 'Critical thinking', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(154, 'Numeracy', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(155, 'Reporting', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(156, 'Troubleshooting', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(157, 'Collaboration', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(158, 'Flexibility', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(160, 'Observation', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(161, 'Participation', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(163, 'Categorizing data', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(164, 'Coordinating', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(165, 'Goal setting', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(166, 'Meeting deadlines', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(167, 'Multi-tasking', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(168, 'Scheduling', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(169, 'Advising', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(170, 'Coaching', 1, '2018-12-23 12:41:46', '2018-12-23 12:41:46'),
(171, 'Delegating', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(172, 'Diplomacy', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(173, 'Interviewing', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(175, 'People management', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(176, 'Problem solving', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(179, 'Honesty', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(180, 'Integrity', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(181, 'Maturity', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(182, 'Patience', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(183, 'Reliability', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(185, 'Car maintenance', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(186, 'Cleaning', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(187, 'Driving', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(188, 'First Aid & CPR', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(189, 'Garden maintenance', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(190, 'Painting a room', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(191, 'Parenting', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(192, 'Vacuuming', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(193, 'Active listening', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(194, 'Constructive criticism', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(195, 'Interpersonal communication', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(196, 'Public speaking', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(197, 'Verbal/Non-verbal communication', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(198, 'Written communication', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(199, 'Caring', 1, '2018-12-23 12:41:47', '2018-12-23 12:41:47'),
(200, 'Cooperation', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(201, 'Curiosity', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(202, 'Perseverance', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(203, 'Sense of humor', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(204, 'Stress management', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(205, 'Verbal and nonverbal communication', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(206, 'Accountability', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(207, 'Analyzing information', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(208, 'Digital literacy', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(209, 'Imagination', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(210, 'Initiative', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(211, 'Reading', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(212, 'Writing', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(213, 'Typing/Word processing', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(214, 'Fluency in coding', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(215, 'Systems administration', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(216, 'Spreadsheets', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(217, 'Email management', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(218, 'Ability to teach and mentor', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(219, 'Risk-taking', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(220, 'Team building', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(221, 'Decision-making', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(222, 'Project planning', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(223, 'Task delegation', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(224, 'Team communication', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(225, 'Team leadership', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(226, 'Attention to detail', 1, '2018-12-23 12:41:48', '2018-12-23 12:41:48'),
(227, 'Software Engineer', 0, '2018-12-25 21:16:34', '2018-12-25 21:16:34'),
(228, 'Developer', 0, '2018-12-25 21:16:34', '2018-12-25 21:16:34'),
(229, 'Programmer', 0, '2018-12-25 21:16:34', '2018-12-25 21:16:34'),
(230, 'Ccna', 0, '2018-12-26 13:22:40', '2018-12-26 13:22:40'),
(231, 'Mikrotik', 0, '2018-12-26 13:22:40', '2018-12-26 13:22:40'),
(232, 'adobe XD', 0, '2019-01-05 21:33:10', '2019-01-05 21:33:10'),
(233, 'Data entry operate', 0, '2019-03-15 23:38:33', '2019-03-15 23:38:33'),
(234, 'Prepare vouchar statement', 0, '2019-03-15 23:38:33', '2019-03-15 23:38:33'),
(235, 'customer relationship', 0, '2019-03-15 23:42:18', '2019-03-15 23:42:18'),
(236, 'Angular Material', 0, '2019-03-16 12:28:52', '2019-03-16 12:28:52'),
(237, 'MySQL', 0, '2019-03-16 12:28:52', '2019-03-16 12:28:52'),
(238, 'Django', 0, '2019-03-16 21:51:57', '2019-03-16 21:51:57'),
(239, 'Dart', 0, '2019-03-16 21:52:36', '2019-03-16 21:52:36'),
(240, 'codeigniter', 0, '2019-03-16 23:09:34', '2019-03-16 23:09:34'),
(243, 'Wordpress', 0, '2019-03-22 23:05:21', '2019-03-22 23:05:21'),
(244, 'c++', 0, '2019-03-22 23:58:59', '2019-03-22 23:58:59'),
(247, 'Android Development', 1, NULL, NULL),
(248, 'AI', 1, NULL, NULL),
(249, 'Machine Learning', 1, NULL, NULL),
(250, 'Deep Learning', 1, NULL, NULL),
(251, 'Data mining', 0, '2020-04-02 10:41:19', '2020-04-02 10:41:19');

-- --------------------------------------------------------

--
-- Table structure for table `target_universities`
--

CREATE TABLE `target_universities` (
  `id` int(10) UNSIGNED NOT NULL,
  `job_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `track_user_job_filters`
--

CREATE TABLE `track_user_job_filters` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `filters` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `track_user_job_filters`
--

INSERT INTO `track_user_job_filters` (`id`, `user_id`, `filters`, `created_at`, `updated_at`) VALUES
(1, 1, 'a:3:{s:16:\"employment_types\";a:1:{i:0;s:21:\"Permanent (Full-Time)\";}s:13:\"salary_ranges\";a:2:{i:0;a:2:{s:5:\"start\";N;s:3:\"end\";N;}i:1;a:2:{s:5:\"start\";i:0;s:3:\"end\";i:9999;}}s:18:\"deadline_durations\";a:3:{i:0;s:1:\"2\";i:1;s:1:\"6\";i:2;s:2:\"13\";}}', '2020-04-16 14:48:19', '2020-04-16 14:49:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci,
  `picture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `picture_sm` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_photo_sm` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_expertise` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `valid` tinyint(1) NOT NULL DEFAULT '0',
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `about`, `picture`, `picture_sm`, `cover_photo`, `cover_photo_sm`, `dob`, `gender`, `company_expertise`, `user_type`, `remember_token`, `created_at`, `updated_at`, `valid`, `provider`, `provider_id`) VALUES
(1, 'Muhammad Rubab', 'rubab891@yahoo.com', NULL, '', 'this is my about', '041120200115485e90c5e42568b.facebook-pic.jpg', '041120200115505e90c5e6a5021.facebook-pic.jpg', '050420202208285eb03dfc05b9e.jpg', '050420202208305eb03dfe34127.jpg', '2020-05-19', 'male', NULL, '0', '', '2020-04-10 19:15:47', '2020-05-19 08:05:27', 1, 'facebook', '2354780081199212'),
(3, 'Rubab Hossain', 'rubab2020@gmail.com', NULL, '', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\nquis nostrud exercitation ullamco laboris nisi ut', '042720201712305ea6be1e43069.jpg', '042720201712315ea6be1f840df.jpg', '042720201730375ea6c25d1795d.jpg', '042720201730375ea6c25d8346b.jpg', NULL, NULL, 'develop software, sell software', '1', '', '2020-04-10 20:09:36', '2020-04-28 18:13:41', 1, 'google', '114054408658864954017');

-- --------------------------------------------------------

--
-- Table structure for table `user_awards`
--

CREATE TABLE `user_awards` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institute` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_awards`
--

INSERT INTO `user_awards` (`id`, `user_id`, `title`, `institute`, `date`, `created_at`, `updated_at`) VALUES
(1, 1, 'ICPC', 'NSU', '2020-05-21', '2020-05-19 08:11:10', '2020-05-19 08:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_experiences`
--

CREATE TABLE `user_experiences` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `occupation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employer` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_id` int(11) DEFAULT NULL,
  `continuing` int(11) DEFAULT NULL,
  `period_from` date DEFAULT NULL,
  `period_to` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_experiences`
--

INSERT INTO `user_experiences` (`id`, `user_id`, `occupation`, `employer`, `application_id`, `continuing`, `period_from`, `period_to`, `created_at`, `updated_at`) VALUES
(1, 1, 'Software Enginner', NULL, NULL, 1, NULL, NULL, '2020-04-10 20:05:08', '2020-05-19 09:47:11'),
(3, 1, 'alsdf', 'Rubab Hossain', 10, 0, '2020-04-20', '2020-04-23', '2020-04-20 15:01:10', '2020-05-19 09:47:11'),
(5, 3, 'test disble circulate', 'Muhammad Rubab', 3, 1, '2020-05-20', NULL, '2020-05-19 19:09:01', '2020-05-19 19:09:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_experience_images`
--

CREATE TABLE `user_experience_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_sm` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_experience_images`
--

INSERT INTO `user_experience_images` (`id`, `image`, `image_sm`, `description`, `work_id`, `created_at`, `updated_at`) VALUES
(1, '050320201824255eaeb7f9a50a1.jpg', '050320201824275eaeb7fb3f163.jpg', NULL, 3, '2020-05-03 12:24:27', '2020-05-03 12:24:27'),
(2, '050420200007455eaf08719d058.jpg', '050420200007475eaf0873cbfb6.jpg', NULL, 3, '2020-05-03 18:07:47', '2020-05-03 18:07:47'),
(3, '050420200007485eaf08741c0eb.jpg', '050420200007485eaf087447deb.jpg', NULL, 3, '2020-05-03 18:07:48', '2020-05-03 18:07:48'),
(4, '050420200007485eaf0874707fc.jpg', '050420200007485eaf0874958ca.jpg', NULL, 3, '2020-05-03 18:07:48', '2020-05-03 18:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_qualifications`
--

CREATE TABLE `user_qualifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `degree` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `result_cgpa` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cgpa_scale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completing_date` date DEFAULT NULL,
  `enrolled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_qualifications`
--

INSERT INTO `user_qualifications` (`id`, `user_id`, `degree`, `result_cgpa`, `cgpa_scale`, `institution`, `completing_date`, `enrolled`, `created_at`, `updated_at`) VALUES
(2, 1, 'Bachelor of Business Administration (BBA)', '3', '5', 'Bangladesh University of Textiles', NULL, 1, '2020-04-10 20:04:56', '2020-05-19 08:05:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Integration software', '2020-05-19 08:05:27', '2020-05-19 08:05:27'),
(2, 1, 'Storage/Data Management', '2020-05-19 08:05:28', '2020-05-19 08:05:28'),
(3, 1, 'Web Architecture\r\n', '2020-05-19 09:47:09', '2020-05-19 09:47:09'),
(4, 1, 'Progressive Web Application', '2020-05-19 09:47:09', '2020-05-19 09:47:09'),
(5, 1, 'Mobile App Development', '2020-05-19 09:47:09', '2020-05-19 09:47:09'),
(6, 1, 'Data Visualization', '2020-05-19 09:47:09', '2020-05-19 09:47:09'),
(7, 1, 'Cloud Computing', '2020-05-19 09:47:09', '2020-05-19 09:47:09'),
(8, 1, 'Prototyping', '2020-05-19 09:47:09', '2020-05-19 09:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `reg_id` int(10) UNSIGNED NOT NULL,
  `jwt_token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_blacklisted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `reg_id` int(10) UNSIGNED NOT NULL,
  `token` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_backgrounds`
--
ALTER TABLE `company_backgrounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_background_reg_id_foreign` (`user_id`);

--
-- Indexes for table `company_images`
--
ALTER TABLE `company_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_images_reg_id_index` (`user_id`);

--
-- Indexes for table `degrees`
--
ALTER TABLE `degrees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employers`
--
ALTER TABLE `employers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favourites`
--
ALTER TABLE `favourites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedbacks_images`
--
ALTER TABLE `feedbacks_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedbacks_images_feedback_id_index` (`feedback_id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_user_id_foreign` (`user_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applications_applicant_id_index` (`applicant_id`),
  ADD KEY `job_applications_job_id_index` (`job_id`);

--
-- Indexes for table `job_benefits`
--
ALTER TABLE `job_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_benefits_job_id_foreign` (`job_id`);

--
-- Indexes for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_skills_job_id_foreign` (`job_id`);

--
-- Indexes for table `job_workdays`
--
ALTER TABLE `job_workdays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_workdays_job_id_foreign` (`job_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `occupations`
--
ALTER TABLE `occupations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_reg_id_foreign` (`reg_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_job_id_index` (`job_id`);

--
-- Indexes for table `queue_jobs`
--
ALTER TABLE `queue_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queue_jobs_queue_reserved_at_index` (`queue`,`reserved_at`);

--
-- Indexes for table `splash_tracks`
--
ALTER TABLE `splash_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `target_universities`
--
ALTER TABLE `target_universities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_universities_job_id_foreign` (`job_id`);

--
-- Indexes for table `track_user_job_filters`
--
ALTER TABLE `track_user_job_filters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `track_user_job_filters_user_id_index` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_email_unique` (`email`),
  ADD UNIQUE KEY `registration_phone_unique` (`phone`);

--
-- Indexes for table `user_awards`
--
ALTER TABLE `user_awards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `awards_reg_id_foreign` (`user_id`);

--
-- Indexes for table `user_experiences`
--
ALTER TABLE `user_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_experience_reg_id_foreign` (`user_id`);

--
-- Indexes for table `user_experience_images`
--
ALTER TABLE `user_experience_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_experience_images_work_id_index` (`work_id`);

--
-- Indexes for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qualification_reg_id_foreign` (`user_id`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skill_reg_id_foreign` (`user_id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_tokens_reg_id_foreign` (`reg_id`);

--
-- Indexes for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_verifications_reg_id_foreign` (`reg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_backgrounds`
--
ALTER TABLE `company_backgrounds`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_images`
--
ALTER TABLE `company_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `degrees`
--
ALTER TABLE `degrees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `employers`
--
ALTER TABLE `employers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `favourites`
--
ALTER TABLE `favourites`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks_images`
--
ALTER TABLE `feedbacks_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_benefits`
--
ALTER TABLE `job_benefits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_skills`
--
ALTER TABLE `job_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_workdays`
--
ALTER TABLE `job_workdays`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `occupations`
--
ALTER TABLE `occupations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `queue_jobs`
--
ALTER TABLE `queue_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `splash_tracks`
--
ALTER TABLE `splash_tracks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `target_universities`
--
ALTER TABLE `target_universities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `track_user_job_filters`
--
ALTER TABLE `track_user_job_filters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_awards`
--
ALTER TABLE `user_awards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_experiences`
--
ALTER TABLE `user_experiences`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_experience_images`
--
ALTER TABLE `user_experience_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_backgrounds`
--
ALTER TABLE `company_backgrounds`
  ADD CONSTRAINT `company_background_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_images`
--
ALTER TABLE `company_images`
  ADD CONSTRAINT `company_images_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedbacks_images`
--
ALTER TABLE `feedbacks_images`
  ADD CONSTRAINT `feedbacks_images_feedback_id_foreign` FOREIGN KEY (`feedback_id`) REFERENCES `feedbacks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_benefits`
--
ALTER TABLE `job_benefits`
  ADD CONSTRAINT `job_benefits_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD CONSTRAINT `job_skills_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_workdays`
--
ALTER TABLE `job_workdays`
  ADD CONSTRAINT `job_workdays_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_reg_id_foreign` FOREIGN KEY (`reg_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `target_universities`
--
ALTER TABLE `target_universities`
  ADD CONSTRAINT `target_universities_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `track_user_job_filters`
--
ALTER TABLE `track_user_job_filters`
  ADD CONSTRAINT `track_user_job_filters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_awards`
--
ALTER TABLE `user_awards`
  ADD CONSTRAINT `awards_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_experiences`
--
ALTER TABLE `user_experiences`
  ADD CONSTRAINT `work_experience_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_experience_images`
--
ALTER TABLE `user_experience_images`
  ADD CONSTRAINT `work_experience_images_work_id_foreign` FOREIGN KEY (`work_id`) REFERENCES `user_experiences` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_qualifications`
--
ALTER TABLE `user_qualifications`
  ADD CONSTRAINT `qualification_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `skill_reg_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_reg_id_foreign` FOREIGN KEY (`reg_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD CONSTRAINT `user_verifications_reg_id_foreign` FOREIGN KEY (`reg_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
