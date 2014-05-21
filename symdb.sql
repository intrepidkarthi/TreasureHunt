-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2011 at 11:46 AM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `symdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `ans`
--

CREATE TABLE IF NOT EXISTS `ans` (
  `qid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(80) NOT NULL DEFAULT '',
  `level` smallint(6) NOT NULL,
  `answ` varchar(30) NOT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logger`
--

CREATE TABLE IF NOT EXISTS `logger` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) unsigned NOT NULL,
  `ip_addr` varchar(16) NOT NULL,
  `ref_url` varchar(80) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ref_detail` varchar(100) NOT NULL,
  `login_out` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  KEY `emp_id` (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `emp_id` int(11) unsigned NOT NULL,
  `emp_name` varchar(100) NOT NULL,
  `emp_pwd` varchar(32) NOT NULL,
  `emp_email` varchar(100) NOT NULL,
  `emp_level` int(2) unsigned NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`emp_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logger`
--
ALTER TABLE `logger`
  ADD CONSTRAINT `logger_ibfk_1` FOREIGN KEY (`emp_id`) REFERENCES `people` (`emp_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
