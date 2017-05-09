-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: :3306
-- Generation Time: Jan 11, 2017 at 10:37 PM
-- Server version: 5.7.16-0ubuntu0.16.04.1
-- PHP Version: 5.6.28-1+deb.sury.org~xenial+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crewfac_production`
--

-- --------------------------------------------------------

--
-- Table structure for table `expedia_iternary`
--

CREATE TABLE IF NOT EXISTS `expedia_iternary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `iternary_id` varchar(50) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `hotel_name` varchar(200) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `no_rooms` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `expedia_iternary`
--

INSERT INTO `expedia_iternary` (`id`, `user_id`, `iternary_id`, `check_in_date`, `check_out_date`, `hotel_name`, `user_email`, `no_rooms`, `created_at`) VALUES
(2, 31, '277951391', '2017-01-11', '2017-01-15', 'Hyatt Regency Austin', 'bhavi@gmail.com', 1, 1484140007),
(4, 97, '277960462', '2017-01-11', '2017-01-12', 'Hotel Granduca Austin', 'rohanmashiyava@live.in', 2, 1484151580);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
