-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2023 at 03:46 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `cake_ready_setup`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `language_id` tinyint(4) NOT NULL DEFAULT 0,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `category_name` varchar(255) NOT NULL,
  `category_configs` varchar(255) DEFAULT '{"icon":"", "isProtected":""}',
  `category_priority` tinyint(4) DEFAULT 99,
  `rec_state` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `language_id`, `parent_id`, `category_name`, `category_configs`, `category_priority`, `rec_state`) VALUES
(1, 0, 0, 'new 2', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1),
(2, 0, 0, 'aa47s', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1),
(3, 0, 0, 'saddasda', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1),
(4, 0, 0, 'sdsds', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1),
(5, 0, 0, 'saddasssss', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1),
(6, 0, 5, '5555', '{\"icon\":\"\",\"isProtected\":\"\"}', 99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text DEFAULT NULL,
  `stat_updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`id`, `config_key`, `config_value`, `stat_updated`) VALUES
(1, 'TRY_USD', '0.52', '2022-12-23 07:42:21'),
(2, 'EUR_USD', '4', '2022-12-23 07:42:21'),
(3, 'GBP_USD', '8', '2022-12-23 07:49:09');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `language_id` tinyint(4) NOT NULL DEFAULT 1,
  `content_title` varchar(255) NOT NULL,
  `content_desc` text DEFAULT NULL,
  `features_ids` varchar(255) DEFAULT NULL,
  `content_search_pool` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `stat_created` datetime NOT NULL,
  `stat_updated` datetime NOT NULL,
  `stat_views` int(11) DEFAULT 0,
  `stat_shares` int(11) DEFAULT 0,
  `rec_state` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `log_url` varchar(255) NOT NULL COMMENT 'Ex: ["","","","","http//:localhost/ptpms/admin/offices/save/7","Offices","save","7"]',
  `log_changes` mediumtext DEFAULT NULL COMMENT 'Ex: [{"before":null}, {"after":"new val"}]',
  `stat_created` datetime NOT NULL DEFAULT current_timestamp(),
  `rec_state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=unread, 2=read'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `log_url`, `log_changes`, `stat_created`, `rec_state`) VALUES
(3513, 1, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Osama Kassar\",\"user_role\":\"admin.root\"}]', '2023-04-18 09:39:50', 1),
(3514, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-04-28 09:40:55', 1),
(3515, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-04-28 12:42:22', 1),
(3516, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/admin/categories/save/-1\",\"Categories\",\"save_new\",\"-1\"]', '[{\"category_priority\":99,\"parent_id\":\"5\",\"category_name\":\"5555\",\"id\":-1}]', '2023-04-28 12:43:32', 1),
(3517, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-05-26 13:14:02', 1),
(3518, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-05-26 14:17:02', 1),
(3519, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-05-26 14:53:59', 1),
(3520, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-07-13 08:29:36', 1),
(3521, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-07-13 09:09:13', 1),
(3522, 1, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Osama Kassar\",\"user_role\":\"admin.root\"}]', '2023-07-13 09:10:13', 1),
(3523, 1, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Osama Kassar\",\"user_role\":\"admin.root\"}]', '2023-07-13 10:14:06', 1),
(3524, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-07-13 12:21:21', 1),
(3525, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-07-13 12:21:22', 1),
(3526, 2, '[\"\",\"\",\"\",\"\",\"http//:localhost/cake_ready_setup/login\",\"Users\",\"login\",\"\"]', '[{\"user_fullname\":\"Admin\",\"user_role\":\"admin.root\"}]', '2023-07-13 12:21:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `message_to` varchar(255) DEFAULT NULL,
  `message_subject` varchar(255) DEFAULT NULL,
  `message_text` text NOT NULL,
  `message_priority` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=normal, 2=warning, 3=urgent',
  `stat_created` datetime DEFAULT current_timestamp(),
  `rec_state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=new, 2=read 	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `specs`
--

CREATE TABLE `specs` (
  `id` int(11) NOT NULL,
  `language_id` tinyint(4) DEFAULT 1,
  `content_id` int(11) DEFAULT NULL,
  `spec_name` varchar(255) NOT NULL,
  `spec_value` varchar(255) DEFAULT NULL,
  `spec_type` varchar(255) DEFAULT NULL COMMENT '3+1 | 2+1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` varchar(255) NOT NULL,
  `user_token` varchar(255) DEFAULT NULL,
  `user_configs` varchar(255) NOT NULL DEFAULT '{"mobile":"", "address":""}',
  `stat_lastlogin` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stat_created` datetime NOT NULL,
  `stat_logins` int(11) NOT NULL DEFAULT 0,
  `stat_ip` varchar(255) DEFAULT NULL,
  `rec_state` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_fullname`, `email`, `password`, `user_role`, `user_token`, `user_configs`, `stat_lastlogin`, `stat_created`, `stat_logins`, `stat_ip`, `rec_state`) VALUES
(1, 'Osama Kassar', 'osama.qassar@gmail.com', '$2y$10$iLiy64VgN.bXPhnXY.HhPe7FZ8nO9akhwLhgWNTYm2T4zbmWjSwKu', 'admin.root', '1', '', '2023-07-13 10:14:06', '2021-06-28 11:16:18', 18, '::1', 1),
(2, 'Admin', 'admin@zadcafe.se', '$2y$10$vQ/mRu.F9b5.tOGomUyQRu1T1ivWJLPyIh6T5W2fWtzpsIR3.bJsK', 'admin.root', '', '\"\"', '2023-07-13 12:21:23', '2022-04-10 12:46:57', 13, '127.0.0.1', 1),
(3, 'sssssssssssa', 'admin@bostanichocolate.com', '$2y$10$o.ocdd5A3pVF9WWhTW77yuWOcokpNpSeyMyXO4QpvUF4ZQOfAnIgO', 'admin.root', NULL, '{\"mobile\":\"987987987987\",\"address\":\"\"}', '2023-04-17 12:06:26', '2023-04-07 11:59:44', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_message`
--

CREATE TABLE `user_message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `stat_created` datetime NOT NULL DEFAULT current_timestamp(),
  `rec_state` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=new, 2=read'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `specs`
--
ALTER TABLE `specs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_message`
--
ALTER TABLE `user_message`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3527;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `specs`
--
ALTER TABLE `specs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_message`
--
ALTER TABLE `user_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
