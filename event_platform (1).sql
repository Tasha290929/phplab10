-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 15, 2024 at 08:01 PM
-- Server version: 8.0.30
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_platform`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `number_seats` int NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `price`, `number_seats`, `date`) VALUES
(1, 'Просмотр фильма на стадионе ', '150.00', 100, '2024-05-01 18:00:00'),
(2, 'Выставка котов', '40.00', 75, '2024-04-12 12:47:42'),
(4, 'Выгул собак', '10.00', 100, '2024-04-25 21:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `event_records`
--

CREATE TABLE `event_records` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `seats_requested` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_records`
--

INSERT INTO `event_records` (`id`, `user_id`, `event_id`, `seats_requested`, `total_price`) VALUES
(1, 5, 2, 0, '0.00'),
(2, 5, 1, 0, '0.00'),
(3, 11, 2, 0, '0.00'),
(4, 17, 2, 10, '400.00'),
(5, 5, 2, 10, '400.00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`id`, `name`, `surname`, `email`, `role_id`) VALUES
(5, 'Tasha', 'IIIIIIIIII', 'Ibsjhbdb@jbfhd.dg', '1'),
(6, 'Aliona', 'fghsfvc', 'dhvfgjdhs@dfj.bv', '1'),
(7, 'Gita', 'atats', 'fndkjnd@hnjg.fd', '1'),
(8, 'Gita', 'atats', 'fkjnd@hnjg.fd', '1'),
(9, 'Aliona', 'fghsfvc', 'dhgjdhs@dfj.bv', '1'),
(10, 'Aliona', 'fghsfvc', 'dhjdhs@dfj.bv', '1'),
(11, 'Aliona', 'fghsfvc', 'ddhs@dfj.bv', '1'),
(12, 'fkmgkdm', 'flknglkndf', 'fngvjdfn@sbhfd.dfv', '1'),
(13, 'efdjjd', 'fgnlnd', 'fgnjkd2dbfdfm@jdfj.dd', '1'),
(14, 'admit', 'admin', 'admin@adm.in', '1'),
(15, 'aaa', 'aaa', 'aaa@aa.a', '1'),
(16, 'AAA', 'AAA', 'AAA@AAAA.AA', '1'),
(17, 'AAA', 'AAA', 'AAA@AAA.AA', '1'),
(18, 'admin', 'admin', 'ad@m.in', '2'),
(19, 'ddd', 'ddd', 'dd@d.d', '1'),
(20, 'fafa', 'fafa', 'fafa@fa.fa', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_records`
--
ALTER TABLE `event_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_records`
--
ALTER TABLE `event_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_records`
--
ALTER TABLE `event_records`
  ADD CONSTRAINT `event_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`id`),
  ADD CONSTRAINT `event_records_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
