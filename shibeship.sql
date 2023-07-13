-- MySQL 5.5.68-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) NOT NULL DEFAULT 'en',
  `id_cat` bigint(20) DEFAULT NULL,
  `id_prod` bigint(20) DEFAULT NULL,
  `id_page` bigint(20) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ord` bigint(20) NOT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) DEFAULT 'en',
  `icon` varchar(50) DEFAULT 'ellipsis-v',
  `id_cat` bigint(20) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `text` longtext,
  `img` varchar(255) DEFAULT NULL,
  `ord` int(1) DEFAULT NULL,
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `fiat`;
CREATE TABLE `fiat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(5) DEFAULT NULL,
  `value` decimal(10,8) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `fiat` (`id`, `currency`, `value`, `date`) VALUES
(1,	'USD',	0.06633000,	'2023-07-13 16:41:30'),
(2,	'EUR',	0.05923200,	'2023-07-13 16:41:30'),
(3,	'GBP',	0.05061000,	'2023-07-13 16:41:30'),
(4,	'AUD',	0.09643700,	'2023-07-13 16:41:30'),
(5,	'BRL',	0.31934000,	'2023-07-13 16:41:30'),
(6,	'CNY',	0.47414600,	'2023-07-13 16:41:30'),
(7,	'JPY',	9.16000000,	'2023-07-13 16:41:30'),
(8,	'CAD',	0.08705000,	'2023-07-13 16:41:30');

DROP TABLE IF EXISTS `generated`;
CREATE TABLE `generated` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_product` bigint(20) DEFAULT NULL,
  `doge_public` varchar(255) DEFAULT NULL,
  `doge_private` varchar(255) DEFAULT NULL,
  `amount` decimal(20,8) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `doge_address` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `postal_code` varchar(255) DEFAULT '',
  `country` varchar(255) DEFAULT '',
  `city` varchar(255) DEFAULT '',
  `phone` varchar(255) DEFAULT '',
  `paid` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) NOT NULL DEFAULT 'en',
  `id_page` bigint(20) NOT NULL DEFAULT '0',
  `type` int(10) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `text` longtext,
  `ord` bigint(20) DEFAULT '0',
  `active` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_shibe` bigint(20) NOT NULL,
  `doge_pub` varchar(255) DEFAULT NULL,
  `doge_prv` varchar(255) DEFAULT NULL,
  `id_cat` bigint(20) NOT NULL,
  `cat_tax` varchar(255) DEFAULT '0',
  `doge` decimal(20,8) DEFAULT NULL,
  `fiat` decimal(20,8) DEFAULT NULL,
  `moon_new` decimal(10,2) DEFAULT '0.00',
  `moon_full` decimal(10,2) DEFAULT '0.00',
  `qty` bigint(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `title` varchar(255) DEFAULT NULL,
  `text` longtext,
  `imgs` longtext,
  `highlighted` tinyint(1) DEFAULT '0',
  `ord` bigint(20) DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '1',
  `date` datetime DEFAULT NULL,
  `shipto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `shibes`;
CREATE TABLE `shibes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tax_id` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `doge_address` varchar(255) NOT NULL,
  `active` int(1) NOT NULL,
  `date` datetime NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `public_info` tinyint(1) DEFAULT '0',
  `text` text,
  `twitter` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2023-07-13 17:16:04