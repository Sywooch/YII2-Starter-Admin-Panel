-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: :3306
-- Generation Time: May 10, 2017 at 05:07 PM
-- Server version: 5.7.18-0ubuntu0.16.04.1
-- PHP Version: 5.6.30-10+deb.sury.org~xenial+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `default_table`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_id` int(11) NOT NULL,
  `action_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller_id` (`controller_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`id`, `controller_id`, `action_name`, `slug`) VALUES
(1, 1, 'index', 'Index'),
(2, 1, 'image-crop-to-file', 'Image Crop To File'),
(3, 1, 'image-save-to-file', 'Image Save To File'),
(4, 2, 'index', 'Index'),
(5, 3, 'index', 'Index'),
(6, 3, 'add-user', 'Add User'),
(7, 3, 'edit-user', 'Edit User'),
(8, 3, 'view-user', 'View User'),
(9, 3, 'change-user-password', 'Change User Password'),
(10, 3, 'status-user', 'Status User'),
(11, 3, 'add-role', 'Add Role'),
(12, 3, 'edit-role', 'Edit Role'),
(13, 3, 'roles', 'Roles'),
(14, 3, 'status-role', 'Status Role'),
(15, 3, 'menus', 'Menus'),
(16, 3, 'add-menu', 'Add Menu'),
(17, 3, 'edit-menu', 'Edit Menu'),
(18, 3, 'set-action', 'Set Action'),
(19, 3, 'down', 'Down'),
(20, 3, 'delete-menu', 'Delete Menu'),
(21, 3, 'controllers', 'Controllers'),
(22, 3, 'add-controller', 'Add Controller'),
(23, 3, 'edit-controller', 'Edit Controller'),
(24, 3, 'delete-controller', 'Delete Controller'),
(25, 3, 'actions', 'Actions'),
(26, 3, 'add-action', 'Add Action'),
(27, 3, 'edit-action', 'Edit Action'),
(28, 3, 'delete-action', 'Delete Action'),
(29, 3, 'rights', 'Rights'),
(30, 4, 'error', 'Error'),
(31, 4, 'login', 'Login'),
(32, 4, 'forgot-password', 'Forgot Password'),
(33, 4, 'lock', 'Lock'),
(34, 4, 'logout', 'Logout'),
(35, 4, 'reset', 'Reset'),
(36, 4, 'get-controllers-and-actions-sync', 'Get Controllers And Actions Sync'),
(37, 5, 'register', 'Register'),
(38, 5, 'profile', 'Profile'),
(39, 5, 'edit-profile', 'Edit Profile'),
(40, 5, 'change-password', 'Change Password'),
(41, 5, 'toggle-status', 'Toggle Status');

-- --------------------------------------------------------

--
-- Table structure for table `app_user_profile`
--

CREATE TABLE IF NOT EXISTS `app_user_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1-male,2-female,0-not specified',
  `phone_no` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `is_app_user` tinyint(1) NOT NULL COMMENT '1-App User, 0 - Not App User',
  `status` tinyint(1) NOT NULL COMMENT '1-Active,0-Inactive',
  `is_deleted` tinyint(1) NOT NULL COMMENT '1-deleted ,0-Not deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_date` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `profile_media_id` (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Profile Table' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE IF NOT EXISTS `controllers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`id`, `controller_name`, `slug`) VALUES
(1, 'cropfile', 'Cropfile'),
(2, 'dashboard', 'Dashboard'),
(3, 'setting', 'Setting'),
(4, 'site', 'Site'),
(5, 'user', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_widgets`
--

CREATE TABLE IF NOT EXISTS `dashboard_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget-name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_platform` enum('web','ios','android') NOT NULL,
  `device_token` varchar(100) NOT NULL,
  `device_unique_id` varchar(100) NOT NULL,
  `is_login` tinyint(1) NOT NULL COMMENT '1- login, 0-logout',
  `access_token` varchar(32) DEFAULT NULL,
  `login_time` int(11) NOT NULL,
  `os` varchar(100) NOT NULL,
  `device_model` varchar(100) NOT NULL,
  `created_date` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `app_consumer_user_id` (`id`),
  KEY `user_id` (`id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `file_url` text NOT NULL,
  `file_path` text NOT NULL,
  `original_name` varchar(100) NOT NULL,
  `staus` tinyint(1) NOT NULL COMMENT '1-active, 0-inactive',
  `is_deleted` tinyint(1) NOT NULL COMMENT '1-deleted 0-not deleted',
  `created_by` int(11) NOT NULL,
  `created_date` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller_id` (`controller_id`,`action_id`),
  KEY `action_id` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `controller_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `controller_id` (`controller_id`,`action_id`),
  KEY `role_id` (`role_id`),
  KEY `action_id` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`, `status`, `is_deleted`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 'Super-Admin', 1, 0, 1464386360, 1494334837, 1, 1),
(2, 'admin', 1, 0, 1494333730, 1494393585, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_dashboard_widget`
--

CREATE TABLE IF NOT EXISTS `role_dashboard_widget` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dashboard_widget_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_widget_id` (`dashboard_widget_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `social`
--

CREATE TABLE IF NOT EXISTS `social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `social_media_platform` enum('facebook','instagram','twitter','gplus') NOT NULL,
  `access_token` text,
  `token_expiry` int(11) DEFAULT NULL,
  `socialmedia_id` varchar(100) NOT NULL,
  `is_connected` tinyint(1) NOT NULL COMMENT '1-connected,0-disconnected',
  `token_updated` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_consumer_user_id` (`id`),
  KEY `user_id` (`id`),
  KEY `user_id_2` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_id` int(11) NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `screenlock` tinyint(1) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role_id`),
  KEY `profile_id` (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actions`
--
ALTER TABLE `actions`
  ADD CONSTRAINT `actions_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `app_user_profile`
--
ALTER TABLE `app_user_profile`
  ADD CONSTRAINT `app_user_profile_ibfk_1` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rights`
--
ALTER TABLE `rights`
  ADD CONSTRAINT `rights_ibfk_1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `rights_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `rights_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `role_dashboard_widget`
--
ALTER TABLE `role_dashboard_widget`
  ADD CONSTRAINT `role_dashboard_widget_ibfk_1` FOREIGN KEY (`dashboard_widget_id`) REFERENCES `dashboard_widgets` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `role_dashboard_widget_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `social`
--
ALTER TABLE `social`
  ADD CONSTRAINT `social_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `app_user_profile` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
