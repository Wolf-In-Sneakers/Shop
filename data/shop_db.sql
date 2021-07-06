-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 07 2021 г., 02:29
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `shop_db`
--
CREATE DATABASE IF NOT EXISTS `shop_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `shop_db`;

-- --------------------------------------------------------

--
-- Структура таблицы `access`
--

CREATE TABLE `access` (
  `id_access` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `access`
--

INSERT INTO `access` (`id_access`, `name`, `created`, `modified`) VALUES
(1, 'admin', '2021-03-28 16:33:02', '2021-03-28 16:33:02'),
(2, 'user', '2021-03-28 16:33:02', '2021-03-28 16:33:02');

-- --------------------------------------------------------

--
-- Структура таблицы `basket`
--

CREATE TABLE `basket` (
  `id_user` int NOT NULL,
  `id_product` int NOT NULL,
  `quantity` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `basket`
--

INSERT INTO `basket` (`id_user`, `id_product`, `quantity`, `created`, `modified`) VALUES
(2, 2, 10, '2021-06-09 18:22:30', '2021-06-10 14:53:10'),
(2, 1, 8, '2021-06-10 18:34:14', '2021-06-10 18:34:15'),
(1, 2, 32, '2021-06-12 18:47:30', '2021-07-06 00:06:21'),
(1, 3, 1, '2021-06-26 18:33:29', '2021-06-26 18:33:29'),
(1, 66, 5, '2021-06-26 21:18:34', '2021-06-26 21:18:34'),
(1, 35, 1, '2021-06-27 09:03:10', '2021-06-27 09:03:10'),
(1, 59, 3, '2021-06-27 10:16:37', '2021-06-27 10:16:38'),
(1, 6, 3, '2021-06-28 22:58:20', '2021-06-28 22:58:20');

-- --------------------------------------------------------

--
-- Структура таблицы `brands`
--

CREATE TABLE `brands` (
  `id_brand` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `brands`
--

INSERT INTO `brands` (`id_brand`, `name`, `created`, `modified`) VALUES
(1, 'Dolce & Gabbana', '2021-01-25 15:12:36', '2021-05-08 21:52:07'),
(2, 'Armani', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(3, 'Adidas', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(4, 'Nike', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(5, 'Puma', '2021-01-25 15:12:36', '2021-02-05 08:48:48');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id_category` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id_category`, `name`, `created`, `modified`) VALUES
(1, 'T-shirts', '2021-01-25 15:22:24', '2021-07-06 23:12:27'),
(2, 'shirts', '2021-01-25 15:22:24', '2021-07-06 23:12:36'),
(3, 'undershirts', '2021-01-25 15:22:24', '2021-07-06 23:13:37'),
(4, 'jumper', '2021-01-25 15:22:24', '2021-07-06 23:15:14'),
(5, 'sweaters', '2021-01-25 15:22:24', '2021-07-06 23:13:51'),
(6, 'hoodies', '2021-01-25 15:22:24', '2021-07-06 23:15:23'),
(7, 'pants', '2021-01-25 15:22:24', '2021-07-06 23:15:32'),
(8, 'jeans', '2021-01-25 15:22:24', '2021-07-06 23:15:39'),
(9, 'shorts', '2021-01-25 15:22:24', '2021-07-06 23:15:46'),
(10, 'sneakers', '2021-01-25 15:22:24', '2021-07-06 23:15:53'),
(11, 'gumshoes', '2021-01-25 15:22:24', '2021-07-06 23:16:11'),
(12, 'boots', '2021-01-25 15:22:24', '2021-07-06 23:16:19'),
(13, 'hats', '2021-01-25 15:22:24', '2021-07-06 23:16:27'),
(14, 'cap', '2021-01-25 15:22:24', '2021-07-06 23:17:08'),
(15, 'baseball caps', '2021-06-11 15:56:31', '2021-07-06 23:17:18'),
(16, 'diadems', '2021-07-05 23:38:21', '2021-07-06 23:17:31'),
(17, 'Earrings', '2021-07-05 23:38:21', '2021-07-06 23:17:41'),
(18, 'Necklaces', '2021-07-05 23:38:21', '2021-07-06 23:17:49'),
(19, 'Bracelets', '2021-07-05 23:38:21', '2021-07-06 23:17:57'),
(20, 'Rings', '2021-07-05 23:38:21', '2021-07-06 23:18:05'),
(21, 'Handbags', '2021-07-05 23:38:21', '2021-07-06 23:18:12');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id_comment` int NOT NULL,
  `id_product` int NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id_comment`, `id_product`, `author`, `text`, `created`, `modified`) VALUES
(1, 2, 'Коля', 'Привет!', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(2, 2, 'Петя', 'Тебе тоже привет', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(3, 2, 'Овик', 'HELLO', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(5, 2, 'Овик', 'Пока!', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(6, 2, 'Никто', 'КУ КУ Ёпта', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(7, 2, 'MRX', 'RED RED', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(8, 2, 'MRX', 'КВА КВА', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(9, 2, 'MRX', '123', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(10, 2, 'MRX', '123', '2021-01-25 15:28:31', '2021-02-05 08:48:48'),
(32, 2, 'user', 'asd', '2021-03-18 14:00:10', '2021-03-18 14:00:10');

-- --------------------------------------------------------

--
-- Структура таблицы `genders`
--

CREATE TABLE `genders` (
  `id_gender` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `genders`
--

INSERT INTO `genders` (`id_gender`, `name`, `created`, `modified`) VALUES
(1, 'men', '2021-03-28 16:31:12', '2021-06-10 20:58:29'),
(2, 'women', '2021-03-28 16:31:12', '2021-06-10 20:58:37'),
(3, 'unisexs', '2021-03-28 16:31:12', '2021-06-11 13:48:27');

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id_image` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id_image`, `name`, `created`) VALUES
(1, 'woman t-shirt.jpg', '2021-06-11 16:05:21'),
(2, 'woman shirt.jpg', '2021-06-11 16:05:21'),
(3, 'woman undershirt.jpg', '2021-06-11 16:05:21'),
(4, 'woman jumper.jpg', '2021-06-11 16:05:21'),
(5, 'woman sweater.jpg', '2021-06-11 16:05:21'),
(6, 'woman hoody.jpg', '2021-06-11 16:05:21'),
(7, 'woman pants.jpg', '2021-06-11 16:05:21'),
(8, 'woman jeans.jpg', '2021-06-11 16:05:21'),
(9, 'woman shorts.jpg', '2021-06-11 16:05:21'),
(10, 'woman sneakers.jpg', '2021-06-11 16:05:21'),
(11, 'woman gumshoes.jpg', '2021-06-11 16:05:21'),
(12, 'woman boots.jpg', '2021-06-11 16:05:21'),
(13, 'woman hat.jpg', '2021-06-11 16:05:21'),
(14, 'woman cap.jpg', '2021-06-11 16:05:21'),
(15, 'woman baseball cap.jpg', '2021-06-11 16:05:21'),
(16, 'man t-shirt.jpg', '2021-06-11 16:14:22'),
(17, 'man shirt.jpg', '2021-06-11 16:14:22'),
(18, 'man undershirt.jpg', '2021-06-11 16:14:22'),
(19, 'man jumper.jpg', '2021-06-11 16:14:22'),
(20, 'man sweater.jpg', '2021-06-11 16:14:22'),
(21, 'man hoody.jpg', '2021-06-11 16:14:22'),
(22, 'man pants.jpg', '2021-06-11 16:14:22'),
(23, 'man jeans.jpg', '2021-06-11 16:14:22'),
(24, 'man shorts.jpg', '2021-06-11 16:14:22'),
(25, 'man sneakers.jpg', '2021-06-11 16:14:22'),
(26, 'man gumshoes.jpg', '2021-06-11 16:14:22'),
(27, 'man boots.jpeg', '2021-06-11 16:14:22'),
(28, 'man hat.jpg', '2021-06-11 16:14:22'),
(29, 'man cap.jpg', '2021-06-11 16:14:22'),
(30, 'man baseball cap.jpg', '2021-06-11 16:14:22'),
(31, 'kid t-shirt.jpg', '2021-06-11 16:14:20'),
(32, 'kid shirt.jpeg', '2021-06-11 16:14:20'),
(33, 'kid undershirt.jpg', '2021-06-11 16:14:20'),
(34, 'kid jumper.jpg', '2021-06-11 16:14:20'),
(35, 'kid sweater.jpg', '2021-06-11 16:14:20'),
(36, 'kid hoody.jpg', '2021-06-11 16:14:20'),
(37, 'kid pants.jpg', '2021-06-11 16:14:20'),
(38, 'kid jeans.jpeg', '2021-06-11 16:14:20'),
(39, 'kid shorts.jpg', '2021-06-11 16:14:20'),
(40, 'kid sneakers.jpg', '2021-06-11 16:14:20'),
(41, 'kid gumshoes.jpg', '2021-06-11 16:14:20'),
(42, 'kid boots.jpg', '2021-06-11 16:14:20'),
(43, 'kid hat.jpg', '2021-06-11 16:14:20'),
(44, 'kid cap.jpg', '2021-06-11 16:14:20'),
(45, 'kid baseball cap.jpg', '2021-06-11 16:14:20'),
(46, 'accessories_diadem.jpg', '2021-07-05 23:33:02'),
(47, 'accessories_earrings.jpg', '2021-07-05 23:34:42'),
(48, 'accessories_necklaces.jpg', '2021-07-05 23:34:42'),
(49, 'accessories_bracelet.jpg', '2021-07-05 23:35:30'),
(50, 'accessories_ring.jpg', '2021-07-05 23:35:30'),
(51, 'accessories_handbag.jpg', '2021-07-05 23:35:45'),
(123, '09dfe98497c96ad98434b092b12d507d.png', '2021-01-25 15:27:42'),
(124, '63e3ce8e33762a5f29e40197aff08ee1.png', '2021-01-25 15:27:42'),
(125, '8409fca2e0b6b520052da266c38d15a1.png', '2021-01-25 15:27:42'),
(126, '967cc3e58dee5748bcf79187dc47b621.png', '2021-01-25 15:27:42'),
(127, '5ea347cdc11d843c1d6db4f5da832196.png', '2021-01-25 15:27:42'),
(128, '619a070d7474098455534ea9b1b5b1c9.png', '2021-01-25 15:27:42'),
(130, 'e80ac78490a69b2f9eaa185a487fe6b9.png', '2021-01-25 15:27:42'),
(131, '6837fb42d72a0b7da4d767b86548f23a.png', '2021-01-31 19:38:47');

-- --------------------------------------------------------

--
-- Структура таблицы `images_products`
--

CREATE TABLE `images_products` (
  `id_product` int NOT NULL,
  `id_image` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images_products`
--

INSERT INTO `images_products` (`id_product`, `id_image`, `created`) VALUES
(25, 124, '2021-01-25 15:25:04'),
(25, 125, '2021-01-25 15:25:04'),
(25, 126, '2021-01-25 15:25:04'),
(25, 127, '2021-01-25 15:25:04'),
(25, 128, '2021-01-25 15:25:04'),
(5, 124, '2021-01-25 15:25:04'),
(3, 128, '2021-01-25 15:25:04'),
(6, 123, '2021-01-25 15:25:04'),
(2, 130, '2021-01-25 15:25:04'),
(6, 130, '2021-01-25 15:25:04'),
(1, 131, '2021-01-31 19:38:47'),
(59, 123, '2021-03-12 13:10:44'),
(66, 123, '2021-04-20 09:57:23'),
(66, 124, '2021-04-20 10:23:46'),
(35, 130, '2021-05-11 22:43:33');

-- --------------------------------------------------------

--
-- Структура таблицы `images_users`
--

CREATE TABLE `images_users` (
  `id_user` int NOT NULL,
  `id_image` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `images_users`
--

INSERT INTO `images_users` (`id_user`, `id_image`, `created`) VALUES
(1, 130, '2021-03-12 12:39:45');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_order` int NOT NULL,
  `id_user` int NOT NULL,
  `id_status` int NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders_products`
--

CREATE TABLE `orders_products` (
  `id_order` int NOT NULL,
  `id_product` int NOT NULL,
  `quantity` int NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `order_status`
--

CREATE TABLE `order_status` (
  `id_status` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `order_status`
--

INSERT INTO `order_status` (`id_status`, `name`, `created`, `modified`) VALUES
(1, 'обрабатывается', '2021-03-28 16:36:23', '2021-03-28 16:38:24'),
(2, 'формируется', '2021-03-28 16:36:23', '2021-03-28 16:38:27'),
(3, 'доставляется', '2021-03-28 16:37:12', '2021-03-28 16:38:31'),
(4, 'ждет оплаты', '2021-03-28 16:37:12', '2021-03-28 16:38:35'),
(5, 'оплачен', '2021-03-28 16:38:04', '2021-03-28 16:38:37'),
(6, 'отменен', '2021-03-28 16:38:04', '2021-03-28 16:38:40'),
(7, 'вернули', '2021-03-28 16:39:29', '2021-03-28 16:39:29'),
(8, 'в ремонте', '2021-03-28 16:39:29', '2021-03-28 16:39:29');

-- --------------------------------------------------------

--
-- Структура таблицы `passwords`
--

CREATE TABLE `passwords` (
  `id_user` int NOT NULL,
  `password` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `passwords`
--

INSERT INTO `passwords` (`id_user`, `password`, `modified`) VALUES
(1, '$2y$10$5KzKlW5LRjHrM0rBt1M2XO8ikYNWHhHcEkL6iYUwq/fpxSoQq6eRe', '2021-03-03 10:19:12'),
(2, '$2y$10$fFg7S9w96BXbzvkyXIi7cePWSImSjvbwXvL5AdGQTDzzlChp5LZwy', '2021-03-03 10:02:32');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id_product` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_category` int NOT NULL,
  `id_gender` int DEFAULT NULL,
  `id_brand` int NOT NULL,
  `id_img_main` int DEFAULT NULL,
  `price` int NOT NULL,
  `viewed` int NOT NULL DEFAULT '0',
  `liked` int NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id_product`, `name`, `id_category`, `id_gender`, `id_brand`, `id_img_main`, `price`, `viewed`, `liked`, `created`, `modified`) VALUES
(1, 'Зауженные джинсы Adidas', 1, 1, 3, 131, 7500, 223, 2, '2021-01-25 15:24:20', '2021-07-06 23:11:47'),
(2, 'Стильная футболка от Puma', 1, 2, 5, 130, 2200, 1702, 106, '2021-01-25 15:24:20', '2021-07-06 00:29:06'),
(3, 'Стильный мужской свитер', 1, 1, 2, 128, 3750, 119, 3, '2021-01-25 15:24:20', '2021-06-28 22:31:14'),
(5, 'Куртка', 1, 1, 1, 124, 5400, 20, 0, '2021-01-25 15:24:20', '2021-06-12 11:26:12'),
(6, 'Современные брюки от D&amp;G', 1, 1, 1, 123, 7200, 730, 72, '2021-01-25 15:24:20', '2021-07-06 23:21:07'),
(7, 'Обычная шмотка', 1, 3, 1, NULL, 4550, 36, 2, '2021-01-25 15:24:20', '2021-06-15 17:34:18'),
(8, 'Заморская вещь', 1, 2, 4, NULL, 1400, 37, 0, '2021-01-25 15:24:20', '2021-06-26 14:23:18'),
(9, 'Чумавые кеды', 1, 1, 3, NULL, 9300, 113, 0, '2021-01-25 15:24:20', '2021-06-27 14:51:19'),
(25, 'asd', 1, 1, 1, 127, 900, 209, 0, '2021-01-25 15:24:20', '2021-07-05 22:41:45'),
(35, 'qweqwe', 1, NULL, 1, 130, 1, 95, 1, '2021-03-05 16:15:42', '2021-06-27 14:39:29'),
(59, 'asd', 1, NULL, 1, 123, 3, 37, 2, '2021-03-12 13:10:44', '2021-06-27 10:16:42'),
(66, 'asd', 1, NULL, 1, 123, 1, 30, 0, '2021-04-18 11:49:46', '2021-07-05 23:12:03');

-- --------------------------------------------------------

--
-- Структура таблицы `sections`
--

CREATE TABLE `sections` (
  `id_section` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sections`
--

INSERT INTO `sections` (`id_section`, `name`, `created`, `modified`) VALUES
(1, 'women', '2021-06-11 13:51:57', '2021-06-11 16:24:57'),
(2, 'men', '2021-06-11 13:51:57', '2021-06-11 16:25:00'),
(3, 'kids', '2021-06-11 13:51:57', '2021-06-11 13:51:57'),
(4, 'accessories', '2021-06-11 13:51:57', '2021-06-11 13:51:57');

-- --------------------------------------------------------

--
-- Структура таблицы `sections_categories`
--

CREATE TABLE `sections_categories` (
  `id` int NOT NULL,
  `id_section` int NOT NULL,
  `id_category` int NOT NULL,
  `id_image` int DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sections_categories`
--

INSERT INTO `sections_categories` (`id`, `id_section`, `id_category`, `id_image`, `created`, `modified`) VALUES
(1, 1, 1, 1, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(2, 1, 2, 2, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(3, 1, 3, 3, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(4, 1, 4, 4, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(5, 1, 5, 5, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(6, 1, 6, 6, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(7, 1, 7, 7, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(8, 1, 8, 8, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(9, 1, 9, 9, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(10, 1, 10, 10, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(11, 1, 11, 11, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(12, 1, 12, 12, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(13, 1, 13, 13, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(14, 1, 14, 14, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(15, 1, 15, 15, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(16, 2, 1, 16, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(17, 2, 2, 17, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(18, 2, 3, 18, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(19, 2, 4, 19, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(20, 2, 5, 20, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(21, 2, 6, 21, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(22, 2, 7, 22, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(23, 2, 8, 23, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(24, 2, 9, 24, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(25, 2, 10, 25, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(26, 2, 11, 26, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(27, 2, 12, 27, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(28, 2, 13, 28, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(29, 2, 14, 29, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(30, 2, 15, 30, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(31, 3, 1, 31, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(32, 3, 2, 32, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(33, 3, 3, 33, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(34, 3, 4, 34, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(35, 3, 5, 35, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(36, 3, 6, 36, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(37, 3, 7, 37, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(38, 3, 8, 38, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(39, 3, 9, 39, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(40, 3, 10, 40, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(41, 3, 11, 41, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(42, 3, 12, 42, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(43, 3, 13, 43, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(44, 3, 14, 44, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(45, 3, 15, 45, '2021-06-11 16:23:29', '2021-06-11 16:23:29'),
(46, 4, 16, 46, '2021-07-05 23:39:15', '2021-07-05 23:41:38'),
(47, 4, 17, 47, '2021-07-05 23:39:15', '2021-07-05 23:41:41'),
(48, 4, 18, 48, '2021-07-05 23:39:15', '2021-07-05 23:41:44'),
(49, 4, 19, 49, '2021-07-05 23:39:15', '2021-07-05 23:41:51'),
(50, 4, 20, 50, '2021-07-05 23:39:15', '2021-07-05 23:41:55'),
(51, 4, 21, 51, '2021-07-05 23:39:15', '2021-07-05 23:42:01');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_access` int NOT NULL,
  `id_img_main` int DEFAULT NULL,
  `last_action` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `name`, `login`, `id_access`, `id_img_main`, `last_action`, `created`, `modified`) VALUES
(1, 'admin', 'admin', 1, 130, '2021-07-06 23:11:43', '2021-01-25 15:23:28', '2021-03-06 15:53:41'),
(2, 'User', 'user', 2, NULL, '2021-06-10 18:34:24', '2021-01-25 15:23:28', '2021-03-06 15:53:44');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `access`
--
ALTER TABLE `access`
  ADD PRIMARY KEY (`id_access`);

--
-- Индексы таблицы `basket`
--
ALTER TABLE `basket`
  ADD KEY `fk_basket_user` (`id_user`),
  ADD KEY `fk_basket_product` (`id_product`);

--
-- Индексы таблицы `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id_brand`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `fk_comment_product` (`id_product`);

--
-- Индексы таблицы `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id_gender`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id_image`);

--
-- Индексы таблицы `images_products`
--
ALTER TABLE `images_products`
  ADD KEY `fk_product_img` (`id_product`),
  ADD KEY `fk_img_product` (`id_image`);

--
-- Индексы таблицы `images_users`
--
ALTER TABLE `images_users`
  ADD KEY `fk_user_img` (`id_user`),
  ADD KEY `fk_img_user` (`id_image`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `fk_order_status` (`id_status`),
  ADD KEY `fk_order_user` (`id_user`);

--
-- Индексы таблицы `orders_products`
--
ALTER TABLE `orders_products`
  ADD KEY `fk_order_product` (`id_order`),
  ADD KEY `fk_product_order` (`id_product`);

--
-- Индексы таблицы `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id_status`);

--
-- Индексы таблицы `passwords`
--
ALTER TABLE `passwords`
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `fk_product_gender` (`id_gender`),
  ADD KEY `fk_product_type` (`id_category`),
  ADD KEY `fk_product_brand` (`id_brand`),
  ADD KEY `fk_product_img_main` (`id_img_main`);

--
-- Индексы таблицы `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id_section`);

--
-- Индексы таблицы `sections_categories`
--
ALTER TABLE `sections_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unk_section_category` (`id_section`,`id_category`) USING BTREE,
  ADD KEY `fk_category_section` (`id_category`),
  ADD KEY `fk_section_category_image` (`id_image`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `fk_user_img_main` (`id_img_main`),
  ADD KEY `fk_user_access` (`id_access`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `access`
--
ALTER TABLE `access`
  MODIFY `id_access` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `brands`
--
ALTER TABLE `brands`
  MODIFY `id_brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `genders`
--
ALTER TABLE `genders`
  MODIFY `id_gender` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id_image` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id_status` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT для таблицы `sections`
--
ALTER TABLE `sections`
  MODIFY `id_section` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `sections_categories`
--
ALTER TABLE `sections_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `fk_basket_product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_basket_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comment_product` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `images_products`
--
ALTER TABLE `images_products`
  ADD CONSTRAINT `fk_img_product` FOREIGN KEY (`id_image`) REFERENCES `images` (`id_image`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_img` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `images_users`
--
ALTER TABLE `images_users`
  ADD CONSTRAINT `fk_img_user` FOREIGN KEY (`id_image`) REFERENCES `images` (`id_image`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_img` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_status` FOREIGN KEY (`id_status`) REFERENCES `order_status` (`id_status`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders_products`
--
ALTER TABLE `orders_products`
  ADD CONSTRAINT `fk_order_product` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_order` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `passwords`
--
ALTER TABLE `passwords`
  ADD CONSTRAINT `fk_user_passwd` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`id_brand`) REFERENCES `brands` (`id_brand`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`id_category`) REFERENCES `sections_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_gender` FOREIGN KEY (`id_gender`) REFERENCES `genders` (`id_gender`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_img_main` FOREIGN KEY (`id_img_main`) REFERENCES `images` (`id_image`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `sections_categories`
--
ALTER TABLE `sections_categories`
  ADD CONSTRAINT `fk_category_section` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_section_category` FOREIGN KEY (`id_section`) REFERENCES `sections` (`id_section`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_section_category_image` FOREIGN KEY (`id_image`) REFERENCES `images` (`id_image`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_access` FOREIGN KEY (`id_access`) REFERENCES `access` (`id_access`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_user_img_main` FOREIGN KEY (`id_img_main`) REFERENCES `images` (`id_image`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
