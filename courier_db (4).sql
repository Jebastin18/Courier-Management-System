-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2025 at 02:30 PM
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
-- Database: `courier_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_code` varchar(10) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL,
  `phone_number` varchar(10) NOT NULL,
  `address` text NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_code`, `branch_name`, `city`, `state`, `phone_number`, `address`, `pincode`, `created_at`) VALUES
(1, 'branch1', 'cbe', 'Coimbutore', 'tamilnadu', '8888888888', 'east street', '655454', '2025-02-25 05:56:39'),
(2, 'branch2', 'Nellai', 'Tirunelveli', 'tamilnadu', '7859595944', 'tirunelveli', '664550', '2025-02-25 06:32:52'),
(3, 'branch3', 'chennai', 'chennai', 'tn', '8888888888', 'hgiksd', '600288', '2025-02-25 11:54:46'),
(4, 'branch4', 'thotukodi', 'thotukudi', 'tamil', '8598539034', 'rragrkhj', '494094', '2025-02-25 11:55:40'),
(5, 'branch5', 'kanyakumari', 'kanyakumari', 'tamilnadu', '6789789745', 'kanyakumari', '657875', '2025-02-26 06:50:58'),
(6, '123', 'joel', 'tirunelveli', 'Tamil Nadu', '6858741401', 'abinaya complex, coimbatore', '654562', '2025-03-10 17:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `parcels`
--

CREATE TABLE `parcels` (
  `id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_address` text NOT NULL,
  `sender_city` varchar(100) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `sender_phone` varchar(15) NOT NULL,
  `sender_pincode` varchar(10) NOT NULL,
  `receiver_name` varchar(255) NOT NULL,
  `receiver_address` text NOT NULL,
  `receiver_city` varchar(100) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `receiver_phone` varchar(15) NOT NULL,
  `receiver_pincode` varchar(10) NOT NULL,
  `from_branch_id` int(11) NOT NULL,
  `to_branch_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED NOT NULL,
  `tracking_number` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcels`
--

INSERT INTO `parcels` (`id`, `sender_name`, `sender_address`, `sender_city`, `sender_email`, `sender_phone`, `sender_pincode`, `receiver_name`, `receiver_address`, `receiver_city`, `receiver_email`, `receiver_phone`, `receiver_pincode`, `from_branch_id`, `to_branch_id`, `total_amount`, `created_at`, `created_by`, `tracking_number`, `status`) VALUES
(1, 'bala', 'kovil street', 'tirunelveli', 'ba@gamil.com', '9877899499', '677777', 'vasanth', 'mid street', 'chennai', 'va@gmail.com', '9038568046', '986048', 2, 1, 250.00, '2025-02-25 11:10:07', 12, NULL, 'Out for Delivery'),
(2, 'boopathi', 'middle strreet', 'salem', 'boo@gamil.com', '9847897894', '646646', 'jebastin', 'church street', 'tirunelveli', 'je@gmail.com', '9478074907', '647488', 1, 2, 0.00, '2025-02-25 11:17:58', 12, NULL, 'In-Transit'),
(3, 'boopathi', 'middle strreet', 'salem', 'boo@gamil.com', '9847897894', '646646', 'jebastin', 'church street', 'tirunelveli', 'je@gmail.com', '9478074907', '647488', 1, 2, 0.00, '2025-02-25 11:22:04', 12, NULL, 'Pending'),
(4, 'alex', 'kila theru', 'tirunelveli', 'al@gamil.com', '8489374979', '678899', 'dhanush', 'ar buildings', 'chennai', 'dh@gmail.com', '9864836875', '678999', 2, 1, 0.00, '2025-02-25 11:27:52', 12, NULL, 'Pending'),
(5, 'alex', 'kila theru', 'tirunelveli', 'al@gamil.com', '8489374979', '678899', 'dhanush', 'ar buildings', 'chennai', 'dh@gmail.com', '9864836875', '678999', 2, 1, 0.00, '2025-02-25 11:36:41', 12, NULL, 'Pending'),
(6, 'joel', 'abc street', 'tirunel', 'jo@gmail.com', '8646395698', '755897', 'boopathi', '123 strreet', 'salem', 'bo@gmail.com', '9796983465', '979879', 2, 1, 0.00, '2025-02-25 11:43:26', 12, NULL, 'Pending'),
(7, 'stephen', '123 street', 'tirunelveli', 'st@gmail.com', '9988765884', '678947', 'ajith', 'abcd Street', 'thothukudi', 'aj@gmail.com', '8776768974', '609438', 2, 1, 250.00, '2025-02-25 11:52:48', 12, NULL, 'Pending'),
(8, 'jebastin', 'east street', 'tirunelveli', 'je@gmail.com', '7666545455', '634534', 'joel', '123 midddle street', 'cbe', 'jo@gmail.com', '9387878789', '699890', 2, 1, 600.00, '2025-02-26 04:27:28', 12, NULL, 'Pending'),
(9, 'boopatjhi', 'fhiksfhk', 'tirunel', 'b@osoif', '4789345797', '947593', 'jebast', 'ifk', 'kjfdgdj', 'je@ff', '4975893789', '495748', 2, 3, 450.00, '2025-02-26 04:29:51', 12, NULL, 'Out for Delivery'),
(10, 'alex', '1123 street', 'tirunelveli', 'alex@gmail.com', '9893097509', '638758', 'bala', 'abcd complex', 'chennai', 'bala@gmail.com', '9768578745', '464666', 2, 3, 200.00, '2025-02-26 10:19:39', 7, NULL, 'Pending'),
(11, 'abi', 'midddle street', 'tirunelveli', 'a@gmail.com', '9073686239', '686298', 'selva', 'kovil strret', 'cbe', 'sel@gmail.com', '9036968378', '698362', 2, 1, 4500.00, '2025-02-26 11:47:49', 7, NULL, 'Pending'),
(12, 'jebastin', 'east st', 'tirunelveli', 'je@gmail.com', '8964968476', '683686', 'joel', 'church st', 'chennai', 'jo@gail.com', '9864986938', '649648', 2, 3, 300.00, '2025-02-26 11:57:35', 7, NULL, 'Pending'),
(13, 'raja', 'north street', 'tir', 'ra@gmail.com', '9749579748', '679489', 'ramu', 'west streer', 'chenai', 'ram@gmail.com', '8974987897', '689374', 2, 3, 200.00, '2025-02-27 04:48:18', 7, NULL, 'Pending'),
(30, 'nixon', 'chenai', 'che', 'nix@gmail.com', '9547667596', '689964', 'bala', 'tirunelveli', 'tvl', 'bala@gmail.com', '9580604760', '759796', 3, 2, 250.00, '2025-03-04 06:13:55', 7, NULL, 'Pending'),
(31, 'dhanush', 'town', 'tirunelveli', 'da@gmail.com', '4588694564', '976597', 'nixon', 'beach road', 'chennai', 'ni@gmail.com', '9805960946', '597596', 2, 3, 250.00, '2025-03-04 06:17:47', 7, NULL, 'Pending'),
(32, 'allwin', 'east street', 'tir', 'all@gmail.com', '9875984757', '687658', 'jebastin', 'church street', 'cbe', 'je@gmail.com', '8097874975', '668999', 2, 1, 250.00, '2025-03-05 05:52:11', 8, NULL, 'Pending'),
(33, 'allwin', 'church street', 'nelllai', 'all@gmail.com', '9979879873', '655555', 'jebastin', 'east street', 'cbe', 'je@gmail.com', '9074798493', '679579', 2, 1, 300.00, '2025-03-05 08:46:38', 8, 'TRK0003303050946', 'In-Transit'),
(34, 'alex', '123 street', 'nellai', 'al@gmail.com', '9847698769', '638968', 'dhanush', 'middle street', 'chennai', 'da@gmail.com', '8643963468', '644444', 2, 3, 500.00, '2025-03-05 12:21:12', 8, 'TRK0003403051321', 'Unsuccessful Delivery Attempt'),
(35, 'joel', 'bridge strret', 'cbe', 'jo@gmail.com', '9795798798', '689694', 'alex', 'church street', 'tvl', 'al@gmail.com', '9797394730', '750970', 1, 2, 250.00, '2025-03-06 06:29:31', 7, 'TRK0003503060729', 'Delivered'),
(36, 'vv', 'ff', 'cbe', 'a@a.com', '9952362224', '641001', 'hh', 'ff', 'erd', 'aa@aa.com', '9942362224', '600001', 2, 3, 500.00, '2025-03-07 10:33:57', 7, 'TRK0003603071133', 'In-Transit'),
(37, 'jebastin', 'bridge street', 'coib', 'jebast@gmailcom', '8786986946', '686798', 'baala', 'bala steet', 'nellai', 'bal@gmail.com', '8678656095', '656464', 1, 2, 250.00, '2025-03-10 06:42:40', 15, 'TRK0003703100742', 'Shipped'),
(38, 'jebastin', 'sffkjsdh', 'lskd', 'jebastinr817@gmail.com', '8498904309', '498907', 'raj', 'klshfklsd', 'hdls', 's733018@gmail.com', '8968686896', '424434', 3, 4, 250.00, '2025-03-10 10:31:49', 15, 'TRK0003803101131', 'Pending'),
(39, 'joel', 'konganapuram', 'salem', 'jjraj505@gmail.com', '926180879', '323232', 'jebstin', 'konganapuram', 'salem', 'aboopathi552@gmail.com', '9865658785', '323232', 3, 4, 25.00, '2025-03-10 17:40:03', 15, 'TRK0003903101840', 'Out for Delivery'),
(40, 'jefg', 'klfsdf', 'lkhgklfd', 'jebastinr817@gmail.com', '9749874987', '874878', 'gjhgj', 'ldffgs', 'jfbjd', 's733018@gmail.com', '7498798749', '479847', 2, 4, 300.00, '2025-03-14 08:10:50', 7, 'TRK0004003140910', 'Pending'),
(41, 'jhf', 'jhfjs', 'dfg', 'jebastinr817@gmail.com', '4875937954', '646444', 'iohfi', 'fhks', 'dsd', 's733018@gmail.com', '9479374973', '555555', 4, 2, 300.00, '2025-03-14 11:42:42', 7, 'TRK0004103141242', 'Pending'),
(42, 'jrjkfkj', 'gsg', 'dgs', 'jebastinr817@gmail.com', '3945793749', '563656', 'fgfsg', 'sdgs', 'gsdg', 's733018@gmail.com', '9809347893', '634636', 4, 4, 2750.00, '2025-03-14 11:51:54', 7, 'TRK0004203141251', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `parcel_items`
--

CREATE TABLE `parcel_items` (
  `id` int(11) NOT NULL,
  `parcel_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `kilograms` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parcel_items`
--

INSERT INTO `parcel_items` (`id`, `parcel_id`, `item_name`, `kilograms`, `price`) VALUES
(1, 11, 'item1', 30.00, 1500.00),
(2, 11, 'item2', 40.00, 2000.00),
(3, 11, 'item5', 20.00, 1000.00),
(4, 11, 'item1', 30.00, 1500.00),
(5, 11, 'item2', 40.00, 2000.00),
(6, 11, 'item5', 20.00, 1000.00),
(11, 13, 'item1', 2.00, 100.00),
(12, 13, 'itwm2', 2.00, 100.00),
(13, 13, 'item1', 2.00, 100.00),
(14, 13, 'itwm2', 2.00, 100.00),
(15, 30, 'item1', 2.00, 100.00),
(16, 30, 'item2', 3.00, 150.00),
(45, 12, 'item1', 4.00, 200.00),
(46, 12, 'item2', 4.00, 200.00),
(85, 31, 'item1', 6.00, 300.00),
(86, 31, 'item2', 2.00, 100.00),
(87, 32, 'item', 5.00, 250.00),
(88, 33, 'item1', 6.00, 300.00),
(89, 34, 'item1', 10.00, 500.00),
(90, 35, 'item', 5.00, 250.00),
(91, 36, 'nn', 3.00, 150.00),
(92, 36, 'jj', 7.00, 350.00),
(93, 37, 'item', 5.00, 250.00),
(94, 38, 'item2', 5.00, 250.00),
(95, 39, 'phone', 0.50, 25.00),
(96, 40, 'rf', 6.00, 300.00),
(97, 41, 'thhg', 6.00, 300.00),
(98, 42, 'ter', 55.00, 2750.00);

-- --------------------------------------------------------

--
-- Table structure for table `tracking_history`
--

CREATE TABLE `tracking_history` (
  `id` int(11) NOT NULL,
  `parcel_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tracking_history`
--

INSERT INTO `tracking_history` (`id`, `parcel_id`, `status`, `updated_at`) VALUES
(1, 1, 'In-Transit', '2025-03-06 04:20:02'),
(2, 2, 'In-Transit', '2025-03-06 04:20:02'),
(3, 33, 'In-Transit', '2025-03-06 04:20:02'),
(4, 34, 'In-Transit', '2025-03-06 04:20:02'),
(5, 1, 'Out for Delivery', '2025-03-06 04:34:02'),
(6, 34, 'Delivered', '2025-03-06 04:40:56'),
(7, 34, 'Unsuccessful Delivery Attempt', '2025-03-06 04:48:11'),
(8, 35, 'Shipped', '2025-03-06 06:36:41'),
(9, 35, 'In-Transit', '2025-03-06 06:37:09'),
(10, 35, 'Out for Delivery', '2025-03-06 06:38:05'),
(11, 35, 'Delivered', '2025-03-06 06:38:36'),
(12, 35, 'Unsuccessful Delivery Attempt', '2025-03-06 06:59:51'),
(13, 35, 'Delivered', '2025-03-07 05:16:02'),
(14, 36, 'Shipped', '2025-03-07 10:37:32'),
(15, 36, 'In-Transit', '2025-03-08 06:37:00'),
(16, 37, 'Shipped', '2025-03-10 06:45:01'),
(17, 9, 'Out for Delivery', '2025-03-10 17:16:01'),
(18, 33, 'In-Transit', '2025-03-10 17:32:02'),
(19, 39, 'Out for Delivery', '2025-03-13 11:39:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `aadhar_number` varchar(20) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `branch_id` int(11) DEFAULT NULL,
  `branch_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `address`, `password`, `aadhar_number`, `phone_number`, `role`, `created_at`, `status`, `branch_id`, `branch_name`) VALUES
(1, 'jebastin', 'jebas', 'jebas@gmail.com', 'Tirunelveli', '$2y$10$6tD4PvG5j07qZRpyma08W./DP3PcPqMjrjKwcyj04WzDchwYEBuJi', '123456789098', '9878675678', 'Admin', '2025-02-24 06:17:21', 'active', 1, ''),
(4, 'joel', 'joel', 'joel@gmail.com', 'tggeg', '$2y$10$.NEpsDEVhKRiZjeI.wMJuOL2AyL8fV62CUUF1YmLOHx5pEUvzLJ22', '289326464689', '8999999999', 'Staff', '2025-02-24 08:06:09', 'active', 1, ''),
(5, 'bala', 'bala', 'bala@gmail.com', 'jjjjjjjjj', '$2y$10$YKHUVmkWH0XJeHodhYxPx.qQXRgg4rFDjCe84EBlO1HblKSwwhhiG', '302332323232', '9999999999', 'Staff', '2025-02-24 08:08:13', 'active', 1, ''),
(6, 'jebastin', 'jebastinr', 'jebasr@gmail.com', 'tryuiop', '$2y$10$Uo/oDwfgWqefp8YiAUQlBO9iqY7eW9n8PbV.m9EkjE/0NysEQa0Te', '567898766728', '7838888888', 'Staff', '2025-02-24 08:26:45', 'active', 1, ''),
(7, 'admin', 'admin', 'alex@gmail.com', 'titit', '$2y$10$GqQVELHtxWPxgxNhEz/sre9/F09lnDP6R05Ukzw.Tmo.O4aNG5nSi', '987654678908', '4797877477', 'Admin', '2025-02-24 08:38:10', 'active', 1, ''),
(8, 'vasanth', 'vasanth', 'vasanth@gmail.com', 'chennai', '$2y$10$q.V2Hhj1HDRQG1u5LgfunOB3o6bVCylkHL4ynmiYzsF5dNuf2IKAy', '876543456788', '9878675689', 'Staff', '2025-02-24 11:44:34', 'active', 1, ''),
(9, 'allwin', 'allwin', 'all@gmail.com', 'gsgfsfafafafa', '$2y$10$RIxqk5IBHQOjcMFRFYIIAe9OFUqbEJbB3l6RDdfsOOXtwDfgdlqcS', '542223323233', '8683658463', 'Staff', '2025-02-26 06:09:35', 'active', 1, ''),
(10, 'stephen', 'stephen', 'stephen@gmail.com', 'hthdhthhsh', '$2y$10$MZP6qAve.zl6TgNJ6UqdUeTg5LTJFT87jJU08tcnMHSJZelvpDb/q', '542872394965', '9878964896', 'Staff', '2025-02-26 06:12:12', 'active', 1, ''),
(11, 'John Doe', 'johndoe', 'johndoe@example.com', '123 Street', 'password', '123456789012', '9876543210', 'Staff', '2025-02-26 06:24:30', 'active', 1, ''),
(12, 'ajith', 'ajith', 'aji@gmail.com', 'tn', '$2y$10$tSM90TkgR1RWAfgDADsjO.je4.9crxzzcqWDQMVbWiUeyIXiJSnq.', '586747568648', '8575959785', 'Staff', '2025-02-26 06:47:47', 'active', 2, ''),
(13, 'nixon', 'nix', 'nix@gmail.com', 'htrighki', '$2y$10$K6C6vY9JW0G/1UzdKxH1sO0cmk8Km/6lA.pZF8vPe7j4YQ4zuRk4m', '874659875987', '6876976897', 'Staff', '2025-02-26 06:51:48', 'active', 5, ''),
(14, 'abi', 'abi', 'abi@gmail.com', 'redyarpeti', '$2y$10$jSrwJOpnPCZ7C3vQ2h922uTJ6WoBVvK2x8dmAATCSju8e7Hktrb2.', '574895657593', '8569796857', 'Admin', '2025-02-26 08:52:25', 'active', 3, 'chennai'),
(15, 'alex', 'alex', 'alexjj@gmail.com', 'tirunelveli', '$2y$10$FA8H5c4xXz1P4ODZx5a3dOaerQi2c3VegJB.kUKkTZmQLgdsLXera', '872978974989', '9788787878', 'Staff', '2025-03-07 09:36:23', 'active', 5, 'kanyakumari');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branch_code` (`branch_code`);

--
-- Indexes for table `parcels`
--
ALTER TABLE `parcels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `from_branch_id` (`from_branch_id`),
  ADD KEY `to_branch_id` (`to_branch_id`);

--
-- Indexes for table `parcel_items`
--
ALTER TABLE `parcel_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parcel_id` (`parcel_id`);

--
-- Indexes for table `tracking_history`
--
ALTER TABLE `tracking_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parcel_id` (`parcel_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `aadhar_number` (`aadhar_number`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD KEY `fk_branch` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parcels`
--
ALTER TABLE `parcels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `parcel_items`
--
ALTER TABLE `parcel_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `tracking_history`
--
ALTER TABLE `tracking_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `parcels`
--
ALTER TABLE `parcels`
  ADD CONSTRAINT `parcels_ibfk_1` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parcels_ibfk_2` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parcel_items`
--
ALTER TABLE `parcel_items`
  ADD CONSTRAINT `parcel_items_ibfk_1` FOREIGN KEY (`parcel_id`) REFERENCES `parcels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracking_history`
--
ALTER TABLE `tracking_history`
  ADD CONSTRAINT `tracking_history_ibfk_1` FOREIGN KEY (`parcel_id`) REFERENCES `parcels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
