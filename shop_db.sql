-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 05 2021 г., 15:07
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
(2, 2, 40, '2021-03-24 12:00:24', '2021-04-18 19:08:01'),
(2, 6, 8, '2021-03-24 12:02:16', '2021-04-18 19:08:01'),
(2, 1, 8, '2021-03-24 12:02:26', '2021-04-18 19:08:01'),
(2, 25, 4, '2021-03-24 12:02:26', '2021-04-18 19:08:01'),
(1, 2, 512, '2021-03-28 16:08:39', '2021-04-18 19:08:10'),
(1, 6, 256, '2021-03-28 16:08:40', '2021-04-18 19:08:10'),
(1, 1, 256, '2021-03-28 16:08:41', '2021-04-18 19:08:10'),
(1, 25, 8, '2021-04-18 11:40:27', '2021-04-18 19:08:10');

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
(1, 'Dolce & Gabbana', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(2, 'Armani', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(3, 'Adidas', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(4, 'Nike', '2021-01-25 15:12:36', '2021-02-05 08:48:48'),
(5, 'Puma', '2021-01-25 15:12:36', '2021-02-05 08:48:48');

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
(1, 'man', '2021-03-28 16:31:12', '2021-03-28 16:31:12'),
(2, 'woman', '2021-03-28 16:31:12', '2021-03-28 16:31:12'),
(3, 'unisex', '2021-03-28 16:31:12', '2021-03-28 16:31:12');

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
(23, '09dfe98497c96ad98434b092b12d507d.png', '2021-01-25 15:27:42'),
(24, '63e3ce8e33762a5f29e40197aff08ee1.png', '2021-01-25 15:27:42'),
(25, '8409fca2e0b6b520052da266c38d15a1.png', '2021-01-25 15:27:42'),
(26, '967cc3e58dee5748bcf79187dc47b621.png', '2021-01-25 15:27:42'),
(27, '5ea347cdc11d843c1d6db4f5da832196.png', '2021-01-25 15:27:42'),
(28, '619a070d7474098455534ea9b1b5b1c9.png', '2021-01-25 15:27:42'),
(30, 'e80ac78490a69b2f9eaa185a487fe6b9.png', '2021-01-25 15:27:42'),
(31, '6837fb42d72a0b7da4d767b86548f23a.png', '2021-01-31 19:38:47');

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
(25, 24, '2021-01-25 15:25:04'),
(25, 25, '2021-01-25 15:25:04'),
(25, 26, '2021-01-25 15:25:04'),
(25, 27, '2021-01-25 15:25:04'),
(25, 28, '2021-01-25 15:25:04'),
(5, 24, '2021-01-25 15:25:04'),
(3, 28, '2021-01-25 15:25:04'),
(6, 23, '2021-01-25 15:25:04'),
(2, 30, '2021-01-25 15:25:04'),
(6, 30, '2021-01-25 15:25:04'),
(1, 31, '2021-01-31 19:38:47'),
(59, 23, '2021-03-12 13:10:44'),
(66, 23, '2021-04-20 09:57:23'),
(66, 24, '2021-04-20 10:23:46');

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
(1, 30, '2021-03-12 12:39:45');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id_order` int NOT NULL,
  `id_user` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id_type` int NOT NULL,
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

INSERT INTO `products` (`id_product`, `name`, `id_type`, `id_gender`, `id_brand`, `id_img_main`, `price`, `viewed`, `liked`, `created`, `modified`) VALUES
(1, 'Зауженные джинсы Adidas', 8, 1, 3, 31, 7500, 189, 2, '2021-01-25 15:24:20', '2021-03-12 14:26:51'),
(2, 'Стильная футболка от Puma', 1, 2, 5, 30, 2200, 1260, 73, '2021-01-25 15:24:20', '2021-04-27 21:28:49'),
(3, 'Стильный мужской свитер', 5, 1, 2, 28, 3750, 114, 3, '2021-01-25 15:24:20', '2021-03-18 14:06:49'),
(5, 'Куртка', 7, 1, 1, 24, 5400, 19, 0, '2021-01-25 15:24:20', '2021-03-05 15:25:12'),
(6, 'Современные брюки от D&amp;G', 7, 1, 1, 23, 7200, 506, 20, '2021-01-25 15:24:20', '2021-04-20 09:57:14'),
(7, 'Обычная шмотка', 7, 3, 1, NULL, 4550, 21, 2, '2021-01-25 15:24:20', '2021-03-05 15:25:10'),
(8, 'Заморская вещь', 13, 2, 2, NULL, 1400, 28, 0, '2021-01-25 15:24:20', '2021-04-18 11:40:37'),
(9, 'Чумавые кеды', 11, 1, 3, NULL, 9300, 25, 0, '2021-01-25 15:24:20', '2021-03-06 12:57:43'),
(25, 'asd', 1, 1, 1, 27, 900, 128, 0, '2021-01-25 15:24:20', '2021-03-28 20:29:58'),
(35, 'qwe', 1, NULL, 1, NULL, 1, 32, 0, '2021-03-05 16:15:42', '2021-03-18 14:06:45'),
(59, 'asd', 3, NULL, 1, 23, 3, 30, 2, '2021-03-12 13:10:44', '2021-04-18 11:33:56'),
(64, 'asd', 2, NULL, 1, NULL, 1, 0, 0, '2021-04-18 11:49:43', '2021-04-18 11:49:43'),
(65, 'asd', 2, NULL, 1, NULL, 1, 0, 0, '2021-04-18 11:49:44', '2021-04-18 11:49:44'),
(66, 'asd', 2, NULL, 1, 23, 1, 10, 0, '2021-04-18 11:49:46', '2021-04-20 10:23:51'),
(75, 'qwe', 1, 2, 2, NULL, 1, 0, 0, '2021-04-18 12:58:32', '2021-04-18 12:58:32'),
(76, 'qwe', 1, 1, 1, NULL, 1, 1, 0, '2021-04-18 13:09:29', '2021-04-18 13:09:45'),
(77, 'qwe111', 1, 1, 1, NULL, 1, 2, 0, '2021-04-18 15:56:04', '2021-04-18 16:05:10'),
(80, 'qwe', 1, 1, 1, NULL, 1, 2, 0, '2021-04-18 16:06:38', '2021-04-18 16:06:42');

-- --------------------------------------------------------

--
-- Структура таблицы `types_products`
--

CREATE TABLE `types_products` (
  `id_type_product` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `types_products`
--

INSERT INTO `types_products` (`id_type_product`, `name`, `created`, `modified`) VALUES
(1, 'футболка', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(2, 'рубашка', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(3, 'майка', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(4, 'джемпер', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(5, 'свитер', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(6, 'толстовка', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(7, 'брюки', '2021-01-25 15:22:24', '2021-03-28 12:14:51'),
(8, 'джинсы', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(9, 'шорты', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(10, 'кроссовки', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(11, 'кеды', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(12, 'ботинки', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(13, 'шляпа', '2021-01-25 15:22:24', '2021-02-05 08:48:48'),
(14, 'шапка', '2021-01-25 15:22:24', '2021-02-05 08:48:48');

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
(1, 'admin', 'admin', 1, 30, '2021-04-27 21:28:53', '2021-01-25 15:23:28', '2021-03-06 15:53:41'),
(2, 'User', 'user', 2, NULL, '2021-04-18 19:08:01', '2021-01-25 15:23:28', '2021-03-06 15:53:44');

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
  ADD KEY `fk_order_status` (`status`),
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
  ADD KEY `fk_product_type` (`id_type`),
  ADD KEY `fk_product_brand` (`id_brand`),
  ADD KEY `fk_product_img_main` (`id_img_main`);

--
-- Индексы таблицы `types_products`
--
ALTER TABLE `types_products`
  ADD PRIMARY KEY (`id_type_product`);

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
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT для таблицы `genders`
--
ALTER TABLE `genders`
  MODIFY `id_gender` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id_image` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT для таблицы `types_products`
--
ALTER TABLE `types_products`
  MODIFY `id_type_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  ADD CONSTRAINT `fk_order_status` FOREIGN KEY (`status`) REFERENCES `order_status` (`id_status`) ON DELETE RESTRICT ON UPDATE CASCADE,
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
  ADD CONSTRAINT `fk_product_gender` FOREIGN KEY (`id_gender`) REFERENCES `genders` (`id_gender`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_img_main` FOREIGN KEY (`id_img_main`) REFERENCES `images` (`id_image`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_product_type` FOREIGN KEY (`id_type`) REFERENCES `types_products` (`id_type_product`) ON UPDATE CASCADE;

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
