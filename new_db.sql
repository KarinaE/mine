-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 07 2017 г., 16:05
-- Версия сервера: 5.6.34-log
-- Версия PHP: 7.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ib_shop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(5) NOT NULL,
  `category` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `main` int(1) NOT NULL,
  `parent_id` int(5) NOT NULL,
  `level` int(2) NOT NULL,
  `child` int(1) DEFAULT NULL,
  `page` varchar(255) COLLATE utf8_bin NOT NULL,
  `ordr` int(3) NOT NULL,
  `status` int(1) NOT NULL,
  `date_add` varchar(12) COLLATE utf8_bin NOT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin NOT NULL,
  `author` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `category`, `name`, `main`, `parent_id`, `level`, `child`, `page`, `ordr`, `status`, `date_add`, `date_upd`, `author`) VALUES
(1, 'Технические', 'Главная страница', 1, 0, 0, NULL, 'main', 1, 1, '1499781051', '1499781058', '1'),
(2, 'Технические', 'Кабинет', 2, 0, 0, NULL, 'account/', 2, 1, '1499781096', '1499781096', '1'),
(3, 'Главное меню', 'Новинки', 2, 0, 0, NULL, 'novinki/', 1, 1, '1499781143', '1499784405', '1'),
(4, 'Главное меню', 'Дизайнеры', 2, 0, 0, NULL, 'dizaineri/', 2, 1, '1499781165', '1499781165', '1'),
(6, 'Главное меню', 'Одежда', 2, 0, 0, 1, 'odejda/', 3, 1, '1499781186', '1499781186', '1'),
(7, 'Главное меню', 'Обувь', 2, 0, 0, 1, 'obuv/', 4, 1, '1499781222', '1499781222', '1'),
(8, 'Главное меню', 'Lookbook', 2, 0, 0, NULL, 'lookbook/', 5, 1, '1499781239', '1499781239', '1'),
(9, 'Главное меню', 'Аксессуары', 2, 0, 0, NULL, 'aksessuari/', 6, 1, '1499781270', '1499781270', '1'),
(10, 'Главное меню', 'Блог', 2, 0, 0, NULL, 'blog/', 7, 1, '1499781285', '1499781285', '1'),
(11, 'Главное меню', 'Контакты', 2, 0, 0, NULL, 'kontakti/', 8, 1, '1499781302', '1499781302', '1'),
(12, 'Технические', 'Корзина', 2, 0, 0, NULL, 'cart/', 3, 1, '1500935590', '1500935590', '1'),
(13, 'Главное меню', 'Футболки', 2, 6, 1, NULL, 't-shirt/', 2, 1, '1502322409', '1502322409', '1'),
(14, 'Главное меню', 'Брюки', 2, 6, 1, NULL, '', 3, 1, '1502379789', '1502379789', '1'),
(15, 'Главное меню', 'Юбки', 2, 6, 1, NULL, '', 4, 1, '1502379803', '1502379803', '1'),
(16, 'Главное меню', 'Ботинки', 2, 7, 1, NULL, '', 1, 1, '1502379825', '1502379825', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_address`
--

CREATE TABLE `clients_address` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `id_client` tinyint(5) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `is_main` tinyint(4) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_address`
--

INSERT INTO `clients_address` (`id`, `id_client`, `address`, `is_main`, `status`) VALUES
(92, 116, 'Hello str 5', 2, 1),
(93, 116, 'Lesi Str 4', 1, 1),
(100, 116, 'bfgbfgb', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_contacts_relations`
--

CREATE TABLE `clients_contacts_relations` (
  `id` tinyint(5) NOT NULL,
  `id_client` tinyint(5) UNSIGNED NOT NULL,
  `id_addr` tinyint(5) UNSIGNED NOT NULL,
  `id_tel` tinyint(5) DEFAULT NULL,
  `is_main` tinyint(4) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_contacts_relations`
--

INSERT INTO `clients_contacts_relations` (`id`, `id_client`, `id_addr`, `id_tel`, `is_main`, `status`) VALUES
(24, 116, 92, 84, 2, 1),
(25, 116, 93, 85, 1, 1),
(32, 116, 100, 110, 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_emails`
--

CREATE TABLE `clients_emails` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `id_client` tinyint(5) UNSIGNED NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `is_main` tinyint(1) DEFAULT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_emails`
--

INSERT INTO `clients_emails` (`id`, `id_client`, `first_name`, `email`, `is_main`, `status`) VALUES
(28, 116, 'lena', 'test.dev.dtntg@gmail.com', 1, 1),
(30, 118, 'fdbvcdfbn', 'Karina@gmail.com', 1, 1),
(32, 120, 'kok', 'Monica33@gmail.com', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_info`
--

CREATE TABLE `clients_info` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `bonuses` int(50) UNSIGNED DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `comments` text,
  `date_add` date DEFAULT NULL,
  `status` tinyint(2) NOT NULL,
  `activation` varchar(255) DEFAULT NULL,
  `activation_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_info`
--

INSERT INTO `clients_info` (`id`, `first_name`, `last_name`, `password`, `bonuses`, `birth_date`, `gender`, `avatar`, `comments`, `date_add`, `status`, `activation`, `activation_time`) VALUES
(116, 'lena', 'koleno', '$2y$10$as/vvOC49ASX/qzVgTuZsexq1t7hzy4y24aw.lpbsXwDZRW6phgKW', 33, '2007-01-01', '2', NULL, NULL, '2017-08-11', 1, '1', '2017-10-04 21:31:16'),
(118, 'fdbvcdfbn', 'pena', '$2y$10$DaZ8NPaFSmvlmeC7TY51aOWImoeHyOIXlsx.sBOSrModF9hdjLwya', NULL, '0000-00-00', '1', NULL, NULL, '2017-08-28', 1, '756e6589ce6352f7b38cac8d5520b4bb', '2017-08-28 16:03:55'),
(120, 'kok', 'freess', '$2y$10$7AaGD8l.yHnRwPfTpkJew.xMRr.d56WY5D9b66ZaDnksCnf.nobka', NULL, '2002-10-16', '2', NULL, NULL, '2017-10-05', 1, '1', '2017-10-05 19:10:34');

-- --------------------------------------------------------

--
-- Структура таблицы `clients_phones`
--

CREATE TABLE `clients_phones` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `id_client` tinyint(5) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `phone` varchar(14) DEFAULT NULL,
  `is_main` tinyint(1) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients_phones`
--

INSERT INTO `clients_phones` (`id`, `id_client`, `first_name`, `phone`, `is_main`, `status`) VALUES
(84, 116, 'lena', '+380997777777', 2, 1),
(85, 116, 'lena', '+380997777777', 1, 1),
(91, 116, 'lena', '3243244234', 0, 1),
(92, 116, 'lena', '234234232423', 0, 1),
(93, 116, 'lena', '3423234', 0, 1),
(94, 116, 'lena', '345345', 0, 1),
(95, 116, 'lena', '2442', 0, 1),
(96, 116, 'lena', '24234234', 0, 1),
(97, 116, 'lena', '24234234', 0, 1),
(98, 116, 'lena', '24234234', 0, 1),
(99, 116, 'lena', '353545', 0, 1),
(100, 116, 'lena', '353545', 0, 1),
(101, 116, 'lena', '2343423', 0, 1),
(102, 116, 'lena', '2343423', 0, 1),
(103, 116, 'lena', '4565', 0, 1),
(105, 116, 'lena', '456456564', 0, 1),
(106, 116, 'lena', '456456564', 0, 1),
(107, 116, 'lena', '456456564', 0, 1),
(108, 116, 'lena', '456456564', 0, 1),
(109, 116, 'lena', '34234324', 0, 1),
(110, 116, 'lena', '4565654', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `clients_soc_acc`
--

CREATE TABLE `clients_soc_acc` (
  `id` tinyint(5) UNSIGNED NOT NULL,
  `id_client` tinyint(5) UNSIGNED NOT NULL,
  `network` varchar(100) NOT NULL,
  `soc_id` varchar(255) NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cloth_sizes`
--

CREATE TABLE `cloth_sizes` (
  `id` int(11) NOT NULL,
  `size` varchar(100) DEFAULT NULL,
  `type_id` int(10) DEFAULT NULL,
  `ordr` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cloth_sizes`
--

INSERT INTO `cloth_sizes` (`id`, `size`, `type_id`, `ordr`) VALUES
(9, 'XS', 2, 1),
(10, 'S', 2, 2),
(11, 'M', 2, 3),
(12, 'L', 2, 4),
(13, 'XL', 2, 5),
(14, 'XS', 3, 1),
(15, 'S', 3, 2),
(16, 'M', 3, 3),
(17, 'L', 3, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `cloth_size_options`
--

CREATE TABLE `cloth_size_options` (
  `id` int(11) NOT NULL,
  `size_option` varchar(100) DEFAULT NULL,
  `type_id` int(10) DEFAULT NULL,
  `ordr` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cloth_size_options`
--

INSERT INTO `cloth_size_options` (`id`, `size_option`, `type_id`, `ordr`) VALUES
(4, 'Охват груди', 2, 1),
(5, 'Высота рукава', 2, 2),
(6, 'Высота', 2, 3),
(7, 'Штанина', 3, 1),
(8, 'Пояс', 3, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `cloth_types`
--

CREATE TABLE `cloth_types` (
  `id` int(5) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `date_add` varchar(12) DEFAULT NULL,
  `date_upd` varchar(12) DEFAULT NULL,
  `author` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `cloth_types`
--

INSERT INTO `cloth_types` (`id`, `name`, `image`, `status`, `date_add`, `date_upd`, `author`) VALUES
(2, 'Футболки (Международная система)', '', 1, '1495209800', '1495209800', 1),
(3, 'Брюки (Международная система)', '', 1, '1495637859', '1495637859', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `parent_id` int(10) NOT NULL,
  `child` int(1) NOT NULL,
  `name` varchar(130) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` int(12) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `page_id` varchar(130) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `answer` int(1) NOT NULL,
  `chain` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;

-- --------------------------------------------------------

--
-- Структура таблицы `components`
--

CREATE TABLE `components` (
  `id` int(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `path` varchar(255) NOT NULL,
  `img` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `language` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `components`
--

INSERT INTO `components` (`id`, `name`, `path`, `img`, `type`, `language`) VALUES
(1, 'Управление контентом', 'content', 'content_icon.png', 'content', 'RU'),
(2, 'Управление категорияим', 'categories', 'categories_icon.png', 'content', 'RU'),
(3, 'Пользователи', 'users', 'users_icon.png', 'options', 'RU'),
(4, 'Настройки системы', 'configs', 'options_icon.png', 'options', 'RU'),
(5, 'Настройка модулей', 'modules', 'module_icon.png', 'view', 'RU'),
(6, 'Менеджер шаблонов', 'templates', 'tmplt_icon.png', 'view', 'RU'),
(7, 'Комментарии', 'comments', 'comments_icon.png', 'plugin', 'RU'),
(8, 'Content manager', 'content', 'content_icon.png', 'content', 'EN'),
(9, 'Categories manager', 'categories', 'categories_icon.png', 'content', 'EN'),
(10, 'Users', 'users', 'users_icon.png', 'options', 'EN'),
(11, 'System configs', 'configs', 'options_icon.png', 'options', 'EN'),
(12, 'Modules manager', 'modules', 'module_icon.png', 'view', 'EN'),
(13, 'Templates manager', 'templates', 'tmplt_icon.png', 'view', 'EN'),
(14, 'Comments', 'comments', 'comments_icon.png', 'plugin', 'EN'),
(15, 'Content manager', 'content', 'content_icon.png', 'content', 'DE'),
(16, 'Kategorien manager', 'categories', 'categories_icon.png', 'content', 'DE'),
(17, 'Benutzer', 'users', 'users_icon.png', 'options', 'DE'),
(18, 'System konfigurationen', 'configs', 'options_icon.png', 'options', 'DE'),
(19, 'Module manager', 'modules', 'module_icon.png', 'view', 'DE'),
(20, 'Vorlagen manager', 'templates', 'tmplt_icon.png', 'view', 'DE'),
(21, 'Bemerkungen', 'comments', 'comments_icon.png', 'plugin', 'DE'),
(22, 'Управление товарами', 'product', 'product_icon.png', 'shop', 'RU'),
(23, 'Products manager', 'product', 'product_icon.png', 'shop', 'EN'),
(24, 'Produkte-Manager', 'product', 'product_icon.png', 'shop', 'DE'),
(25, 'Mögliche Optionen', 'parameters', 'parameters_icon.png', 'shop', 'DE'),
(26, 'Параметры продуктов', 'parameters', 'parameters_icon.png', 'shop', 'RU'),
(27, 'Options products', 'parameters', 'parameters_icon.png', 'shop', 'EN'),
(28, 'Управление заказами', 'order', 'order_icon.png', 'shop', 'RU'),
(29, 'Shop manager', 'order', 'order_icon.png', 'shop', 'EN'),
(30, 'Filialleiter', 'order', 'order_icon.png', 'shop', 'DE'),
(31, 'Типы и размеры', 'sizes', 'sizes_icon.png', 'shop', 'RU'),
(33, 'Types and sizes', 'sizes', 'sizes_icon.png', 'shop', 'EN'),
(34, 'Typen und Größen', 'sizes', 'sizes_icon.png', 'shop', 'DE'),
(35, 'Заказчики', 'clients', 'clients_icon.png', 'clients', 'RU'),
(36, 'Costumers', 'clients', 'clients_icon.png', 'clients', 'EN'),
(37, 'Kunden', 'clients', 'clients_icon.png', 'clients', 'DE'),
(38, 'Поставщики', 'suppliers', 'suppliers_icon.png', 'clients', 'RU'),
(39, 'Suppliers', 'suppliers', 'suppliers_icon.png', 'clients', 'EN'),
(40, 'Lieferanten', 'suppliers', 'suppliers_icon.png', 'clients', 'DE');

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE `content` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `show_title` int(1) NOT NULL,
  `show_date` int(1) NOT NULL,
  `showCmnt` int(1) NOT NULL,
  `cat_img` int(1) NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `contype` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `content` longtext COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `keywords` varchar(500) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `template` varchar(100) COLLATE utf8_bin NOT NULL,
  `main_lang` varchar(20) COLLATE utf8_bin NOT NULL,
  `language` mediumtext COLLATE utf8_bin,
  `params` mediumtext COLLATE utf8_bin,
  `brand` int(5) DEFAULT NULL,
  `ordr` int(3) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_bin NOT NULL,
  `views` int(10) NOT NULL,
  `comments` int(5) NOT NULL,
  `status` int(1) NOT NULL,
  `date_add` int(12) NOT NULL,
  `date_upd` int(12) NOT NULL,
  `author` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `content`
--

INSERT INTO `content` (`id`, `name`, `show_title`, `show_date`, `showCmnt`, `cat_img`, `url`, `contype`, `content`, `title`, `keywords`, `description`, `template`, `main_lang`, `language`, `params`, `brand`, `ordr`, `category`, `views`, `comments`, `status`, `date_add`, `date_upd`, `author`) VALUES
(1, 'Магазин одежды - Главная', 2, 2, 2, 2, 'main', '0', '', 'Магазин одежды - главная', '', 'Магазин одежды - главная', '1', 'RU', 'a:1:{s:2:\"EN\";s:3:\"en/\";}', NULL, NULL, 0, '1', 1, 0, 1, 1464822418, 1500508627, '1'),
(2, 'Одежда', 2, 2, 2, 2, 'odejda/', '0', '', 'Одежда', 'Одежда', 'Одежда', '2', 'RU', NULL, NULL, NULL, 0, '6', 1, 0, 1, 1464822418, 1499783688, '1'),
(3, 'Прогулка по Сорренто: Новая реклама Dolce & Gabbana', 2, 2, 2, 2, 'sorento-d-g/', 'news', '<p>Рекламную кампанию своей летней коллекции Tropico Italiano бренд Dolce &amp; Gabbana снимал на улицах Сорренто.</p>', 'Прогулка по Сорренто: Новая реклама Dolce & Gabbana', '', 'Рекламную кампанию своей летней коллекции Tropico Italiano бренд Dolce & Gabbana снимал на улицах Сорренто.', '6', 'RU', NULL, NULL, NULL, 0, '10', 2, 0, 1, 1465169553, 1499784021, '1'),
(4, 'Представлена осенне-зимняя коллекция бренда J.PEREKRIOSTOVA', 2, 2, 2, 2, 'kolekcia-brenda-perekristova/', 'news', '<h3>Представлена осенне-зимняя коллекция бренда J.PEREKRIOSTOVA</h3>', 'Представлена осенне-зимняя коллекция бренда J.PEREKRIOSTOVA', '', 'Представлена осенне-зимняя коллекция бренда J.PEREKRIOSTOVA', '6', 'RU', NULL, NULL, NULL, 0, '10', 2, 0, 1, 1466121430, 1499784003, '1'),
(5, 'Мои покупки', 2, 2, 2, 2, 'cart/', 'cart', '<p>Корзина</p>', 'Корзина товаров', 'Корзина товаров', 'Корзина товаров', '4', 'RU', NULL, NULL, NULL, 0, '12', 1, 0, 1, 0, 1500935611, '1'),
(6, 'Появились купальники для киевского пляжного патруля от DARI Co Swimwear', 2, 2, 2, 2, 'kupalniki-dlia-patrulia/', 'news', '<p>Появились купальники для киевского пляжного патруля от DARI Co Swimwear</p>', 'Появились купальники для киевского пляжного патруля от DARI Co Swimwear', '', 'Появились купальники для киевского пляжного патруля от DARI Co Swimwear', '6', 'RU', NULL, NULL, NULL, 0, '10', 8, 0, 1, 1472820074, 1499783970, '1'),
(7, 'Baby Dior презентовали новое видео', 2, 2, 2, 2, 'baby-dior-prezentovali/', 'news', '', 'Baby Dior презентовали новое видео', '', 'Baby Dior презентовали новое видео', '6', 'RU', NULL, NULL, NULL, 0, '10', 1, 0, 1, 1479082922, 1499784067, '1'),
(8, '404', 2, 2, 2, 2, '404.html', 'page', '<div class=\"block-404\"><img src=\"/aviva/images/404.png\" /></div>', '404', '', '404', '6', 'RU', NULL, NULL, NULL, 0, '', 382, 0, 1, 1485475320, 1499784678, '1'),
(9, 'Новинки', 2, 2, 2, 2, 'Новинки', '0', '', 'Новинки', '', 'Новинки', '2', 'RU', NULL, NULL, NULL, 0, '3', 8, 0, 1, 1485527599, 1499784423, '1'),
(10, 'Скидки', 2, 2, 2, 2, 'skidki/', '0', '', 'Скидки', '', 'Скидки', '2', 'RU', NULL, NULL, NULL, 0, '', 11, 0, 1, 1485560812, 1499784472, '1'),
(11, 'Блог | Новости', 2, 2, 2, 2, 'blog/', '0', '', 'Блог | Новости', '', 'Блог | Новости', '5', 'RU', NULL, NULL, NULL, 0, '', 6, 0, 1, 1485562176, 1500509974, '1'),
(12, 'Lookbook', 2, 2, 2, 2, 'lookbook/', '0', '', 'Lookbook', '', 'Lookbook', '6', 'RU', NULL, NULL, NULL, 0, '8', 4, 0, 1, 1485565504, 1499784582, '1'),
(13, 'Страница брэнда', 2, 2, 2, 2, 'brandpage/', '0', '<div class=\"col-md-6\">\r\n<h3>\\</h3>\r\n</div>', 'Страница брэнда', '', 'Страница брэнда', '2', 'RU', NULL, NULL, NULL, 0, '', 10, 0, 1, 1485566704, 1499784656, '1'),
(14, 'Sign In', 2, 2, 2, 2, 'account/', 'account', '<p>sdfsdfs</p>', 'Sign in', '', '', '4', 'RU', NULL, NULL, NULL, 0, '2', 37, 0, 1, 0, 1499783607, '2'),
(15, 'Mainpage', 2, 2, 2, 2, 'en/', '0', '', 'Магазин одежды - главная', '', 'Магазин одежды - главная', '1', 'EN', 'a:1:{s:2:\"RU\";s:4:\"main\";}', NULL, NULL, 0, '1', 1, 0, 1, 1464822418, 1499814414, '1'),
(16, 'Новая страница для новости', 2, 2, 2, 2, '', '0', '', '', '', '', '', '', NULL, NULL, NULL, 0, '', 0, 0, 1, 1500507025, 1500507025, '1'),
(17, 'Эль Фэннинг загорает и купается в новой кампании Miu Miu', 2, 2, 2, 2, 'el-fenning-zagorajet-i-kupaetsia/', 'news', '<div class=\"quote\">&ldquo;Героини загорают и купаются, а их образы отсылают к эстетике культовых фильмов 60-х.&rdquo;</div>\r\n\r\n<p>Девушки примеряли купальники, шорты, пальто с принтами, яркие сандалии и пластиковые головные уборы из новой коллекции Miu Miu.</p>\r\n\r\n<div class=\"article-slider\">\r\n<div><img alt=\"\" src=\"/template/front-end/img/article2.jpg\" /></div>\r\n\r\n<div><img alt=\"\" src=\"/template/front-end/img/article1.jpg\" /></div>\r\n</div>', 'Эль Фэннинг загорает и купается в новой кампании Miu Miu', '', '«Внезапно, следующее лето» – такое название получила кампания весенне-летней коллекции Miu Miu, автором которой выступил британский фотограф Аласдер Маклеллан. Съемка проходила в Малибу, на мысе Дюм, а лицами кампании стали Эль Фаннинг, Карен Элсон, Лара Стоун, Соня Бен Аммар, Эльза Брисингер, Биржит Кос, Майова Николас и Эллен Роуз.', '6', 'RU', NULL, NULL, NULL, 0, '10', 31, 0, 1, 1500584984, 1500590821, '1'),
(18, 'Подтверждение заказа', 2, 2, 2, 2, 'checkout/', 'cart', '<p>Корзина</p>', 'Подтверждение заказа', 'Подтверждение заказа', 'Подтверждение заказа', '4', 'RU', NULL, NULL, NULL, 0, '12', 1, 0, 1, 0, 1500935611, '1'),
(19, '404', 2, 2, 2, 2, 'rest/', 'ajax', '', '404', '', '', 'null', 'RU', NULL, NULL, NULL, 0, '0', 0, 0, 1, 0, 1500935611, '1'),
(20, 'BEVZA', 2, 2, 2, 2, 'brand/bevza/', 'category', '<div class=\"col-xs-4\"><img alt=\"Bevza\" data-alt-src=\"images/bevza.jpg\" src=\"/images/upload/images/content/bevza.jpg\" /></div>\r\n\r\n<div class=\"col-xs-8\">\r\n<h3>BEVZA</h3>\r\n\r\n<p>Украинский бренд модной одежды и аксессуаров Bevza занял нишу минимализма в 2 006 года. Основные черты бренда Bevza - это простота, чувственность и подчеркнутая аккуратность. Чистые и точные линии, архитектурный крой одежды, монохромные цвета и смелость дизайна в сочетании с индивидуальностью являются главной особенностью украинского бренда. Понятие Bevza WD (white dress &ndash; белое платье) &ndash; это &quot;must-have&quot; бренда, символ чистоты и минимализма. Купить дизайнерскую одежду Bevza вы можете в нашем магазине.</p>\r\n</div>', 'BEVZA', 'BEVZA', 'BEVZA', '2', 'RU', NULL, NULL, 0, 0, '4', 790, 0, 1, 1501543041, 1502409782, '1'),
(22, 'Прогулка по Сорренто: Новая реклама Dolce & Gabbana', 2, 2, 2, 2, 'lookbook-randompage/', 'lookbook', '<p>Рекламную кампанию своей летней коллекции Tropico Italiano бренд Dolce &amp; Gabbana снимал на улицах Сорренто.</p>', 'Прогулка по Сорренто: <br>Новая реклама Dolce & Gabbana', '', 'Прогулка по Сорренто: \r\nНовая реклама Dolce & Gabbana', '6', 'RU', NULL, NULL, 0, 0, '8', 121, 0, 1, 1503424442, 1503498342, '2');

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` int(2) NOT NULL,
  `name` varchar(4) COLLATE utf8_bin NOT NULL,
  `val` varchar(10) COLLATE utf8_bin NOT NULL,
  `symbol` varchar(5) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`, `val`, `symbol`) VALUES
(1, 'UAH', '1', 'грн');

-- --------------------------------------------------------

--
-- Структура таблицы `dreambox`
--

CREATE TABLE `dreambox` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dreambox`
--

INSERT INTO `dreambox` (`id`, `client_id`, `product_id`, `status`) VALUES
(41, 118, 1, 1),
(42, 118, 7, 1),
(43, 118, 6, 1),
(44, 118, 4, 1),
(45, 118, 5, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int(20) NOT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL,
  `date` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `favorites`
--

INSERT INTO `favorites` (`id`, `client_id`, `product_id`, `date`) VALUES
(1, 2, 11, 1485449739),
(2, 2, 7, 1495449739);

-- --------------------------------------------------------

--
-- Структура таблицы `images`
--

CREATE TABLE `images` (
  `id` int(10) NOT NULL,
  `content_id` int(10) NOT NULL,
  `type` int(1) NOT NULL,
  `main` int(1) NOT NULL,
  `smallimg` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` int(1) NOT NULL,
  `date_add` varchar(12) COLLATE utf8_bin NOT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `images`
--

INSERT INTO `images` (`id`, `content_id`, `type`, `main`, `smallimg`, `image`, `status`, `date_add`, `date_upd`) VALUES
(2, 1, 2, 1, '/images/product/small/1499872494.jpg', '/images/product/1499872494.jpg', 1, '1499872494', '1499872494'),
(3, 1, 2, 2, '/images/product/small/1499872502.jpg', '/images/product/1499872502.jpg', 1, '1499872502', '1499872502'),
(4, 1, 2, 2, '/images/product/small/1499872509.jpg', '/images/product/1499872509.jpg', 1, '1499872509', '1499872509'),
(5, 1, 2, 2, '/images/product/small/1499872513.png', '/images/product/1499872513.png', 1, '1499872513', '1499872513'),
(6, 1, 2, 2, '/images/product/small/1499872518.jpg', '/images/product/1499872518.jpg', 1, '1499872518', '1499872518'),
(7, 1, 2, 2, '/images/product/small/1499872523.jpg', '/images/product/1499872523.jpg', 1, '1499872524', '1499872524'),
(8, 1, 3, 2, '/images/product/small/1499872502_1-value2.jpg', '/images/product/1499872502_1-value2.jpg', 1, '1499872572', '1499872572'),
(9, 1, 3, 2, '/images/product/small/1499872494_1-value2.jpg', '/images/product/1499872494_1-value2.jpg', 1, '1499872575', '1499872575'),
(10, 1, 3, 2, '/images/product/small/1499872509_1-value2.jpg', '/images/product/1499872509_1-value2.jpg', 1, '1499872578', '1499872578'),
(11, 1, 3, 2, '/images/product/small/1499872513_1-value2.jpg', '/images/product/1499872513_1-value2.jpg', 1, '1499872580', '1499872580'),
(12, 1, 3, 2, '/images/product/small/1499872518_1-value2.jpg', '/images/product/1499872518_1-value2.jpg', 1, '1499872599', '1499872599'),
(13, 1, 3, 2, '/images/product/small/1499872523_1-value2.png', '/images/product/1499872523_1-value2.png', 1, '1499872601', '1499872601'),
(14, 1, 3, 2, '/images/product/small/1499872494_1-value3.jpg', '/images/product/1499872494_1-value3.jpg', 1, '1499873275', '1499873275'),
(15, 1, 3, 2, '/images/product/small/1499872502_1-value3.jpg', '/images/product/1499872502_1-value3.jpg', 1, '1499873280', '1499873280'),
(16, 1, 3, 2, '/images/product/small/1499872509_1-value3.jpg', '/images/product/1499872509_1-value3.jpg', 1, '1499873288', '1499873288'),
(17, 1, 3, 2, '/images/product/small/1499872513_1-value3.jpg', '/images/product/1499872513_1-value3.jpg', 1, '1499873294', '1499873294'),
(18, 1, 3, 2, '/images/product/small/1499872518_1-value3.jpg', '/images/product/1499872518_1-value3.jpg', 1, '1499873297', '1499873297'),
(19, 1, 3, 2, '/images/product/small/1499872523_1-value3.jpg', '/images/product/1499872523_1-value3.jpg', 1, '1499873300', '1499873300'),
(20, 2, 2, 1, '/images/product/small/1499900383.jpg', '/images/product/1499900383.jpg', 1, '1499900383', '1499900383'),
(21, 2, 2, 2, '/images/product/small/1499900402.jpg', '/images/product/1499900402.jpg', 1, '1499900402', '1499900402'),
(22, 3, 2, 1, '/images/product/small/1499900508.jpg', '/images/product/1499900508.jpg', 1, '1499900508', '1499900508'),
(23, 3, 2, 2, '/images/product/small/1499900517.png', '/images/product/1499900517.png', 1, '1499900517', '1499900517'),
(25, 4, 2, 1, '/images/product/small/1499900646.jpg', '/images/product/1499900646.jpg', 1, '1499900647', '1499900647'),
(26, 4, 2, 2, '/images/product/small/1499900656.png', '/images/product/1499900656.png', 1, '1499900656', '1499900656'),
(27, 6, 2, 1, '/images/product/small/1499900734.jpg', '/images/product/1499900734.jpg', 1, '1499900735', '1499900735'),
(28, 6, 2, 2, '/images/product/small/1499900740.jpg', '/images/product/1499900740.jpg', 1, '1499900740', '1499900740'),
(29, 7, 2, 1, '/images/product/small/1499900809.jpg', '/images/product/1499900809.jpg', 1, '1499900809', '1499900809'),
(30, 7, 2, 2, '/images/product/small/1499900824.jpg', '/images/product/1499900824.jpg', 1, '1499900824', '1499900824'),
(31, 5, 2, 1, '/images/product/small/1499900898.png', '/images/product/1499900898.png', 1, '1499900899', '1499900899'),
(32, 5, 2, 2, '/images/product/small/1499900909.jpg', '/images/product/1499900909.jpg', 1, '1499900909', '1499900909'),
(33, 1, 2, 2, '/images/product/small/1500410367.png', '/images/product/1500410367.png', 1, '1500410367', '1500410367'),
(34, 3, 1, 1, '/images/content/small/1500503943.jpg', '/images/content/1500503943.jpg', 1, '1500503944', '1500503944'),
(35, 4, 1, 1, '/images/content/small/1500503973.png', '/images/content/1500503973.png', 1, '1500503973', '1500503973'),
(36, 6, 1, 1, '/images/content/small/1500503984.png', '/images/content/1500503984.png', 1, '1500503984', '1500503984'),
(37, 7, 1, 1, '/images/content/small/1500503997.png', '/images/content/1500503997.png', 1, '1500503997', '1500503997'),
(38, 17, 1, 1, '/images/content/small/1500585009.jpg', '/images/content/1500585009.jpg', 1, '1500585010', '1500585010'),
(40, 20, 1, 1, '/images/content/small/1501543172.png', '/images/content/1501543172.png', 1, '1501543172', '1501543172'),
(43, 21, 1, 2, '/images/content/small/1503394810.png', '/images/content/1503394810.png', 1, '1503394811', '1503394811'),
(44, 21, 1, 2, '/images/content/small/1503394819.jpg', '/images/content/1503394819.jpg', 1, '1503394819', '1503394819'),
(45, 22, 1, 1, '/images/content/small/1503425065.jpg', '/images/content/1503425065.jpg', 1, '1503425066', '1503425066'),
(46, 22, 1, 2, '/images/content/small/1503425078.jpg', '/images/content/1503425078.jpg', 1, '1503425078', '1503425078');

-- --------------------------------------------------------

--
-- Структура таблицы `language`
--

CREATE TABLE `language` (
  `id` int(2) NOT NULL,
  `status` int(1) NOT NULL,
  `date_add` varchar(12) COLLATE utf8_bin NOT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin NOT NULL,
  `smallimg` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(10) COLLATE utf8_bin NOT NULL,
  `short_name` varchar(100) COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `language`
--

INSERT INTO `language` (`id`, `status`, `date_add`, `date_upd`, `smallimg`, `name`, `short_name`) VALUES
(0, 1, '1499815123', '', '/images/lang/flag/1499815123.jpg', 'Русский', 'RU'),
(12, 1, '1495200091', '', '/images/lang/flag/1495200091.jpg', 'English', 'EN');

-- --------------------------------------------------------

--
-- Структура таблицы `lookbook`
--

CREATE TABLE `lookbook` (
  `id` int(10) NOT NULL,
  `content_id` int(10) DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `lookbook`
--

INSERT INTO `lookbook` (`id`, `content_id`, `product_id`) VALUES
(10, 20, 7),
(13, 20, 6),
(14, 20, 1),
(16, 20, 7),
(18, 20, 3),
(19, 20, 13),
(20, 20, 11),
(21, 22, 7),
(22, 22, 6),
(23, 22, 2),
(24, 22, 4),
(25, 22, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT '0',
  `ident` varchar(50) NOT NULL,
  `sort` tinyint(5) NOT NULL DEFAULT '0',
  `type` enum('cat','module') NOT NULL DEFAULT 'module',
  `enable` enum('y','n') NOT NULL DEFAULT 'y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu`
--

INSERT INTO `menu` (`id`, `id_parent`, `ident`, `sort`, `type`, `enable`) VALUES
(1, NULL, '#', 18, 'cat', 'y'),
(2, NULL, '#', 9, 'cat', 'y'),
(3, NULL, '#', 8, 'cat', 'y'),
(4, NULL, '#', 7, 'cat', 'y'),
(6, NULL, '#', 1, 'cat', 'y'),
(7, 1, 'users', 10, 'module', 'y'),
(8, 1, 'configs', 9, 'module', 'y'),
(9, 2, 'categories', 5, 'module', 'y'),
(10, 3, 'content', 5, 'module', 'y'),
(11, 4, 'modules', 2, 'module', 'y'),
(12, 4, 'templates', 1, 'module', 'y'),
(13, 6, 'comments', 9, 'module', 'y'),
(14, 16, 'product', 2, 'module', 'y'),
(15, 16, 'parameters', 4, 'module', 'y'),
(16, NULL, '#', 15, 'cat', 'y'),
(17, 16, 'order', 5, 'module', 'y'),
(18, 16, 'sizes', 5, 'module', 'y'),
(19, NULL, '#', 13, 'cat', 'y'),
(20, 19, 'clients', 3, 'module', 'y'),
(21, 19, 'suppliers', 5, 'module', 'y');

-- --------------------------------------------------------

--
-- Структура таблицы `menu_name`
--

CREATE TABLE `menu_name` (
  `id` int(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `language` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_name`
--

INSERT INTO `menu_name` (`id`, `name`, `language`) VALUES
(1, 'Optionen', 'DE'),
(1, 'Options', 'EN'),
(1, 'Настройки', 'RU'),
(2, 'Categories', 'EN'),
(2, 'Kategorien', 'DE'),
(2, 'Категории', 'RU'),
(3, 'Content', 'EN'),
(3, 'Inhalt', 'DE'),
(3, 'Контент', 'RU'),
(4, 'Template', 'EN'),
(4, 'Vorlage', 'DE'),
(4, 'Внешний вид', 'RU'),
(6, 'Plugins', 'DE'),
(6, 'Plugins', 'EN'),
(6, 'Плагины', 'RU'),
(7, 'Benutzer-Manager', 'DE'),
(7, 'Users manager', 'EN'),
(7, 'Управление пользователями', 'RU'),
(8, 'Site config', 'DE'),
(8, 'Site config', 'EN'),
(8, 'Настройка сайта', 'RU'),
(9, 'Categories manager', 'EN'),
(9, 'Kategorien manager', 'DE'),
(9, 'Управление категорияим', 'RU'),
(10, 'Content manager', 'EN'),
(10, 'Content-manager', 'DE'),
(10, 'Управление контентом', 'RU'),
(11, 'Module manager', 'DE'),
(11, 'Modules manager', 'EN'),
(11, 'Настройка модулей', 'RU'),
(12, 'Templates manager', 'EN'),
(12, 'Vorlage manager', 'DE'),
(12, 'Менеджер шаблонов', 'RU'),
(13, 'Bemerkungen', 'DE'),
(13, 'Comments', 'EN'),
(13, 'Комментарии', 'RU'),
(14, 'Products manager', 'EN'),
(14, 'Produkte-Manager', 'DE'),
(14, 'Управление товарами', 'RU'),
(15, 'Mögliche Optionen', 'DE'),
(15, 'Product options', 'EN'),
(15, 'Параметры товаров', 'RU'),
(16, 'Filialleiter', 'DE'),
(16, 'Shop', 'EN'),
(16, 'Магазин', 'RU'),
(17, 'Auftragsmanagement', 'DE'),
(17, 'Orders manager', 'EN'),
(17, 'Управление заказами', 'RU'),
(18, 'Größen', 'DE'),
(18, 'Sizes', 'EN'),
(18, 'Размеры и типы', 'RU'),
(19, 'Clients and orders', 'EN'),
(19, 'Kunden und Aufträge', 'DE'),
(19, 'Клиенты и заказы', 'RU'),
(20, 'Costumers', 'EN'),
(20, 'Kunden', 'DE'),
(20, 'Заказчики', 'RU'),
(21, 'Lieferanten', 'DE'),
(21, 'Suppliers', 'EN'),
(21, 'Поставщики', 'RU');

-- --------------------------------------------------------

--
-- Структура таблицы `menu_user_types_relations`
--

CREATE TABLE `menu_user_types_relations` (
  `menu_id` int(11) NOT NULL,
  `user_types_id` int(11) NOT NULL,
  `access` int(1) NOT NULL,
  `add` int(1) NOT NULL,
  `edit_your` int(1) NOT NULL,
  `edit` int(1) NOT NULL,
  `change_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_user_types_relations`
--

INSERT INTO `menu_user_types_relations` (`menu_id`, `user_types_id`, `access`, `add`, `edit_your`, `edit`, `change_status`) VALUES
(1, 1, 1, 1, 0, 1, 1),
(2, 1, 1, 1, 0, 1, 1),
(3, 1, 1, 1, 0, 1, 1),
(4, 1, 1, 1, 0, 1, 1),
(6, 1, 1, 1, 0, 1, 1),
(7, 1, 1, 1, 0, 1, 1),
(8, 1, 1, 1, 0, 1, 1),
(9, 1, 1, 1, 0, 1, 1),
(10, 1, 1, 1, 0, 1, 1),
(11, 1, 1, 1, 0, 1, 1),
(12, 1, 1, 1, 0, 1, 1),
(13, 1, 1, 1, 0, 1, 1),
(1, 2, 1, 0, 0, 0, 0),
(2, 2, 1, 0, 0, 0, 0),
(3, 2, 1, 0, 0, 0, 0),
(4, 2, 1, 0, 0, 0, 0),
(6, 2, 1, 0, 0, 0, 0),
(7, 2, 1, 1, 1, 0, 0),
(8, 2, 1, 1, 1, 0, 0),
(9, 2, 1, 1, 1, 0, 0),
(10, 2, 1, 1, 1, 0, 0),
(11, 2, 1, 1, 1, 0, 0),
(12, 2, 1, 1, 1, 0, 0),
(13, 2, 1, 1, 1, 0, 0),
(14, 1, 1, 1, 0, 1, 1),
(14, 2, 1, 1, 1, 0, 0),
(15, 1, 1, 1, 0, 1, 1),
(15, 2, 0, 0, 0, 0, 0),
(17, 1, 1, 1, 0, 1, 1),
(17, 2, 1, 0, 1, 1, 1),
(16, 1, 1, 0, 1, 1, 1),
(16, 2, 1, 0, 1, 1, 1),
(1, 3, 1, 0, 0, 0, 0),
(2, 3, 1, 0, 0, 0, 0),
(3, 3, 1, 0, 0, 0, 0),
(4, 3, 0, 0, 0, 0, 0),
(6, 3, 1, 0, 0, 0, 0),
(16, 3, 1, 0, 0, 0, 0),
(7, 3, 1, 1, 1, 0, 1),
(8, 3, 0, 0, 0, 0, 0),
(9, 3, 1, 1, 1, 1, 1),
(10, 3, 1, 1, 1, 1, 1),
(11, 3, 0, 0, 0, 0, 0),
(12, 3, 1, 1, 1, 1, 1),
(13, 3, 1, 1, 1, 1, 1),
(14, 3, 1, 1, 1, 1, 1),
(15, 3, 1, 1, 1, 1, 1),
(17, 3, 1, 1, 1, 1, 1),
(18, 1, 1, 1, 0, 1, 1),
(19, 1, 1, 0, 0, 0, 0),
(19, 2, 0, 0, 0, 0, 0),
(20, 1, 1, 1, 0, 1, 1),
(20, 2, 0, 0, 0, 0, 0),
(21, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `modules`
--

CREATE TABLE `modules` (
  `id` int(5) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `category` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `scripts` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  `code` text COLLATE utf8_bin,
  `date_add` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `author` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `modules`
--

INSERT INTO `modules` (`id`, `name`, `category`, `scripts`, `status`, `code`, `date_add`, `date_upd`, `author`) VALUES
(1, 'Главное меню', 'menu', NULL, '1', 'a:8:{s:10:\"show_title\";b:0;s:3:\"css\";s:24:\"collapse navbar-collapse\";s:6:\"ul_css\";s:27:\"nav nav-justified main-menu\";s:11:\"level_start\";s:1:\"0\";s:9:\"level_end\";s:1:\"0\";s:10:\"show_child\";b:0;s:8:\"cat_tree\";s:23:\"Главное меню\";s:10:\"parent_cat\";b:0;}', '1464564761', '1499785535', '1'),
(2, 'Новинки и избранное - ГЛАВНАЯ', 'code', NULL, '1', '/core/module/view/code/module2.php', '1464736693', '1499813174', '1'),
(3, 'Панель авторизации', 'code', '', '1', '/core/module/view/code/module3.php', '1464737024', '1503949844', '1'),
(4, 'Переключение языков', 'lang', NULL, '1', 'a:5:{s:3:\"css\";s:35:\"col-lg-7 col-md-6 col-sm-4 col-xs-4\";s:10:\"show_title\";i:1;s:9:\"show_text\";b:0;s:8:\"show_img\";b:0;s:10:\"parent_cat\";b:0;}', '1464738050', '1499814554', '1'),
(5, 'Новинки', 'products', NULL, '1', 'a:28:{s:10:\"show_title\";i:1;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:0:\"\";s:3:\"css\";s:18:\"new-items-carousel\";s:9:\"block_css\";s:7:\"product\";s:10:\"show_order\";s:24:\"ORDER BY c.date_add DESC\";s:6:\"params\";s:0:\"\";s:5:\"qunty\";s:1:\"6\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:5:\"6,7,9\";s:11:\"page_depend\";b:0;s:4:\"date\";s:0:\"\";s:11:\"monthFormat\";s:0:\"\";s:7:\"showCat\";b:0;s:10:\"show_price\";i:1;s:9:\"show_date\";b:0;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:0:\"\";s:8:\"img_size\";s:0:\"\";s:6:\"slider\";i:1;}', '1464822975', '1500319484', '1'),
(6, 'Поиск', 'search', 'search.js', '1', 'btn-group', '1464826035', '1502318680', '1'),
(7, 'Часто просматриваемые', 'products', NULL, '1', 'a:28:{s:10:\"show_title\";i:1;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:0:\"\";s:3:\"css\";s:15:\"carousel-looked\";s:9:\"block_css\";s:7:\"product\";s:10:\"show_order\";s:21:\"ORDER BY c.views DESC\";s:6:\"params\";s:0:\"\";s:5:\"qunty\";s:1:\"4\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:5:\"6,7,9\";s:11:\"page_depend\";b:0;s:4:\"date\";s:0:\"\";s:11:\"monthFormat\";s:0:\"\";s:7:\"showCat\";b:0;s:10:\"show_price\";i:1;s:9:\"show_date\";b:0;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:0:\"\";s:8:\"img_size\";s:0:\"\";s:6:\"slider\";i:1;}', '1464915462', '1500319478', '1'),
(8, 'Другие товары этого дизайнера', 'products', '', '1', 'a:29:{s:10:\"show_title\";i:1;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:0:\"\";s:3:\"css\";s:20:\"carousel-other-items\";s:9:\"block_css\";s:7:\"product\";s:10:\"show_order\";s:24:\"ORDER BY c.date_add DESC\";s:5:\"brand\";s:0:\"\";s:5:\"qunty\";s:1:\"5\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:1:\"6\";s:11:\"page_depend\";b:0;s:12:\"brand_depend\";i:1;s:4:\"date\";s:0:\"\";s:11:\"monthFormat\";s:0:\"\";s:7:\"showCat\";b:0;s:10:\"show_price\";i:1;s:9:\"show_date\";b:0;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:0:\"\";s:8:\"img_size\";s:0:\"\";s:6:\"slider\";b:0;}', '1465165886', '1502319156', '1'),
(9, 'Скидки - ГЛАВНАЯ', 'code', NULL, '1', '/core/module/view/code/module9.php', '1465169203', '1500319008', '1'),
(10, 'Вам так же могут понравиться', 'products', NULL, '1', 'a:29:{s:10:\"show_title\";i:1;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:0:\"\";s:3:\"css\";s:17:\"carousel-you-like\";s:9:\"block_css\";s:7:\"product\";s:10:\"show_order\";s:21:\"ORDER BY c.views DESC\";s:5:\"brand\";s:0:\"\";s:5:\"qunty\";s:1:\"5\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";b:0;s:11:\"page_depend\";i:1;s:12:\"brand_depend\";b:0;s:4:\"date\";s:0:\"\";s:11:\"monthFormat\";s:0:\"\";s:7:\"showCat\";b:0;s:10:\"show_price\";i:1;s:9:\"show_date\";b:0;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:0:\"\";s:8:\"img_size\";s:0:\"\";s:6:\"slider\";b:0;}', '1466693792', '1500488345', '1'),
(11, 'Скидки (мобильная) - ГЛАВНАЯ', 'code', NULL, '1', '/core/module/view/code/module11.php', '1466033821', '1500319082', '1'),
(13, 'Меню в футере', 'menu', NULL, '1', 'a:8:{s:10:\"show_title\";b:0;s:3:\"css\";s:49:\"col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2\";s:6:\"ul_css\";s:39:\"nav nav-pills nav-justified menu-bottom\";s:11:\"level_start\";s:1:\"0\";s:9:\"level_end\";s:1:\"2\";s:10:\"show_child\";b:0;s:8:\"cat_tree\";b:0;s:10:\"parent_cat\";b:0;}', '1466034460', '1500591378', '1'),
(14, 'Футер', 'code', NULL, '1', '/core/module/view/code/module14.php', '1466035346', '1499816772', '1'),
(15, 'Currency', 'currency', NULL, '1', 'a:4:{s:3:\"css\";s:27:\"dropdown-select drop-scroll\";s:9:\"show_text\";i:1;s:11:\"show_symbol\";b:0;s:10:\"parent_cat\";b:0;}', '1466037511', '1466121155', '1'),
(16, 'Читать больше - ссылка на новости', 'code', '', '1', '/core/module/view/code/module16.php', '1466121764', '1500592497', '1'),
(17, 'Актуальные новости', 'pagelinks', '', '1', 'a:27:{s:10:\"show_title\";i:1;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:0:\"\";s:3:\"css\";s:3:\"row\";s:9:\"block_css\";s:9:\"news-item\";s:10:\"show_order\";s:24:\"ORDER BY c.date_add DESC\";s:6:\"params\";s:0:\"\";s:5:\"qunty\";s:1:\"3\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:2:\"10\";s:11:\"page_depend\";b:0;s:4:\"date\";s:5:\"j m Y\";s:11:\"monthFormat\";s:4:\"long\";s:7:\"product\";i:1;s:7:\"showCat\";b:0;s:9:\"show_date\";i:1;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:13:\"image-preview\";s:8:\"img_size\";s:0:\"\";}', '1466122049', '1500590898', '1'),
(18, 'Корзина над меню', 'code', NULL, '1', '/core/module/view/code/module18.php', '1466184761', '1501464610', '1'),
(19, 'Главная новость', 'pagelinks', NULL, '2', 'a:27:{s:10:\"show_title\";b:0;s:11:\"description\";i:1;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";b:0;s:11:\"links_ppage\";s:2:\"10\";s:3:\"css\";s:3:\"row\";s:9:\"block_css\";s:9:\"news-item\";s:10:\"show_order\";s:23:\"ORDER BY c.date_add ASC\";s:6:\"params\";s:0:\"\";s:5:\"qunty\";s:1:\"1\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:2:\"10\";s:11:\"page_depend\";b:0;s:4:\"date\";s:5:\"j m Y\";s:11:\"monthFormat\";s:4:\"long\";s:7:\"product\";i:1;s:7:\"showCat\";b:0;s:9:\"show_date\";i:1;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:13:\"image-preview\";s:8:\"img_size\";s:0:\"\";}', '1465169624', '1500567870', '1'),
(20, 'Diamonds picker', 'code', NULL, '1', '/core/module/view/code/module20.php', '1479083502', '1485569871', '1'),
(22, 'WAS GIBT\'S NEUES - CODE', 'code', NULL, '1', '/core/module/view/code/module21.php', '1485803620', '1485803620', '1'),
(23, 'Фильтры', 'filters', '', '1', 'a:2:{s:4:\"type\";s:7:\"filters\";s:10:\"parent_cat\";b:0;}', '1498086052', '1502318659', '1'),
(24, 'Все новости', 'pagelinks', NULL, '1', 'a:27:{s:10:\"show_title\";b:0;s:11:\"description\";i:1;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";i:1;s:11:\"links_ppage\";s:2:\"10\";s:3:\"css\";s:3:\"row\";s:9:\"block_css\";s:9:\"news-item\";s:10:\"show_order\";s:23:\"ORDER BY c.date_add ASC\";s:6:\"params\";s:0:\"\";s:5:\"qunty\";s:0:\"\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:2:\"10\";s:11:\"page_depend\";b:0;s:4:\"date\";s:5:\"j m Y\";s:11:\"monthFormat\";s:4:\"long\";s:7:\"product\";i:1;s:7:\"showCat\";b:0;s:9:\"show_date\";i:1;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:13:\"image-preview\";s:8:\"img_size\";s:0:\"\";}', '1465169624', '1500584633', '1'),
(25, 'Товары в категории', 'products', '', '1', 'a:30:{s:10:\"show_title\";b:0;s:11:\"description\";b:0;s:14:\"show_art_title\";i:1;s:12:\"title_params\";b:0;s:8:\"multi_pg\";b:0;s:5:\"pagin\";i:1;s:11:\"links_ppage\";s:1:\"3\";s:3:\"css\";s:3:\"row\";s:9:\"block_css\";s:25:\"product col-md-4 col-xs-6\";s:10:\"show_order\";s:24:\"ORDER BY c.date_add DESC\";s:5:\"brand\";s:0:\"\";s:5:\"qunty\";s:0:\"\";s:4:\"term\";s:0:\"\";s:10:\"parent_cat\";s:1:\"6\";s:11:\"page_depend\";b:0;s:12:\"brand_depend\";b:0;s:7:\"angular\";i:1;s:4:\"date\";s:0:\"\";s:11:\"monthFormat\";s:0:\"\";s:7:\"showCat\";b:0;s:10:\"show_price\";i:1;s:9:\"show_date\";b:0;s:8:\"showComm\";b:0;s:9:\"showViews\";b:0;s:7:\"statPos\";s:5:\"after\";s:8:\"show_img\";i:1;s:14:\"image_position\";s:0:\"\";s:7:\"img_css\";s:0:\"\";s:8:\"img_size\";s:0:\"\";s:6:\"slider\";b:0;}', '1465165886', '1502641919', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `modules_items`
--

CREATE TABLE `modules_items` (
  `id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `data` varchar(255) DEFAULT NULL,
  `ordr` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `modules_items`
--

INSERT INTO `modules_items` (`id`, `module_id`, `name`, `type`, `data`, `ordr`) VALUES
(1, 23, 'Категории', 1, '6', 1),
(2, 23, 'Категории', 1, '7', 2),
(4, 23, 'Цена', 5, '1', 4),
(5, 23, 'Размер', 4, '1', 5),
(6, 23, 'Параметр товара', 3, '1', 6),
(40, 23, 'Производитель', 2, '0', 3);

-- --------------------------------------------------------

--
-- Структура таблицы `options`
--

CREATE TABLE `options` (
  `id` int(1) NOT NULL,
  `admin_folder` varchar(100) NOT NULL,
  `path` varchar(50) NOT NULL,
  `sitename` varchar(50) NOT NULL,
  `admin_lang` varchar(10) NOT NULL,
  `admin_alias` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `options`
--

INSERT INTO `options` (`id`, `admin_folder`, `path`, `sitename`, `admin_lang`, `admin_alias`) VALUES
(1, 'admin', '/', 'Shop', 'RU', 'Sh');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(6) NOT NULL,
  `id_client` tinyint(5) UNSIGNED DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `shipping_type` int(2) DEFAULT NULL,
  `payment_type` int(3) DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `price` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `comment` text COLLATE utf8_bin,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `id_client`, `date_add`, `shipping_type`, `payment_type`, `payment_status`, `price`, `comment`, `status`) VALUES
(8, 1, '2017-07-29 03:49:47', 2, 2, '', '405', NULL, 1),
(9, 1, '2017-07-29 03:53:05', 2, 2, '', '405', NULL, 1),
(10, 1, '2017-07-29 03:53:41', 2, 2, '', '405', NULL, 1),
(11, 1, '2017-07-29 03:55:56', 2, 2, '', '405', NULL, 1),
(12, 1, '2017-07-29 03:56:18', 2, 2, '', '405', NULL, 1),
(13, 1, '2017-07-29 03:56:52', 2, 2, '', '405', NULL, 1),
(14, 1, '2017-07-29 03:59:28', 2, 2, '', '405', NULL, 1),
(15, 1, '2017-07-29 03:59:34', 2, 2, '', '405', NULL, 1),
(16, 1, '2017-07-29 04:02:24', 2, 2, '', '405', NULL, 1),
(17, 1, '2017-07-29 04:05:08', 2, 2, '', '405', NULL, 1),
(18, 1, '2017-07-29 04:05:43', 2, 2, '', '405', NULL, 1),
(19, 1, '2017-07-29 04:06:00', 1, 1, '', '405', NULL, 1),
(20, 1, '2017-07-29 04:06:15', 1, 1, '', '405', NULL, 1),
(21, 1, '2017-07-29 04:07:29', 1, 1, '', '405', NULL, 1),
(22, 1, '2017-07-29 04:07:37', 1, 1, '', '405', NULL, 1),
(23, 1, '2017-07-29 04:08:01', 1, 1, '', '405', NULL, 1),
(24, 1, '2017-07-29 04:08:23', 1, 1, '', '405', NULL, 1),
(25, 1, '2017-07-29 04:08:39', 1, 1, '', '405', NULL, 1),
(26, 1, '2017-07-29 04:09:38', 1, 1, '', '405', NULL, 1),
(27, 1, '2017-07-29 04:12:06', 1, 1, '', '405', NULL, 1),
(28, 1, '2017-07-29 04:13:47', 1, 1, '', '405', NULL, 1),
(29, 116, '2017-08-15 00:00:00', 2, 2, NULL, '1535', NULL, 1),
(30, 116, '2017-08-03 00:00:00', 2, 2, NULL, '455', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(7) NOT NULL,
  `order_id` int(6) NOT NULL,
  `product_id` int(6) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `image` varchar(255) COLLATE utf8_bin NOT NULL,
  `price` varchar(50) COLLATE utf8_bin NOT NULL,
  `options` text COLLATE utf8_bin NOT NULL,
  `size` int(5) DEFAULT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `name`, `image`, `price`, `options`, `size`, `status`) VALUES
(36, 8, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(37, 8, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(38, 8, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(39, 9, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(40, 9, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(41, 9, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(42, 10, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(43, 10, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(44, 10, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(45, 11, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(46, 11, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(47, 11, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(48, 12, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(49, 12, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(50, 12, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(51, 13, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(52, 13, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(53, 13, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(54, 14, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(55, 14, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(56, 14, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(57, 15, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(58, 15, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(59, 15, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(60, 16, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(61, 16, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(62, 16, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(63, 17, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(64, 17, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(65, 17, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(66, 18, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(67, 18, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(68, 18, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(69, 19, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(70, 19, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(71, 19, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(72, 20, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(73, 20, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(74, 20, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(78, 22, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(79, 22, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(80, 22, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(81, 23, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(82, 23, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(83, 23, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(84, 24, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(85, 24, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(86, 24, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(87, 25, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(88, 25, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(89, 25, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(90, 26, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(91, 26, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(92, 26, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(93, 27, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(94, 27, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(95, 27, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(96, 28, 1, '', '/images/product/small/1499872494_1-value3.jpg', '135', 'a:1:{s:10:\"imagebtn-1\";s:1:\"3\";}', 0, 1),
(97, 28, 7, '', '/images/product/small/1499900809.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 0, 1),
(98, 28, 1, '', '/images/product/small/1499872494-125.jpg', '135', 'a:2:{s:10:\"imagebtn-1\";s:1:\"3\";s:4:\"null\";N;}', 11, 1),
(99, 29, 7, '', '/images/product/small/1499900809.jpg', '135', '', NULL, 1),
(100, 30, 1, '', '/images/product/small/1499872494-125.jpg', '158', '', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `params`
--

CREATE TABLE `params` (
  `id` int(2) NOT NULL,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `status` int(1) NOT NULL,
  `date_add` varchar(12) COLLATE utf8_bin NOT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `show_title` int(1) NOT NULL,
  `show_date` int(1) NOT NULL,
  `showCmnt` int(1) NOT NULL,
  `cat_img` int(1) NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `contype` varchar(50) COLLATE utf8_bin NOT NULL,
  `content` longtext COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `keywords` varchar(500) COLLATE utf8_bin NOT NULL,
  `description` mediumtext COLLATE utf8_bin NOT NULL,
  `template` varchar(100) COLLATE utf8_bin NOT NULL,
  `main_lang` varchar(20) COLLATE utf8_bin NOT NULL,
  `language` mediumtext COLLATE utf8_bin,
  `params` mediumtext COLLATE utf8_bin,
  `ordr` int(3) DEFAULT NULL,
  `category` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `views` int(10) DEFAULT NULL,
  `comments` int(5) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `oldprice` decimal(8,2) DEFAULT NULL,
  `brand` int(5) DEFAULT NULL,
  `type_size` int(10) DEFAULT NULL,
  `bonus` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `vendor` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `options_array` text COLLATE utf8_bin,
  `status` int(1) NOT NULL,
  `date_add` int(12) NOT NULL,
  `date_upd` int(12) NOT NULL,
  `author` varchar(100) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `show_title`, `show_date`, `showCmnt`, `cat_img`, `url`, `contype`, `content`, `title`, `keywords`, `description`, `template`, `main_lang`, `language`, `params`, `ordr`, `category`, `views`, `comments`, `price`, `oldprice`, `brand`, `type_size`, `bonus`, `vendor`, `options_array`, `status`, `date_add`, `date_upd`, `author`) VALUES
(1, 'Платье', 2, 2, 2, 2, 'item-page/', 'product', '<p class=\"item-element\">Аппликация: <span>бантик</span></p>\r\n\r\n<p class=\"item-element\">Застёжка: <span>задняя , застёжка на молнию</span></p>\r\n\r\n<p class=\"item-element\">Подкладка: <span>с подкладкой</span></p>\r\n\r\n<p class=\"item-element\">Ворот: <span>воротник</span></p>\r\n\r\n<p class=\"item-element\">Карманы: <span>два кармана спереди</span></p>\r\n\r\n<p class=\"item-element\">Рукова: <span>длинные</span></p>\r\n\r\n<p class=\"item-element\">Основной материал: <span> Натуральная Шерсть 65 % Шёлк 35 %</span></p>\r\n\r\n<p class=\"item-element\">Вторичные материалы: <span> Шёлк 100 % </span></p>', 'Платье', '', 'Платье', '3', 'RU', NULL, 'N;', 0, '13', 391, 0, '135.00', '215.00', 4, 2, '1.0', '805926', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1485449739, 1502379587, '1'),
(2, 'Ботинки', 2, 2, 2, 2, 'item-page/', 'product', '<p>Dieser in Perfektion gearbeiteter Diamantring zeichnet sich wegen seiner abgerundeten Form mit viel Charme und Eleganz aus. &nbsp;Er wird in unserer Goldschmiedewerkstatt f&uuml;r Sie in feinster Handarbeit aus 18 kt. Gold angefertigt, Sie k&ouml;nnen ihn in der Ausf&uuml;hrung Gelb, Wei&szlig; oder Rosegold fertigen lassen.</p>\r\n\r\n<p>Der runde Brillantschliff kann Individuell zwischen den Gr&ouml;&szlig;en 0.20 ct. &ndash; 1.50 ct.&nbsp; gew&auml;hlt werden, bei diesem Modell ist eine andere Schliffart nicht m&ouml;glich zu besetzen.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Der Stein ist in einer Teilzargenfassung gesetzt und erh&auml;lt durch die Seiten&ouml;ffnungen genug Licht f&uuml;r eine optimale Brillanz.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Rounded', '', 'Dieser in Perfektion gearbeiteter Diamantring zeichnet sich wegen seiner abgerundeten Form mit viel Charme und Eleganz aus.', '3', 'RU', NULL, 'N;', 0, '16', 0, 0, '220.00', '0.00', 4, 2, '', '12121', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1464996646, 1502467098, '1'),
(3, 'Юбка', 2, 2, 2, 2, 'item-page/', 'product', '<p>Dieser ausgefallene Ring hat zwei Seiten und hebt sich durch die Individualit&auml;t ab und kann mit einem Princess Diamant besetzt werden, jedoch auch mit weiteren Schliffarten wie z.B. dem runden Brillantschliff.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>\r\n\r\n<p>Sie k&ouml;nnen ganz Individuell Ihren Stein ausw&auml;hlen in der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.50 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Die feinste Handarbeit aus einer Hand, unsere Goldschmiedewerkstatt fertigt Ihnen den Ring in 18kt Gold, Sie w&auml;hlen lediglich aus Gelb, Wei&szlig; oder Rosegold.</p>\r\n\r\n<p>Der Diamant wird in einer 4 Krappen Fassung gehalten und hebt sich mit hoher Brillanz ab, gerne k&ouml;nnen Sie das Modell auch in der Ausf&uuml;hrung mit 6 Krappen anfertigen.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Юбка', '', '', '3', 'RU', NULL, 'N;', 0, '6', 0, 0, '333.00', '215.00', 1, 2, '1.5', '1121', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1464996732, 1502467592, '1'),
(4, 'Платье', 2, 2, 2, 2, 'item-page/', 'product', '<p>Dieser Ring zeichnet sich aus einer eleganten und charmanten Form aus und kann mit dem klassischen runden Brillantschliff besetzt werden, sowohl auch mit weiteren Schliffarten wie z.B. dem Oval Schliff. Sie haben die M&ouml;glichkeit den Stein zwischen den Gr&ouml;&szlig;en 0.20 ct. &ndash; 1.50 ct. Auszuw&auml;hlen.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Die beste Handarbeit aus unserer Goldschmiedewerkstatt, gefertigt in 18kt Gold und Sie w&auml;hlen in welcher Ausf&uuml;hrung, Gelbgold, Wei&szlig;gold oder in Rosegold.</p>\r\n\r\n<p>Die 4 Krappen Fassung, hebt den besetzten Diamanten durch mehr Lichteinfluss hervor und l&auml;sst Ihn strahlen.&nbsp; Gerne k&ouml;nnen Sie Individuell dieses Modell in einer 6 Krappen Fassung anfertigen lassen.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Endless', '', '', '3', 'RU', NULL, 'N;', 0, '13', 13, 0, '135.00', '0.00', 4, 2, '0', '112123', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1485450667, 1502467598, '1'),
(5, 'Брюки', 2, 2, 2, 2, 'item-page/', 'product', '<p>Der au&szlig;ergew&ouml;hnliche Ring wird mit einem runden Brillantschliff in einer Teilzargenfassung gefasst und erh&auml;lt durch die Seiten&ouml;ffnungen genug Licht f&uuml;r die optimale Brillanz des Diamanten. Sie haben die M&ouml;glichkeit den Stein zwischen den Gr&ouml;&szlig;en 0.20 ct. &ndash; 1.50 ct. Auszuw&auml;hlen.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Dieser sehr elegante wie auch spezielle Diamantring, wird in feinster Handarbeit in unserer Goldschmiede Werkstatt f&uuml;r Sie angefertigt.</p>\r\n\r\n<p>Angefertigt wird der Ring in 18kt Gold, Sie k&ouml;nnen hierzu die Ausf&uuml;hrung von Gelbgold, Wei&szlig;gold oder Rosegold w&auml;hlen.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Брюки', '', 'Брюки', '3', 'RU', NULL, 'N;', 0, '6', 3, 0, '99.00', '333.00', 2, 2, '', '876876', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1466176346, 1499900933, '1'),
(6, 'Кофта', 2, 2, 2, 2, 'item-page/', 'product', '<p>Geschwungene Linien bringen diesen einzigartigen Ring in eine harmonische Form und kann mit einem runden Brillantschliff, sowohl mit weiteren Schliffarten wie z.B. einem Emerald Schliff besetzt werden. Sie haben die M&ouml;glichkeit den Stein zwischen den Gr&ouml;&szlig;en 0.20 ct. &ndash; 1.50 ct. Auszuw&auml;hlen.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Der Ring wird in bester Handarbeit von unserer Goldschmiede Werkstatt angefertigt und auf Ihren W&uuml;nschen auch gerne angepasst.</p>\r\n\r\n<p>Angefertigt wird der Ring in 18kt Gold, Sie k&ouml;nnen hierzu die Ausf&uuml;hrung von Gelbgold, Wei&szlig;gold oder Rosegold w&auml;hlen.</p>\r\n\r\n<p>Die 4 Krappen Fassung ist die Krone des Diamanten und hebt ihn in hoher Brillanz vor, gerne k&ouml;nnen Sie dieses Modell auch mit einer 6 Krappen Fassung fertigen lassen.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Кофта', '', 'Кофта', '', 'RU', NULL, 'N;', 0, '6', 0, 0, '135.00', '0.00', 1, 2, '1.5', '432423', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1466176377, 1499900776, '1'),
(7, 'Сумка', 2, 2, 2, 2, 'item-page/', 'product', '<p>Dieser in 18 kt Gold Handgefertigter Diamantring, kann mit einem runden Brillantschliff sowohl auch in anderen Schliffarten wie z.B. der Princess Schliff besetzt werden. Sie k&ouml;nnen ganz Individuell Ihren Stein ausw&auml;hlen in der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.50 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird Ihnen individuell ein Modell in 3D angefertigt, die Bearbeitung wird mit Ihnen pers&ouml;nlich besprochen, bis Ihre W&uuml;nsche umgesetzt werden.</p>\r\n\r\n<p>Der Diamant wird in einer abgehobenen 4 Krappen Fassung gesetzt, sodass der Stein seine volle Brillanz erh&auml;lt. Dieses Modell kann gerne in einer 6 Krappen Fassung f&uuml;r Sie angefertigt werden.</p>\r\n\r\n<p>W&auml;hlen Sie zwischen drei Ausf&uuml;hrungen aus 18kt Gelbgold, Wei&szlig;gold oder in Rosegold.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Сумка', '', 'Сумка', '3', 'RU', NULL, 'N;', 0, '6', 1, 0, '99.00', '0.00', 1, 2, '10', '312312', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1466177017, 1499900840, '1'),
(8, 'Classico 4 Krappen', 2, 2, 2, 2, 'de/ring-classico', 'product', '<p>Dieser klassische Solit&auml;rring, ist f&uuml;r viele Anl&auml;sse geeignet und kann nicht nur mit einem runden Brillantschliff gefasst werden, sondern auch weitere Schliffarten wie z.B. der Princess Diamant besetzt werden. Sie k&ouml;nnen die Steingr&ouml;&szlig;e individuell ausw&auml;hlen von 0.20 ct. &ndash; 1.50 ct.</p>\r\n\r\n<p>Die sehr elegante 4 Krappen Fassung, l&auml;sst den Diamanten sehr besonders in Geltung kommen, gerne kann auch dieses Modell in einer 6 Krappen Fassung f&uuml;r Sie hergestellt werden.</p>\r\n\r\n<p>Der Ring wird in perfekter Handarbeit in unseren Goldschmied Werkst&auml;tten hergestellt und kann indrei Ausf&uuml;hrung bestellt werden 18 Karat Gelbgold, Wei&szlig;gold oder in Rosegold.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Classico 4 Krappen', '', 'Dieser klassische Solitärring, ist für viele Anlässe geeignet und kann nicht nur mit einem runden Brillantschliff gefasst werden, sondern auch weitere Schliffarten wie z.B. der Princess Diamant besetzt werden.', '3', 'DE', NULL, NULL, 0, '1', 0, 0, '135.00', '0.00', 1, 0, '1.5', 'a:5:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";}', 'a:2:{i:4;a:2:{s:6:\"option\";s:1:\"7\";s:5:\"order\";i:1;}i:3;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:4;}}', 1, 1466177051, 1485443563, '1'),
(9, 'Timeless 4 Krappen', 2, 2, 2, 2, 'test', 'product', '<p>Dieser Zeitlose Ring wird in feinster Handarbeit f&uuml;r Sie in unserer Goldschmiede Werkstatt hergestellt f&uuml;r den besonderen Anlass. Sie k&ouml;nnen den Ring mit einem runden Brillantschliff oder auch in anderen Schliffarten besetzen lassen in der Gr&ouml;&szlig;e 0.20 ct. &ndash; 1.50 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird Ihnen individuell ein Modell in 3D angefertigt, die Bearbeitung wird mit Ihnen pers&ouml;nlich besprochen, bis Ihre W&uuml;nsche umgesetzt werden.</p>\r\n\r\n<p>Die sehr elegante 4 Krappen Fassung, l&auml;sst den Diamanten sehr besonders in Geltung kommen, gerne kann auch dieses Modell in einer 6 Krappen Fassung f&uuml;r Sie angefertigt werden.</p>\r\n\r\n<p>Der elegante Ring kann in verschiedener Ausf&uuml;hrung bestellt werden, 18kt Gelbgold, Wei&szlig;gold oder in Rosegold.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Timeless 4 Krappen', '', 'Dieser Zeitlose Ring wird in feinster Handarbeit für Sie in unserer Goldschmiede Werkstatt hergestellt für den besonderen Anlass.', '3', 'DE', NULL, NULL, 1, '1', 1, 0, '135.00', '0.00', 1, 0, '1.5', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:2:{i:1;a:2:{s:6:\"option\";s:1:\"7\";s:5:\"order\";i:1;}i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:2;}}', 1, 1463608743, 1485444618, '1'),
(10, 'Madonna 6 Krappen', 2, 2, 2, 2, 'de/ring-madonna', 'product', '<p>Dieser zeitlose Solit&auml;rring, kann der klassische Verlobungsring sein oder auch f&uuml;r andere Anl&auml;sse geeignet sein und kann nicht nur mit einem runden Brillantschliff gefasst werden, sondern auch weitere Schliffarten wie z.B. der Princess Diamant besetzt werden.&nbsp; Sie k&ouml;nnen die Steingr&ouml;&szlig;e individuell ausw&auml;hlen von 0.20 ct. &ndash; 1.50 ct.</p>\r\n\r\n<p>Eine 6 Krappen Fassung hebt den Diamanten sehr besonders hervor und strahlt eine Brillanz und wirkt gr&ouml;&szlig;er.</p>\r\n\r\n<p>Der Ring wird in perfekter Handarbeit in unseren Goldschmied Werkst&auml;tten hergestellt und kann in drei Ausf&uuml;hrung bestellt werden 18 Karat Gelbgold, Wei&szlig;gold oder in Rosegold.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Madonna 6 Krappen', 'Solitär Diamant-Ohrringe', 'Dieser zeitlose Solitärring, kann der klassische Verlobungsring sein oder auch für andere Anlässe geeignet sein und kann nicht nur mit einem runden Brillantschliff gefasst werden, sondern auch weitere Schliffarten wie z.B. der Princess Diamant besetzt werden.', '3', 'DE', NULL, NULL, 0, '1', 3, 0, '99.00', '0.00', 1, 0, '1.5', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:2:{i:1;a:2:{s:6:\"option\";s:1:\"7\";s:5:\"order\";i:1;}i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:2;}}', 1, 1464996646, 1485444390, '1'),
(11, 'Fabulous', 2, 2, 2, 2, 'de/ring-fabulous', 'product', '<p>Dieser in feinster Qualit&auml;t angefertigter Ring wird mit runden Diamanten auf der Seitenschiene besetzt und l&auml;sst den Ring von jeder Position aus gl&auml;nzen. Er wird in unserer Goldschmiedewerkstatt f&uuml;r Sie in bester Handarbeit aus 18 kt. Gold angefertigt, Sie k&ouml;nnen ihn in der Ausf&uuml;hrung Gelb, Wei&szlig; oder Rosegold fertigen lassen.</p>\r\n\r\n<p>Der Hauptstein kann Individuell ausgew&auml;hlt werden, unsere Empfehlung ist der Runde Brillantschliff und kann zwischen 0.20 ct &ndash; 1.00 ct. sein. Die besetzten Seitensteine liegen je nach Hauptstein bei ca. 0.50 ct. &ndash; 0.65 ct. und haben die Reinheit VS in der Farbe G-H.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Der Zentrale Stein wird mit einer 4 Krappenfassung gahalten und hat durch die &Ouml;ffnungen an der Seite sowie auf der unteren Seite genug Lichteinfluss f&uuml;r die optimale Brillanz.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Seitenbesatz mit kleinen Diamanten ca. 0.50 ct. &ndash; 0.65 ct.</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Fabulous', '', 'Dieser in feinster Qualität angefertigter Ring wird mit runden Diamanten auf der Seitenschiene besetzt und lässt den Ring von jeder Position aus glänzen.', '3', 'DE', NULL, NULL, 0, '1', 0, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:2:{i:1;a:2:{s:6:\"option\";s:1:\"7\";s:5:\"order\";i:1;}i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:2;}}', 1, 1464996732, 1485448911, '1'),
(13, 'Halos', 2, 2, 2, 2, 'de/ring-halos', 'product', '<p>Dieser in feinster Qualit&auml;t angefertigter Halo Ring wird mit runden Diamanten um den Hauptstein herum besetzt, sowohl auch auf der Seitenschiene, der den zentralen Stein Gr&ouml;&szlig;er erscheinen l&auml;sst und Ihn in Geltung bringt. Er wird in unserer Goldschmiedewerkstatt f&uuml;r Sie in bester Handarbeit aus 18 kt. Gold angefertigt, Sie k&ouml;nnen ihn in der Ausf&uuml;hrung Gelb, Wei&szlig; oder Rosegold fertigen lassen.</p>\r\n\r\n<p>Der Hauptstein kann Individuell ausgew&auml;hlt werden, unsere Empfehlung ist der Runde Brillantschliff und kann zwischen 0.20 ct &ndash; 1.00 ct. sein.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>\r\n\r\n<p>Die besetzten Seitensteine liegen je nach Hauptstein bei ca. 0.50 ct. &ndash; 0.65 ct. und haben die Reinheit VS in der Farbe G-H.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Der Zentrale Stein wird mit einer 4 Krappenfassung gahalten und hat durch die &Ouml;ffnungen an der Seite sowie auf der unteren Seite genug Lichteinfluss f&uuml;r die optimale Brillanz.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Seitenbesatz mit kleinen Diamanten ca. 0.50 ct. &ndash; 0.65 ct.</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Halos', '', 'Dieser in feinster Qualität angefertigter Halo Ring wird mit runden Diamanten um den Hauptstein herum besetzt, sowohl auch auf der Seitenschiene, der den zentralen Stein Größer erscheinen lässt und Ihn in Geltung bringt.', '3', 'DE', NULL, NULL, 0, '1', 15, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:2:{i:1;a:2:{s:6:\"option\";s:1:\"7\";s:5:\"order\";i:1;}i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:2;}}', 1, 1485452191, 1485448901, '1'),
(14, 'Plug 4 Krappen', 2, 2, 2, 2, 'de/ohringe-plug-4-krappen', 'product', '<p>Die klassischen Ohrstecker f&uuml;r jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie k&ouml;nnen die Ohrringe in der Ausf&uuml;hrung Gelb, Wei&szlig; sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem runden Brillantschliff der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.00 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Bei der 4 Krappen Fassung kommt der Diamanten durch viel Lichteinstrahlung besonders zur Geltung.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Plug 4 Krappen', '', 'Die klassischen Ohrstecker für jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie können die Ohrringe in der Ausführung Gelb, Weiß sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem runden Brillantschliff der Größe von 0.20 ct. – 1.00 ct.', '3', 'DE', NULL, NULL, 0, '4', 0, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:1;}}', 1, 1485452191, 1485452191, '1'),
(15, 'Infinity 4 Krappen', 2, 2, 2, 2, 'de/ohringe-infinity-4-krappen', 'product', '<p>Die klassischen Ohrstecker f&uuml;r jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie k&ouml;nnen die Ohrringe in der Ausf&uuml;hrung Gelb, Wei&szlig; sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem quadratischen Princess Schliff der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.00 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Bei der 4 Krappen Fassung kommt der Diamanten durch viel Lichteinstrahlung besonders zur Geltung.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Infinity 4 Krappen', '', 'Die klassischen Ohrstecker für jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil.', '3', 'DE', NULL, NULL, 0, '4', 2, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:1;}}', 1, 1485449739, 1485452191, '1'),
(16, 'Passion 6 Krappen', 2, 2, 2, 2, 'de/ohringe-passion-6-krappen', 'product', '<p>Die klassischen Ohrstecker f&uuml;r jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie k&ouml;nnen die Ohrringe in der Ausf&uuml;hrung Gelb, Wei&szlig; sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem runden Brillantschliff der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.00 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Bei der 6 Krappen Fassung kommt der Diamanten durch viel Lichteinstrahlung besonders zur Geltung.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Passion 6 Krappen', '', 'Die klassischen Ohrstecker für jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil.', '3', 'DE', NULL, NULL, 0, '4', 2, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:2;}}', 1, 1485448669, 1485450667, '1'),
(17, 'Beloved 6 Krappen', 2, 2, 2, 2, 'de/ohringe-beloved-6-krappen', 'product', '<p>Die klassischen Ohrstecker f&uuml;r jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie k&ouml;nnen die Ohrringe in der Ausf&uuml;hrung Gelb, Wei&szlig; sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem Ovalen Schliff der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.00 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Bei der 6 Krappen Fassung kommt der Diamanten durch viel Lichteinstrahlung besonders zur Geltung.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Beloved 6 Krappen', '', 'Die klassischen Ohrstecker für jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil.', '3', 'DE', NULL, NULL, 0, '4', 0, 0, '99.00', '0.00', 1, 0, '1.0', 'a:10:{i:0;s:5:\"Round\";i:1;s:4:\"Pear\";i:2;s:8:\"Princess\";i:3;s:8:\"Marquise\";i:4;s:4:\"Oval\";i:5;s:7:\"R', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"6\";s:5:\"order\";i:1;}}', 1, 1485448901, 1485449739, '1'),
(18, 'Loyalty 4 Krappen', 2, 2, 2, 2, 'de/ohringe-loyalty-4-krappen', 'product', '<p>Die klassischen Ohrstecker f&uuml;r jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil. Sie k&ouml;nnen die Ohrringe in der Ausf&uuml;hrung Gelb, Wei&szlig; sowie Rosegold anfertigen lassen und Sie besetzen mit Diamanten in einem Ovalen Schliff der Gr&ouml;&szlig;e von 0.20 ct. &ndash; 1.00 ct.</p>\r\n\r\n<p>Bei gr&ouml;&szlig;erem Steinbesatz, ist eine Abweichung der Schiene m&ouml;glich, hierbei wird die Bearbeitung mit Ihnen pers&ouml;nlich besprochen und nach Ihren W&uuml;nschen angefertigt.</p>\r\n\r\n<p>Bei der 4 Krappen Fassung kommt der Diamanten durch viel Lichteinstrahlung besonders zur Geltung.</p>\r\n\r\n<ul>\r\n	<li>18 kt. Gelbgold, Wei&szlig;gold oder Rosegold</li>\r\n	<li>Goldschmiede Handarbeit Made in Germany</li>\r\n	<li>Individuelle Anfertigung speziell nach Ihren W&uuml;nschen</li>\r\n</ul>\r\n\r\n<p>Sie m&ouml;chten ein anderes Schmuckst&uuml;ck?</p>\r\n\r\n<p>Sie haben eine ganz andere Vorstellung und m&ouml;chten Sie vorab in 3D erstellen?</p>\r\n\r\n<p>Kontaktieren Sie uns f&uuml;r eine Individuelle Anfertigung, wir beraten Sie gerne.</p>', 'Loyalty 4 Krappen', '', 'Die klassischen Ohrstecker für jeden Tag, Handgearbeitet aus 18kt Gold und einem Zeitlosen Stil.', '3', 'DE', NULL, 'N;', 0, '4', 0, 0, '99.00', '0.00', 0, 3, '20', '000001d', 'a:1:{i:0;a:2:{s:6:\"option\";s:1:\"1\";s:5:\"order\";i:1;}}', 1, 1485449739, 1495668849, '1');

-- --------------------------------------------------------

--
-- Структура таблицы `product_options`
--

CREATE TABLE `product_options` (
  `id` int(5) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `options_array` text COLLATE utf8_bin,
  `type` varchar(15) COLLATE utf8_bin NOT NULL,
  `half` int(1) NOT NULL,
  `image` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` int(1) NOT NULL,
  `author` int(10) NOT NULL,
  `date_add` int(12) NOT NULL,
  `date_upd` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `product_options`
--

INSERT INTO `product_options` (`id`, `name`, `options_array`, `type`, `half`, `image`, `status`, `author`, `date_add`, `date_upd`) VALUES
(1, 'Цвет', 'a:12:{i:1;a:4:{s:3:\"val\";s:14:\"Красный\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:1;s:3:\"css\";s:18:\"color-checkbox-red\";}i:2;a:4:{s:3:\"val\";s:12:\"Желтый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:2;s:3:\"css\";s:21:\"color-checkbox-yellow\";}i:3;a:4:{s:3:\"val\";s:10:\"Синий\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:3;s:3:\"css\";s:19:\"color-checkbox-blue\";}i:4;a:4:{s:3:\"val\";s:10:\"Серый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:4;s:3:\"css\";s:19:\"color-checkbox-grey\";}i:5;a:4:{s:3:\"val\";s:14:\"Зеленый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:5;s:3:\"css\";s:20:\"color-checkbox-green\";}i:6;a:4:{s:3:\"val\";s:18:\"Оранжевый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:6;s:3:\"css\";s:21:\"color-checkbox-orange\";}i:7;a:4:{s:3:\"val\";s:20:\"Фиолетовый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:7;s:3:\"css\";s:21:\"color-checkbox-purple\";}i:8;a:4:{s:3:\"val\";s:14:\"Розовый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:8;s:3:\"css\";s:19:\"color-checkbox-rose\";}i:9;a:4:{s:3:\"val\";s:18:\"Салатовый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:9;s:3:\"css\";s:26:\"color-checkbox-light-green\";}i:10;a:4:{s:3:\"val\";s:10:\"Белый\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:10;s:3:\"css\";s:20:\"color-checkbox-white\";}i:11;a:4:{s:3:\"val\";s:12:\"Черный\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:11;s:3:\"css\";s:20:\"color-checkbox-black\";}i:12;a:4:{s:3:\"val\";s:14:\"Цветной\";s:8:\"multiply\";s:1:\"1\";s:5:\"order\";i:12;s:3:\"css\";s:20:\"color-checkbox-multi\";}}', 'imagebtn', 2, '', 1, 1, 1463089268, 1500406397);

-- --------------------------------------------------------

--
-- Структура таблицы `product_options_active`
--

CREATE TABLE `product_options_active` (
  `id` int(11) NOT NULL,
  `product_id` int(10) NOT NULL DEFAULT '0',
  `option_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product_options_active`
--

INSERT INTO `product_options_active` (`id`, `product_id`, `option_value`) VALUES
(31, 1, '_1-value3'),
(34, 1, '_1-value2'),
(35, 1, '_1-value1'),
(36, 2, '_1-value1'),
(37, 2, '_1-value6');

-- --------------------------------------------------------

--
-- Структура таблицы `product_quantity`
--

CREATE TABLE `product_quantity` (
  `id` int(10) NOT NULL,
  `value` int(5) DEFAULT NULL,
  `product_id` int(10) DEFAULT NULL,
  `size_id` int(5) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `product_quantity`
--

INSERT INTO `product_quantity` (`id`, `value`, `product_id`, `size_id`, `params`) VALUES
(1, 8, 1, 14, NULL),
(2, 2, 1, 14, '1-value1-'),
(3, 2, 1, 15, '1-value1-'),
(4, 9, 1, 15, NULL),
(5, 3, 1, 16, '1-value1-'),
(6, 11, 1, 16, NULL),
(7, 4, 1, 17, '1-value1-'),
(8, 13, 1, 17, NULL),
(9, 6, 1, 14, '1-value2-'),
(10, 7, 1, 15, '1-value2-'),
(11, 8, 1, 16, '1-value2-'),
(12, 9, 1, 17, '1-value2-'),
(13, 1, 2, 9, '1-value1-'),
(14, 2, 2, 9, NULL),
(15, 2, 2, 10, '1-value1-'),
(16, 4, 2, 10, NULL),
(17, 3, 2, 11, '1-value1-'),
(18, 6, 2, 11, NULL),
(19, 4, 2, 12, '1-value1-'),
(20, 8, 2, 12, NULL),
(21, 5, 2, 13, '1-value1-'),
(22, 10, 2, 13, NULL),
(23, 1, 2, 9, '1-value6-'),
(24, 2, 2, 10, '1-value6-'),
(25, 3, 2, 11, '1-value6-'),
(26, 4, 2, 12, '1-value6-'),
(27, 5, 2, 13, '1-value6-');

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` varchar(150) DEFAULT NULL,
  `date_add` int(12) DEFAULT NULL,
  `date_upd` int(12) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `author` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `image`, `date_add`, `date_upd`, `status`, `author`) VALUES
(1, 'Armani Collezioni', '', '', '', '', 1499812909, NULL, 1, 1),
(2, 'CTRL Wear', 'Роман Дьяченко', '380509601720', 'romand123@gmail.com', '/images/upload/images/brand-logos/ctrl.png', 1499812949, NULL, 1, 1),
(3, 'Balenciaga', '', '', '', '', 1499812964, NULL, 1, 1),
(4, 'DIOR', '', '', '', '', 1499812970, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers_sizes`
--

CREATE TABLE `suppliers_sizes` (
  `id` int(5) NOT NULL,
  `supplier_id` int(5) DEFAULT NULL,
  `size_type_id` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `suppliers_sizes`
--

INSERT INTO `suppliers_sizes` (`id`, `supplier_id`, `size_type_id`) VALUES
(20, 2, 2),
(21, 2, 3),
(22, 4, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers_sizes_values`
--

CREATE TABLE `suppliers_sizes_values` (
  `id` int(5) NOT NULL,
  `supplier_size_id` int(5) DEFAULT NULL,
  `pack` int(5) DEFAULT NULL,
  `size_id` int(5) DEFAULT NULL,
  `size_option_id` int(5) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `suppliers_sizes_values`
--

INSERT INTO `suppliers_sizes_values` (`id`, `supplier_size_id`, `pack`, `size_id`, `size_option_id`, `value`) VALUES
(1, 4, 22, 9, 4, '33'),
(2, 4, 22, 10, 4, '33'),
(3, 4, 22, 11, 4, '11'),
(4, 4, 22, 12, 4, '11'),
(5, 4, 22, 13, 4, '33'),
(6, 4, 22, 9, 5, '1'),
(7, 4, 22, 10, 5, '2'),
(8, 4, 22, 11, 5, '3'),
(9, 4, 22, 12, 5, '4'),
(10, 4, 22, 13, 5, '5'),
(11, 4, 22, 13, 6, '6'),
(12, 4, 22, 11, 6, '7'),
(13, 4, 22, 12, 6, '8'),
(14, 4, 22, 10, 6, '3'),
(15, 4, 22, 9, 6, '2');

-- --------------------------------------------------------

--
-- Структура таблицы `templates`
--

CREATE TABLE `templates` (
  `id` int(5) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `pos_array` text COLLATE utf8_bin NOT NULL,
  `status` int(1) NOT NULL,
  `author` varchar(10) COLLATE utf8_bin NOT NULL,
  `date_add` varchar(12) COLLATE utf8_bin NOT NULL,
  `date_upd` varchar(12) COLLATE utf8_bin NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Дамп данных таблицы `templates`
--

INSERT INTO `templates` (`id`, `name`, `pos_array`, `status`, `author`, `date_add`, `date_upd`) VALUES
(4, 'Кабинет и корзина', 'a:6:{i:2;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:3;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:3;}i:4;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:4;}i:1;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:5;}i:6;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:5;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:10;}}', 1, '1', '1472820132', '1499782515'),
(5, 'Новости', 'a:8:{i:1;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:2;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:2;}i:3;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:9;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:6;}i:4;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:7;}i:7;a:3:{s:8:\"mod_name\";s:2:\"19\";s:8:\"position\";s:4:\"NEWS\";s:9:\"pos_order\";i:8;}i:8;a:3:{s:8:\"mod_name\";s:2:\"24\";s:8:\"position\";s:4:\"NEWS\";s:9:\"pos_order\";i:9;}i:5;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:11;}}', 1, '1', '1466121235', '1500508447'),
(3, 'Страница товара', 'a:8:{i:1;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:2;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:3;}i:3;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:8;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:6;}i:4;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:7;}i:6;a:3:{s:8:\"mod_name\";s:1:\"8\";s:8:\"position\";s:10:\"POSITION_1\";s:9:\"pos_order\";i:9;}i:7;a:3:{s:8:\"mod_name\";s:2:\"10\";s:8:\"position\";s:10:\"POSITION_4\";s:9:\"pos_order\";i:11;}i:5;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:15;}}', 1, '1', '1466386163', '1499782496'),
(2, 'Страница категорий', 'a:9:{i:1;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:2;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:3;}i:3;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:8;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:6;}i:4;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:7;}i:9;a:3:{s:8:\"mod_name\";s:2:\"23\";s:8:\"position\";s:10:\"INNER_LEFT\";s:9:\"pos_order\";i:8;}i:7;a:3:{s:8:\"mod_name\";s:2:\"17\";s:8:\"position\";s:10:\"POSITION_5\";s:9:\"pos_order\";i:9;}i:10;a:3:{s:8:\"mod_name\";s:2:\"25\";s:8:\"position\";s:11:\"INNER_RIGHT\";s:9:\"pos_order\";i:9;}i:6;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:13;}}', 1, '1', '1466121235', '1499782487'),
(1, 'Главная страница', 'a:13:{i:4;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:5;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:3;}i:6;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:4;}i:16;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:13;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:6;}i:3;a:3:{s:8:\"mod_name\";s:1:\"2\";s:8:\"position\";s:6:\"SLIDER\";s:9:\"pos_order\";i:9;}i:8;a:3:{s:8:\"mod_name\";s:1:\"5\";s:8:\"position\";s:10:\"POSITION_1\";s:9:\"pos_order\";i:11;}i:9;a:3:{s:8:\"mod_name\";s:1:\"9\";s:8:\"position\";s:10:\"POSITION_2\";s:9:\"pos_order\";i:12;}i:10;a:3:{s:8:\"mod_name\";s:2:\"11\";s:8:\"position\";s:10:\"POSITION_3\";s:9:\"pos_order\";i:13;}i:11;a:3:{s:8:\"mod_name\";s:1:\"7\";s:8:\"position\";s:10:\"POSITION_4\";s:9:\"pos_order\";i:14;}i:12;a:3:{s:8:\"mod_name\";s:2:\"17\";s:8:\"position\";s:10:\"POSITION_5\";s:9:\"pos_order\";i:15;}i:15;a:3:{s:8:\"mod_name\";s:2:\"16\";s:8:\"position\";s:10:\"POSITION_5\";s:9:\"pos_order\";i:16;}i:14;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:18;}}', 1, '1', '1464564723', '1499782476'),
(6, 'Новость', 'a:7:{i:1;a:3:{s:8:\"mod_name\";s:1:\"4\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:1;}i:2;a:3:{s:8:\"mod_name\";s:1:\"3\";s:8:\"position\";s:6:\"HEADER\";s:9:\"pos_order\";i:3;}i:3;a:3:{s:8:\"mod_name\";s:1:\"6\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:5;}i:8;a:3:{s:8:\"mod_name\";s:2:\"18\";s:8:\"position\";s:4:\"CART\";s:9:\"pos_order\";i:6;}i:4;a:3:{s:8:\"mod_name\";s:1:\"1\";s:8:\"position\";s:8:\"MAINMENU\";s:9:\"pos_order\";i:7;}i:7;a:3:{s:8:\"mod_name\";s:2:\"17\";s:8:\"position\";s:10:\"POSITION_5\";s:9:\"pos_order\";i:9;}i:6;a:3:{s:8:\"mod_name\";s:2:\"14\";s:8:\"position\";s:6:\"FOOTER\";s:9:\"pos_order\";i:13;}}', 1, '1', '1485475339', '1499783557');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(32) NOT NULL,
  `passw` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  `last_visit` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `reg_date`, `login`, `passw`, `salt`, `last_visit`, `status`) VALUES
(1, 'Виктор', '2016-04-12 22:15:19', 'orlando', 'ad1a7ae1dbe453cfb92f07d5fdadd0e2', 'g%#11T', '2017-08-09 18:02:59', 1),
(2, 'Karina', '2017-03-23 17:36:13', 'Karina', '3b49dec6f8f99e2e43c6b4be7f817316', '12345', '2017-04-21 00:26:14', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `ident` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_types`
--

INSERT INTO `user_types` (`id`, `id_project`, `name`, `ident`) VALUES
(1, 1, 'Administrator', 'admin'),
(2, 1, 'Guest', 'guest'),
(3, 1, 'Admin', 'super');

-- --------------------------------------------------------

--
-- Структура таблицы `user_types_rel`
--

CREATE TABLE `user_types_rel` (
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `newid` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user_types_rel`
--

INSERT INTO `user_types_rel` (`user_id`, `type_id`, `newid`) VALUES
(1, 1, 7),
(3, 1, 9),
(2, 1, 13);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_address`
--
ALTER TABLE `clients_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Индексы таблицы `clients_contacts_relations`
--
ALTER TABLE `clients_contacts_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_addr` (`id_addr`),
  ADD KEY `id_tel` (`id_tel`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `is_main` (`is_main`);

--
-- Индексы таблицы `clients_emails`
--
ALTER TABLE `clients_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Индексы таблицы `clients_info`
--
ALTER TABLE `clients_info`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients_phones`
--
ALTER TABLE `clients_phones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `phone` (`phone`),
  ADD KEY `is_main` (`is_main`);

--
-- Индексы таблицы `clients_soc_acc`
--
ALTER TABLE `clients_soc_acc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_client` (`id_client`);

--
-- Индексы таблицы `cloth_sizes`
--
ALTER TABLE `cloth_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `cloth_size_options`
--
ALTER TABLE `cloth_size_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `cloth_types`
--
ALTER TABLE `cloth_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);
ALTER TABLE `content` ADD FULLTEXT KEY `category` (`category`);

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dreambox`
--
ALTER TABLE `dreambox`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `images_ibfk_1` (`content_id`);

--
-- Индексы таблицы `lookbook`
--
ALTER TABLE `lookbook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `modules`
--
ALTER TABLE `modules`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `modules_items`
--
ALTER TABLE `modules_items`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `product_options_active`
--
ALTER TABLE `product_options_active`
  ADD PRIMARY KEY (`id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `product_quantity`
--
ALTER TABLE `product_quantity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `suppliers_sizes`
--
ALTER TABLE `suppliers_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_sizes_ibfk_1` (`supplier_id`);

--
-- Индексы таблицы `suppliers_sizes_values`
--
ALTER TABLE `suppliers_sizes_values`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `templates`
--
ALTER TABLE `templates`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `clients_address`
--
ALTER TABLE `clients_address`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT для таблицы `clients_contacts_relations`
--
ALTER TABLE `clients_contacts_relations`
  MODIFY `id` tinyint(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `clients_emails`
--
ALTER TABLE `clients_emails`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT для таблицы `clients_info`
--
ALTER TABLE `clients_info`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT для таблицы `clients_phones`
--
ALTER TABLE `clients_phones`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
--
-- AUTO_INCREMENT для таблицы `clients_soc_acc`
--
ALTER TABLE `clients_soc_acc`
  MODIFY `id` tinyint(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `content`
--
ALTER TABLE `content`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `dreambox`
--
ALTER TABLE `dreambox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT для таблицы `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT для таблицы `lookbook`
--
ALTER TABLE `lookbook`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `modules_items`
--
ALTER TABLE `modules_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `product_options_active`
--
ALTER TABLE `product_options_active`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT для таблицы `product_quantity`
--
ALTER TABLE `product_quantity`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT для таблицы `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `suppliers_sizes`
--
ALTER TABLE `suppliers_sizes`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `suppliers_sizes_values`
--
ALTER TABLE `suppliers_sizes_values`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `templates`
--
ALTER TABLE `templates`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `clients_address`
--
ALTER TABLE `clients_address`
  ADD CONSTRAINT `clients_address_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_contacts_relations`
--
ALTER TABLE `clients_contacts_relations`
  ADD CONSTRAINT `clients_contacts_relations_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clients_contacts_relations_ibfk_2` FOREIGN KEY (`id_addr`) REFERENCES `clients_address` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_emails`
--
ALTER TABLE `clients_emails`
  ADD CONSTRAINT `clients_emails_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_phones`
--
ALTER TABLE `clients_phones`
  ADD CONSTRAINT `clients_phones_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `clients_soc_acc`
--
ALTER TABLE `clients_soc_acc`
  ADD CONSTRAINT `clients_soc_acc_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `clients_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `lookbook`
--
ALTER TABLE `lookbook`
  ADD CONSTRAINT `lookbook_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lookbook_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_options_active`
--
ALTER TABLE `product_options_active`
  ADD CONSTRAINT `product_options_active_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_quantity`
--
ALTER TABLE `product_quantity`
  ADD CONSTRAINT `product_quantity_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `suppliers_sizes`
--
ALTER TABLE `suppliers_sizes`
  ADD CONSTRAINT `suppliers_sizes_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
