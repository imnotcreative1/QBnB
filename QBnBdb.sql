-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2016 at 09:52 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qbnb`
--

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `id` int(4) NOT NULL,
  `period` int(4) NOT NULL,
  `address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`id`, `period`, `address`) VALUES
(27, 3, '24 University Ave'),
(28, 4, '56 University Avenue'),
(29, 5, '56 University Avenue'),
(30, 7, '56 University Avenue'),
(32, 34, '56 University Avenue'),
(33, 23, '35 Princess Street'),
(34, 33, '56 University Avenue'),
(35, 3, '24 University Ave'),
(36, 2, '35 Princess Street'),
(37, 77, '35 Princess Street'),
(38, 33, 'ta adress'),
(40, 3, '56 University Avenue'),
(41, 55, '35 Princess Street'),
(42, 4, '35 Princess Street'),
(43, 32, '35 Princess Street'),
(44, 33, '56 University Avenue'),
(45, 23, '24 University Ave'),
(47, 27, '411 Third Ave');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `ID` int(11) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `booking_status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`ID`, `email`, `booking_status`) VALUES
(27, 'idontgohere@outlook.com', 'REQUESTED'),
(29, 'idontgohere@outlook.com', 'CONFIRMED'),
(30, 'igohere@gmail.com', 'CANCELLED'),
(35, 'igohere@gmail.com', 'REJECTED'),
(38, 'taemail@ta.com', 'REQUESTED'),
(42, 'chageill@queensu.ca', 'REQUESTED'),
(43, 'chageill@queensu.ca', 'REJECTED'),
(45, 'wayne@gretzky.com', 'REQUESTED');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `email` varchar(254) NOT NULL,
  `address` varchar(50) NOT NULL,
  `property_rating` decimal(1,0) DEFAULT NULL,
  `comment` varchar(500) NOT NULL,
  `reply` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`email`, `address`, `property_rating`, `comment`, `reply`) VALUES
('chageill@queensu.ca', '24 University Ave', '1', 'Too loud', '');

-- --------------------------------------------------------

--
-- Table structure for table `degree`
--

CREATE TABLE `degree` (
  `degree_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `degree`
--

INSERT INTO `degree` (`degree_name`) VALUES
('BComm'),
('BEng'),
('BSc'),
('MSc'),
('PhD');

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE `district` (
  `district_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `district`
--

INSERT INTO `district` (`district_name`) VALUES
('Downtown'),
('Entertainment'),
('University');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `faculty_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`faculty_name`) VALUES
('Arts & Science'),
('Commerce'),
('Engineering & Applied Science'),
('Law');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `address` varchar(50) NOT NULL,
  `private_bath` tinyint(1) DEFAULT '0',
  `shared_bath` tinyint(1) DEFAULT '0',
  `close_to_subway` tinyint(1) DEFAULT '0',
  `pool` tinyint(1) DEFAULT '0',
  `full_kitchen` tinyint(1) DEFAULT '0',
  `laundry` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `email` varchar(254) NOT NULL,
  `password` varchar(50) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `phone_num` varchar(15) NOT NULL,
  `year` decimal(4,0) UNSIGNED NOT NULL,
  `degree_name` varchar(10) NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`email`, `password`, `admin`, `phone_num`, `year`, `degree_name`, `faculty_name`, `Name`) VALUES
('chageill@queensu.ca', 'password', 0, '456333121', '2015', 'BComm', 'Commerce', 'Commerce Student'),
('idontgohere@outlook.com', 'password', 0, '98765432', '1999', 'MSc', 'Law', 'Western Grad'),
('igohere@gmail.com', 'password', 0, '666666666', '1996', 'BSc', 'Arts & Science', 'Student 1'),
('iloveQueens@queensu.ca', 'password', 0, '6781237481', '2017', 'BEng', 'Law', 'Joe Smith'),
('matt@queensu.ca', 'pass', 1, '1234567', '2017', 'BEng', 'Engineering & Applied Science', 'Matt Sims'),
('patrick@queensu.ca', 'password', 1, '12344444', '2016', 'BEng', 'Engineering & Applied Science', 'Patrick Dang-Ho'),
('taemail@ta.com', 'pass', 0, '613', '2107', 'BSc', 'Arts & Science', 'TA'),
('wayne@gretzky.com', 'iamthebest', 0, '9999999', '1999', 'MSc', 'Law', 'Wayne Gretzky');

-- --------------------------------------------------------

--
-- Table structure for table `pointofinterest`
--

CREATE TABLE `pointofinterest` (
  `point_name` varchar(20) NOT NULL,
  `district_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pointofinterest`
--

INSERT INTO `pointofinterest` (`point_name`, `district_name`) VALUES
('Metro', 'Downtown'),
('Pizza Pizza', 'Downtown'),
('Stages', 'Entertainment'),
('Bain Lab', 'University'),
('ILC', 'University');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `address` varchar(50) NOT NULL,
  `email` varchar(254) NOT NULL,
  `price` decimal(9,2) UNSIGNED NOT NULL,
  `district_name` varchar(20) NOT NULL,
  `rooms` smallint(5) UNSIGNED NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`address`, `email`, `price`, `district_name`, `rooms`, `type`) VALUES
('24 University Ave', 'chageill@queensu.ca', '500.00', 'University', 5, 'flat'),
('35 Princess Street', 'wayne@gretzky.com', '99.00', 'Downtown', 15, 'house'),
('411 Third Ave', 'chageill@queensu.ca', '123.00', 'Entertainment', 23, 'flat'),
('56 University Avenue', 'idontgohere@outlook.com', '23.00', 'University', 2, 'apartment'),
('89 University', 'matt@queensu.ca', '55.00', 'University', 4, 'bedroom'),
('ta adress', 'taemail@ta.com', '5000.00', 'University', 50, 'apartment');

-- --------------------------------------------------------

--
-- Table structure for table `property_type`
--

CREATE TABLE `property_type` (
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `property_type`
--

INSERT INTO `property_type` (`type`) VALUES
('apartment'),
('bedroom'),
('flat'),
('house');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `property_rating` decimal(1,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`property_rating`) VALUES
('1'),
('2'),
('3'),
('4'),
('5');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `booking_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`booking_status`) VALUES
('CANCELLED'),
('CONFIRMED'),
('REJECTED'),
('REQUESTED');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `address` (`address`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `email` (`email`),
  ADD KEY `booking_status` (`booking_status`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`email`,`address`),
  ADD KEY `property_rating` (`property_rating`),
  ADD KEY `address` (`address`);

--
-- Indexes for table `degree`
--
ALTER TABLE `degree`
  ADD PRIMARY KEY (`degree_name`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
  ADD PRIMARY KEY (`district_name`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`faculty_name`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`address`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`email`),
  ADD KEY `faculty_name` (`faculty_name`),
  ADD KEY `degree_name` (`degree_name`);

--
-- Indexes for table `pointofinterest`
--
ALTER TABLE `pointofinterest`
  ADD PRIMARY KEY (`point_name`),
  ADD KEY `district_name` (`district_name`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`address`),
  ADD KEY `email` (`email`),
  ADD KEY `district_name` (`district_name`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `property_type`
--
ALTER TABLE `property_type`
  ADD PRIMARY KEY (`type`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`property_rating`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`booking_status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `aval_constraint` FOREIGN KEY (`address`) REFERENCES `property` (`address`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `availability` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`email`) REFERENCES `member` (`email`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`booking_status`) REFERENCES `status` (`booking_status`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`property_rating`) REFERENCES `rating` (`property_rating`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`email`) REFERENCES `member` (`email`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`address`) REFERENCES `property` (`address`);

--
-- Constraints for table `features`
--
ALTER TABLE `features`
  ADD CONSTRAINT `features_ibfk_1` FOREIGN KEY (`address`) REFERENCES `property` (`address`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`faculty_name`) REFERENCES `faculty` (`faculty_name`),
  ADD CONSTRAINT `member_ibfk_2` FOREIGN KEY (`degree_name`) REFERENCES `degree` (`degree_name`);

--
-- Constraints for table `pointofinterest`
--
ALTER TABLE `pointofinterest`
  ADD CONSTRAINT `pointofinterest_ibfk_1` FOREIGN KEY (`district_name`) REFERENCES `district` (`district_name`);

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`email`) REFERENCES `member` (`email`),
  ADD CONSTRAINT `property_ibfk_2` FOREIGN KEY (`district_name`) REFERENCES `district` (`district_name`),
  ADD CONSTRAINT `property_ibfk_3` FOREIGN KEY (`type`) REFERENCES `property_type` (`type`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
