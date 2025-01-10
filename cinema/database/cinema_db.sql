-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2025 at 07:54 AM
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
-- Database: `cinema_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cinemas`
--

CREATE TABLE `cinemas` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `ticket_list` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `tickets_booked` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `schedule` datetime DEFAULT NULL,
  `price` int(10) NOT NULL DEFAULT 0,
  `image_url` varchar(255) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `films`
--

INSERT INTO `films` (`id`, `title`, `duration`, `rating`, `schedule`, `price`, `image_url`, `description`) VALUES
(8, 'G.I. Joe: The Rise of Cobra (2009)', 118, 3.00, '2025-01-13 14:17:00', 35000, 'uploads/gijoe.jpg', 'G.I. Joe: The Rise of Cobra adalah sebuah film laga fiksi ilmiah militer Amerika Serikat tahun 2009 berdasarkan pada waralaba mainan buatan Hasbro, dengan inspirasi dari seri buku komik dan seri kartun G.I. Joe: A Real American Hero.'),
(9, 'Furious 7', 137, 3.80, '2025-01-10 14:23:00', 44000, 'uploads/ff7.jpg', 'Furious 7 (juga dikenal sebagai Fast & Furious 7) adalah sebuah film aksi tahun 2015 yang disutradarai oleh James Wan dan ditulis oleh Chris Morgan. Ini adalah sekuel dari Fast & Furious 6 (2013) dan The Fast and the Furious: Tokyo Drift (2006) dan angsuran ketujuh dalam waralaba Fast & Furious.'),
(10, 'The Fate of the Furious', 136, 3.30, '2025-01-12 14:26:00', 45000, 'uploads/ff8.jpg', 'The Fate of the Furious (juga dikenal sebagai F8 atau Fast & Furious 8) adalah sebuah film aksi tahun 2017 yang disutradarai oleh F. Gary Gray dan ditulis oleh Chris Morgan. Ini adalah sekuel dari Furious 7 (2015) dan angsuran kedelapan dalam waralaba Fast & Furious.'),
(11, 'Mile 22', 94, 4.00, '2025-01-12 19:29:00', 30000, 'uploads/mile22.jpg', 'Mile 22 adalah sebuah film thriller aksi spionase Amerika Serikat tahun 2018 yang disutradarai oleh Peter Berg dan ditulis oleh Lea Carpenter, dari sebuah cerita karya Carpenter dan Graham Roland. [5] Film ini dibintangi oleh Mark Wahlberg, Iko Uwais, John Malkovich, Lauren Cohan, dan Ronda Rousey.'),
(12, 'The Mummy (1999)', 124, 3.50, '2025-01-10 17:35:00', 50000, 'uploads/themummy.jpg', 'The Mummy adalah sebuah film aksi-petualangan Amerika Serikat tahun 1999 yang ditulis dan disutradarai oleh Stephen Sommers, dibintangi oleh Brendan Fraser, Rachel Weisz, John Hannah, dan Arnold Vosloo dalam peran utama sebagai mumi yang dihidupkan kembali.'),
(13, 'Tomb Raider', 118, 3.80, '2025-01-14 19:40:00', 43000, 'uploads/tombraider.jpeg', 'Tomb Raider, yang dikenal sebagai Lara Croft: Tomb Raider dari tahun 2001 hingga 2008, adalah waralaba media yang berasal dari seri video game aksi-petualangan yang dibuat oleh pengembang video game Inggris Core Design. Waralaba saat ini dimiliki oleh CDE Entertainment.');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `seat` varchar(10) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `film_id` int(11) DEFAULT NULL,
  `seat` varchar(10) DEFAULT NULL,
  `status` enum('sold','available','paid') DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_number`, `film_id`, `seat`, `status`, `customer_id`, `name`, `payment_method`) VALUES
(18, NULL, 12, 'A1', 'sold', 8, NULL, NULL),
(20, NULL, 9, 'B3', 'paid', 8, 'alisya', 'credit_card');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `image_user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `image_user`, `email`, `phone`) VALUES
(7, 'admin', '$2y$10$7VrL7XHvpvxDd3Duwf97O.SspTJ0MEVvf3OwzB/bNCqB.bPTpEJpK', 'admin', 'uploads/6780b7f7c169c-admin_cinema.png', 'admin@gmail.com', 2147483647),
(8, 'user', '$2y$10$rqP/fJCN/2j23/2Wuaz48e1MLS.VBQ5cIjuwXq4Zq9jS6mjldq7He', 'customer', 'uploads/6780b799014ac-user_cinema.png', 'user@gmail.com', 2147483647);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_id` (`film_id`),
  ADD KEY `fk_tickets_customers` (`customer_id`);

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
-- AUTO_INCREMENT for table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`);

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_tickets_customers` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_tickets_films` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
