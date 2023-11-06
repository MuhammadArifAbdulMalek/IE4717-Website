-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2023 at 12:38 AM
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
-- Table structure for table `confirmedorder`
--

CREATE TABLE `confirmedorder` (
  `order_id` int(11) NOT NULL,
  `user_id` varchar(19) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `addresscity` varchar(30) NOT NULL,
  `addresspostalcode` varchar(30) NOT NULL,
  `billingaddress1` varchar(255) NOT NULL,
  `billingaddress2` varchar(255) NOT NULL,
  `billingcity` varchar(255) NOT NULL,
  `billingpostalcode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `confirmedorder`
--

INSERT INTO `confirmedorder` (`order_id`, `user_id`, `product_id`, `size`, `quantity`, `price`, `subtotal`, `name`, `email`, `phonenumber`, `address1`, `address2`, `addresscity`, `addresspostalcode`, `billingaddress1`, `billingaddress2`, `billingcity`, `billingpostalcode`) VALUES
(27, 'guest_65491fb8234c0', 2, '0', 13, 165.00, 2145.00, '', '', '0', '', '', '', '', '', '', '', ''),
(28, 'guest_65491fb8234c0', 1, 'US 4.0', 11, 165.00, 1815.00, '', '', '0', '', '', '', '', '', '', '', ''),
(29, 'guest_65491fb8234c0', 1, 'US 4.5', 3, 165.00, 495.00, 'Muhammad Arif', 'muhdarif1999@gmail.com', '97101944', 'aaa', '', 'Singapore', '510519', 'aaaa', '', 'Singapore', '510519');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `confirmedorder`
--
ALTER TABLE `confirmedorder`
  ADD PRIMARY KEY (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `confirmedorder`
--
ALTER TABLE `confirmedorder`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
