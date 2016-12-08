-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2016 at 01:15 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zoo_vrt`
--

-- --------------------------------------------------------

--
-- Table structure for table `adopter_exclusive_facts`
--

CREATE TABLE `adopter_exclusive_facts` (
  `animal_id` int(11) NOT NULL,
  `fact` text COLLATE utf8_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adopter_exclusive_photos`
--

CREATE TABLE `adopter_exclusive_photos` (
  `animal_id` int(11) NOT NULL,
  `photo_path` varchar(256) COLLATE utf8_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adopter_exclusive_videos`
--

CREATE TABLE `adopter_exclusive_videos` (
  `animal_id` int(11) NOT NULL,
  `video_path` varchar(256) COLLATE utf8_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `adoptions`
--

CREATE TABLE `adoptions` (
  `visitor_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `family_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `parent_order_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guard_assigned_animals`
--

CREATE TABLE `guard_assigned_animals` (
  `guard_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mammal_animals`
--

CREATE TABLE `mammal_animals` (
  `animal_id` int(11) NOT NULL,
  `species_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `age` int(11) NOT NULL,
  `sex` varchar(1) COLLATE utf8_croatian_ci NOT NULL,
  `birth_location` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `arrival_date` date NOT NULL,
  `photo_path` varchar(256) COLLATE utf8_croatian_ci NOT NULL,
  `interesting_facts` text COLLATE utf8_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `parent_class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `species`
--

CREATE TABLE `species` (
  `species_id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `family_id` int(11) NOT NULL,
  `size` text COLLATE utf8_croatian_ci,
  `nutrition` text COLLATE utf8_croatian_ci,
  `predators` text COLLATE utf8_croatian_ci,
  `lifetime` text COLLATE utf8_croatian_ci,
  `habitat` text COLLATE utf8_croatian_ci,
  `lifestyle` text COLLATE utf8_croatian_ci,
  `reproduction` text COLLATE utf8_croatian_ci,
  `distribution` text COLLATE utf8_croatian_ci,
  `location_x` int(11) NOT NULL,
  `location_y` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `password_hash` varchar(60) COLLATE utf8_croatian_ci NOT NULL,
  `first_last_name` varchar(64) COLLATE utf8_croatian_ci NOT NULL,
  `year_of_birth` int(11) NOT NULL,
  `city` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `email` varchar(32) COLLATE utf8_croatian_ci NOT NULL,
  `role` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `first_last_name`, `year_of_birth`, `city`, `email`, `role`) VALUES
(1, 'admin', '$2a$08$d/PQYIgWTi2tlmAmZoKy2O/vpCmSCtjNUHqwv0Y0EpEDGuLwUXY7W', 'Admin', 2017, 'Zagreb', 'admin@zoo-vrt.hr', 4);

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `visitor_id` int(11) NOT NULL,
  `species_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_croatian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adopter_exclusive_facts`
--
ALTER TABLE `adopter_exclusive_facts`
  ADD KEY `adopter_exclusive_facts_fk0` (`animal_id`);

--
-- Indexes for table `adopter_exclusive_photos`
--
ALTER TABLE `adopter_exclusive_photos`
  ADD KEY `adopter_exclusive_photos_fk0` (`animal_id`);

--
-- Indexes for table `adopter_exclusive_videos`
--
ALTER TABLE `adopter_exclusive_videos`
  ADD KEY `adopter_exclusive_videos_fk0` (`animal_id`);

--
-- Indexes for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD PRIMARY KEY (`visitor_id`,`animal_id`),
  ADD KEY `adoptions_fk1` (`animal_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`family_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `families_fk0` (`parent_order_id`);

--
-- Indexes for table `guard_assigned_animals`
--
ALTER TABLE `guard_assigned_animals`
  ADD PRIMARY KEY (`guard_id`,`animal_id`),
  ADD KEY `guard_assigned_animals_fk1` (`animal_id`);

--
-- Indexes for table `mammal_animals`
--
ALTER TABLE `mammal_animals`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `mammal_animals_fk0` (`species_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `orders_fk0` (`parent_class_id`);

--
-- Indexes for table `species`
--
ALTER TABLE `species`
  ADD PRIMARY KEY (`species_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `species_fk0` (`family_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`visitor_id`,`species_id`),
  ADD KEY `visits_fk1` (`species_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `mammal_animals`
--
ALTER TABLE `mammal_animals`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `species`
--
ALTER TABLE `species`
  MODIFY `species_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `adopter_exclusive_facts`
--
ALTER TABLE `adopter_exclusive_facts`
  ADD CONSTRAINT `adopter_exclusive_facts_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals` (`animal_id`);

--
-- Constraints for table `adopter_exclusive_photos`
--
ALTER TABLE `adopter_exclusive_photos`
  ADD CONSTRAINT `adopter_exclusive_photos_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals` (`animal_id`);

--
-- Constraints for table `adopter_exclusive_videos`
--
ALTER TABLE `adopter_exclusive_videos`
  ADD CONSTRAINT `adopter_exclusive_videos_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals` (`animal_id`);

--
-- Constraints for table `adoptions`
--
ALTER TABLE `adoptions`
  ADD CONSTRAINT `adoptions_fk0` FOREIGN KEY (`visitor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `adoptions_fk1` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals` (`animal_id`);

--
-- Constraints for table `families`
--
ALTER TABLE `families`
  ADD CONSTRAINT `families_fk0` FOREIGN KEY (`parent_order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `guard_assigned_animals`
--
ALTER TABLE `guard_assigned_animals`
  ADD CONSTRAINT `guard_assigned_animals_fk0` FOREIGN KEY (`guard_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `guard_assigned_animals_fk1` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals` (`animal_id`);

--
-- Constraints for table `mammal_animals`
--
ALTER TABLE `mammal_animals`
  ADD CONSTRAINT `mammal_animals_fk0` FOREIGN KEY (`species_id`) REFERENCES `species` (`species_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_fk0` FOREIGN KEY (`parent_class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `species`
--
ALTER TABLE `species`
  ADD CONSTRAINT `species_fk0` FOREIGN KEY (`family_id`) REFERENCES `families` (`family_id`);

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_fk0` FOREIGN KEY (`visitor_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `visits_fk1` FOREIGN KEY (`species_id`) REFERENCES `species` (`species_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;