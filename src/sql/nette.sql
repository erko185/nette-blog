-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Pi 13.Aug 2021, 16:17
-- Verzia serveru: 10.4.17-MariaDB
-- Verzia PHP: 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `nette`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `perex` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `hide` int(11) DEFAULT NULL,
  `like_reaction` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dislike_reaction` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `perex`, `content`, `hide`, `like_reaction`, `created_at`, `dislike_reaction`) VALUES
(1, 'Article One', 'article-one', 'Lorem ipusm dolor one', 'Lorem ipusm dolor one', 0, 1, '2021-08-06 16:37:22', 1),
(2, 'Article Two', 'article-two', 'Lorem ipsum dolor two', 'Lorem ipsum dolor two', 1, 2, '2021-08-06 17:37:22', 0),
(3, 'Article Three', 'article-three', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 0, 1, '2021-08-06 18:37:22', 0),
(4, 'Article Four', 'article-four', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 1, 0, '2021-08-06 19:37:22', 0),
(5, 'Article Five', 'article-five', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 1, 0, '2021-08-06 20:37:22', 0),
(6, 'Article Six', 'article-six', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 1, 0, '2021-08-06 21:37:22', 0),
(7, 'Article Seven', 'article-seven', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 0, 0, '2021-08-06 22:37:22', 0),
(8, 'Article Eight', 'article-eight', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 0, 0, '2021-08-06 23:37:22', 0),
(9, 'Article Nine', 'article-nine', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 1, 0, '2021-09-06 16:37:22', 0),
(10, 'Article Ten', 'article-ten', 'Lorem ipsum dolor three', 'Lorem ipsum dolor three', 0, 0, '2021-09-06 17:37:22', 0);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `reaction`
--

CREATE TABLE `reaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `reaction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `reaction`
--

INSERT INTO `reaction` (`id`, `user_id`, `post_id`, `reaction`) VALUES
(16, 3, 1, 1),
(17, 3, 2, 1),
(18, 4, 1, 0),
(19, 4, 2, 1),
(20, 4, 3, 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255)  DEFAULT NULL,
  `password` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `created_at`) VALUES
(3, 'erko185@gmail.com', '$2y$12$6DGUrLRTOtfXFXLK0EaDG.c8Kms/vfDavpMI8oOsUoJEj.x3md6Tm', '2021-08-05 17:58:16'),
(4, 'erko186@gmail.com', '$2y$12$HPXZlK1vxuulJoFehcpbxeShhTA87RYX3IAt3khdynVciZ.mOphXK', '2021-08-08 17:59:10');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_uindex` (`slug`);

--
-- Indexy pre tabuľku `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reaction_posts_id_fk` (`post_id`),
  ADD KEY `reaction_users_id_fk` (`user_id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_uindex` (`email`) USING HASH;

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pre tabuľku `reaction`
--
ALTER TABLE `reaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `reaction_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `reaction_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
