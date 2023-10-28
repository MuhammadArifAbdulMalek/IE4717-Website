-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2023 at 07:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoeshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `colourway` varchar(50) NOT NULL,
  `us_size` decimal(3,1) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_name`, `colourway`, `us_size`, `qty`) VALUES
(1, 'Nike Air Force One', 'White', 5.0, 15),
(2, 'Nike Air Force One', 'White', 5.5, 15),
(3, 'Nike Air Force One', 'White', 6.0, 15),
(4, 'Nike Air Force One', 'White', 6.5, 15),
(5, 'Nike Air Force One', 'White', 7.0, 15),
(6, 'Nike Air Force One', 'White', 7.5, 15),
(7, 'Nike Air Force One', 'White', 8.0, 15),
(8, 'Nike Air Force One', 'White', 8.5, 15),
(9, 'Nike Air Force One', 'White', 9.0, 15),
(10, 'Nike Air Force One', 'White', 9.5, 15),
(11, 'Nike Air Force One', 'White', 10.0, 15),
(12, 'Nike Air Force One', 'Black', 5.0, 15),
(13, 'Nike Air Force One', 'Black', 5.5, 15),
(14, 'Nike Air Force One', 'Black', 6.0, 15),
(15, 'Nike Air Force One', 'Black', 6.5, 15),
(16, 'Nike Air Force One', 'Black', 7.0, 15),
(17, 'Nike Air Force One', 'Black', 7.5, 15),
(18, 'Nike Air Force One', 'Black', 8.0, 15),
(19, 'Nike Air Force One', 'Black', 8.5, 15),
(20, 'Nike Air Force One', 'Black', 9.0, 15),
(21, 'Nike Air Force One', 'Black', 9.5, 15),
(22, 'Nike Air Force One', 'Black', 10.0, 15),
(23, 'Nike Air Force One', 'Brown', 5.0, 15),
(24, 'Nike Air Force One', 'Brown', 5.5, 15),
(25, 'Nike Air Force One', 'Brown', 6.0, 15),
(26, 'Nike Air Force One', 'Brown', 6.5, 15),
(27, 'Nike Air Force One', 'Brown', 7.0, 15),
(28, 'Nike Air Force One', 'Brown', 7.5, 15),
(29, 'Nike Air Force One', 'Brown', 8.0, 15),
(30, 'Nike Air Force One', 'Brown', 8.5, 15),
(31, 'Nike Air Force One', 'Brown', 9.0, 15),
(32, 'Nike Air Force One', 'Brown', 9.5, 15),
(33, 'Nike Air Force One', 'Brown', 10.0, 15);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
