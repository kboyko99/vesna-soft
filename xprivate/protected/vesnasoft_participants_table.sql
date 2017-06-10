-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Час створення: Кві 01 2017 р., 12:12
-- Версія сервера: 5.6.28-0ubuntu0.14.04.1
-- Версія PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База даних: `cool_portal`
--

-- --------------------------------------------------------

--
-- Структура таблиці `vesnasoft_participants`
--
CREATE TABLE IF NOT EXISTS `vesnasoft_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_surname` varchar(250) NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `project_title` varchar(200) DEFAULT NULL,
  `project_description` varchar(500) default null,
  `project_category` varchar(30) DEFAULT NULL,
  `hackathon_key` varchar(11) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `guest` varchar(10) DEFAULT NULL,
  `exhibition_product` varchar(200) DEFAULT NULL,
  `interactive_element` varchar(200) DEFAULT NULL,
  `exhibition_needs` varchar(500) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=215 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
INSERT INTO `vesnasoft_participants` (`id`, `name_surname`, `age`, `phone`, `email`, `project_title`, `project_description`, `project_category`, `hackathon_key`, `company`, `exhibition_product`, `interactive_element`, `exhibition_needs`, `created_at`) VALUES
(220,	'Годунко Максим',	30,	'0669742763',	'maksim_godunko@mail.ru',	NULL,	NULL,	NULL,	NULL,	'Школа робототехніки ЦНТУ (КНТУ) та гурток &quot;Робототехніка та 3Д моделювання&quot; гімназії ім.Т.Шевченка (шк№5)',	'моделі роботів та особливості функціонування гуртка робототехніки для школярів',	'0',	'два стола (парти), розетка і , по можливості, два снікерса з пляшкою коли:)',	'2017-04-06 12:21:40'),
(221,	'ірина іванова',	28,	'0952409572',	'konstantishka@bigmir.net',	NULL,	NULL,	NULL,	NULL,	'лдіоалдвіоа',	'вішоагвірала',	'0',	'аргвіаршгіврнп8шікгашщіовдалоі\nчай',	'2017-04-06 15:59:49'),
(222,	'Iзмайлов Михайло',	20,	'637831249',	'm-izmaylov@inbox.ru',	NULL,	NULL,	NULL,	'EJBKM',	NULL,	NULL,	NULL,	NULL,	'2017-04-06 21:56:35'),
(223,	'Карнаух Андрій',	20,	'988661190',	'assdwqe@gmail.com',	NULL,	NULL,	NULL,	'RoZbD',	NULL,	NULL,	NULL,	NULL,	'2017-04-06 22:21:35'),
(224,	'Никита Коряк',	19,	'955766680',	'bahyce@gmail.com',	NULL,	NULL,	NULL,	'123456',	NULL,	NULL,	NULL,	NULL,	'2017-04-07 16:01:49'),
(225,	'Карауш Дмитро',	14,	NULL,	'dkaraush@gmail.com',	NULL,	NULL,	NULL,	'123456',	NULL,	NULL,	NULL,	NULL,	'2017-04-07 20:38:04'),
(226,	'Баракаєв Ігор',	16,	'631909252',	'ibarakaiev@gmail.com',	NULL,	NULL,	NULL,	'123456',	NULL,	NULL,	NULL,	NULL,	'2017-04-07 20:38:04');
