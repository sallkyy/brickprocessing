-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: MySQL-8.2
-- Время создания: Май 02 2025 г., 00:45
-- Версия сервера: 8.2.0
-- Версия PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `brick_project_2024_4`
--

-- --------------------------------------------------------

--
-- Структура таблицы `achievements`
--

CREATE TABLE `achievements` (
  `achievement_id` int NOT NULL,
  `test_id` int NOT NULL,
  `min_score` int NOT NULL,
  `achive_description` varchar(255) NOT NULL,
  `achive_text` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `achievements`
--

INSERT INTO `achievements` (`achievement_id`, `test_id`, `min_score`, `achive_description`, `achive_text`) VALUES
(2, 1, 50, 'Задание на 5 с плюсом!', 'Детка...');

-- --------------------------------------------------------

--
-- Структура таблицы `notes`
--

CREATE TABLE `notes` (
  `note_id` int NOT NULL,
  `user_id` int NOT NULL,
  `note_head` varchar(255) NOT NULL,
  `note_body` text NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `notes`
--

INSERT INTO `notes` (`note_id`, `user_id`, `note_head`, `note_body`, `created_at`) VALUES
(2, 1, 'Пивасик', 'Эх ', '2025-05-01'),
(4, 1, 'абоба', 'оставлен след в истории проекта', '2025-05-01');

-- --------------------------------------------------------

--
-- Структура таблицы `questions`
--

CREATE TABLE `questions` (
  `question_id` int NOT NULL,
  `test_id` int NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `questions`
--

INSERT INTO `questions` (`question_id`, `test_id`, `description`) VALUES
(1, 1, 'Вы часто чувствуете себя некомфортно без причины'),
(2, 1, 'Вы с трудом переносите непредвиденные ситуации'),
(3, 1, 'Вам тяжело сосредоточиться на делах из-за навязчивых мыслей'),
(4, 1, 'Вы часто откладываете дела, которые приносят вам дискомфорт'),
(5, 1, 'Тревога ощущается Вами как привычное состояние, а не как что-то временное'),
(6, 2, 'Легко ли Вам простить себе ошибки прошлого?'),
(7, 2, 'Проблемы позволяют разобраться в себе?'),
(8, 2, 'Неудачи происходят из-за случайностей, а не из-за ошибок'),
(9, 2, 'Вы часто ищете хоть что-то хорошее в тяжелых ситуациях'),
(10, 2, 'Вы готовы приукрасить любую ситуацию, чтобы поддержать человека'),
(11, 3, 'От любого раздражителя Вы легко можете «вспыхнуть»'),
(12, 3, 'Маленьких наглых и невоспитанных детей надо контролировать и не выводить в социум'),
(13, 3, 'Вы легко можете наговорить гадостей в порыве злобы (это приносит Вам удовольствие)'),
(14, 3, 'Бабок в метро в час-пик нужно пинать с лестниц'),
(15, 3, 'Часто раздражителем для Вас может являться что-то незначительное, что обычно раздражителем не считается');

-- --------------------------------------------------------

--
-- Структура таблицы `tests`
--

CREATE TABLE `tests` (
  `test_id` int NOT NULL,
  `about_text` varchar(255) NOT NULL,
  `max_result` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `tests`
--

INSERT INTO `tests` (`test_id`, `about_text`, `max_result`) VALUES
(1, 'Тест на уровень тревожности', 50),
(2, 'Пессимизм или оптимизм?', 50),
(3, 'Тест на стрессоустойчивость!', 50),
(7, 'ъэ', 50);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `login` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `login`, `password`, `email`, `role`) VALUES
(1, '123', '123', '123@123.com', 'user'),
(2, 'admin', 'admin', 'admin@yandex.ru', 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `user_achievement`
--

CREATE TABLE `user_achievement` (
  `user_id` int NOT NULL,
  `achievement_id` int NOT NULL,
  `date_of_getting` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user_achievement`
--

INSERT INTO `user_achievement` (`user_id`, `achievement_id`, `date_of_getting`) VALUES
(1, 2, '2025-05-01');

-- --------------------------------------------------------

--
-- Структура таблицы `user_test`
--

CREATE TABLE `user_test` (
  `user_id` int NOT NULL,
  `test_id` int NOT NULL,
  `score` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Дамп данных таблицы `user_test`
--

INSERT INTO `user_test` (`user_id`, `test_id`, `score`) VALUES
(1, 1, 50),
(1, 2, 29),
(1, 3, 40);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`achievement_id`),
  ADD KEY `test_id` (`test_id`);

--
-- Индексы таблицы `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `questions_ibfk_1` (`test_id`);

--
-- Индексы таблицы `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Индексы таблицы `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD PRIMARY KEY (`user_id`,`achievement_id`),
  ADD KEY `achivement_id` (`achievement_id`);

--
-- Индексы таблицы `user_test`
--
ALTER TABLE `user_test`
  ADD PRIMARY KEY (`user_id`,`test_id`),
  ADD KEY `test_id` (`test_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `achievements`
--
ALTER TABLE `achievements`
  MODIFY `achievement_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `achievements`
--
ALTER TABLE `achievements`
  ADD CONSTRAINT `achievements_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`test_id`);

--
-- Ограничения внешнего ключа таблицы `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_achievement`
--
ALTER TABLE `user_achievement`
  ADD CONSTRAINT `user_achievement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_achievement_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`achievement_id`);

--
-- Ограничения внешнего ключа таблицы `user_test`
--
ALTER TABLE `user_test`
  ADD CONSTRAINT `user_test_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_test_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`test_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
