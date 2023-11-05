-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2023 at 04:06 AM
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
  `us_size_5.0` int(11) NOT NULL,
  `us_size_5.5` int(11) NOT NULL,
  `us_size_6.0` int(11) NOT NULL,
  `us_size_6.5` int(11) NOT NULL,
  `us_size_7.0` int(11) NOT NULL,
  `us_size_7.5` int(11) NOT NULL,
  `us_size_8.0` int(11) NOT NULL,
  `us_size_8.5` int(11) NOT NULL,
  `us_size_9.0` int(11) NOT NULL,
  `us_size_9.5` int(11) NOT NULL,
  `us_size_10.0` int(11) NOT NULL,
  `us_size_10.5` int(11) NOT NULL,
  `us_size_11.0` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_name`, `colourway`, `us_size_5.0`, `us_size_5.5`, `us_size_6.0`, `us_size_6.5`, `us_size_7.0`, `us_size_7.5`, `us_size_8.0`, `us_size_8.5`, `us_size_9.0`, `us_size_9.5`, `us_size_10.0`, `us_size_10.5`, `us_size_11.0`) VALUES
(1, 'Nike Air Force One', 'white', 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15),
(2, 'Nike Air Force One', 'black', 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15),
(3, 'Nike Air Force One', 'brown', 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
