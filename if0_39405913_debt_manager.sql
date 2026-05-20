-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql110.infinityfree.com
-- Generation Time: May 17, 2026 at 07:58 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_39405913_debt_manager`
--

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

CREATE TABLE `debts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `total_months` int(11) NOT NULL,
  `payment_day` int(11) NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debts`
--

INSERT INTO `debts` (`id`, `name`, `amount`, `total_months`, `payment_day`) VALUES
(20, 'grocery april', '2196.00', 3, 15),
(21, 'Aircon', '27000.00', 9, 30),
(22, 'Grocery May', '2355.00', 3, 30),
(23, 'May', '3081.00', 3, 15),
(24, 'dining', '3500.00', 3, 15),
(25, 'Grocey March 2nd	', '3381.00', 3, 15),
(26, 'SM', '1929.00', 3, 15),
(27, 'RCS', '3672.00', 3, 30),
(28, 'Grocery June', '2448.00', 3, 30),
(29, 'Jollibee', '714.00', 1, 15),
(30, 'Grocery July', '4626.00', 3, 15),
(31, 'ebike', '26400.00', 12, 15),
(32, 'Tingi', '3906.00', 3, 15),
(33, 'Grocery july', '4614.00', 3, 15),
(34, 'Card swipe', '2835.00', 3, 15),
(35, 'Osave', '2319.00', 3, 15),
(36, 'Loan 20k', '23544.00', 6, 30),
(39, 'tingi', '5700.00', 3, 15),
(40, 'tingi 2', '5700.00', 3, 30),
(41, 'dec grocery', '3576.00', 3, 15),
(42, 'dec grocery', '3576.00', 3, 30),
(45, 'jan grocery', '6159.00', 3, 15),
(46, 'jan grocery', '6159.00', 3, 30),
(47, 'sapatos(389), gasul(500), nawasa(1000), samgy(320), baguio(1100))', '3309.00', 1, 15),
(48, 'feb grocery', '4383.00', 3, 15);

-- --------------------------------------------------------

--
-- Table structure for table `debt_payments`
--

CREATE TABLE `debt_payments` (
  `id` int(11) NOT NULL,
  `debt_id` int(11) NOT NULL,
  `payment_month` int(11) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debt_payments`
--

INSERT INTO `debt_payments` (`id`, `debt_id`, `payment_month`, `is_paid`, `payment_date`) VALUES
(43, 20, 1, 1, '2025-07-02'),
(44, 20, 2, 1, '2025-07-02'),
(45, 20, 3, 1, '2025-07-15'),
(46, 21, 1, 1, '2025-07-02'),
(47, 21, 2, 1, '2025-07-02'),
(48, 21, 3, 1, '2025-07-02'),
(49, 21, 4, 1, '2025-07-31'),
(50, 21, 5, 1, '2025-08-31'),
(51, 21, 6, 1, '2025-09-30'),
(52, 21, 7, 1, '2025-11-05'),
(53, 21, 8, 1, '2025-12-04'),
(54, 21, 9, 1, '2026-01-03'),
(55, 22, 1, 1, '2025-07-02'),
(56, 22, 2, 1, '2025-07-02'),
(57, 22, 3, 1, '2025-07-31'),
(58, 23, 1, 1, '2025-07-02'),
(59, 23, 2, 1, '2025-07-15'),
(60, 23, 3, 1, '2025-08-16'),
(61, 24, 1, 1, '2025-07-15'),
(62, 24, 2, 1, '2025-08-16'),
(63, 24, 3, 1, '2025-09-16'),
(64, 25, 1, 1, '2025-07-06'),
(65, 25, 2, 1, '2025-07-06'),
(66, 25, 3, 1, '2025-07-15'),
(67, 26, 1, 1, '2025-07-15'),
(68, 26, 2, 1, '2025-08-16'),
(69, 26, 3, 1, '2025-09-16'),
(70, 27, 1, 1, '2025-07-31'),
(71, 27, 2, 1, '2025-08-31'),
(72, 27, 3, 1, '2025-09-30'),
(73, 28, 1, 1, '2025-07-31'),
(74, 28, 2, 1, '2025-08-31'),
(75, 28, 3, 1, '2025-09-30'),
(76, 29, 1, 1, '2025-07-15'),
(77, 30, 1, 1, '2025-07-15'),
(78, 30, 2, 1, '2025-08-16'),
(79, 30, 3, 1, '2025-09-16'),
(80, 31, 1, 1, '2025-10-15'),
(81, 31, 2, 1, '2025-11-15'),
(82, 31, 3, 1, '2025-12-16'),
(83, 31, 4, 1, '2026-01-15'),
(84, 31, 5, 1, '2026-02-15'),
(85, 31, 6, 1, '2026-03-15'),
(86, 31, 7, 1, '2026-04-15'),
(87, 31, 8, 0, NULL),
(88, 31, 9, 0, NULL),
(89, 31, 10, 0, NULL),
(90, 31, 11, 0, NULL),
(91, 31, 12, 1, '2026-02-15'),
(92, 32, 1, 1, '2025-09-16'),
(93, 32, 2, 1, '2025-10-15'),
(94, 32, 3, 1, '2025-11-15'),
(95, 33, 1, 1, '2025-09-16'),
(96, 33, 2, 1, '2025-10-15'),
(97, 33, 3, 1, '2025-11-15'),
(98, 34, 1, 1, '2025-09-16'),
(99, 34, 2, 1, '2025-10-15'),
(100, 34, 3, 1, '2025-11-15'),
(101, 35, 1, 1, '2025-09-16'),
(102, 35, 2, 1, '2025-10-15'),
(103, 35, 3, 1, '2025-11-15'),
(104, 36, 1, 1, '2025-11-05'),
(105, 36, 2, 1, '2025-12-04'),
(106, 36, 3, 1, '2026-01-03'),
(107, 36, 4, 1, '2026-01-30'),
(108, 36, 5, 1, '2026-02-28'),
(109, 36, 6, 1, '2026-03-31'),
(124, 39, 1, 1, '2025-12-16'),
(125, 39, 2, 1, '2026-01-15'),
(126, 39, 3, 1, '2026-02-15'),
(127, 40, 1, 1, '2025-12-04'),
(128, 40, 2, 1, '2026-01-03'),
(129, 40, 3, 1, '2026-01-30'),
(130, 41, 1, 1, '2026-01-15'),
(131, 41, 2, 1, '2026-02-15'),
(132, 41, 3, 1, '2026-03-15'),
(133, 42, 1, 1, '2026-01-03'),
(134, 42, 2, 1, '2026-01-30'),
(135, 42, 3, 1, '2026-02-28'),
(142, 45, 1, 1, '2026-03-15'),
(143, 45, 2, 1, '2026-04-15'),
(144, 45, 3, 0, NULL),
(145, 46, 1, 1, '2026-03-31'),
(146, 46, 2, 1, '2026-04-30'),
(147, 46, 3, 0, NULL),
(148, 47, 1, 1, '2026-03-31'),
(149, 48, 1, 1, '2026-03-15'),
(150, 48, 2, 1, '2026-04-15'),
(151, 48, 3, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'dm_admin', '$2y$10$gMSv4PCT51Y9Uq5zEQk4SOOCidCpB3SZEcoEZ4PdCO7AEysLvP2K6', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `debts`
--
ALTER TABLE `debts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debt_payments`
--
ALTER TABLE `debt_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `debt_id` (`debt_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `debts`
--
ALTER TABLE `debts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `debt_payments`
--
ALTER TABLE `debt_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `debt_payments`
--
ALTER TABLE `debt_payments`
  ADD CONSTRAINT `debt_payments_ibfk_1` FOREIGN KEY (`debt_id`) REFERENCES `debts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
