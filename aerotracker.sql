-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 10, 2024 at 04:29 AM
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
-- Database: `aerotracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `baggage`
--

CREATE TABLE `baggage` (
  `baggage_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `baggage_type` enum('Carry-On','Checked','Excess') NOT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `baggage`
--

INSERT INTO `baggage` (`baggage_id`, `user_id`, `flight_id`, `baggage_type`, `weight`, `cost`) VALUES
(7, 32, 7, 'Carry-On', 20.00, 0.00),
(8, 33, 7, 'Excess', 55.00, 50.00),
(58, 81, 7, 'Checked', 33.00, 20.00),
(60, 83, 7, 'Carry-On', 56.00, 0.00),
(66, 89, 7, 'Excess', 75.00, 50.00),
(70, 95, 7, 'Carry-On', 34.00, 0.00),
(71, 96, 7, 'Excess', 11.00, 50.00),
(73, 99, 12, 'Excess', 22.00, 50.00),
(74, 100, 7, 'Excess', 34.00, 50.00),
(75, 101, 7, 'Carry-On', 1.00, 0.00),
(77, 103, 7, 'Carry-On', 11.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `flight_id`) VALUES
(16, 32, 7),
(67, 81, 7),
(69, 83, 7),
(75, 89, 7),
(79, 95, 7),
(80, 96, 7),
(82, 99, 12),
(83, 100, 7),
(84, 101, 7),
(86, 103, 7);

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `flight_id` int(11) NOT NULL,
  `departure_city` varchar(100) NOT NULL,
  `arrival_city` varchar(100) NOT NULL,
  `trip_type` enum('one-way','round-trip') NOT NULL,
  `departure_date` date NOT NULL,
  `arrival_date` date DEFAULT NULL,
  `num_passengers` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`flight_id`, `departure_city`, `arrival_city`, `trip_type`, `departure_date`, `arrival_date`, `num_passengers`, `booking_id`) VALUES
(7, 'Calgary', 'New York', 'one-way', '2024-12-01', NULL, 2, NULL),
(8, 'Toronto', 'Los Angeles', 'round-trip', '2024-12-15', '2024-12-22', 1, NULL),
(9, 'Vancouver', 'Chicago', 'round-trip', '2024-12-10', '2024-12-18', 3, NULL),
(10, 'New York', 'London', 'round-trip', '2024-12-15', '2024-12-22', 150, NULL),
(11, 'Los Angeles', 'Tokyo', 'round-trip', '2024-12-18', '2024-12-28', 180, NULL),
(12, 'Chicago', 'Paris', 'one-way', '2024-12-20', NULL, 120, NULL),
(13, 'San Francisco', 'Dubai', 'round-trip', '2024-12-25', '2025-01-05', 200, NULL),
(14, 'Miami', 'Toronto', 'one-way', '2024-12-17', NULL, 100, NULL),
(15, 'Dallas', 'Mexico City', 'round-trip', '2024-12-21', '2024-12-27', 140, NULL),
(16, 'Seattle', 'Sydney', 'round-trip', '2024-12-19', '2025-01-02', 170, NULL),
(17, 'Atlanta', 'Berlin', 'one-way', '2024-12-23', NULL, 130, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Seats`
--

CREATE TABLE `Seats` (
  `Seat_id` int(11) NOT NULL,
  `Flight_id` int(11) NOT NULL,
  `User_id` int(11) DEFAULT NULL,
  `Seat_class` enum('Economy','Business','First Class') NOT NULL,
  `Seat_status` enum('Available','Sold','Swapped') NOT NULL DEFAULT 'Available',
  `is_listed_for_sale` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Seats`
--

INSERT INTO `Seats` (`Seat_id`, `Flight_id`, `User_id`, `Seat_class`, `Seat_status`, `is_listed_for_sale`) VALUES
(2, 7, 81, 'Economy', 'Sold', 0),
(3, 7, 83, 'Economy', 'Sold', 0),
(4, 7, 84, 'Economy', 'Sold', 0),
(5, 7, NULL, 'Economy', 'Swapped', 1),
(6, 7, NULL, 'Economy', 'Swapped', 1),
(7, 7, NULL, 'Economy', 'Swapped', 1),
(8, 7, 103, 'Economy', 'Available', 1),
(19, 8, NULL, 'Economy', 'Available', 0),
(20, 12, 99, 'First Class', 'Sold', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ticket_requests`
--

CREATE TABLE `ticket_requests` (
  `request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `Seat_id` int(11) NOT NULL,
  `flight_id` int(11) NOT NULL,
  `action` enum('Sell','Swap') NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected','Completed') DEFAULT 'Pending',
  `requested_seat_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `listed_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket_requests`
--

INSERT INTO `ticket_requests` (`request_id`, `user_id`, `Seat_id`, `flight_id`, `action`, `price`, `status`, `requested_seat_id`, `created_at`, `updated_at`, `listed_date`) VALUES
(33, 81, 2, 7, 'Sell', 500.00, 'Pending', NULL, '2024-12-03 01:07:23', '2024-12-03 01:07:23', '2024-12-02 18:07:23'),
(41, 89, 6, 7, 'Sell', NULL, 'Completed', NULL, '2024-12-03 04:30:53', '2024-12-03 04:30:56', '2024-12-02 21:30:53'),
(46, 95, 7, 7, 'Sell', NULL, 'Completed', NULL, '2024-12-08 05:18:35', '2024-12-08 05:18:47', '2024-12-07 22:18:35'),
(47, 96, 7, 7, 'Swap', NULL, 'Completed', NULL, '2024-12-08 05:28:41', '2024-12-08 05:28:42', '2024-12-07 22:28:41'),
(49, 100, 8, 7, 'Sell', NULL, 'Completed', NULL, '2024-12-09 01:22:45', '2024-12-09 01:22:51', '2024-12-08 18:22:45'),
(50, 101, 8, 7, 'Swap', NULL, 'Completed', NULL, '2024-12-09 01:24:30', '2024-12-09 01:24:33', '2024-12-08 18:24:30'),
(51, 103, 8, 7, 'Sell', NULL, 'Completed', NULL, '2024-12-09 01:49:29', '2024-12-09 01:49:37', '2024-12-08 18:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `phone`) VALUES
(32, 'nathan', 'nathan@gmail.com', '$2y$10$cf1sv9ZBhlHRQUobCW7Gnes0p8wA5Y7ksUO.BhYvpp4NeP6EqIvZi', '403-256-2521'),
(33, 'canada', 'canada@gmail.com', '$2y$10$nYYipO0/NqvbVP8Y3DRhL.DSzelswpvVhSp.yLGSIIpUfqrEB/dam', '403-254-1451'),
(81, 'Ali Hassan', 'ali@gmail.com', '$2y$10$TGYkRGJrKAeabvCGOf74ie3qC4Viz9WilDYnvN9vTu.nY7naHNe52', '555-555-5555'),
(83, 'Draek', 'drake@gmail.com', '$2y$10$F2TWFZ.YquONF4/ejP4YbuBb.D1kygNS7MRuE28LsGDLDQj.tmksm', '555-555-5555'),
(84, 'Ski', 'ski@gmail.com', '$2y$10$zm2o.joupwuRk9eCfNTX0u0zZCY1dACBJHurhQGK1VUcLmLw0z1rC', '555-555-5555'),
(89, 'Muhmmad Yo', 'yousaf@gmail.com', '$2y$10$KxBW.Dwy.hgG1LgPmTupUuZbvl1nKsEoAUEWgQnxUBtRl4uVGpfUS', '222-222-2222'),
(95, 'Abdullah Yousaf', 'abdullahyousaf21@gmail.com', '$2y$10$iIFrE0YBRjoEsKHb.axt..NFuiNAWWX6iXECq1gxq6C62ecSeEbs.', '555-555-5555'),
(96, 'Shah Rukh Khan', 'shah@gmail.com', '$2y$10$77x8GDW5qSlQjc7Kx5aXWOIQALi1F990YV/e6ypfC.hDGfw6hj2xi', '555-555-5555'),
(99, 'Gigachaf', 'giga@gmail.com', '$2y$10$XU2CfapUISr.JsWRg4eoIuv2RA9IDZX8ivYsVNa2sKiljnCapS5ae', '111-111-1111'),
(100, 'Thor', 'thor@gmail.com', '$2y$10$vNfuAxezszezzISJcgleauSwQ3xDL4Sqep569eL0ZzqpEUeFGN47G', '333-333-3333'),
(101, 'thro', 'spiderman@gmail.com', '$2y$10$ZRNsFKrxP5/hqWd3HqvVOeHeYVzwP4Q06PZS5mpdRVebTZaQJXg2u', '123-123-1233'),
(103, 'John Doe', 'johndoe@gmail.com', '$2y$10$KlT2gOYTaFBpTJsrfqFiEuoRQiht0mHR1f.USmmqhfwIITcssLdW6', '333-333-3333');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baggage`
--
ALTER TABLE `baggage`
  ADD PRIMARY KEY (`baggage_id`),
  ADD KEY `baggage_ibfk_1` (`user_id`),
  ADD KEY `baggage_ibfk_2` (`flight_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `booking_ibfk_1` (`user_id`),
  ADD KEY `booking_ibfk_2` (`flight_id`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`flight_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `Seats`
--
ALTER TABLE `Seats`
  ADD PRIMARY KEY (`Seat_id`),
  ADD KEY `seats_ibfk_1` (`Flight_id`),
  ADD KEY `seats_ibfk_2` (`User_id`);

--
-- Indexes for table `ticket_requests`
--
ALTER TABLE `ticket_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `seat_id` (`Seat_id`),
  ADD KEY `ticket_requests_ibfk_1` (`user_id`),
  ADD KEY `ticket_requests_ibfk_3` (`flight_id`),
  ADD KEY `ticket_requests_ibfk_4` (`requested_seat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `baggage`
--
ALTER TABLE `baggage`
  MODIFY `baggage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `flights`
--
ALTER TABLE `flights`
  MODIFY `flight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Seats`
--
ALTER TABLE `Seats`
  MODIFY `Seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `ticket_requests`
--
ALTER TABLE `ticket_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `baggage`
--
ALTER TABLE `baggage`
  ADD CONSTRAINT `baggage_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `baggage_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`);

--
-- Constraints for table `Seats`
--
ALTER TABLE `Seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`Flight_id`) REFERENCES `Flights` (`flight_id`),
  ADD CONSTRAINT `seats_ibfk_2` FOREIGN KEY (`User_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `ticket_requests`
--
ALTER TABLE `ticket_requests`
  ADD CONSTRAINT `ticket_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `ticket_requests_ibfk_3` FOREIGN KEY (`flight_id`) REFERENCES `flights` (`flight_id`),
  ADD CONSTRAINT `ticket_requests_ibfk_4` FOREIGN KEY (`requested_seat_id`) REFERENCES `seats` (`Seat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
