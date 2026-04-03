-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 12:51 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u506206132_betaApp`
--

-- --------------------------------------------------------

--
-- Table structure for table `community_channels`
--

CREATE TABLE `community_channels` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `handle` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `status` varchar(225) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_channels`
--

INSERT INTO `community_channels` (`id`, `name`, `handle`, `description`, `icon`, `status`, `is_private`, `created_at`) VALUES
(4, 'puppy training', '#puppy-training', 'puppy training sai teja trainer', 'default_icon.png', '1', 0, '2026-03-24 12:22:26'),
(5, 'Adoption', '#adoption', 'Pet adoption offers a compassionate way to provide homes for animals in need. Organizations like the Blue Cross, recognized by the Animal Welfare Board of India since 1992, support ethical adoption practices. Adopting a pet often involves requirements such as indoor living, home visits, and regular check-ins to ensure the animal\'s well-being. For example, a two-year-old male poodle in Gurgaon or an indoor cat requiring video updates exemplify the personalized care and commitment involved. Adoption not only saves lives but also promotes responsible pet ownership.', '1774416763_b794c10918d712348356.jpg', '1', 0, '2026-03-25 05:31:20'),
(6, 'pet care', '#petCare', 'pet care and pet health tips', '1774618372_49756194035bb30aecc4.png', '1', 0, '2026-03-27 13:32:52'),
(7, 'Pet Training', '#petTraining', '', '1774689031_415cd1d5f82920db5d5d.jpeg', '1', 0, '2026-03-28 14:40:31'),
(8, 'HappiePets', '#petsServices', 'Petsfolio is an Indian online platform designed to simplify pet ownership by bringing together pet buying, adoption, and care services in one place. It connects pet lovers with breeders, sellers, and service providers, making it easier to find pets such as dogs, cats, and other small animals. The platform also offers a wide range of pet products, including food, toys, grooming items, and accessories, ensuring that pet owners can meet all their needs conveniently.', '1774871624_8325a0799e4cdb1600c1.jpg', '1', 1, '2026-03-30 17:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `community_channel_joins`
--

CREATE TABLE `community_channel_joins` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `channel_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_channel_joins`
--

INSERT INTO `community_channel_joins` (`id`, `user_id`, `channel_id`, `created_at`) VALUES
(24, 4, 4, '2026-03-26 17:26:51'),
(25, 4, 5, '2026-03-26 18:18:02'),
(33, 268, 4, '2026-03-27 12:40:18'),
(46, 267, 4, '2026-03-28 18:42:55'),
(47, 267, 7, '2026-03-28 18:54:18'),
(51, 270, 7, '2026-03-30 14:36:08'),
(52, 270, 4, '2026-03-30 14:36:57'),
(53, 270, 8, '2026-03-30 17:25:26'),
(54, 266, 5, '2026-03-31 13:28:28'),
(55, 272, 6, '2026-04-01 13:14:09'),
(57, 271, 4, '2026-04-01 15:37:11');

-- --------------------------------------------------------

--
-- Table structure for table `community_comment_dislikes`
--

CREATE TABLE `community_comment_dislikes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_comment_likes`
--

CREATE TABLE `community_comment_likes` (
  `id` bigint(20) NOT NULL,
  `comment_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_comment_likes`
--

INSERT INTO `community_comment_likes` (`id`, `comment_id`, `user_id`, `created_at`) VALUES
(0, 19, 142, '2026-03-25 17:50:31'),
(0, 25, 142, '2026-03-26 11:16:46'),
(0, 22, 142, '2026-03-26 11:27:13'),
(0, 34, 142, '2026-03-26 12:12:36'),
(0, 32, 142, '2026-03-26 12:12:38'),
(0, 21, 142, '2026-03-26 12:12:41'),
(0, 33, 142, '2026-03-26 15:17:02'),
(0, 48, 142, '2026-03-26 19:00:42'),
(0, 50, 4, '2026-03-27 10:37:52'),
(0, 61, 266, '2026-03-27 18:47:20'),
(0, 56, 266, '2026-03-27 18:47:27'),
(0, 59, 266, '2026-03-27 18:47:53'),
(0, 57, 266, '2026-03-27 18:47:54'),
(0, 80, 268, '2026-03-28 16:02:14'),
(0, 90, 268, '2026-03-28 18:53:21'),
(0, 102, 268, '2026-03-30 10:55:52'),
(0, 101, 268, '2026-03-30 10:55:54'),
(0, 98, 270, '2026-03-30 10:58:17'),
(0, 90, 270, '2026-03-30 11:57:40'),
(0, 96, 142, '2026-03-30 12:13:54'),
(0, 100, 142, '2026-03-30 12:15:19'),
(0, 113, 270, '2026-03-30 15:29:52'),
(0, 94, 270, '2026-03-30 17:58:15'),
(0, 100, 270, '2026-03-30 17:58:59'),
(0, 120, 268, '2026-03-30 18:01:58'),
(0, 121, 270, '2026-03-30 18:02:29');

-- --------------------------------------------------------

--
-- Table structure for table `community_notifications`
--

CREATE TABLE `community_notifications` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `actor_id` bigint(20) DEFAULT NULL,
  `type` enum('like','comment','follow','reply') DEFAULT NULL,
  `reference_id` bigint(20) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_notifications`
--

INSERT INTO `community_notifications` (`id`, `user_id`, `actor_id`, `type`, `reference_id`, `is_read`, `created_at`) VALUES
(1, 1, 142, 'reply', 16, 0, '2026-03-26 11:35:44'),
(2, 142, 4, 'comment', 16, 0, '2026-03-26 15:34:29'),
(3, 142, 4, 'like', 16, 0, '2026-03-26 15:34:34'),
(4, 4, 142, 'reply', 16, 0, '2026-03-26 15:46:03'),
(5, 2, 142, 'comment', 39, 0, '2026-03-26 16:48:27'),
(6, 2, 142, 'comment', 27, 0, '2026-03-26 16:48:44'),
(7, 2, 142, 'like', 39, 0, '2026-03-26 16:49:33'),
(8, 1, 142, 'like', 20, 0, '2026-03-26 17:20:14'),
(9, 1, 142, 'like', 20, 0, '2026-03-26 17:20:16'),
(10, 1, 142, 'like', 20, 0, '2026-03-26 17:20:17'),
(11, 2, 142, 'comment', 39, 0, '2026-03-26 17:25:37'),
(12, 2, 4, 'like', 39, 0, '2026-03-26 17:27:08'),
(13, 2, 4, 'like', 39, 0, '2026-03-26 17:27:46'),
(14, 2, 4, 'comment', 39, 0, '2026-03-26 17:28:26'),
(15, 4, 142, 'like', 40, 0, '2026-03-26 17:31:53'),
(16, 4, 142, 'comment', 40, 0, '2026-03-26 17:33:05'),
(17, 142, 4, 'reply', 40, 0, '2026-03-26 17:33:26'),
(18, 2, 142, 'like', 27, 0, '2026-03-26 17:47:06'),
(19, 2, 142, 'like', 25, 0, '2026-03-26 17:47:10'),
(20, 2, 142, 'like', 24, 0, '2026-03-26 17:47:12'),
(21, 2, 142, 'like', 23, 0, '2026-03-26 17:47:13'),
(22, 2, 142, 'like', 22, 0, '2026-03-26 17:47:15'),
(23, 4, 142, 'like', 21, 0, '2026-03-26 17:47:17'),
(24, 2, 142, 'like', 43, 0, '2026-03-26 18:04:16'),
(25, 2, 142, 'like', 43, 0, '2026-03-26 18:04:21'),
(26, 2, 142, 'like', 42, 0, '2026-03-26 18:55:27'),
(27, 2, 142, 'comment', 42, 0, '2026-03-26 19:00:39'),
(28, 2, 142, 'like', 42, 0, '2026-03-26 19:59:08'),
(29, 2, 142, 'like', 42, 0, '2026-03-27 10:23:19'),
(30, 2, 142, 'comment', 42, 0, '2026-03-27 10:24:16'),
(31, 2, 4, 'like', 42, 0, '2026-03-27 10:25:52'),
(32, 142, 4, 'like', 51, 0, '2026-03-27 10:32:59'),
(33, 142, 4, 'like', 51, 0, '2026-03-27 10:35:11'),
(34, 142, 4, 'comment', 51, 0, '2026-03-27 10:35:38'),
(35, 142, 4, 'comment', 51, 0, '2026-03-27 10:38:39'),
(36, 142, 4, 'reply', 51, 0, '2026-03-27 10:38:39'),
(37, 2, 142, 'like', 42, 0, '2026-03-27 11:47:30'),
(38, 2, 142, 'like', 42, 0, '2026-03-27 11:47:40'),
(39, 2, 142, 'like', 42, 0, '2026-03-27 11:47:44'),
(40, 2, 142, 'like', 42, 0, '2026-03-27 11:47:47'),
(41, 2, 142, 'like', 44, 0, '2026-03-27 11:49:15'),
(42, 2, 142, 'like', 44, 0, '2026-03-27 11:49:59'),
(43, 2, 142, 'like', 41, 0, '2026-03-27 11:51:19'),
(44, 2, 142, 'like', 39, 0, '2026-03-27 11:51:22'),
(45, 2, 142, 'comment', 42, 0, '2026-03-27 11:57:21'),
(46, 2, 142, 'comment', 42, 0, '2026-03-27 11:58:38'),
(47, 2, 142, 'like', 44, 0, '2026-03-27 12:25:01'),
(48, 2, 142, 'like', 41, 0, '2026-03-27 12:25:05'),
(49, 2, 142, 'like', 42, 0, '2026-03-27 12:29:45'),
(50, 2, 268, 'like', 42, 0, '2026-03-27 12:39:36'),
(51, 2, 142, 'like', 44, 0, '2026-03-27 14:09:45'),
(52, 2, 142, 'like', 23, 0, '2026-03-27 14:09:51'),
(53, 2, 266, 'comment', 42, 0, '2026-03-27 14:52:53'),
(54, 2, 266, 'like', 44, 0, '2026-03-27 14:53:22'),
(55, 266, 142, 'comment', 54, 0, '2026-03-27 17:45:01'),
(56, 2, 268, 'comment', 42, 0, '2026-03-27 17:45:36'),
(57, 2, 142, 'comment', 42, 0, '2026-03-27 17:47:30'),
(58, 268, 142, 'reply', 42, 0, '2026-03-27 17:47:30'),
(59, 266, 142, 'like', 54, 0, '2026-03-27 17:47:52'),
(60, 4, 142, 'like', 19, 0, '2026-03-27 17:47:56'),
(61, 2, 268, 'comment', 42, 0, '2026-03-27 17:48:27'),
(62, 142, 268, 'reply', 42, 0, '2026-03-27 17:48:27'),
(63, 2, 142, 'comment', 44, 0, '2026-03-27 18:47:05'),
(64, 2, 266, 'comment', 42, 0, '2026-03-27 18:47:59'),
(65, 2, 266, 'comment', 42, 0, '2026-03-27 18:48:34'),
(66, 2, 266, 'comment', 42, 0, '2026-03-27 18:48:52'),
(67, 2, 266, 'comment', 42, 0, '2026-03-28 13:19:16'),
(68, 268, 266, 'reply', 42, 0, '2026-03-28 13:19:16'),
(69, 2, 268, 'comment', 42, 0, '2026-03-28 13:19:47'),
(70, 1, 268, 'reply', 42, 0, '2026-03-28 13:19:47'),
(71, 2, 268, 'comment', 42, 0, '2026-03-28 13:29:02'),
(72, 2, 268, 'reply', 42, 0, '2026-03-28 13:29:02'),
(73, 2, 268, 'comment', 44, 0, '2026-03-28 16:02:00'),
(74, 2, 268, 'comment', 44, 0, '2026-03-28 16:02:07'),
(75, 2, 268, 'like', 39, 0, '2026-03-28 16:02:28'),
(76, 2, 266, 'comment', 39, 0, '2026-03-28 17:38:37'),
(77, 267, 268, 'like', 63, 0, '2026-03-28 17:46:21'),
(78, 2, 267, 'like', 41, 0, '2026-03-28 17:47:27'),
(79, 266, 267, 'like', 54, 0, '2026-03-28 17:50:44'),
(80, 267, 142, 'like', 63, 0, '2026-03-28 18:03:43'),
(81, 2, 267, 'comment', 39, 0, '2026-03-28 18:05:01'),
(82, 2, 142, 'comment', 41, 0, '2026-03-28 18:12:42'),
(83, 267, 268, 'like', 68, 0, '2026-03-28 18:47:49'),
(84, 267, 268, 'like', 68, 0, '2026-03-28 18:48:08'),
(85, 267, 268, 'like', 68, 0, '2026-03-28 18:48:16'),
(86, 267, 268, 'like', 68, 0, '2026-03-28 18:48:25'),
(87, 30, 268, 'comment', 59, 0, '2026-03-28 18:49:03'),
(88, 30, 267, 'like', 59, 0, '2026-03-28 18:52:22'),
(89, 30, 267, 'comment', 59, 0, '2026-03-28 18:52:38'),
(90, 268, 267, 'reply', 59, 0, '2026-03-28 18:52:38'),
(91, 30, 267, 'comment', 59, 0, '2026-03-28 18:52:52'),
(92, 268, 267, 'reply', 59, 0, '2026-03-28 18:52:52'),
(93, 30, 268, 'comment', 59, 0, '2026-03-28 18:53:40'),
(94, 267, 268, 'reply', 59, 0, '2026-03-28 18:53:40'),
(95, 2, 270, 'like', 41, 0, '2026-03-30 10:35:09'),
(96, 267, 270, 'like', 68, 0, '2026-03-30 10:35:17'),
(97, 267, 270, 'comment', 68, 0, '2026-03-30 10:35:29'),
(98, 267, 270, 'comment', 68, 0, '2026-03-30 10:52:02'),
(99, 30, 268, 'comment', 59, 0, '2026-03-30 10:52:48'),
(100, 267, 270, 'comment', 68, 0, '2026-03-30 10:53:12'),
(101, 2, 268, 'like', 39, 0, '2026-03-30 10:55:01'),
(102, 2, 268, 'like', 23, 0, '2026-03-30 10:55:05'),
(103, 2, 268, 'like', 24, 0, '2026-03-30 10:55:12'),
(104, 4, 268, 'like', 21, 0, '2026-03-30 10:55:15'),
(105, 4, 268, 'comment', 21, 0, '2026-03-30 10:55:39'),
(106, 4, 268, 'comment', 21, 0, '2026-03-30 10:55:48'),
(107, 267, 270, 'like', 66, 0, '2026-03-30 15:03:49'),
(108, 267, 268, 'like', 68, 0, '2026-03-30 18:00:31'),
(109, 267, 268, 'comment', 68, 0, '2026-03-30 18:00:42'),
(110, 267, 268, 'comment', 68, 0, '2026-03-30 18:00:54'),
(111, 268, 270, 'like', 82, 0, '2026-03-30 18:01:36'),
(112, 268, 270, 'comment', 82, 0, '2026-03-30 18:01:43'),
(113, 270, 268, 'reply', 82, 0, '2026-03-30 18:02:08'),
(114, 268, 270, 'comment', 82, 0, '2026-03-30 18:02:37'),
(115, 268, 270, 'reply', 82, 0, '2026-03-30 18:02:37'),
(116, 30, 270, 'like', 85, 0, '2026-03-30 18:18:36'),
(117, 30, 270, 'like', 92, 0, '2026-03-30 18:31:40'),
(118, 2, 271, 'like', 41, 0, '2026-04-01 14:42:43'),
(119, 267, 271, 'like', 68, 0, '2026-04-01 15:20:31');

-- --------------------------------------------------------

--
-- Table structure for table `community_posts`
--

CREATE TABLE `community_posts` (
  `id` bigint(20) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `post_text` text DEFAULT NULL,
  `post_type` enum('text','image','video','question') DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `comments_count` int(11) DEFAULT 0,
  `shares_count` int(11) DEFAULT 0,
  `visibility` enum('public','followers') DEFAULT NULL,
  `is_pinned` tinyint(1) DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `status` enum('active','deleted','blocked','scheduled') DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_posts`
--

INSERT INTO `community_posts` (`id`, `channel_id`, `user_id`, `user_type`, `post_text`, `post_type`, `media_url`, `location`, `likes_count`, `comments_count`, `shares_count`, `visibility`, `is_pinned`, `scheduled_time`, `status`, `created_at`, `updated_at`) VALUES
(16, 4, 142, 'user', 'Hii\nNamaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste ', 'image', NULL, 'Telangana', 1, 2, 0, NULL, 0, NULL, 'deleted', '2026-03-25 16:46:56', '2026-03-26 12:56:31'),
(17, 5, 142, 'user', 'Hi\nI was just kidding 😂 😅 🙃 I have to go to the office and then you will have received it will work on the progress of you for your reply 😀 👍 I have to go to sleep with ', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-25 18:07:56', '2026-03-26 15:31:21'),
(18, 5, 142, 'user', 'Hiii\nI was just thinking 🤔 I have not having a great time in my time 😕 😒 🙃 I am in a while ago and let you ', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-25 18:08:57', '2026-03-26 12:52:29'),
(19, 5, 4, 'user', 'Hii\nNamaste 🙏 👋 🙏 I have not been implemented namaste I have not been implemented namaste namaste I have not been implemented namaste namaste I have not been \n\n\n#saiiii', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'active', '2026-03-26 15:35:18', '2026-03-27 17:47:56'),
(20, 5, 1, 'admin', '🐾 Meet Bruno – Your Future Best Friend!\r\n\r\nBruno is a 2-year-old Labrador with a heart full of love and a tail that never stops wagging! 🐕\r\nHe was rescued and is now looking for a forever home where he can shower someone with unconditional love.\r\n\r\n✨ Friendly & playful\r\n✨ Vaccinated & healthy\r\n✨ Loves kids & other pets\r\n\r\n🏡 Give Bruno the home he deserves.\r\n📍 Location: HYD\r\n📞 Contact: 7894661230\r\n\r\n👉 Adopt, don’t shop. Change a life today!', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'active', '2026-03-26 10:05:21', '2026-03-26 12:59:22'),
(21, 4, 4, 'user', 'Nice looking forward for the update 😀 I have not been able namaste to discuss with you for your wishes 😀 I have not been able namaste to discuss with him and he was in a bit of the year ', 'image', NULL, 'Telangana', 2, 2, 0, NULL, 0, NULL, 'active', '2026-03-26 15:37:39', '2026-03-30 10:55:48'),
(22, 5, 2, 'admin', '🐾 Adopt Me!\r\n\r\nHi, I’m Luna 🐱\r\nI’m cute, cuddly, and waiting just for YOU 💖\r\n\r\n✔ Vaccinated\r\n✔ Litter trained\r\n✔ Super friendly\r\n\r\n📍 Basheerbagh,Hyderabad\r\n📲 Tap to adopt now!', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'active', '2026-03-26 10:12:13', '2026-03-26 17:47:15'),
(23, 4, 2, 'admin', '🏠 1. Potty Training (First Priority)\r\n\r\nGoal: Teach where to pee/poop 🚽\r\n\r\nSteps:\r\n\r\nTake puppy outside:\r\nAfter waking up\r\nAfter eating\r\nEvery 2–3 hours\r\nUse a fixed command like “Go potty”\r\nReward immediately after success (treat + praise)\r\n\r\n❌ Avoid punishment\r\n✅ Consistency = success', 'text', NULL, NULL, 2, 0, 0, 'public', 0, NULL, 'active', '2026-03-26 10:20:08', '2026-03-30 10:55:05'),
(24, 4, 2, 'admin', '🦴 3. Basic Commands (Start Early)\r\n\r\nTrain daily (5–10 mins sessions)\r\n\r\nEssential commands:\r\n\r\nSit\r\nStay\r\nCome\r\nNo\r\n\r\nMethod:\r\n\r\nUse treat\r\nSay command\r\nGuide action\r\nReward immediately\r\n\r\n💡 Puppies learn fast with positive reinforcement', 'text', NULL, NULL, 2, 0, 0, 'public', 0, NULL, 'active', '2026-03-26 10:21:57', '2026-03-30 10:55:12'),
(25, 4, 2, 'admin', 'Steps:\r\n\r\nTake puppy outside:\r\nAfter waking up\r\nAfter eating\r\nEvery 2–3 hours\r\nUse a fixed command like “Go potty”\r\nReward immediately after success (treat + praise)\r\n\r\n❌ Avoid punishment\r\n✅ Consistency = success', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-26 10:22:49', '2026-03-26 12:56:31'),
(26, 4, 2, 'admin', 'dsdsd', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-26 10:22:57', '2026-03-26 12:56:31'),
(27, 4, 2, 'admin', '2. Crate Training\r\n\r\nGoal: Safe space + discipline\r\n\r\nHow:\r\n\r\nMake crate comfortable (bed + toy)\r\nDon’t use crate as punishment\r\nStart with short durations\r\nGradually increase time\r\n\r\n👉 Helps in:\r\n\r\nPotty training\r\nReducing anxiety\r\nPreventing destruction', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'active', '2026-03-26 10:23:31', '2026-03-26 18:35:50'),
(38, 4, 2, 'admin', '3. Basic Commands (Start Early)\r\n\r\nTrain daily (5–10 mins sessions)\r\n\r\nEssential commands:\r\n\r\nSit\r\nStay\r\nCome\r\nNo\r\n\r\nMethod:\r\n\r\nUse treat\r\nSay command\r\nGuide action\r\nReward immediately\r\n\r\n💡 Puppies learn fast with positive reinforcement', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-26 10:46:44', '2026-03-26 12:56:31'),
(39, 4, 2, 'admin', '🐕‍🦺 4. Leash Training\r\n\r\nGoal: No pulling walks 🚶‍♂️\r\n\r\nSteps:\r\n\r\nStart indoors\r\nLet puppy get used to leash\r\nWalk slowly\r\nReward when walking beside you\r\n\r\n❌ Don’t pull leash harshly\r\n✅ Stop walking if puppy pulls\r\n.....', 'text', NULL, NULL, 3, 2, 0, 'public', 0, NULL, 'active', '2026-03-26 10:49:12', '2026-03-30 10:55:01'),
(40, 4, 4, 'user', 'Hello guys , welcome to petsfolio community ', 'text', NULL, 'Telangana', 1, 1, 0, NULL, 0, NULL, 'active', '2026-03-26 17:29:10', '2026-03-26 12:56:31'),
(41, 4, 2, 'admin', '👥 5. Socialization (VERY IMPORTANT)\r\n\r\nExpose puppy to:\r\n\r\nPeople\r\nOther dogs\r\nSounds (traffic, doorbell)\r\n\r\n👉 Prevents fear & aggression later', 'text', NULL, NULL, 4, 1, 0, 'public', 0, NULL, 'active', '2026-03-26 12:30:01', '2026-04-01 14:42:43'),
(42, 4, 2, 'admin', '👥 5. Socialization (VERY IMPORTANT)\r\n\r\nExpose puppy to:\r\n\r\nPeople\r\nOther dogs\r\nSounds (traffic, doorbell)\r\n\r\n👉 Prevents fear & aggression later', 'text', NULL, NULL, 2, 8, 0, 'public', 0, NULL, 'blocked', '2026-03-26 12:30:57', '2026-03-30 12:08:49'),
(43, 4, 2, 'admin', '🍖 6. Bite Training (Stop Nipping)\r\n\r\nPuppies bite a lot 😅\r\n\r\nFix:\r\n\r\nSay “NO” firmly\r\nGive chew toy instead\r\nStop playing if biting continues', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-26 12:34:00', '2026-03-26 12:56:31'),
(44, 4, 2, 'admin', '🍖 6. Bite Training (Stop Nipping)\r\n\r\nPuppies bite a lot 😅\r\n\r\nFix:\r\n\r\nSay “NO” firmly\r\nGive chew toy instead\r\nStop playing if biting continues', 'text', NULL, NULL, 2, 1, 0, 'public', 0, NULL, 'blocked', '2026-03-26 12:36:00', '2026-03-28 17:25:37'),
(45, 4, 142, 'user', 'Hiii', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-26 18:55:52', '2026-03-26 18:56:17'),
(46, 4, 142, 'user', 'Hiiiii\n\n\n\n\n', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-26 19:27:50', '2026-03-26 19:28:43'),
(47, 5, 4, 'user', 'Hisabahsjsj', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 10:26:59', '2026-03-27 10:31:52'),
(48, 4, 4, 'user', 'Ndjsjs', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 10:27:44', '2026-03-27 10:31:50'),
(49, 4, 4, 'user', 'Hshsha', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 10:28:11', '2026-03-27 10:31:48'),
(50, 5, 4, 'user', 'Jsjsjajanz', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 10:30:34', '2026-03-27 10:31:46'),
(51, 4, 142, 'user', 'Hiiii 👋☺️☺️👋👋 na ho ra h hvyvtvycrctvubyvhbtxtvubink k liye kam hua hai voo ki koi kaam hai', 'image', NULL, 'Telangana', 1, 2, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 10:32:36', '2026-03-27 08:52:46'),
(52, 4, 142, 'user', 'Hiiii aruhvhvhvh h na to do anything for you to be happy to help you to be a part 😊 of the day of the day of my birthday 🎈 God bless you with lots and lots of happiness and peace ✌️', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 11:29:11', '2026-03-27 08:52:40'),
(53, 4, 142, 'user', 'Hhuiiiuytrfghj na ho bhai isliye nahi he na aaj I am nothing without new scope of the day I will be there in my room now and will be there in the evening of the day of the day of the day of the day of the day of ', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-27 11:40:48', '2026-03-27 08:52:33'),
(54, 5, 266, 'user', 'Post to check for testing', 'image', NULL, 'Telangana', 3, 0, 0, NULL, NULL, NULL, 'blocked', '2026-03-27 17:22:54', '2026-04-02 18:36:57'),
(55, 5, 142, 'user', 'Hii\nHhhu namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste namaste ', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'blocked', '2026-03-27 18:41:57', '2026-04-01 11:43:31'),
(56, 7, 267, 'user', '', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 14:45:41', '2026-03-28 17:45:23'),
(57, 4, 2, 'admin', 'User → Message\r\n     ↓\r\nEmbedding → Search memory\r\n     ↓\r\nFetch relevant facts\r\n     ↓\r\nCombine with recent chat\r\n     ↓\r\nSend to Phi-3\r\n     ↓\r\nStore new memory', 'text', NULL, NULL, 0, 0, 0, 'public', 0, '2026-03-29 10:00:00', 'scheduled', '2026-03-28 15:13:37', '2026-03-28 15:13:37'),
(58, 4, 268, 'user', 'Uhshshns', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'active', '2026-03-28 16:03:03', '2026-03-28 16:03:03'),
(59, 4, 30, 'admin', 'Pet Training', 'text', NULL, NULL, 1, 4, 0, 'public', 0, NULL, 'deleted', '2026-03-28 17:01:40', '2026-03-30 15:18:59'),
(60, 7, 267, 'user', 'Pet is my daily source of joy', 'image', NULL, 'Telangana', 0, 1, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 17:25:37', '2026-03-28 17:45:20'),
(61, 4, 266, 'user', 'My pet is a good pet \nIt listens to me ', 'text', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'active', '2026-03-28 17:34:47', '2026-03-31 13:28:19'),
(62, 4, 267, 'user', 'Hii', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 17:44:58', '2026-03-28 17:48:49'),
(63, 4, 267, 'user', 'Hiii', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 17:45:49', '2026-03-28 18:47:03'),
(64, 7, 267, 'user', 'How is my pet', 'image', NULL, 'Telangana', 0, 1, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 17:57:42', '2026-03-28 18:10:26'),
(65, 7, 267, 'user', 'How is my pet', 'image', NULL, 'Telangana', 1, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-28 18:11:30', '2026-03-28 18:47:01'),
(66, 7, 267, 'user', '', 'image', NULL, 'Telangana', 2, 0, 0, NULL, NULL, NULL, 'active', '2026-03-28 18:13:41', '2026-03-30 15:03:49'),
(67, 7, 267, 'user', 'Hi', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-28 18:23:52', '2026-03-28 18:46:57'),
(68, 4, 267, 'user', 'Hiii', 'image', NULL, 'Telangana', 3, 14, 0, NULL, 1, NULL, 'deleted', '2026-03-28 18:47:23', '2026-04-02 19:02:59'),
(69, 7, 270, 'user', 'Hi All Welcome to Petsfolio community.', 'text', NULL, 'Telangana', 1, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 11:19:20', '2026-03-30 18:09:57'),
(70, 4, 270, 'user', 'Hlooo', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 12:04:47', '2026-03-30 14:36:49'),
(71, 7, 270, 'user', 'Hi', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 14:36:13', '2026-03-30 14:36:44'),
(72, 7, 270, 'user', '', 'image', NULL, 'Telangana', 1, 5, 0, NULL, NULL, NULL, 'active', '2026-03-30 14:59:45', '2026-03-30 17:36:12'),
(73, 4, 2, 'admin', 'dfdfd', 'text', NULL, NULL, 0, 0, 0, 'public', 0, '2026-03-31 12:00:00', 'scheduled', '2026-03-30 15:05:58', '2026-03-30 15:05:58'),
(74, 7, 270, 'user', 'Hi', 'text', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 15:24:29', '2026-03-30 15:25:27'),
(75, 7, 270, 'user', 'Hlo', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 15:26:24', '2026-03-30 15:26:42'),
(76, 7, 270, 'user', '', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'active', '2026-03-30 15:31:14', '2026-03-30 15:31:14'),
(77, 4, 30, 'admin', 'Hlo Welcome To Petsfolio Community. Testing future Drop', 'text', NULL, NULL, 0, 0, 0, 'public', 0, '2026-03-30 16:10:00', 'scheduled', '2026-03-30 16:07:02', '2026-03-30 16:07:02'),
(78, 7, 30, 'admin', 'Future Drop 04.15', 'text', NULL, NULL, 0, 0, 0, 'public', 0, '2026-03-30 16:15:00', 'scheduled', '2026-03-30 16:13:17', '2026-03-30 16:13:17'),
(79, 8, 270, 'user', 'Test', 'image', NULL, 'Telangana', 1, 1, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 17:37:25', '2026-03-30 18:08:59'),
(80, 8, 270, 'user', 'Testing ', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 17:41:43', '2026-03-30 17:41:53'),
(81, 8, 270, 'user', 'Pet services available ', 'image', NULL, 'Telangana', 1, 0, 0, NULL, NULL, NULL, 'active', '2026-03-30 17:51:08', '2026-03-30 17:51:20'),
(82, 4, 268, 'user', 'Njnj', 'text', NULL, 'Telangana', 1, 3, 0, NULL, NULL, NULL, 'active', '2026-03-30 18:01:10', '2026-03-30 18:02:37'),
(83, 4, 268, 'user', '', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 18:03:10', '2026-03-30 18:03:35'),
(84, 8, 270, 'user', 'Hil', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'deleted', '2026-03-30 18:03:33', '2026-03-30 18:09:26'),
(85, 8, 30, 'admin', 'PETS SERVICES AVAILABLE', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:17:05', '2026-03-30 18:29:44'),
(86, 8, 30, 'admin', 'All pets Services Available', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:19:50', '2026-03-30 18:29:39'),
(87, 4, 2, 'admin', 'dfdfd', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:22:27', '2026-03-30 18:22:43'),
(88, 4, 2, 'admin', 'fcfgbv fgfgffgfg', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:22:57', '2026-03-30 18:23:26'),
(89, 4, 2, 'admin', 'fvgjhnl./;', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:25:28', '2026-03-30 18:29:08'),
(90, 4, 2, 'admin', 'fvgjhnl./;', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:28:36', '2026-03-30 18:28:42'),
(91, 4, 2, 'admin', 'adsfggffgffgfg', 'text', NULL, NULL, 0, 0, 0, 'public', 0, NULL, 'deleted', '2026-03-30 18:28:54', '2026-03-30 18:29:05'),
(92, 8, 30, 'admin', 'ALL PETS SERVICES AVAILABLE', 'text', NULL, NULL, 1, 0, 0, 'public', 0, NULL, 'active', '2026-03-30 18:30:52', '2026-03-30 18:33:21'),
(93, 6, 272, 'user', '\n1. Fun & Fascinating Dog Facts \n\nUnique Nose Prints: Just like human fingerprints, no two dogs have the same nose print. They are entirely unique.\n\nSuperhuman Smell: Dogs possess up to 300 million olfactory receptors in their noses, compared to only 6 million in humans, making their sense of smell at least 40x better than ours.\n\nLeft or Right Pawed: Dogs can be right or left-pawed, just like humans, according to studies where they performed food retrieval tasks.\n\nThey Can “See” Time: Dogs have an internal clock and a powerful sense of smell that helps them gauge time, which is why they often know when you are coming home.\n\nCheesy Paws: If your dog\'s paws smell like corn chips, it’s because of the bacteria buildup from sweating through their paw pads (the only place they ', 'image', NULL, 'Telangana', 0, 0, 0, NULL, NULL, NULL, 'active', '2026-04-01 13:14:14', '2026-04-01 13:14:14'),
(94, 4, 271, 'user', 'Hellooo', 'image', NULL, 'California', 1, 1, 0, NULL, NULL, NULL, 'active', '2026-04-01 15:37:16', '2026-04-01 15:38:25'),
(95, 4, 271, 'user', 'Heyyyy', 'image', NULL, 'California', 0, 0, 0, NULL, NULL, NULL, 'active', '2026-04-01 15:39:17', '2026-04-01 15:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_channels`
--

CREATE TABLE `community_post_channels` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `channel_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_channels`
--

INSERT INTO `community_post_channels` (`id`, `post_id`, `channel_id`) VALUES
(1, 5, 4),
(2, 6, 4),
(3, 7, 4),
(4, 8, 4),
(5, 9, 4),
(6, 10, 5),
(7, 11, 4),
(8, 12, 4),
(9, 13, 4),
(10, 14, 4),
(11, 15, 4),
(12, 16, 4),
(13, 17, 5),
(14, 18, 5),
(15, 19, 5),
(16, 21, 4),
(17, 40, 4),
(18, 45, 4),
(19, 46, 4),
(20, 47, 5),
(21, 48, 4),
(22, 49, 4),
(23, 50, 5),
(24, 51, 4),
(25, 52, 4),
(26, 53, 4),
(27, 54, 5),
(28, 55, 5),
(29, 56, 7),
(30, 58, 4),
(31, 60, 7),
(32, 61, 4),
(33, 62, 4),
(34, 63, 4),
(35, 64, 7),
(36, 65, 7),
(37, 66, 7),
(38, 67, 7),
(39, 68, 4),
(40, 69, 7),
(41, 70, 4),
(42, 71, 7),
(43, 72, 7),
(44, 74, 7),
(45, 75, 7),
(46, 76, 7),
(47, 79, 8),
(48, 80, 8),
(49, 81, 8),
(50, 82, 4),
(51, 83, 4),
(52, 84, 8),
(53, 93, 6),
(54, 94, 4),
(55, 95, 4);

-- --------------------------------------------------------

--
-- Table structure for table `community_post_comments`
--

CREATE TABLE `community_post_comments` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `parent_comment_id` bigint(20) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_comments`
--

INSERT INTO `community_post_comments` (`id`, `post_id`, `user_id`, `user_type`, `parent_comment_id`, `comment`, `likes_count`, `created_at`) VALUES
(21, 16, 1, 'user', 19, 'dfd', 1, '2026-03-26 05:06:34'),
(40, 16, 4, 'user', NULL, 'Hii saiteja', 0, '2026-03-26 15:34:29'),
(47, 40, 4, 'user', 46, 'Hello', 0, '2026-03-26 17:33:26'),
(50, 51, 4, 'user', NULL, 'Jai shree Ram ', 1, '2026-03-27 10:35:38'),
(52, 51, 4, 'user', 51, 'Hii', 0, '2026-03-27 10:38:39'),
(57, 42, 1, 'admin', 56, 'hiii', 1, '2026-03-27 12:06:20'),
(59, 42, 268, 'user', NULL, 'Hii', 1, '2026-03-27 17:45:36'),
(61, 42, 268, 'user', 60, 'How r u', 1, '2026-03-27 17:48:27'),
(64, 42, 266, 'user', NULL, 'Hi', 0, '2026-03-27 18:47:59'),
(65, 42, 266, 'user', NULL, '@vijay hai', 0, '2026-03-27 18:48:34'),
(66, 42, 266, 'user', NULL, '@petsfolio hi', 0, '2026-03-27 18:48:52'),
(67, 42, 1, NULL, 64, 'hello', 0, '2026-03-28 07:47:05'),
(68, 42, 266, 'user', 61, '😃😀😀😀😀😀😀', 0, '2026-03-28 13:19:16'),
(69, 42, 268, 'user', 57, 'Hiii', 0, '2026-03-28 13:19:47'),
(70, 42, 1, 'admin', 59, 'hiii', 0, '2026-03-28 07:54:16'),
(71, 42, 1, 'admin', 66, 'default dns of provider', 0, '2026-03-28 07:55:08'),
(72, 42, 1, 'admin', 66, 'testing vijay', 0, '2026-03-28 07:56:29'),
(73, 42, 2, 'admin', 66, 'testing vijay2', 0, '2026-03-28 07:57:18'),
(74, 42, 268, 'user', 73, 'Hi', 0, '2026-03-28 13:29:02'),
(75, 42, 2, 'admin', 66, 'hiii', 0, '2026-03-28 08:00:12'),
(76, 42, 2, 'admin', 66, 'hiii', 0, '2026-03-28 08:01:18'),
(77, 42, 2, 'admin', 66, 'default dns of provider', 0, '2026-03-28 08:04:57'),
(78, 42, 2, 'admin', 66, 'default dns of provider', 0, '2026-03-28 08:07:07'),
(79, 42, 2, 'admin', 66, 'hiii', 0, '2026-03-28 13:38:51'),
(81, 44, 268, 'user', 80, 'Hsjs', 0, '2026-03-28 16:02:07'),
(82, 60, 267, 'user', NULL, 'Pic not showing', 0, '2026-03-28 17:32:26'),
(83, 60, 30, 'admin', 82, 'I Can Visiable', 0, '2026-03-28 17:38:35'),
(84, 39, 266, 'user', NULL, 'Hello', 0, '2026-03-28 17:38:37'),
(85, 39, 267, 'user', NULL, 'Hloo', 0, '2026-03-28 18:05:01'),
(86, 39, 26, 'admin', 85, 'hiii', 0, '2026-03-28 18:05:29'),
(87, 64, 267, 'user', NULL, 'Nice', 0, '2026-03-28 18:08:52'),
(88, 64, 30, 'admin', 87, 'tq', 0, '2026-03-28 18:09:11'),
(89, 41, 142, 'user', NULL, 'Hii', 0, '2026-03-28 18:12:42'),
(90, 59, 268, 'user', NULL, 'Hi abhilash', 2, '2026-03-28 18:49:03'),
(91, 59, 267, 'user', 90, 'Hlo', 0, '2026-03-28 18:52:38'),
(93, 59, 268, 'user', 92, 'Hi', 0, '2026-03-28 18:53:40'),
(94, 68, 270, 'user', NULL, 'Nice', 1, '2026-03-30 10:35:29'),
(96, 68, 30, 'admin', 94, 'Thank you', 1, '2026-03-30 10:38:09'),
(97, 68, 30, 'admin', 94, 'tq', 0, '2026-03-30 10:39:13'),
(98, 68, 270, 'user', NULL, 'Hlo', 1, '2026-03-30 10:52:02'),
(99, 59, 268, 'user', 90, 'Hi', 0, '2026-03-30 10:52:48'),
(100, 68, 270, 'user', NULL, 'Hi', 2, '2026-03-30 10:53:12'),
(101, 21, 268, 'user', NULL, 'Heyyyyyyyy', 1, '2026-03-30 10:55:39'),
(102, 21, 268, 'user', 101, 'Hyu', 1, '2026-03-30 10:55:48'),
(103, 68, 30, 'admin', 98, 'HI', 0, '2026-03-30 12:54:42'),
(104, 68, 2, 'admin', 94, 'hiii', 0, '2026-03-30 12:57:32'),
(105, 68, 2, 'admin', 98, 'hiii', 0, '2026-03-30 13:06:12'),
(106, 68, 30, 'admin', 100, 'HI', 0, '2026-03-30 13:16:20'),
(107, 68, 30, 'admin', 100, 'Thank you', 0, '2026-03-30 14:54:37'),
(108, 72, 270, 'user', NULL, 'Hi', 0, '2026-03-30 15:00:16'),
(109, 72, 270, 'user', NULL, 'Image not visible ', 0, '2026-03-30 15:00:26'),
(110, 72, 30, 'admin', 109, 'I Can Visiable', 0, '2026-03-30 15:01:05'),
(111, 68, 2, 'admin', 100, 'hiii', 0, '2026-03-30 15:26:27'),
(112, 68, 30, 'admin', 98, 'HI', 0, '2026-03-30 15:28:03'),
(113, 68, 30, 'admin', 100, 'justnow', 1, '2026-03-30 15:29:16'),
(114, 68, 2, 'admin', 98, 'testing vijay', 0, '2026-03-30 15:33:16'),
(115, 72, 2, 'admin', 109, 'acha', 0, '2026-03-30 15:36:21'),
(116, 72, 2, 'admin', 109, 'abhilash', 0, '2026-03-30 15:37:21'),
(117, 79, 270, 'user', NULL, 'Hlo', 0, '2026-03-30 17:44:52'),
(118, 68, 268, 'user', NULL, 'Hi', 0, '2026-03-30 18:00:42'),
(119, 68, 268, 'user', NULL, 'Hjh', 0, '2026-03-30 18:00:54'),
(120, 82, 270, 'user', NULL, 'Hlo ', 1, '2026-03-30 18:01:43'),
(121, 82, 268, 'user', 120, 'Jhhj', 1, '2026-03-30 18:02:08'),
(122, 82, 270, 'user', 121, 'Fdre', 0, '2026-03-30 18:02:37'),
(123, 94, 271, 'user', NULL, 'Hey', 0, '2026-04-01 15:38:25');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_dislikes`
--

CREATE TABLE `community_post_dislikes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_likes`
--

CREATE TABLE `community_post_likes` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_likes`
--

INSERT INTO `community_post_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(9, 18, 142, '2026-03-26 12:41:04'),
(20, 17, 142, '2026-03-26 15:29:34'),
(21, 16, 4, '2026-03-26 15:34:34'),
(27, 39, 4, '2026-03-26 17:27:46'),
(28, 40, 142, '2026-03-26 17:31:53'),
(29, 27, 142, '2026-03-26 17:47:06'),
(30, 25, 142, '2026-03-26 17:47:10'),
(31, 24, 142, '2026-03-26 17:47:12'),
(33, 22, 142, '2026-03-26 17:47:15'),
(34, 21, 142, '2026-03-26 17:47:17'),
(36, 43, 142, '2026-03-26 18:04:21'),
(40, 42, 4, '2026-03-27 10:25:52'),
(42, 51, 4, '2026-03-27 10:35:11'),
(50, 39, 142, '2026-03-27 11:51:22'),
(52, 41, 142, '2026-03-27 12:25:05'),
(54, 53, 142, '2026-03-27 12:25:25'),
(55, 42, 142, '2026-03-27 12:29:45'),
(56, 52, 142, '2026-03-27 12:30:17'),
(58, 44, 142, '2026-03-27 14:09:45'),
(59, 23, 142, '2026-03-27 14:09:51'),
(60, 44, 266, '2026-03-27 14:53:22'),
(61, 54, 142, '2026-03-27 17:47:52'),
(62, 19, 142, '2026-03-27 17:47:56'),
(63, 56, 267, '2026-03-28 14:53:33'),
(66, 41, 267, '2026-03-28 17:47:27'),
(67, 54, 267, '2026-03-28 17:50:44'),
(69, 63, 142, '2026-03-28 18:03:43'),
(70, 65, 267, '2026-03-28 18:12:00'),
(71, 66, 267, '2026-03-28 18:15:03'),
(76, 59, 267, '2026-03-28 18:52:22'),
(77, 41, 270, '2026-03-30 10:35:09'),
(78, 68, 270, '2026-03-30 10:35:17'),
(79, 39, 268, '2026-03-30 10:55:01'),
(80, 23, 268, '2026-03-30 10:55:05'),
(81, 24, 268, '2026-03-30 10:55:12'),
(82, 21, 268, '2026-03-30 10:55:15'),
(83, 72, 270, '2026-03-30 15:00:09'),
(84, 66, 270, '2026-03-30 15:03:49'),
(85, 79, 270, '2026-03-30 17:37:53'),
(86, 81, 270, '2026-03-30 17:51:20'),
(87, 69, 270, '2026-03-30 17:56:49'),
(88, 68, 268, '2026-03-30 18:00:31'),
(89, 82, 270, '2026-03-30 18:01:36'),
(90, 85, 270, '2026-03-30 18:18:36'),
(91, 92, 270, '2026-03-30 18:31:40'),
(92, 61, 266, '2026-03-31 13:28:19'),
(93, 54, 266, '2026-03-31 13:28:24'),
(94, 55, 142, '2026-04-01 11:43:31'),
(95, 41, 271, '2026-04-01 14:42:43'),
(96, 68, 271, '2026-04-01 15:20:31'),
(97, 94, 271, '2026-04-01 15:38:18');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_media`
--

CREATE TABLE `community_post_media` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `media_type` enum('image','video') DEFAULT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_media`
--

INSERT INTO `community_post_media` (`id`, `post_id`, `media_type`, `media_url`, `created_at`) VALUES
(1, 7, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774435808_30518645d715ac45bcc8.jpg', '2026-03-25 16:20:08'),
(2, 8, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774436130_599de6ba3cc9202960af.jpg', '2026-03-25 16:25:30'),
(3, 9, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774436187_be5e25e1edb7d7ca4655.jpg', '2026-03-25 16:26:27'),
(4, 10, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774436358_f3f3d0261a80a538e74f.jpg', '2026-03-25 16:29:18'),
(5, 11, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774436726_9da03ea6fd213243cb06.jpg', '2026-03-25 16:35:26'),
(6, 12, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774436899_c1c37134e734c1226117.mp4', '2026-03-25 16:38:19'),
(7, 15, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437334_144f865a90278ede32ec.jpg', '2026-03-25 16:45:34'),
(8, 15, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437334_9e0786b99fe3af328a8a.png', '2026-03-25 16:45:34'),
(9, 15, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437334_74aa0939b7b5b491dbd2.png', '2026-03-25 16:45:34'),
(10, 15, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437334_d9fd6f2ed1fc6651bf99.mp4', '2026-03-25 16:45:34'),
(11, 16, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437416_8e1d3a9fbc5c354b94fd.jpg', '2026-03-25 16:46:56'),
(12, 16, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437416_3b8cfa492b5dd2e7f3f5.png', '2026-03-25 16:46:56'),
(13, 16, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437416_b6d1c350e40e58bbad79.jpg', '2026-03-25 16:46:56'),
(14, 16, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774437416_8502a33e94fa9177e7a0.mp4', '2026-03-25 16:46:56'),
(15, 17, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442276_ae21bd5ecfc43915efed.jpg', '2026-03-25 18:07:56'),
(16, 17, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442276_efddfc1180a7ed8f4ab5.png', '2026-03-25 18:07:56'),
(17, 17, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442276_1b89a623ee039b3744ee.jpg', '2026-03-25 18:07:56'),
(18, 17, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442276_bbce0f4290501d09fc93.mp4', '2026-03-25 18:07:56'),
(19, 18, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442337_58ae99885176fe38985d.jpg', '2026-03-25 18:08:57'),
(20, 18, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442337_e1a96d6b6669b45a094d.png', '2026-03-25 18:08:57'),
(21, 18, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442337_8f22cfa0373dd79c3a0a.png', '2026-03-25 18:08:57'),
(22, 18, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774442337_47e75054708b5bb941ec.mp4', '2026-03-25 18:08:57'),
(23, 19, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519518_f84e5288fef9f6a6fcd2.png', '2026-03-26 15:35:18'),
(24, 19, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519518_7ab29cde6c03f950d9e2.png', '2026-03-26 15:35:18'),
(25, 19, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519518_bc6976dc3d6b8f3e08ad.jpg', '2026-03-26 15:35:18'),
(26, 21, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519659_37fdcd188568cfa31241.jpg', '2026-03-26 15:37:39'),
(27, 21, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519659_ad5e0bb3061242473c1a.jpg', '2026-03-26 15:37:39'),
(28, 21, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774519659_33ea30e34adc5f62bbea.png', '2026-03-26 15:37:39'),
(29, 37, 'image', 'https://beta.petsfolio.in/public/uploads/community/media/1774521917_3c6557a64fd311a865f1.jpg', '2026-03-26 10:45:17'),
(30, 37, 'image', 'https://beta.petsfolio.in/public/uploads/community/media/1774521917_542d670eff52f8d1e851.jpg', '2026-03-26 10:45:17'),
(31, 38, 'image', 'https://beta.petsfolio.in/public/uploads/community/media/1774522004_d42870fe307ee31f59c6.jpg', '2026-03-26 10:46:44'),
(32, 38, 'image', 'https://beta.petsfolio.in/public/uploads/community/media/1774522004_89f362c13b917dc44795.jpg', '2026-03-26 10:46:44'),
(33, 38, 'image', 'https://beta.petsfolio.in/public/uploads/community/media/1774522004_21a7ccf5e8ad79559d0e.jpg', '2026-03-26 10:46:44'),
(34, 39, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774522152_125c8c72e04e39fce58f.jpg', '2026-03-26 10:49:12'),
(35, 39, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774522152_34136825d4c511eda3dd.jpg', '2026-03-26 10:49:12'),
(36, 39, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774522152_343693ecd1da1aead3cb.jpg', '2026-03-26 10:49:12'),
(37, 39, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774522692_3c58bd794678e206f1f1.jpg', '2026-03-26 10:58:12'),
(38, 24, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528116_9eccc4cd857b85d00d13.jpg', '2026-03-26 12:28:36'),
(39, 24, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528116_ffb4db62fe491d26e244.jpg', '2026-03-26 12:28:36'),
(40, 24, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528116_717bd4d0e052e0c265f7.jpg', '2026-03-26 12:28:36'),
(41, 23, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528138_9d13618daf08b3095bf9.jpg', '2026-03-26 12:28:58'),
(42, 23, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528138_8469b3741c14628a7258.jpg', '2026-03-26 12:28:58'),
(43, 41, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528204_4609cc78f9ebc2204d7d.jpg', '2026-03-26 12:30:04'),
(44, 41, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528204_bef6181c7890e03d3ee6.jpg', '2026-03-26 12:30:04'),
(45, 41, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528204_8c5d4d81685d3d7359af.jpg', '2026-03-26 12:30:04'),
(46, 41, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528204_838ffb7486d5b81b6ae4.jpg', '2026-03-26 12:30:04'),
(47, 42, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528259_45fd717b50b00013f4cf.jpg', '2026-03-26 12:30:59'),
(48, 42, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528259_0eff53d03b2fae7e108f.jpg', '2026-03-26 12:30:59'),
(49, 42, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528259_56e1dbdd8ebfd26dd553.jpg', '2026-03-26 12:30:59'),
(50, 42, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528259_c605652dc694b8d0ab45.jpg', '2026-03-26 12:30:59'),
(51, 43, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528444_07ded4c2d17d10ffd92e.jpg', '2026-03-26 12:34:04'),
(52, 43, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528444_124456e90e16f4514643.jpg', '2026-03-26 12:34:04'),
(53, 43, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528444_e5c49d30ccd1a4061ca6.jpg', '2026-03-26 12:34:04'),
(54, 44, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528562_5d630b58abeccefe8b95.jpg', '2026-03-26 12:36:02'),
(55, 44, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528562_6989d457e2c6e0613b4e.jpg', '2026-03-26 12:36:02'),
(56, 44, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774528562_ea58f3a7d39e212f5cb7.jpg', '2026-03-26 12:36:02'),
(57, 45, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774531552_988fdfe494adb80d536f.jpg', '2026-03-26 18:55:52'),
(58, 45, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774531552_caa0b9b7002e0d998da6.jpg', '2026-03-26 18:55:52'),
(59, 45, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774531552_6ffb8785f7750e0274c4.jpg', '2026-03-26 18:55:52'),
(60, 46, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774533470_46209ed0a6dcb58ca614.jpg', '2026-03-26 19:27:50'),
(61, 46, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774533470_1e133d444acd8bddd31a.jpg', '2026-03-26 19:27:50'),
(62, 46, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774533470_c9de2348ea6f61465112.jpg', '2026-03-26 19:27:50'),
(63, 46, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774533470_8a15ccaa0ad596fac1f3.mp4', '2026-03-26 19:27:50'),
(67, 51, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774589612_e4c885d9bfd7bd637849.jpg', '2026-03-27 11:03:32'),
(69, 51, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774589883_52a1a7225aa1afc7cf24.jpg', '2026-03-27 11:08:03'),
(70, 51, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774589883_84d08f24ccf2c22c2459.jpg', '2026-03-27 11:08:03'),
(71, 52, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774591151_50e55ddec836ba42dbe0.jpg', '2026-03-27 11:29:11'),
(72, 52, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774591151_678f754e94de10341bfb.mp4', '2026-03-27 11:29:11'),
(73, 53, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774591848_04bba54e48cd7c3e69a6.jpg', '2026-03-27 11:40:48'),
(74, 53, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774591848_3aca57c062fd4630db38.mp4', '2026-03-27 11:40:48'),
(76, 54, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774612374_a6be088e07c26fef8181.jpg', '2026-03-27 17:22:54'),
(77, 54, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774612374_f67b7304aa088befd2ef.jpg', '2026-03-27 17:22:54'),
(78, 54, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774612374_2d837ae5a4743696c862.mp4', '2026-03-27 17:22:54'),
(79, 55, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774617117_0ad24164b112ec2fbe45.jpg', '2026-03-27 18:41:57'),
(80, 55, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774617129_d572b3a27b058d687af7.jpg', '2026-03-27 18:42:09'),
(81, 56, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774689341_3186ac78035d117bef22.jpg', '2026-03-28 14:45:41'),
(82, 60, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774698937_900e868cca9f8aa7c2aa.jpg', '2026-03-28 17:25:37'),
(83, 62, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774700098_2f6fde783acb5b817b6b.jpg', '2026-03-28 17:44:58'),
(84, 62, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774700098_2c6f9faa57151de8ea0b.mp4', '2026-03-28 17:44:58'),
(85, 63, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774700149_a67bf397acca5580d5b3.jpg', '2026-03-28 17:45:49'),
(86, 64, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774700862_9b9e30901797c36a87c0.jpg', '2026-03-28 17:57:42'),
(87, 65, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774701690_a401a95bf26225b91c91.jpg', '2026-03-28 18:11:30'),
(88, 66, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774701821_d269f2762789ad8d1629.jpg', '2026-03-28 18:13:41'),
(89, 66, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774701821_d690343f092c812e389f.jpg', '2026-03-28 18:13:41'),
(90, 67, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774702432_b13e1f087256126fa82f.jpg', '2026-03-28 18:23:52'),
(91, 68, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774703843_bcd704182a422d5d8555.jpg', '2026-03-28 18:47:23'),
(92, 68, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774703843_d5d06a61297e300c0a56.jpg', '2026-03-28 18:47:23'),
(93, 72, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774862985_7f9a11eb89b619839ce0.jpg', '2026-03-30 14:59:45'),
(94, 75, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774864584_99814c3a829d97e30a6c.jpg', '2026-03-30 15:26:24'),
(95, 76, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774864874_829b4979ee6684260da9.jpg', '2026-03-30 15:31:14'),
(96, 79, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774872445_14d45353367e9debe562.jpg', '2026-03-30 17:37:25'),
(97, 80, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774872703_b41fecd7af9210c47e0f.jpg', '2026-03-30 17:41:43'),
(98, 81, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774873268_f96cb69656dc7bbf46c0.jpg', '2026-03-30 17:51:08'),
(99, 81, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774873268_b809ef9bcf378cc2bb3f.jpg', '2026-03-30 17:51:08'),
(100, 83, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774873990_39787cdbc51bd82f99ca.jpg', '2026-03-30 18:03:10'),
(101, 84, '', 'https://beta.petsfolio.in/public/uploads/community/media/1774874013_63f04daeb2f3bf71fc9d.jpg', '2026-03-30 18:03:33'),
(102, 91, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875534_92cbd319ae1481c6796f.jpg', '2026-03-30 18:28:54'),
(103, 91, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875534_fe7f854ea5833b18b6a7.jpg', '2026-03-30 18:28:54'),
(104, 91, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875534_f34dc5f31b74a5455a79.png', '2026-03-30 18:28:54'),
(105, 91, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875534_b5e0be627f8ffe59e3b7.png', '2026-03-30 18:28:54'),
(106, 92, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875652_57a5d0f108e0c600dc7b.jpg', '2026-03-30 18:30:52'),
(107, 92, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875652_51a8c28fd7eb381c3360.jpg', '2026-03-30 18:30:52'),
(108, 92, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875652_b81660bf407fa59f2b34.jpg', '2026-03-30 18:30:52'),
(109, 92, 'image', 'https://beta.petsfolio.in/admin/public/uploads/community/media/1774875652_ff2af49b3438345699f2.jpeg', '2026-03-30 18:30:52'),
(110, 93, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775029454_5238488a187648f0e613.jpg', '2026-04-01 13:14:14'),
(111, 94, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775038036_bff0dce0e78bfeb7954e.jpg', '2026-04-01 15:37:16'),
(112, 94, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775038036_3864c5492c05c6f8a44d.jpg', '2026-04-01 15:37:16'),
(113, 94, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775038036_7c7b6286c5588bf290e8.jpg', '2026-04-01 15:37:16'),
(114, 95, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775038157_8e3dabbf4ae195e93b1b.jpg', '2026-04-01 15:39:17'),
(115, 95, '', 'https://beta.petsfolio.in/public/uploads/community/media/1775038157_8652d8af1ef60b70959d.jpg', '2026-04-01 15:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_reports`
--

CREATE TABLE `community_post_reports` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_type` varchar(225) DEFAULT NULL,
  `title` varchar(225) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_reports`
--

INSERT INTO `community_post_reports` (`id`, `post_id`, `user_id`, `user_type`, `title`, `reason`, `created_at`) VALUES
(4, 42, 142, 'user', 'Spam', 'Gttg', '2026-03-28 15:53:56'),
(5, 44, 142, 'user', 'Spam', '', '2026-03-28 15:55:28'),
(6, 44, 142, 'user', 'Spam', '', '2026-03-28 15:55:36'),
(7, 58, 268, 'user', 'Inappropriate Content', '', '2026-03-28 16:05:47'),
(8, 44, 142, 'user', 'Inappropriate Content', 'Hbhgg', '2026-03-28 16:08:00'),
(9, 64, 267, 'user', 'False Information', '', '2026-03-28 17:58:36'),
(10, 65, 267, 'user', 'False Information', 'False', '2026-03-28 18:12:12'),
(11, 39, 142, 'user', 'Spam', '', '2026-03-28 18:13:07'),
(12, 68, 267, 'user', 'Other', 'Jajg', '2026-03-28 18:53:58'),
(13, 21, 268, 'user', 'Spam', '', '2026-03-30 10:56:03'),
(14, 68, 270, 'user', 'Inappropriate Content', 'Fake', '2026-03-30 10:59:43'),
(15, 68, 270, 'user', 'Spam', 'Spammm', '2026-03-30 11:00:58'),
(16, 59, 268, 'user', 'False Information', 'GM', '2026-03-30 11:45:12'),
(17, 59, 268, 'user', 'Inappropriate Content', '', '2026-03-30 11:49:22'),
(18, 68, 270, 'user', 'Inappropriate Content', 'Hlo ', '2026-03-30 11:53:28'),
(19, 68, 142, 'user', 'Spam', '', '2026-03-30 12:18:21'),
(20, 72, 270, 'user', 'Spam', 'Spammm ', '2026-03-30 15:02:31'),
(21, 79, 270, 'user', 'False Information', '', '2026-03-30 17:38:18'),
(22, 92, 270, 'user', 'Harassment or Bullying', '', '2026-03-30 18:31:56'),
(23, 54, 266, 'user', 'Spam', '', '2026-03-30 18:38:48'),
(24, 55, 268, 'user', 'Spam', '', '2026-03-31 13:12:59'),
(25, 68, 267, 'user', 'Inappropriate Content', 'Fake news', '2026-04-02 18:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_saves`
--

CREATE TABLE `community_post_saves` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_saves`
--

INSERT INTO `community_post_saves` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(23, 21, 4, '2026-03-26 15:37:51'),
(27, 43, 142, '2026-03-26 18:04:13'),
(43, 39, 266, '2026-03-27 17:21:14'),
(46, 55, 142, '2026-03-27 18:45:17'),
(47, 42, 142, '2026-03-27 18:46:41'),
(49, 56, 267, '2026-03-28 14:53:59'),
(51, 63, 267, '2026-03-28 17:48:55'),
(52, 39, 268, '2026-03-30 10:54:55'),
(53, 68, 270, '2026-03-30 11:12:15'),
(55, 68, 271, '2026-04-01 15:37:59');

-- --------------------------------------------------------

--
-- Table structure for table `community_post_shares`
--

CREATE TABLE `community_post_shares` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_post_tags`
--

CREATE TABLE `community_post_tags` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `tag` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `community_post_tags`
--

INSERT INTO `community_post_tags` (`id`, `post_id`, `tag`) VALUES
(7, 11, 'vijayyyy'),
(8, 12, 'vijayy'),
(9, 19, 'saiiii');

-- --------------------------------------------------------

--
-- Table structure for table `community_tags`
--

CREATE TABLE `community_tags` (
  `id` bigint(20) NOT NULL,
  `tag_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `community_user_follows`
--

CREATE TABLE `community_user_follows` (
  `id` bigint(20) NOT NULL,
  `follower_id` bigint(20) DEFAULT NULL,
  `following_id` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(225) NOT NULL,
  `email` varchar(225) NOT NULL,
  `phone` varchar(225) DEFAULT NULL,
  `profile` varchar(225) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `password` varchar(225) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `fcm_token` varchar(225) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `role_id`, `name`, `email`, `phone`, `profile`, `gender`, `password`, `otp`, `fcm_token`, `status`, `created_at`) VALUES
(1, 1, 'harish', 'harish24.infasta@gmail.com', '9885010497', 'LogoSvg.svg', 'male', '$2y$10$75s7YdHMu.5Kauk99asp5OpfxowXKyN6j0WdvSmfzFVNv/0WfSsYO', '649020', NULL, 0, '2024-11-30 15:34:53'),
(2, 2, 'Vijay', 'vijay.infasta@gmail.com', NULL, 'LogoSvg.svg', 'male', '$2y$10$3UpkiaCgsRSJmtJCZ8Tr/eXHbTJjeu0uv8roC4u1pcQ9TZVJ1ndkq', '', '', 1, '2025-02-03 18:58:59'),
(22, 3, 'Niharika', 'niharika.infasta@gmail.com', NULL, 'LogoSvg.svg', 'female', '$2y$10$9qWiTafICiMgDpQM116PZObciiP/GUCG4Q8GD./2nEMCNt7j1OJ0.', '', NULL, 1, '2025-02-06 04:44:21'),
(23, 2, 'Ugendra', 'info@petsfolio.com', NULL, 'LogoSvg.svg', 'male', '$2y$10$4.hvY3MVCY/3J2ZqGv/6yOxZePjIsElsRgk1585OsbuhORJItJLPu', '', NULL, 1, '2025-02-10 11:46:56'),
(24, 3, 'Sahil', 'sahil.infasta@gmail.com', NULL, 'LogoSvg.svg', 'male', '$2y$10$SnKfClr5ADf1iGETnmCY8ugDQDeyNLOFSpDxlQAUDsa2TQwhZIPhy', '', '', 1, '2025-02-11 11:20:37'),
(25, 1, 'Shiva kumar', 'shiva.acedezines@gmail.com', NULL, 'LogoSvg.svg', 'male', '$2y$10$cJbHlIAM5H5kv7EtY2kwlOVZIsfUjxsMyyMXoCYnXkX8Vil9tik.K', '', '', NULL, '2025-03-28 12:57:21'),
(26, 4, 'Saiteja', 'saiteja.infasta@gmail.com', NULL, 'LogoSvg.svg', 'female', '$2y$10$6BCFJvHwbf.FApdPfQpjjOYZvT5/GbCEq1pwADNHRlETmr7HPD/uK', '', '', 1, '2025-07-08 04:51:42'),
(29, 2, 'Nikhitha', 'nikhitha.infasta@gmail.com', NULL, 'LogoSvg.svg', 'female', '$2y$10$fPT4SBL5ZiASqOO9iIAOAe7eyWIPAblpkg1tcIm0.lS7R5A7/dFQi', '', '', 1, '2025-12-18 10:43:57'),
(30, 3, 'Abhilash', 'abhilash.infasta@gmail.com', NULL, 'LogoSvg.svg', 'male', '', '', '', 1, '2026-02-05 10:49:39'),
(31, 1, 'saiteja', 'sai@gmal.com', NULL, 'LogoSvg.svg', 'male', '', NULL, NULL, 1, '2026-02-19 06:50:56'),
(35, 1, 'Anil', 'anil.infasta@gmail.com', NULL, 'LogoSvg.svg', 'male', '', NULL, NULL, 1, '2026-02-20 11:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `whatsapp_enabled` varchar(255) DEFAULT NULL,
  `device_token` varchar(2000) DEFAULT NULL,
  `auth_token` varchar(1000) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `source` enum('browser','app') DEFAULT NULL,
  `has_app_installed` varchar(225) DEFAULT NULL,
  `sales_comment` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `gender`, `address`, `city`, `profile`, `otp`, `longitude`, `latitude`, `whatsapp_enabled`, `device_token`, `auth_token`, `ip_address`, `status`, `created_at`, `updated_at`, `source`, `has_app_installed`, `sales_comment`) VALUES
(142, 'Saiteja', 'saiteja.infasta@gmail.com', '7288909906', 'Male', NULL, NULL, 'd8YK9NMgrBqf3caGyoBP6gMOPRG.jpg', '', NULL, NULL, NULL, 'cHvSwqERShaeLbdAHMBXR-:APA91bH5lVGDhOiRFoU32kfJI-A0C3ZYxxi5VEpKNF-IZNKnws_k-HfVnzYF5oy_dOHoVl5EzmFSOfvK0q7Z1R4NPu-pr-CV_iL9htepckZlSXKOtHOyAas', '85b08e595a18b6e9681f11285cf4c876', '183.82.119.59', 'active', '2026-04-01 06:09:16', '2026-04-01 06:09:16', '', NULL, NULL),
(266, 'Rubby', 'Nikhitha.baddi1998@gmail.com', '9959471805', 'Female', NULL, NULL, '73VTu8aCpZdUC8BA2cYyKQYlXwh.jpg', '', NULL, NULL, NULL, 'dWmyB9nxTWe3pDBkv7vBwa:APA91bHyo2Qn09GnA6COvkX_tE0Eyl3VFTvTfPshx1Az5yhY-XAbjhLSbDIPX2b41meInzUWDwr5wv7PylkenwXD_D4XlyWaRHbWaB7GH3KZpqBZKj_BeSU', '3b846ba2db9438902e4c10df68077a5d', '2406:b400:d5:ea7c:dc58:7997:d547:9755', 'active', '2026-04-01 11:59:17', '2026-04-01 11:59:17', NULL, NULL, NULL),
(267, 'Petti Abhilash ', 'abhilash.infasta@gmail.com', '8179550538', 'Male', NULL, NULL, 'mHL4xYCh6jidhwCwcGw6x3Lh1mj.jpg', '', NULL, NULL, NULL, 'e7aXqwOFSTubZzr7BxWfl7:APA91bG8qnYCemo-8EqLwjXzT5JX2N2I-QF0xCpwXVr8Ki-ECzF7GBTsmuT0x8EeAjU1P0oroBETwspRQO9r-1Nv62Rv3lMl8ykBiJT458gM9CvKWXRWwzY', '9b02e1c5107db4ec2db59b49d3d44ae1', '2409:40f0:1128:1a86:9039:60ff:fe87:2768', 'active', '2026-04-01 04:57:55', '2026-04-01 04:57:55', NULL, NULL, NULL),
(268, 'Vijay', 'Vijay.infasta@gmail.com', '6303132973', 'Male', NULL, NULL, '0XvvurYqbGk17jP7kxkkEOrFags.jpg', '', NULL, NULL, NULL, 'cTvkBf5VQ22kI1zFLBvmoe:APA91bG5GQyH5FJ7-119L71U6vZFo3JwGiyJ3CTwBfvXC6jEjeRRnFmzpvuTQ1Q6pHnLNE8-w7mOBKYzdZ1cLKotFrVZRW-cUnkSEtUpUB1zEoCoCcNcfG8', '97809b66c4b686194d9d7535c993714e', '106.76.203.210', 'active', '2026-03-31 07:41:55', '2026-03-31 07:41:55', NULL, NULL, NULL),
(269, NULL, NULL, '8179550530', NULL, NULL, NULL, NULL, '2215', NULL, NULL, NULL, 'default_token', NULL, '183.82.119.59', 'active', '2026-03-27 09:07:56', NULL, NULL, NULL, NULL),
(270, 'Raju', 'raju@gmail.com', '9676103284', 'Male', NULL, NULL, 'imDPUiWW56rkpoWyXPwlPwuvvFF.jpg', '', NULL, NULL, NULL, 'fmvARsBUTJitFG9z4woEIk:APA91bHu2vSijOCEQ5H2KdNWjH_ET-bZqbQFSu6B7BGAF9DrHPk2ABNFgup7yJHAXUZzgIg8LXBDhsJbz-F3iSVV0VdYRFHCIpz4miyKXs6ZzmfMsNI8MoQ', '4c7cfc3a0061743f0ff393134f422ee4', '2409:40f0:1197:8717:b455:c1ff:fe7f:9cd0', 'active', '2026-03-30 06:57:01', '2026-03-30 06:57:01', NULL, NULL, NULL),
(271, 'Sahil Doi', 'revanth.acewebacademy@gmail.com', '8758396038', 'Male', NULL, NULL, 'bwxRuxKhtf380eYKtpwrXUpRdIH.jpg', '', NULL, NULL, NULL, 'famOnG2xS469VTXKYENO0I:APA91bFVgPlbM3D-UCCUB8IK1iMTJn0KDYVYZXZQqAmXwyY6sEYMv6me2khk97DHcq1hIYNZZ3dPBi6lTtEHucqzteDKUFqm0U4gAeMCmJDtc_gOQrUQZQQ', 'aa5bbe38cf1c2385029839f33f20cd7b', '183.82.119.59', 'active', '2026-04-03 12:14:59', '2026-04-03 12:14:59', NULL, NULL, NULL),
(272, 'Fan', 'Jfdd@ffh.ff', '7671857496', 'Male', NULL, NULL, 'vIXFlqU3dwKOsA9bNka8WnTpxVn.jpg', '', NULL, NULL, NULL, 'dYev8GRTSEmJmj2X7GRqWx:APA91bFdKJjEyCur_8Clwow7e0aOAYtwMsLrUil2b0iwdbOZBOlAH-mgmjgKUZFD0PiudHD9yN5fLS4WSdEXp7F2rPiOeDioLDTVglyMK-sbptBbaVctSwU', 'd37da56fc21fb4299c4eb443c6b3351e', '2406:b400:d5:ea7c:f9a7:e395:5e80:482f', 'active', '2026-04-02 04:54:28', '2026-04-02 04:54:28', NULL, NULL, NULL),
(273, NULL, NULL, '7095654312', NULL, NULL, NULL, NULL, '7100', NULL, NULL, NULL, 'default_token', NULL, '2406:b400:d5:ea7c:b1c2:ce47:980d:a99c', 'active', '2026-04-02 05:01:56', NULL, NULL, NULL, NULL),
(274, 'Chubby', 'nikhitha.infasta@gmail.com', '7095654315', 'Female', NULL, NULL, 'nvd8EspjZCfphHLpFl2RGqNrD1V.jpg', '', NULL, NULL, NULL, 'erGwyqjcQ7-VBHUgo0UHuR:APA91bGJadXX0B-BkcQr23HGDgpYeJBnvDr2hGU73f3BMuZ2rPDr-gwIQEO4navA_QHQBkrek1wNJAbXsmcSHFLkOsxsenuBtSUAhsBuVberOTp1ILEVgs0', 'f55bfaf672108a0bb375cfbfd9bc3db8', '2406:b400:d5:ea7c:b1c2:ce47:980d:a99c', 'active', '2026-04-02 05:12:36', '2026-04-02 05:12:36', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `community_channels`
--
ALTER TABLE `community_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_channel_joins`
--
ALTER TABLE `community_channel_joins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_comment_dislikes`
--
ALTER TABLE `community_comment_dislikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_notifications`
--
ALTER TABLE `community_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_posts`
--
ALTER TABLE `community_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `community_post_channels`
--
ALTER TABLE `community_post_channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_comments`
--
ALTER TABLE `community_post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_parent` (`parent_comment_id`);

--
-- Indexes for table `community_post_dislikes`
--
ALTER TABLE `community_post_dislikes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_media`
--
ALTER TABLE `community_post_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_reports`
--
ALTER TABLE `community_post_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `community_post_saves`
--
ALTER TABLE `community_post_saves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_save` (`post_id`,`user_id`);

--
-- Indexes for table `community_post_shares`
--
ALTER TABLE `community_post_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_tag` (`tag`);

--
-- Indexes for table `community_tags`
--
ALTER TABLE `community_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`);

--
-- Indexes for table `community_user_follows`
--
ALTER TABLE `community_user_follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_follow` (`follower_id`,`following_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `community_channels`
--
ALTER TABLE `community_channels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `community_channel_joins`
--
ALTER TABLE `community_channel_joins`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `community_comment_dislikes`
--
ALTER TABLE `community_comment_dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `community_notifications`
--
ALTER TABLE `community_notifications`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `community_posts`
--
ALTER TABLE `community_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `community_post_channels`
--
ALTER TABLE `community_post_channels`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `community_post_comments`
--
ALTER TABLE `community_post_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `community_post_dislikes`
--
ALTER TABLE `community_post_dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_likes`
--
ALTER TABLE `community_post_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `community_post_media`
--
ALTER TABLE `community_post_media`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `community_post_reports`
--
ALTER TABLE `community_post_reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `community_post_saves`
--
ALTER TABLE `community_post_saves`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `community_post_shares`
--
ALTER TABLE `community_post_shares`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_post_tags`
--
ALTER TABLE `community_post_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `community_tags`
--
ALTER TABLE `community_tags`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `community_user_follows`
--
ALTER TABLE `community_user_follows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
