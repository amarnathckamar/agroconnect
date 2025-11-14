-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 07:55 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `marketplace`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`) VALUES
(2, 19, 24, 3);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `address` text DEFAULT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `seller_id`, `product_id`, `message`, `address`, `order_status`, `status`, `created_at`, `order_id`) VALUES
(1, 20, 24, 'You have a new order! Product: Organic Basmati Rice, Quantity: 1.', NULL, 'delivered', 'read', '2025-04-25 18:51:40', NULL),
(2, 20, 24, 'You have a new order! Product: Organic Basmati Rice, Quantity: 1.', 'Chundakkattil house Nidumannur post', 'delivered', 'read', '2025-04-25 19:02:10', NULL),
(3, 20, 24, 'You have a new order! Product: Organic Basmati Rice, Quantity: 3.', 'Chundakkattil house Nidumannur post', 'delivered', 'read', '2025-04-25 19:19:20', 9);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`) VALUES
(7, 19, '2025-04-26 00:21:40'),
(8, 19, '2025-04-26 00:32:10'),
(9, 19, '2025-04-26 00:49:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 7, 24, 1),
(2, 8, 24, 1),
(3, 9, 24, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `seller_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `seller_id`) VALUES
(24, 'Organic Basmati Rice', 'Aged, long-grain basmati rice grown using traditional methods without chemicals.(per kg)', 120.00, 'uploads/1745606119.jpg', 20),
(25, 'Red Rajma (Kidney Beans)', 'Premium quality handpicked rajma from the hills of Uttarakhand.', 140.00, 'uploads/1745606153.jpg', 20),
(26, 'Whole Wheat Flour (Atta)', 'Stone-ground from naturally grown wheat, rich in fiber and nutrients.(per kg)', 45.00, 'uploads/1745606191.jpg', 20),
(27, 'Raw Turmeric (Whole Haldi)', 'Naturally dried turmeric roots packed with flavor and antioxidants.(per kg)', 180.00, 'uploads/1745606275.jpg', 21),
(28, 'Moong Dal (Split Green Gram)', 'Unpolished and sun-dried moong dal for everyday cooking.(per kg)', 90.00, 'uploads/1745606312.jpg', 21),
(29, 'Fresh Tomatoes (Desi Variety)', 'Naturally ripened farm tomatoes with juicy texture and tangy taste.(per kg)', 30.00, 'uploads/1745606389.jpg', 22),
(30, 'Desi Mangoes (Alphonso)', 'Farm-fresh Alphonso mangoes, handpicked and carbide-free.(per dozen)', 180.00, 'uploads/1745606425.jpg', 22),
(31, 'Organic Green Chillies', 'Naturally grown green chillies full of spice and aroma.(per 250g)', 20.00, 'uploads/1745606454.jpg', 22),
(32, 'Sweet Potatoes (Shakarkand)', 'Freshly harvested, pesticide-free sweet potatoes.(per kg)', 40.00, 'uploads/1745606482.jpg', 22),
(33, 'Leafy Spinach (Palak)', 'Fresh-cut palak rich in iron and vitamins, grown without chemicals.(per bunch)', 25.00, 'uploads/1745606509.jpg', 22),
(34, 'Cold Pressed Groundnut Oil', 'Traditional wooden churned oil, pure and rich in flavor.(per litre)', 220.00, 'uploads/1745606558.jpg', 21),
(35, 'Organic Mustard Oil', 'Cold pressed, pungent mustard oil from heirloom seeds.(per litre)', 180.00, 'uploads/1745606594.jpg', 21),
(36, 'Handpicked Cashew Nuts', 'Naturally processed whole cashews, unsalted and fresh.(per kg)', 650.00, 'uploads/1745606645.jpg', 20),
(37, 'Dry Ginger Powder', 'Sun-dried and ground ginger with a strong aroma.(per 100g)', 110.00, 'uploads/1745606677.jpg', 20),
(38, 'Cow Ghee', 'Ghee made from curd-churned milk of grass-fed indigenous cows.(per litre)', 750.00, 'uploads/1745606714.jpg', 20),
(39, 'Free-Range Eggs', 'Brown eggs laid by hens raised in open natural conditions.(per dozen)', 80.00, 'uploads/1745606744.jpg', 20),
(40, 'Moringa Powder (Drumstick Leaves)', 'Superfood powder from dried moringa leaves, rich in nutrients.(per 100g)', 150.00, 'uploads/1745606771.jpg', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL,
  `logged_in` tinyint(1) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `role`, `logged_in`, `address`) VALUES
(19, 'amar', 'amarnathck22@gmail.com', '$2y$10$A.njtmSXLiWavy4lEPcmYecfV3radERehuDC673yv7A6a.9fwAqyq', '2025-04-25 18:14:51', 'user', 0, 'Chundakkattil house Nidumannur post'),
(20, 'alan', 'alan@gmail.com', '$2y$10$OKE/WIhsO9vONjnMpmE7/uiGsmCFjeLHdGGshnADgIU1zddbSuZ.6', '2025-04-25 18:18:47', 'seller', 0, NULL),
(21, 'david', 'david@gmail.com', '$2y$10$7.CkE1QZR70zCGaaaLsvruYOkYhtSHo5L/qE8mDJr..R8stTnAiA2', '2025-04-25 18:37:08', 'seller', 0, NULL),
(22, 'ramesh', 'ramesh@gmail.com', '$2y$10$uNz8S9iiN0kPAYUURT56ne6cikK6MqFYu5QpE7w46nEMEVmQBout.', '2025-04-25 18:39:06', 'seller', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `notifications_ibfk_2` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
