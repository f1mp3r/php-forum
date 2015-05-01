-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2015 at 12:45 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpforum`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
`id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_name` varchar(50) NOT NULL,
  `author_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `text`, `user_id`, `question_id`, `date_created`, `author_name`, `author_email`) VALUES
(6, 'answering this shit', 1, 21, '2015-05-01 12:48:13', '', NULL),
(7, 'test', 1, 10, '2015-05-01 14:02:14', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `slug`) VALUES
(1, 'Пробна категория', 0, 'probna-kategorija'),
(2, 'PHP Board #2', 0, 'php-board-2'),
(3, 'Примерна подкатегория', 1, 'примерна-подкатегория'),
(4, 'Yet another subcategory', 3, 'yet-another-subcategory');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '0',
  `views` int(11) NOT NULL DEFAULT '0',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `slug` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `title`, `text`, `user_id`, `category_id`, `views`, `date_created`, `slug`) VALUES
(1, 'Пробен въпрос за сигурността на сайта', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 1, 3, 184, '2015-04-29 17:43:25', 'proben-vpros-za-sigurnostta-na-sajta'),
(2, 'Моят примерен Въпроз', 'Това е моят примерен текст.\r\nТой е много смислен и логически структуриран.\r\nbh 4uek', 1, 2, 13, '2015-04-30 17:30:44', 'mojat-primeren-vproz'),
(10, 'Meine simple question to ask', 'Can jet fuel melt steel beams?', 1, 2, 10, '2015-04-30 17:43:10', 'meine-simple-question-to-ask'),
(16, 'test again', 'Test text', 1, 1, 9, '2015-04-30 17:58:00', 'test-again'),
(20, 'One last i think?', 'Not sure if it is last', 1, 3, 4, '2015-05-01 12:46:52', 'one-last-i-think'),
(21, 'One last i think?', 'Not sure if it is last', 1, 3, 3, '2015-05-01 12:47:25', 'one-last-i-think'),
(22, 'Muh questions', 'muh questions\r\naz takoa\r\nnimoa mn dubre', 1, 3, 2, '2015-05-01 16:49:30', 'muh-questions'),
(23, 'Muh questions v2', 'muh questions mdae', 1, 3, 7, '2015-05-01 16:49:44', 'muh-questions-v2'),
(25, 'Proben vabroz', 'Malko tegzd', 1, 1, 4, '2015-05-01 21:42:01', 'proben-vabroz');

-- --------------------------------------------------------

--
-- Table structure for table `questions_tags`
--

CREATE TABLE IF NOT EXISTS `questions_tags` (
  `tag_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions_tags`
--

INSERT INTO `questions_tags` (`tag_id`, `question_id`) VALUES
(21, 2),
(21, 15),
(21, 16),
(21, 17),
(21, 18),
(21, 19),
(21, 21),
(21, 22),
(21, 23),
(21, 25),
(22, 10),
(23, 10),
(26, 15),
(26, 16),
(28, 17),
(28, 18),
(28, 19),
(29, 17),
(29, 18),
(29, 19),
(30, 17),
(30, 18),
(30, 19),
(31, 17),
(31, 18),
(31, 19),
(32, 21),
(33, 21),
(34, 23),
(35, 23),
(36, 23),
(44, 2),
(44, 20),
(45, 10),
(46, 10),
(47, 23),
(48, 25),
(49, 25),
(50, 25),
(54, 1),
(56, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
`id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=57 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tag`, `slug`) VALUES
(18, 'моите', 'moite'),
(19, 'примерни', 'primerni'),
(20, 'тагове', 'tagove'),
(21, 'таг', 'tag'),
(22, 'steel', 'steel'),
(23, 'beams', 'beams'),
(26, 'test', 'test'),
(27, 'da', 'da'),
(28, 'sum', 'sum'),
(29, 'tags', 'tags'),
(30, 'bruh', 'bruh'),
(31, 'pls', 'pls'),
(32, 'yeah', 'yeah'),
(33, 'last', 'last'),
(34, 'tak', 'tak'),
(35, 'kat', 'kat'),
(36, 'taka', 'taka'),
(37, 'towa', 'towa'),
(38, 'sa', 'sa'),
(39, 'nowi', 'nowi'),
(40, 'tagowe', 'tagowe'),
(41, 'now', 'now'),
(42, 'още', 'oshche'),
(43, 'примери', 'primeri'),
(44, 'example', 'example'),
(45, 'jet', 'jet'),
(46, 'fuel', 'fuel'),
(47, 'kak li', 'kak-li'),
(48, 'primeren', 'primeren'),
(49, 'може би', 'mozhe-bi'),
(50, 'а може би не', 'a-mozhe-bi-ne'),
(51, 'probvam', 'probvam'),
(52, 'nanovo', 'nanovo'),
(53, 'demo', 'demo'),
(54, 'i i oshte edin primer', 'i-i-oshte-edin-primer'),
(55, 'and again', 'and-again'),
(56, 'trying concat', 'trying-concat');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `date_created`, `is_admin`) VALUES
(1, 'f1mp3r', 'b335d217b1db9c86a6fc1f4d2e9c7eda', 'craizup@gmail.com', '2015-04-30 02:03:29', 1),
(2, 'demo', '2cc28648c063f981046822114d952c8d', 'az@ti.ne', '2015-04-30 21:56:05', 0),
(3, 'testuser', 'b335d217b1db9c86a6fc1f4d2e9c7eda', 'asd@asd.as', '2015-05-01 22:43:01', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_tags`
--
ALTER TABLE `questions_tags`
 ADD PRIMARY KEY (`tag_id`,`question_id`), ADD UNIQUE KEY `tag_id_2` (`tag_id`,`question_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
