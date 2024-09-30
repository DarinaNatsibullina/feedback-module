-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 10.0.0.57
-- Время создания: Сен 30 2024 г., 05:23
-- Версия сервера: 5.7.37-40
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `f1033603_univerproject`
--

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `surname`, `subject`, `email`, `phone`, `message`, `created_at`, `file_name`, `newsletter`) VALUES
(9, 'Анна', 'Петрова', 'admission', 'anna.petrov@gmail.com', '+79211231212', 'Здравствуйте! Хочу поступить в этом году , какие слеудющие шаги?', '2024-09-01 02:57:32', NULL, 0),
(10, 'Дмитрий', 'Смирнов', 'university_life', 'dmitry.smirnov@yandex.ru', '', 'Добрый день!\r\n\r\nКакие мероприятия проводяться регулярно в университете?', '2024-08-13 02:58:19', NULL, 0),
(11, 'Екатерина', 'Иванова', 'finance', 'ekaterina.ivanova@mail.ru', '', 'Добрый вечер! \r\n\r\nСколько стоит обучение в год?', '2024-08-22 02:59:42', NULL, 0),
(12, 'Алексей', 'Кузнецов', 'international_students', 'alexey.kuznetsov@outlook.com', '', 'Я гражданин Узбекистана, хочу переехать в Москву и поступить к вам. Какие документы требуются?', '2024-09-05 03:00:58', NULL, 0),
(13, 'Мария', 'Соколова', 'programs', 'maria.sokolova@gmail.com', '', 'Проводяться ли у вас вебинары для абитуриентов? Не могу определиться с программой', '2024-09-27 03:02:10', NULL, 0),
(14, 'Николай', 'Давыдов', 'admission', 'superkolya@mail.ru', '+79211111111', 'Куда выслать документы?', '2024-09-27 03:18:12', NULL, 0),
(17, 'Алина', 'Малина', 'finance', 'malina@yandex.ru', '', 'Какие доступны программы на 2026 гол?', '2024-09-27 05:45:20', NULL, 0),
(18, 'Василий', 'Ильин', 'admission', 'd@e.ru', '', 's', '2024-09-27 06:42:33', NULL, 0),
(19, 'Катерина', 'Сидорова', 'admission', 'sid@fdasf.ru', '+79113212223', 'Куда выслать документы?', '2024-09-01 06:51:23', NULL, 1),
(22, 'Константин', 'Кабанов', 'administrative', 'kaban@mail.ru', '', 'Забыл отправить всю фотографию', '2024-09-23 18:33:33', 'photo 2.jpg', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` enum('operator','marketer','admin') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(4, 'operator1', '$2y$10$07T2Xn8wJ5r3j4rzLhJa2.8inceVFeJSSOW1XIeJNio5TRjQLbQfK', 'operator'),
(5, 'marketer1', '$2y$10$WZ2S5zqPEZhulE29WoxLL.gBctRwPLjshfkSqZpBSjuarVdXrmbxi', 'marketer'),
(6, 'admin1', '$2y$10$XfLvnygQR/47Qu4muviiyeOcLAJAkcaM0ll4neNryBuecFjqTdhIi', 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
