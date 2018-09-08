# aizu-php-framework


-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) CHARACTER SET latin1 NOT NULL,
  `name` varchar(250) NOT NULL,
  `type` varchar(250) CHARACTER SET latin1 NOT NULL,
  `last_login` int(11) NOT NULL,
  `online_ping` int(11) DEFAULT '0',
  `online_browser` text CHARACTER SET latin1,
  `online_screen` text CHARACTER SET latin1,
  `online_screen_section` text CHARACTER SET latin1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
