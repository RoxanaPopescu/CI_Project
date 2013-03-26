-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 12, 2012 at 04:19 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dp`
--

-- --------------------------------------------------------

--
-- Table structure for table `admissions`
--

CREATE TABLE IF NOT EXISTS `admissions` (
  `adm_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `adm_name` varchar(255) NOT NULL,
  PRIMARY KEY (`adm_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admissions`
--

INSERT INTO `admissions` (`adm_id`, `adm_name`) VALUES
(1, 'Gallery admission injection/Carburetor');

-- --------------------------------------------------------

--
-- Table structure for table `bodies`
--

CREATE TABLE IF NOT EXISTS `bodies` (
  `body_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `body_name` varchar(255) NOT NULL,
  PRIMARY KEY (`body_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `bodies`
--

INSERT INTO `bodies` (`body_id`, `body_name`) VALUES
(1, 'Sedan'),
(2, 'Hatchback'),
(3, 'Coupe'),
(4, 'Cabriolet');

-- --------------------------------------------------------

--
-- Table structure for table `car_brands`
--

CREATE TABLE IF NOT EXISTS `car_brands` (
  `cbrand_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(255) NOT NULL,
  PRIMARY KEY (`cbrand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `car_brands`
--

INSERT INTO `car_brands` (`cbrand_id`, `brand_name`) VALUES
(1, 'Acura'),
(2, 'Alfa Romeo'),
(3, 'Ford'),
(4, 'Jaguar');

-- --------------------------------------------------------

--
-- Table structure for table `car_models`
--

CREATE TABLE IF NOT EXISTS `car_models` (
  `cmodel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(255) NOT NULL,
  `cbrand_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmodel_id`),
  KEY `cbrand_id` (`cbrand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `car_models`
--

INSERT INTO `car_models` (`cmodel_id`, `model_name`, `cbrand_id`) VALUES
(3, 'INTEGRA coupe', 1);

-- --------------------------------------------------------

--
-- Table structure for table `car_types`
--

CREATE TABLE IF NOT EXISTS `car_types` (
  `ctype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL,
  `engine_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '-',
  `capacity_cm3` int(10) unsigned NOT NULL,
  `kw` int(10) unsigned NOT NULL,
  `hp` int(10) unsigned NOT NULL,
  `cylinders` int(10) unsigned NOT NULL,
  `fabrication_year_start` varchar(150) NOT NULL,
  `fabrication_year_end` varchar(150) NOT NULL DEFAULT 'present',
  `cmodel_id` int(10) unsigned NOT NULL,
  `fuel_id` int(10) unsigned NOT NULL,
  `tr_id` int(10) unsigned NOT NULL,
  `body_id` int(10) unsigned NOT NULL,
  `adm_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ctype_id`),
  KEY `fuel_id` (`fuel_id`),
  KEY `tr_id` (`tr_id`),
  KEY `body_id` (`body_id`),
  KEY `adm_id` (`adm_id`),
  KEY `cmodel_id` (`cmodel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `car_types`
--

INSERT INTO `car_types` (`ctype_id`, `type_name`, `engine_code`, `capacity_cm3`, `kw`, `hp`, `cylinders`, `fabrication_year_start`, `fabrication_year_end`, `cmodel_id`, `fuel_id`, `tr_id`, `body_id`, `adm_id`) VALUES
(1, '1.5', 'D15A1', 1488, 63, 85, 4, '1960', '1988', 3, 1, 1, 3, 1),
(2, '1.6', '-', 1590, 74, 100, 4, '1985', '1990', 3, 1, 1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `categ_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categ_name` varchar(255) NOT NULL,
  `is_part` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`categ_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categ_id`, `categ_name`, `is_part`) VALUES
(1, 'Filters', 1),
(2, 'Suspension', 1),
(3, 'Car Oil', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('6ba3450eeb480356b84b03e1cda60981', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:13.0) Gecko/20100101 Firefox/13.0', 1339432430, 'a:6:{s:9:"user_data";s:0:"";s:7:"user_id";s:1:"2";s:10:"first_name";s:5:"Admin";s:10:"user_level";s:2:"70";s:12:"is_logged_in";b:1;s:11:"car_type_id";i:1;}');

-- --------------------------------------------------------

--
-- Table structure for table `ct_p_n_d`
--

CREATE TABLE IF NOT EXISTS `ct_p_n_d` (
  `final_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `available_qty` int(10) unsigned NOT NULL DEFAULT '0',
  `product_image` varchar(255) NOT NULL DEFAULT 'default_img.png',
  `product_price` float unsigned NOT NULL,
  `product_status` varchar(150) NOT NULL DEFAULT 'in stock',
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pp_code` int(10) unsigned NOT NULL,
  `ctype_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`final_id`),
  KEY `pp_code` (`pp_code`),
  KEY `ctype_id` (`ctype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ct_p_n_d`
--

INSERT INTO `ct_p_n_d` (`final_id`, `available_qty`, `product_image`, `product_price`, `product_status`, `added_date`, `pp_code`, `ctype_id`) VALUES
(1, 11, 'default_img.png', 25.5, 'in stock', '2012-04-05 11:15:47', 2, 1),
(2, 30, 'default_img.png', 250, 'in stock', '2012-04-05 11:15:47', 3, 1),
(3, 0, 'default_img.png', 13.8, 'out of stock', '2012-04-05 11:22:41', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE IF NOT EXISTS `details` (
  `detail_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `detail_name` varchar(255) NOT NULL,
  PRIMARY KEY (`detail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`detail_id`, `detail_name`) VALUES
(1, 'height'),
(2, 'width'),
(3, 'inner diameter'),
(4, 'outer diameter'),
(5, 'angle'),
(6, 'Weight'),
(9, 'Inner Corner');

-- --------------------------------------------------------

--
-- Table structure for table `fuel`
--

CREATE TABLE IF NOT EXISTS `fuel` (
  `fuel_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fuel_name` varchar(255) NOT NULL,
  PRIMARY KEY (`fuel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fuel`
--

INSERT INTO `fuel` (`fuel_id`, `fuel_name`) VALUES
(1, 'gas'),
(2, 'diesel');

-- --------------------------------------------------------

--
-- Table structure for table `name_desc`
--

CREATE TABLE IF NOT EXISTS `name_desc` (
  `product_code` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pn_id` int(10) unsigned NOT NULL,
  `pd_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`product_code`),
  KEY `pn_id` (`pn_id`),
  KEY `pd_id` (`pd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `name_desc`
--

INSERT INTO `name_desc` (`product_code`, `pn_id`, `pd_id`) VALUES
(1, 1, 2),
(2, 3, 3),
(3, 1, 4),
(4, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `total_price` float unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `total_price`, `date`, `user_id`) VALUES
(2, 301, '2012-04-27 22:19:21', 1),
(3, 565.98, '2012-04-28 20:17:08', 1),
(4, 1250, '2012-04-28 20:31:42', 1),
(5, 410.92, '2012-04-29 00:17:45', 1),
(6, 352, '2012-04-29 00:28:44', 3),
(7, 25.5, '2012-05-13 14:54:46', 3),
(8, 229.5, '2012-05-14 12:11:14', 3),
(9, 3000, '2012-05-15 11:18:33', 1),
(10, 500, '2012-05-25 23:25:26', 1),
(11, 76.5, '2012-05-31 18:30:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_part`
--

CREATE TABLE IF NOT EXISTS `order_part` (
  `order_id` int(10) unsigned NOT NULL,
  `final_id` int(10) unsigned NOT NULL,
  `qty_bought` float unsigned NOT NULL,
  `item_price` float unsigned NOT NULL,
  `total_price` float unsigned NOT NULL,
  KEY `order_id` (`order_id`),
  KEY `final_id` (`final_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_part`
--

INSERT INTO `order_part` (`order_id`, `final_id`, `qty_bought`, `item_price`, `total_price`) VALUES
(2, 1, 2, 25.5, 51),
(2, 2, 1, 250, 250),
(3, 2, 1, 250, 250),
(4, 2, 5, 250, 1250),
(5, 1, 1, 25.5, 25.5),
(5, 2, 1, 250, 250),
(6, 1, 4, 25.5, 102),
(6, 2, 1, 250, 250),
(7, 1, 1, 25.5, 25.5),
(8, 1, 9, 25.5, 229.5),
(9, 2, 12, 250, 3000),
(10, 2, 2, 250, 500),
(11, 1, 3, 25.5, 76.5);

-- --------------------------------------------------------

--
-- Table structure for table `order_universal`
--

CREATE TABLE IF NOT EXISTS `order_universal` (
  `order_id` int(10) unsigned NOT NULL,
  `univ_id` int(10) unsigned NOT NULL,
  `qty_bought` float unsigned NOT NULL,
  `item_price` float unsigned NOT NULL,
  `total_price` float unsigned NOT NULL,
  KEY `order_id` (`order_id`),
  KEY `univ_id` (`univ_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_universal`
--

INSERT INTO `order_universal` (`order_id`, `univ_id`, `qty_bought`, `item_price`, `total_price`) VALUES
(3, 1, 14, 22.57, 315.98),
(5, 1, 6, 22.57, 135.42);

-- --------------------------------------------------------

--
-- Table structure for table `pn_details`
--

CREATE TABLE IF NOT EXISTS `pn_details` (
  `pn_id` int(10) unsigned NOT NULL,
  `detail_id` int(10) unsigned NOT NULL,
  KEY `pn_id` (`pn_id`),
  KEY `detail_id` (`detail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pn_details`
--

INSERT INTO `pn_details` (`pn_id`, `detail_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 6),
(1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `prodr_name_desc`
--

CREATE TABLE IF NOT EXISTS `prodr_name_desc` (
  `pp_code` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producer_id` int(10) unsigned NOT NULL,
  `product_code` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pp_code`),
  KEY `producer_id` (`producer_id`),
  KEY `product_code` (`product_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `prodr_name_desc`
--

INSERT INTO `prodr_name_desc` (`pp_code`, `producer_id`, `product_code`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 2),
(5, 3, 3),
(6, 3, 4);

-- --------------------------------------------------------

--
-- Table structure for table `producers`
--

CREATE TABLE IF NOT EXISTS `producers` (
  `producer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producer_name` varchar(255) NOT NULL,
  PRIMARY KEY (`producer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `producers`
--

INSERT INTO `producers` (`producer_id`, `producer_name`) VALUES
(1, 'VIC'),
(2, 'CROSLAND'),
(3, 'Ashuki'),
(4, 'Castrol');

-- --------------------------------------------------------

--
-- Table structure for table `product_descriptions`
--

CREATE TABLE IF NOT EXISTS `product_descriptions` (
  `pd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pd_desc` text NOT NULL,
  PRIMARY KEY (`pd_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `product_descriptions`
--

INSERT INTO `product_descriptions` (`pd_id`, `pd_desc`) VALUES
(1, 'Height: 2mm|Width: 6mm'),
(2, 'Height: 57mm|Inner diameter: 165mm|Outer diameter: 212mm|'),
(3, 'Ulei mineral aditivat pentru extrema presiune cu utilizari multiple, destinat lubrifierii diferentialelor hypoidale si transmisiilor manuale la care fabricantul recomanda utilizarea unui ulei cu nivelul de performanta GL-5.\r\n\r\nPoate fi utilizat in conditii de temperaturi extreme, asigurand selectari usoare ale treptei de viteza in conditii de temperaturi scazute.'),
(4, 'Inner diameter: 80mm|Outer diameter: 212mm|Width: 50cm|'),
(5, 'Height: 57mm|Inner diameter: 165mm|Outer diameter: 212mm|Inner Corner|');

-- --------------------------------------------------------

--
-- Table structure for table `product_names`
--

CREATE TABLE IF NOT EXISTS `product_names` (
  `pn_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pn_name` varchar(255) NOT NULL,
  `categ_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pn_id`),
  KEY `categ_id` (`categ_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `product_names`
--

INSERT INTO `product_names` (`pn_id`, `pn_name`, `categ_id`) VALUES
(1, 'Air Filter', 1),
(2, 'Damper', 2),
(3, 'Castrol EPX 80W-90 (1L)', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tractions`
--

CREATE TABLE IF NOT EXISTS `tractions` (
  `tr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tr_name` varchar(255) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tractions`
--

INSERT INTO `tractions` (`tr_id`, `tr_name`) VALUES
(1, 'front traction'),
(2, 'back traction'),
(3, 'total traction');

-- --------------------------------------------------------

--
-- Table structure for table `universal_products`
--

CREATE TABLE IF NOT EXISTS `universal_products` (
  `univ_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `available_qty` int(10) unsigned NOT NULL DEFAULT '0',
  `product_image` varchar(255) NOT NULL DEFAULT 'default_img.png',
  `product_price` float unsigned NOT NULL,
  `product_status` varchar(150) NOT NULL DEFAULT 'in stock',
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pp_code` int(10) unsigned NOT NULL,
  PRIMARY KEY (`univ_id`),
  KEY `pp_code` (`pp_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `universal_products`
--

INSERT INTO `universal_products` (`univ_id`, `available_qty`, `product_image`, `product_price`, `product_status`, `added_date`, `pp_code`) VALUES
(1, 14, 'default_img.png', 22.57, 'in stock', '2012-04-24 12:49:47', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `user_level` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `address`, `user_level`) VALUES
(1, 'Peter', 'Pettigrew', 'peter.p@gmail.com', '336898', '25be62d9c279a4cb3f5779e34c30e105e1d2f582', 'T. Vladimirescu, 89', 1),
(2, 'Admin', 'Admin', 'admin@dp.com', '6693124', '7350f9b8932a44bab58622daa8c06ac744ac98e5', 'Str. Mizil, nr. 12', 70),
(3, 'Roxana', 'Popescu', 'rox.pop@gmail.com', '40125897', '4d8490db37ea257fdc47adfd0c4ad5489b57a2e2', 'Bla Bla nr 12', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `car_models`
--
ALTER TABLE `car_models`
  ADD CONSTRAINT `car_models_ibfk_1` FOREIGN KEY (`cbrand_id`) REFERENCES `car_brands` (`cbrand_id`) ON DELETE CASCADE;

--
-- Constraints for table `car_types`
--
ALTER TABLE `car_types`
  ADD CONSTRAINT `car_types_ibfk_1` FOREIGN KEY (`fuel_id`) REFERENCES `fuel` (`fuel_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_types_ibfk_2` FOREIGN KEY (`tr_id`) REFERENCES `tractions` (`tr_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_types_ibfk_3` FOREIGN KEY (`body_id`) REFERENCES `bodies` (`body_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_types_ibfk_4` FOREIGN KEY (`adm_id`) REFERENCES `admissions` (`adm_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `car_types_ibfk_5` FOREIGN KEY (`cmodel_id`) REFERENCES `car_models` (`cmodel_id`) ON DELETE CASCADE;

--
-- Constraints for table `ct_p_n_d`
--
ALTER TABLE `ct_p_n_d`
  ADD CONSTRAINT `ct_p_n_d_ibfk_1` FOREIGN KEY (`pp_code`) REFERENCES `prodr_name_desc` (`pp_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `ct_p_n_d_ibfk_2` FOREIGN KEY (`ctype_id`) REFERENCES `car_types` (`ctype_id`) ON DELETE CASCADE;

--
-- Constraints for table `name_desc`
--
ALTER TABLE `name_desc`
  ADD CONSTRAINT `name_desc_ibfk_1` FOREIGN KEY (`pn_id`) REFERENCES `product_names` (`pn_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `name_desc_ibfk_2` FOREIGN KEY (`pd_id`) REFERENCES `product_descriptions` (`pd_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_part`
--
ALTER TABLE `order_part`
  ADD CONSTRAINT `order_part_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_part_ibfk_2` FOREIGN KEY (`final_id`) REFERENCES `ct_p_n_d` (`final_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_universal`
--
ALTER TABLE `order_universal`
  ADD CONSTRAINT `order_universal_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_universal_ibfk_4` FOREIGN KEY (`univ_id`) REFERENCES `universal_products` (`univ_id`) ON DELETE CASCADE;

--
-- Constraints for table `pn_details`
--
ALTER TABLE `pn_details`
  ADD CONSTRAINT `pn_details_ibfk_1` FOREIGN KEY (`pn_id`) REFERENCES `product_names` (`pn_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pn_details_ibfk_2` FOREIGN KEY (`detail_id`) REFERENCES `details` (`detail_id`) ON DELETE CASCADE;

--
-- Constraints for table `prodr_name_desc`
--
ALTER TABLE `prodr_name_desc`
  ADD CONSTRAINT `prodr_name_desc_ibfk_1` FOREIGN KEY (`producer_id`) REFERENCES `producers` (`producer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prodr_name_desc_ibfk_2` FOREIGN KEY (`product_code`) REFERENCES `name_desc` (`product_code`) ON DELETE CASCADE;

--
-- Constraints for table `product_names`
--
ALTER TABLE `product_names`
  ADD CONSTRAINT `product_names_ibfk_1` FOREIGN KEY (`categ_id`) REFERENCES `categories` (`categ_id`) ON DELETE CASCADE;

--
-- Constraints for table `universal_products`
--
ALTER TABLE `universal_products`
  ADD CONSTRAINT `universal_products_ibfk_1` FOREIGN KEY (`pp_code`) REFERENCES `prodr_name_desc` (`pp_code`) ON DELETE CASCADE;
