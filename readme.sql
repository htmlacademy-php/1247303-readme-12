-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 16 2021 г., 20:45
-- Версия сервера: 5.7.29
-- Версия PHP: 7.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `schema`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `publictation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `publictation_date`, `content`, `user_id`, `post_id`) VALUES
(1, '2021-03-08 13:33:43', 'Отличные фото!', 1, 3),
(2, '2021-03-07 18:25:44', 'Жду, не дождусь 6-ой сезон :-)', 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `likes`
--

CREATE TABLE `likes` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(1, 2, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `content` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_user_id` int NOT NULL,
  `recipient_user_id` int NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type_id` int NOT NULL,
  `publictation_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `title` text NOT NULL,
  `content` varchar(256) DEFAULT NULL,
  `author_quote` text,
  `img_path` varchar(128) DEFAULT NULL,
  `video_path` varchar(128) DEFAULT NULL,
  `site_path` varchar(128) DEFAULT NULL,
  `count_view` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `type_id`, `publictation_date`, `title`, `content`, `author_quote`, `img_path`, `video_path`, `site_path`, `count_view`) VALUES
(1, 1, 2, '2021-01-06 21:05:02', 'Цитата', 'Мы в жизни любим только раз, а после ищем лишь похожих.Мы в жизни любим только раз, а после ищем лишь похожих.', 'Неизвестный Автор', NULL, NULL, NULL, 10),
(2, 2, 1, '2021-03-01 12:04:02', 'Игра престолов', 'Не могу дождаться начала финального сезона своего любимого сериала!', NULL, NULL, NULL, NULL, 12),
(3, 3, 3, '2021-03-03 20:24:02', 'Наконец, обработал фотки!', NULL, NULL, 'img/rock-medium.jpg', NULL, NULL, 8);

-- --------------------------------------------------------

--
-- Структура таблицы `relations_posts_tags`
--

CREATE TABLE `relations_posts_tags` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `tags_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `follower_user_id` int NOT NULL,
  `following_user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `follower_user_id`, `following_user_id`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `tags`
--

CREATE TABLE `tags` (
  `id` int NOT NULL,
  `title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `types`
--

CREATE TABLE `types` (
  `id` int NOT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `types`
--

INSERT INTO `types` (`id`, `type`, `class_name`) VALUES
(1, 'Текст', 'text'),
(2, 'Цитата', 'quote'),
(3, 'Фото', 'photo'),
(4, 'Видео', 'video'),
(5, 'Ссылка', 'link');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_path` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `dt_add`, `email`, `login`, `password`, `avatar_path`, `first_name`, `last_name`) VALUES
(1, '2021-03-10 15:11:12', 'tanya@test.com', 'tanya', '123456', 'userpic-tanya.jpg', 'Татьяна', 'Петрова'),
(2, '2021-02-10 20:16:14', 'vlad@test.com', 'vlad', '654321', 'userpic.jpg', 'Влад', 'Кусков'),
(3, '2020-05-07 10:35:07', 'vitya@test.com', 'victor', '0', 'userpic-mark.jpg', 'Виктор', 'Сухомлинов'),
(4, '2019-07-11 21:48:17', 'michael@mail.com', 'misha', 'qwerty', 'userpic-misha.jpg', 'Михаил', 'Иванов');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publictation_date` (`publictation_date`),
  ADD KEY `content` (`content`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Индексы таблицы `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`post_id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content` (`content`),
  ADD KEY `sender_user_id` (`sender_user_id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`),
  ADD KEY `publictation_date` (`publictation_date`),
  ADD KEY `img_path` (`img_path`),
  ADD KEY `video_path` (`video_path`),
  ADD KEY `site_path` (`site_path`),
  ADD KEY `count_view` (`count_view`),
  ADD KEY `content` (`content`);
ALTER TABLE `posts` ADD FULLTEXT KEY `title` (`title`);
ALTER TABLE `posts` ADD FULLTEXT KEY `author_quote` (`author_quote`);

--
-- Индексы таблицы `relations_posts_tags`
--
ALTER TABLE `relations_posts_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `tags_id` (`tags_id`);

--
-- Индексы таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_user_id` (`follower_user_id`),
  ADD KEY `following_user_id` (`following_user_id`);

--
-- Индексы таблицы `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`);

--
-- Индексы таблицы `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_name` (`class_name`),
  ADD KEY `type` (`type`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `password` (`password`),
  ADD KEY `avatar_path` (`avatar_path`),
  ADD KEY `dt_add` (`dt_add`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `relations_posts_tags`
--
ALTER TABLE `relations_posts_tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `types`
--
ALTER TABLE `types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
