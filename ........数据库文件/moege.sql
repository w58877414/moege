-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-07-17 19:38:33
-- 服务器版本： 5.6.36
-- PHP Version: 7.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moege`
--

-- --------------------------------------------------------

--
-- 表的结构 `moe_admin`
--

CREATE TABLE `moe_admin` (
  `a_id` int(11) NOT NULL,
  `a_username` varchar(20) NOT NULL DEFAULT '',
  `a_password` varchar(128) NOT NULL DEFAULT '',
  `a_salt` varchar(32) NOT NULL DEFAULT '',
  `a_nickname` varchar(20) NOT NULL DEFAULT '',
  `a_head` varchar(200) DEFAULT NULL,
  `a_email` varchar(30) DEFAULT NULL,
  `a_ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `a_level` enum('0','1','2') NOT NULL DEFAULT '2',
  `a_phone` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_admin`
--

INSERT INTO `moe_admin` (`a_id`, `a_username`, `a_password`, `a_salt`, `a_nickname`, `a_head`, `a_email`, `a_ctime`, `a_level`, `a_phone`) VALUES
(1, 'admin', '8f7042c75ff9f713a42fbabe7dc7fd35e3bf1b13205862d4b3c1477340fb2a59041b8fe6d491cead6b48c9d81d6805fe438e3d9c481687c536d31c3863ad1fcc', '$AMIxVtSyA63@OLz&Ps/X5c8Dc$xTttE', 'Mr.阿萌', 'moe_upload/head/admin1.jpg', 'admin@wangmengyu.cn', '2017-01-26 00:00:00', '0', '18752060610'),
(2, 'admin1', '3f29d92ba8140b00c81960580a16ed79b4d2c4b45e67a7a76e498abe03e16649913caaadd9d613ec2481f427cc058078e2b4878e05e9f913d1bad57cf2d97836', '7cNxYFNB7@Y)tSHe^mr%y+xm^Fs&%^!5', 'Mr.阿萌1', 'moe_upload/head/admin2.jpg', 'admin@wangmengyu.cn', '2017-01-27 00:00:00', '1', '18752060610'),
(3, 'admin2', 'cf576027bee355f8fb4cb3a4e83c8b03edf0503626fb32b3ef41db296a9bc4f70cfb041f24b7f3f998706d5608dca1d2e3ed8f7135dfd2850cd9200276f9bba5', '4+40hmT&braT2)wDaC9w=_%Pr6aI1*8o', '测试1', 'moe_upload/head/defalut.png', '', '2017-02-11 01:00:30', '2', '');

-- --------------------------------------------------------

--
-- 表的结构 `moe_admin_log`
--

CREATE TABLE `moe_admin_log` (
  `id` int(11) NOT NULL,
  `aid` int(11) NOT NULL DEFAULT '0',
  `aurl` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `atime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_admin_log`
--

INSERT INTO `moe_admin_log` (`id`, `aid`, `aurl`, `ip`, `atime`) VALUES
(1, 1, '/index.php?m=MoeDemo&v=logging', '117.88.254.78', '2017-03-10 01:58:10'),
(2, 1, '/index.php?m=MoeDemo&v=memberAdd', '117.88.254.78', '2017-03-10 01:59:43'),
(3, 1, '/index.php?m=MoeDemo&v=memberUpd', '117.88.254.78', '2017-03-10 02:01:55'),
(4, 1, '/index.php?m=MoeDemo&v=groupAdd', '117.88.254.78', '2017-03-10 02:04:24'),
(5, 1, '/index.php?m=MoeDemo&v=groupAdd', '117.88.254.78', '2017-03-10 02:04:35'),
(6, 1, '/index.php?m=MoeDemo&v=groupAdd', '117.88.254.78', '2017-03-10 02:04:45'),
(7, 1, '/index.php?m=MoeDemo&v=logging', '222.95.83.103', '2017-03-10 10:10:09'),
(8, 1, '/index.php?m=MoeDemo&v=logging', '58.213.85.18', '2017-03-15 14:56:05'),
(9, 1, '/index.php?m=MoeDemo&v=memberDel&mid=5', '58.213.85.18', '2017-03-15 14:56:14'),
(10, 1, '/index.php?m=MoeDemo&v=adminUpdPass', '58.213.85.18', '2017-03-15 14:58:20'),
(11, 1, '/index.php?m=MoeDemo&v=adminUpdPass', '58.213.85.18', '2017-03-15 14:59:10'),
(12, 1, '/index.php?m=MoeDemo&v=adminUpdPass', '58.213.85.18', '2017-03-15 14:59:26'),
(13, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:00:44'),
(14, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:13:36'),
(15, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:14:49'),
(16, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:15:23'),
(17, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:15:26'),
(18, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:16:33'),
(19, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:16:37'),
(20, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.213.85.18', '2017-03-15 15:16:47'),
(21, 1, '/index.php?m=MoeDemo&v=logging', '58.213.85.18', '2017-03-15 16:19:24'),
(22, 1, '/index.php?m=MoeDemo&v=memberDel&mid=6', '58.213.85.18', '2017-03-15 18:22:30'),
(23, 1, '/index.php?m=MoeDemo&v=logging', '58.217.162.130', '2017-03-18 15:35:53'),
(24, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.217.162.130', '2017-03-18 15:36:35'),
(25, 1, '/index.php?m=MoeDemo&v=noticeAdd', '58.217.162.130', '2017-03-18 15:38:58'),
(26, 1, '/index.php?m=MoeDemo&v=adminUpd', '58.217.162.130', '2017-03-18 15:40:19'),
(27, 1, '/index.php?m=MoeDemo&v=logout', '58.217.162.130', '2017-03-18 15:41:08'),
(28, 1, '/index.php?m=MoeDemo&v=logging', '117.136.66.131', '2017-03-19 01:06:52'),
(29, 1, '/index.php?m=MoeDemo&v=picUpdDis', '117.136.66.131', '2017-03-19 01:13:02'),
(30, 1, '/index.php?m=MoeDemo&v=noticeDel&nid=2', '117.136.66.131', '2017-03-19 01:13:11'),
(31, 1, '/index.php?m=MoeDemo&v=adminUpdhead', '117.136.66.131', '2017-03-19 01:16:51'),
(32, 1, '/index.php?m=MoeDemo&v=adminUpdhead', '117.136.66.131', '2017-03-19 01:17:37'),
(33, 1, '/index.php?m=MoeDemo&v=adminUpdhead', '117.136.66.131', '2017-03-19 01:17:52'),
(34, 1, '/index.php?m=MoeDemo&v=adminUpdhead', '117.136.66.131', '2017-03-19 01:20:30'),
(35, 1, '/index.php?m=MoeDemo&v=adminUpdhead', '117.136.66.131', '2017-03-19 01:26:40'),
(36, 1, '/index.php?m=MoeDemo&v=memberUpdhead', '117.136.66.131', '2017-03-19 01:33:42'),
(37, 1, '/index.php?m=MoeDemo&v=memberUpdhead', '117.136.66.131', '2017-03-19 01:33:56'),
(38, 1, '/index.php?m=MoeDemo&v=memberDel&mid=7', '117.136.66.131', '2017-03-19 01:34:06'),
(39, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-07 14:26:05'),
(40, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-05-07 14:50:29'),
(41, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-05-07 14:51:30'),
(42, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-07 14:52:05'),
(43, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-07 20:51:09'),
(44, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-07 23:43:46'),
(45, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-08 01:37:41'),
(46, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-05-09 16:11:03'),
(47, 1, '?m=MoeDemo&v=logging', '122.96.140.194', '2017-05-10 19:14:50'),
(48, 1, '?m=MoeDemo&v=noticeUpd&nid=1', '122.96.140.194', '2017-05-10 19:15:57'),
(49, 1, '?m=MoeDemo&v=noticeUpd&nid=1', '122.96.140.194', '2017-05-10 19:16:27'),
(50, 1, '?m=MoeDemo&v=logging', '122.96.140.194', '2017-05-11 15:09:29'),
(51, 1, '?m=MoeDemo&v=logging', '223.112.6.226', '2017-05-12 13:39:06'),
(52, 1, '?m=MoeDemo&v=logging', '223.112.6.226', '2017-05-12 23:57:32'),
(53, 1, '?m=MoeDemo&v=logout', '223.112.6.226', '2017-05-13 00:00:31'),
(54, 2, '?m=MoeDemo&v=logging', '223.112.6.226', '2017-05-13 00:00:49'),
(55, 2, '?m=MoeDemo&v=picTagDel&tagid=58&pid=24', '223.112.6.226', '2017-05-13 00:01:02'),
(56, 1, '?m=MoeDemo&v=logging', '223.112.6.226', '2017-05-13 01:44:04'),
(57, 1, '/moege/index.php?m=MoeDemo&v=logging', '::1', '2017-07-18 00:33:22'),
(58, 1, '/moege/index.php?m=MoeDemo&v=adminUpdPass', '::1', '2017-07-18 00:33:59'),
(59, 1, '/moege/index.php?m=MoeDemo&v=adminUpd', '::1', '2017-07-18 00:34:05'),
(60, 1, '/moege/index.php?m=MoeDemo&v=adminUpdPass', '::1', '2017-07-18 00:34:26'),
(61, 1, '/moege/index.php?m=MoeDemo&v=adminUpd', '::1', '2017-07-18 00:34:46'),
(62, 1, '/moege/index.php?m=MoeDemo&v=adminUpdPass', '::1', '2017-07-18 00:39:10'),
(63, 1, '/moege/index.php?m=MoeDemo&v=adminUpdPass', '::1', '2017-07-18 00:39:28'),
(64, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:39:53'),
(65, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:40:13'),
(66, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:40:20'),
(67, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:40:27'),
(68, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:40:35'),
(69, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 00:40:42'),
(70, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 01:19:50'),
(71, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 01:23:10'),
(72, 1, '/moege/index.php?m=MoeDemo&v=picDel&pid=', '::1', '2017-07-18 01:29:35'),
(73, 1, '/moege/index.php?m=MoeDemo&v=picDel&pid=25', '::1', '2017-07-18 01:30:00'),
(74, 1, '/moege/index.php?m=MoeDemo&v=noticeAdd', '::1', '2017-07-18 01:41:31'),
(75, 1, '/moege/index.php?m=MoeDemo&v=noticeAdd', '::1', '2017-07-18 01:46:27'),
(76, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=1', '::1', '2017-07-18 01:46:41'),
(77, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:46:49'),
(78, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:47:34'),
(79, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:47:52'),
(80, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:48:59'),
(81, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:49:10'),
(82, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:49:20'),
(83, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:49:28'),
(84, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:49:36'),
(85, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:49:41'),
(86, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:50:03'),
(87, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:50:08'),
(88, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:50:34'),
(89, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:50:52'),
(90, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:51:13'),
(91, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:51:22'),
(92, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 01:51:36'),
(93, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:05:54'),
(94, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:11:40'),
(95, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:12:11'),
(96, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:12:18'),
(97, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:12:35'),
(98, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:12:44'),
(99, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:12:48'),
(100, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:13:11'),
(101, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:13:24'),
(102, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:17:31'),
(103, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 02:19:04'),
(104, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 02:19:22'),
(105, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 02:19:28'),
(106, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 02:20:20'),
(107, 1, '/moege/index.php?m=MoeDemo&v=memberUpdDis', '::1', '2017-07-18 02:20:22'),
(108, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:21:12'),
(109, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:23:27'),
(110, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:25:03'),
(111, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:25:50'),
(112, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:26:34'),
(113, 1, '/moege/index.php?m=MoeDemo&v=adminUpd', '::1', '2017-07-18 02:26:41'),
(114, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 02:26:48'),
(115, 1, '/moege/index.php?m=MoeDemo&v=memberUpdPass', '::1', '2017-07-18 02:26:53'),
(116, 1, '/moege/index.php?m=MoeDemo&v=adminUpdPass', '::1', '2017-07-18 02:28:20'),
(117, 1, '/moege/index.php?m=MoeDemo&v=adminUpd', '::1', '2017-07-18 02:28:27'),
(118, 1, '/moege/index.php?m=MoeDemo&v=adminDel&aid=3', '::1', '2017-07-18 02:31:04'),
(119, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:31:27'),
(120, 1, '/moege/index.php?m=MoeDemo&v=noticeUpd&nid=3', '::1', '2017-07-18 02:31:51'),
(121, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:30:01'),
(122, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:30:21'),
(123, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:30:25'),
(124, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:30:28'),
(125, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:32:11'),
(126, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:32:36'),
(127, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:34:40'),
(128, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:34:46'),
(129, 1, '/moege/index.php?m=MoeDemo&v=memberClear&mid=1', '::1', '2017-07-18 03:34:59'),
(130, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 03:37:00'),
(131, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 03:37:16'),
(132, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 03:37:21'),
(133, 1, '/moege/index.php?m=MoeDemo&v=memberUpd', '::1', '2017-07-18 03:37:30'),
(134, 1, '/moege/index.php?m=MoeDemo&v=memberUpdDis', '::1', '2017-07-18 03:37:34'),
(135, 1, '/moege/index.php?m=MoeDemo&v=memberDel&mid=2', '::1', '2017-07-18 03:37:42'),
(136, 1, '/moege/index.php?m=MoeDemo&v=memberDel&mid=8', '::1', '2017-07-18 03:37:48');

-- --------------------------------------------------------

--
-- 表的结构 `moe_favorite`
--

CREATE TABLE `moe_favorite` (
  `m_id` int(11) NOT NULL DEFAULT '0',
  `pic_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏';

-- --------------------------------------------------------

--
-- 表的结构 `moe_follow`
--

CREATE TABLE `moe_follow` (
  `m_id` int(11) NOT NULL DEFAULT '0',
  `fm_id` int(11) NOT NULL DEFAULT '0',
  `lock` int(1) NOT NULL DEFAULT '0',
  `locktime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `moe_group`
--

CREATE TABLE `moe_group` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(10) NOT NULL DEFAULT '',
  `group_info` varchar(255) DEFAULT NULL,
  `group_ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='兴趣群组';

--
-- 转存表中的数据 `moe_group`
--

INSERT INTO `moe_group` (`group_id`, `group_name`, `group_info`, `group_ctime`) VALUES
(1, '官方群组', '这是官方的呦', '2017-03-10 02:04:24'),
(2, '测试群组1', '测试群组', '2017-03-10 02:04:35'),
(3, '测试群组2', '测试群组', '2017-03-10 02:04:45');

-- --------------------------------------------------------

--
-- 表的结构 `moe_group_join`
--

CREATE TABLE `moe_group_join` (
  `group_id` int(11) NOT NULL DEFAULT '0',
  `m_id` int(11) NOT NULL DEFAULT '0',
  `join_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组参加';

--
-- 转存表中的数据 `moe_group_join`
--

INSERT INTO `moe_group_join` (`group_id`, `m_id`, `join_time`) VALUES
(1, 1, '2017-03-11 02:57:31');

-- --------------------------------------------------------

--
-- 表的结构 `moe_member`
--

CREATE TABLE `moe_member` (
  `m_id` int(11) NOT NULL,
  `m_name` varchar(20) NOT NULL DEFAULT '',
  `m_password` varchar(128) NOT NULL DEFAULT '',
  `m_salt` varchar(32) NOT NULL DEFAULT '' COMMENT '',
  `m_nickname` varchar(20) NOT NULL DEFAULT '' COMMENT '',
  `m_tag` varchar(120) DEFAULT '',
  `m_gender` varchar(4) DEFAULT '' COMMENT '',
  `m_address` varchar(30) DEFAULT '',
  `m_birthday` date DEFAULT NULL,
  `m_job` varchar(20) DEFAULT '',
  `m_qq` varchar(20) DEFAULT '',
  `m_email` varchar(30) DEFAULT '',
  `m_info` varchar(255) DEFAULT NULL,
  `m_head` varchar(200) DEFAULT '',
  `m_tool` varchar(30) DEFAULT '',
  `m_ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员';

--
-- 转存表中的数据 `moe_member`
--

INSERT INTO `moe_member` (`m_id`, `m_name`, `m_password`, `m_salt`, `m_nickname`, `m_tag`, `m_gender`, `m_address`, `m_birthday`, `m_job`, `m_qq`, `m_email`, `m_info`, `m_head`, `m_tool`, `m_ctime`) VALUES
(1, 'admin1', 'cc00477b319653e4208cf55c139a9c71a7f3dce5f242f156972d0757529290de4d16d32f25048d72cb1433a65240acab63852dacfbb18ddcef59ba2c72ea5cd2', '_8@X#ND(pU7WV7g8K327=mcxo*C/^x3P', '阿萌', '', '男', '江苏南京', '1995-03-28', '程序猿', '', 'admin@wangmengyu.cn', '萌物控重症患者', 'moe_upload/head/1.jpg', '数位板，铅笔', '2017-03-10 01:59:43'),
(3, 'macong', 'ee509a585899f4584ce6b7af7898ac578fb0551d5a049bef46db410cc93a8350a22e7dcb3c2ca35b3a3aadee1593ebbdd600ada3a28e16a5a3e4ab4049f5511c', 'bmMd#buPYW95gfD/$8s0PSvQ/w9k1o6o', '马大聪', '', '', '', NULL, '', '', '', NULL, 'moe_upload/head/defalut.png', '', '2017-03-15 10:47:21'),
(4, 'wangmengyu', '49c8ba3fe274e03d842a40f9795d3b77ce81cc79e68684950f18705a466ceddf42859eb32793a57a6db52cc410a956a1e8ce76b234c55ca4316632f2cb0e9944', 'sXxo+5LmSBfXC!o#v*xbxPt1ZbKdgYuA', '小阿萌', '', '', '', NULL, '', '', '', NULL, 'moe_upload/head/defalut.png', '', '2017-03-15 10:48:22');

-- --------------------------------------------------------

--
-- 表的结构 `moe_notice`
--

CREATE TABLE `moe_notice` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `article` text NOT NULL,
  `ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_notice`
--

INSERT INTO `moe_notice` (`id`, `title`, `article`, `ctime`) VALUES
(1, '格萌网正式开张啦~~来加入我们吧~~', '&lt;p&gt;格萌网正式开张啦~~来加入我们吧~~&lt;/p&gt;', '2017-03-15 15:16:37'),
(3, '系统程序架构简介，及系统账户体验', '&lt;p style=&quot;text-indent: 2em;&quot;&gt;本站是一个原画资源分享网站，每个参与者都可以利用本平台进行自己作品的分享，浏览及检索其他参与者的作品，并能够对他人的作品进行打分评价，以及评论交流。&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;电脑端地址：&lt;a href=&quot;http://moege.wangmengyu.cn&quot; target=&quot;_blank&quot;&gt;http://moege.wangmengyu.cn&lt;/a&gt;&lt;span style=&quot;color: rgb(57, 57, 57);&quot;&gt;&lt;br/&gt; &lt;/span&gt;&lt;span style=&quot;color: rgb(57, 57, 57); text-indent: 2em;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;移动端地址：&lt;a href=&quot;http://moege.wangmengyu.cn/?m=Moem&quot; target=&quot;_blank&quot;&gt;http://moege.wangmengyu.cn/?m=Moem&lt;/a&gt;&lt;br/&gt; &lt;/span&gt;&lt;span style=&quot;color: rgb(57, 57, 57); text-indent: 2em;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;后台地址：&lt;a href=&quot;http://moege.wangmengyu.cn/?m=MoeDemo&quot; target=&quot;_blank&quot;&gt;http://moege.wangmengyu.cn/?m=MoeDemo&lt;/a&gt;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;&lt;span style=&quot;color: rgb(57, 57, 57); text-indent: 2em;&quot;&gt; &lt;/span&gt;&lt;span style=&quot;color: rgb(57, 57, 57); text-indent: 2em;&quot;&gt;体验账户，请查看本文末尾。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;本系统架构是仿照CI框架的使用模式，利用原生PHP构建的基于MVC的网站程序，除网站文本编辑器及评论功能外，没有使用任何第三方的轮子，前端则只使用了JQuery。&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;虽有很多不成熟的地方，但初期的功能已基本完善，可作为上线的程序处理。&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;整个系统核心文件为26.2KB（未压缩），包含了6个常用的封装类：&lt;br/&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;①数据库conn类、②顶级base类、③模型Model类、④控制器Controller类、⑤图片处理imgGD类、⑥验证码imgCode类。&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;①数据库conn类：使用PDO对象连接数据库，使用持久化连接，并构建了相对完善的格式化操作方法，一定程度上避免了sql注入的风险，且支持rollBack回滚操作。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;②顶级base类：包含了常用的一些方法操作，例如加密，格式化，表单过滤，URL参数操作，URL跳转等常用的方法。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;③模型Model类：包含了模型对应数据库的初始化操作。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;④控制器Controller类：包含了对模型以及模板视图的检测、初始化，调用，数据传递等方法。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;⑤图片处理imgGD类：包含了对图片的大小，尺寸，截取，生成等处理，为了避免图片攻击，本站上传的图片不会直接存入文件，而是通过imagecopyresampled()方法，重采样生成数据，以此才能存入本地。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;⑥验证码imgCode类：利用GD库构建的验证码生成类。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;本系统整体实现并不困难，开发过程中所花费的时间主要还是细节上的问题：&lt;br/&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;后端时间主要花在数据库优化，方法格式化封装，图片处理上等。&lt;br/&gt;&lt;/span&gt;&lt;span style=&quot;text-indent: 2em;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;前端时间主要花在头像编辑截取功能，自适应，后台菜单栏仿wordpress的滑动效果等。&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;管理员账户：admin、admin1、admin2（密码均为adminadmin，各自对应着不同的权限等级）&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;用户账户：admin1、caiwenhao、macong、wangmengyu、xbz0412（密码均为useruser）&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;PS：后台管理员账户列表入口，为点击左侧菜单栏管理员头像，管理员的任何更改操作都会被记录为操作日志。&lt;/p&gt;&lt;p style=&quot;text-indent: 2em;&quot;&gt;PS：本域名为测试展示用，所以开放所有功能及账户，用户账户&lt;span style=&quot;text-indent: 32px;&quot;&gt;admin1存在上传作品（为本人所画），删除操作神马的也请随意，但为了让其他人也能够正常浏览，用户信息修改功能被我禁止了，该公告也被禁止编辑，请见谅，我也会时不时更新快照。&lt;/span&gt;&lt;/p&gt;', '2017-07-18 01:41:31');

-- --------------------------------------------------------

--
-- 表的结构 `moe_pic`
--

CREATE TABLE `moe_pic` (
  `pic_id` int(11) NOT NULL,
  `pic_name` varchar(40) NOT NULL,
  `pic_info` varchar(255) DEFAULT '',
  `pic_typeid` int(11) NOT NULL DEFAULT '0',
  `pic_ctime` datetime NOT NULL,
  `pic_src` varchar(200) NOT NULL,
  `pic_bsrc` varchar(200) NOT NULL,
  `pic_ssrc` varchar(200) NOT NULL,
  `pic_tool` varchar(100) DEFAULT NULL,
  `pic_isshow` enum('0','1') NOT NULL DEFAULT '0',
  `m_id` int(11) NOT NULL DEFAULT '0',
  `weektimes` int(11) NOT NULL DEFAULT '0',
  `weekgrade` int(11) NOT NULL DEFAULT '0',
  `weekgrades` int(11) NOT NULL DEFAULT '0',
  `weeklast` date NOT NULL DEFAULT '0000-00-00',
  `daytimes` int(11) NOT NULL DEFAULT '0',
  `daygrade` int(11) NOT NULL DEFAULT '0',
  `daygrades` int(11) NOT NULL DEFAULT '0',
  `daylast` date NOT NULL DEFAULT '0000-00-00',
  `alltimes` int(11) NOT NULL DEFAULT '0',
  `allgrade` int(11) NOT NULL DEFAULT '0',
  `allgrades` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图片';

-- --------------------------------------------------------

--
-- 表的结构 `moe_pic_grades`
--

CREATE TABLE `moe_pic_grades` (
  `pic_id` int(11) NOT NULL DEFAULT '0',
  `m_id` int(11) NOT NULL DEFAULT '0',
  `grade` int(2) NOT NULL DEFAULT '0',
  `gdate` date NOT NULL DEFAULT '0000-00-00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_pic_grades`
--

INSERT INTO `moe_pic_grades` (`pic_id`, `m_id`, `grade`, `gdate`) VALUES
(23, 1, 10, '2017-05-07'),
(24, 8, 10, '2017-05-13');

-- --------------------------------------------------------

--
-- 表的结构 `moe_pic_tag`
--

CREATE TABLE `moe_pic_tag` (
  `pic_id` int(11) NOT NULL DEFAULT '0',
  `tag_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_pic_tag`
--

INSERT INTO `moe_pic_tag` (`pic_id`, `tag_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(3, 7),
(4, 4),
(4, 8),
(4, 9),
(4, 10),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(6, 4),
(6, 11),
(6, 12),
(6, 13),
(7, 4),
(7, 14),
(7, 15),
(8, 4),
(8, 16),
(8, 17),
(8, 18),
(9, 2),
(9, 4),
(9, 12),
(9, 19),
(10, 4),
(10, 20),
(10, 21),
(10, 22),
(10, 23),
(11, 2),
(11, 4),
(11, 11),
(11, 24),
(11, 25),
(11, 26),
(12, 4),
(12, 27),
(12, 28),
(12, 29),
(13, 11),
(13, 21),
(13, 22),
(13, 30),
(13, 31),
(14, 4),
(14, 32),
(14, 33),
(15, 4),
(15, 34),
(15, 35),
(15, 36),
(16, 37),
(16, 38),
(16, 39),
(16, 40),
(17, 4),
(17, 11),
(17, 12),
(17, 41),
(18, 4),
(18, 11),
(18, 31),
(18, 42),
(18, 43),
(18, 44),
(19, 4),
(19, 11),
(19, 45),
(19, 46),
(19, 47),
(20, 4),
(20, 48),
(20, 49),
(20, 50),
(21, 4),
(21, 9),
(21, 43),
(21, 51),
(22, 4),
(22, 9),
(22, 43),
(22, 52),
(22, 53),
(23, 4),
(23, 54),
(23, 55),
(23, 56),
(24, 4),
(24, 43),
(24, 57),
(25, 59);

-- --------------------------------------------------------

--
-- 表的结构 `moe_tag`
--

CREATE TABLE `moe_tag` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(20) NOT NULL DEFAULT '',
  `tag_ctime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `moe_tag`
--

INSERT INTO `moe_tag` (`tag_id`, `tag_name`, `tag_ctime`) VALUES
(1, '问题儿童都来自异世界', '2017-03-10 11:27:26'),
(2, '日系', '2017-03-10 11:27:26'),
(3, '黑兔', '2017-03-10 11:27:26'),
(4, '手绘', '2017-03-10 11:27:26'),
(5, '刀剑神域', '2017-03-10 11:31:11'),
(6, '亚丝娜', '2017-03-10 11:31:11'),
(7, '本子娜', '2017-03-10 11:31:11'),
(8, '要听爸爸的话', '2017-03-10 11:32:15'),
(9, '萝莉', '2017-03-10 11:32:15'),
(10, '裸体', '2017-03-10 11:32:15'),
(11, '萌妹子', '2017-03-10 11:36:24'),
(12, '微笑', '2017-03-10 11:36:24'),
(13, '食梦者玛利', '2017-03-10 11:36:24'),
(14, '零之使魔', '2017-03-10 11:37:18'),
(15, '露易丝', '2017-03-10 11:37:18'),
(16, '镜音双子', '2017-03-10 11:38:07'),
(17, '镜音', '2017-03-10 11:38:07'),
(18, '皮卡丘', '2017-03-10 11:38:07'),
(19, '妹子', '2017-03-10 11:38:51'),
(20, '博丽灵梦', '2017-03-10 11:39:59'),
(21, '东方', '2017-03-10 11:39:59'),
(22, '东方Project', '2017-03-10 11:39:59'),
(23, '巫女', '2017-03-10 11:39:59'),
(24, '栗山未来', '2017-03-10 11:41:05'),
(25, '境界的彼方', '2017-03-10 11:41:05'),
(26, '眼睛娘', '2017-03-10 11:41:05'),
(27, '信蜂', '2017-03-10 11:42:22'),
(28, '拉格', '2017-03-10 11:42:22'),
(29, '正太', '2017-03-10 11:42:22'),
(30, '大刀', '2017-03-10 11:43:39'),
(31, '兽娘', '2017-03-10 11:43:39'),
(32, '路飞', '2017-03-10 11:44:26'),
(33, '海贼王', '2017-03-10 11:44:26'),
(34, '罪恶王冠', '2017-03-10 11:45:16'),
(35, '女神', '2017-03-10 11:45:16'),
(36, '歌姬', '2017-03-10 11:45:16'),
(37, '英雄联盟', '2017-03-10 11:45:53'),
(38, 'LOL', '2017-03-10 11:45:53'),
(39, '艾希', '2017-03-10 11:45:53'),
(40, '露露', '2017-03-10 11:45:53'),
(41, '花', '2017-03-10 11:49:07'),
(42, '傲娇', '2017-03-10 11:49:58'),
(43, '少女', '2017-03-10 11:49:58'),
(44, '尾巴', '2017-03-10 11:49:58'),
(45, '神奇宝贝', '2017-03-11 02:45:44'),
(46, '拟人', '2017-03-11 02:45:44'),
(47, '骨头', '2017-03-11 02:45:44'),
(48, '夏目友人帐', '2017-03-11 02:51:05'),
(49, '小狐狸', '2017-03-11 02:51:05'),
(50, '温馨', '2017-03-11 02:51:05'),
(51, '伞', '2017-03-11 02:52:58'),
(52, '百合', '2017-03-11 02:54:24'),
(53, '双子', '2017-03-11 02:54:24'),
(54, '本多二代', '2017-03-11 02:55:25'),
(55, '境界线上的地平线', '2017-03-11 02:55:25'),
(56, '蜻蜓切', '2017-03-11 02:55:25'),
(57, '舰队', '2017-03-11 02:56:42'),
(58, '手机', '2017-03-11 02:56:42'),
(59, 'qq', '2017-07-18 01:25:02');

-- --------------------------------------------------------

--
-- 表的结构 `moe_type`
--

CREATE TABLE `moe_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(10) NOT NULL DEFAULT '',
  `type_info` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类';

--
-- 转存表中的数据 `moe_type`
--

INSERT INTO `moe_type` (`type_id`, `type_name`, `type_info`) VALUES
(1, '原创', '个人原创的内容'),
(2, '伪原创', '伪原创的内容'),
(3, '临摹', '临摹的内容'),
(4, '分享', '分享的内容');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `moe_admin`
--
ALTER TABLE `moe_admin`
  ADD PRIMARY KEY (`a_id`),
  ADD UNIQUE KEY `a_username` (`a_username`);

--
-- Indexes for table `moe_admin_log`
--
ALTER TABLE `moe_admin_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moe_favorite`
--
ALTER TABLE `moe_favorite`
  ADD PRIMARY KEY (`m_id`,`pic_id`);

--
-- Indexes for table `moe_follow`
--
ALTER TABLE `moe_follow`
  ADD PRIMARY KEY (`m_id`,`fm_id`),
  ADD KEY `locktime` (`locktime`);

--
-- Indexes for table `moe_group`
--
ALTER TABLE `moe_group`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `moe_group_join`
--
ALTER TABLE `moe_group_join`
  ADD PRIMARY KEY (`group_id`,`m_id`);

--
-- Indexes for table `moe_member`
--
ALTER TABLE `moe_member`
  ADD PRIMARY KEY (`m_id`),
  ADD UNIQUE KEY `m_name` (`m_name`),
  ADD KEY `m_ctime` (`m_ctime`);

--
-- Indexes for table `moe_notice`
--
ALTER TABLE `moe_notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moe_pic`
--
ALTER TABLE `moe_pic`
  ADD PRIMARY KEY (`pic_id`),
  ADD KEY `pic_ctime` (`pic_ctime`);

--
-- Indexes for table `moe_pic_grades`
--
ALTER TABLE `moe_pic_grades`
  ADD PRIMARY KEY (`pic_id`,`m_id`);

--
-- Indexes for table `moe_pic_tag`
--
ALTER TABLE `moe_pic_tag`
  ADD PRIMARY KEY (`pic_id`,`tag_id`);

--
-- Indexes for table `moe_tag`
--
ALTER TABLE `moe_tag`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `tag_name` (`tag_name`),
  ADD KEY `tag_time` (`tag_ctime`);

--
-- Indexes for table `moe_type`
--
ALTER TABLE `moe_type`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `moe_admin`
--
ALTER TABLE `moe_admin`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `moe_admin_log`
--
ALTER TABLE `moe_admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;
--
-- 使用表AUTO_INCREMENT `moe_group`
--
ALTER TABLE `moe_group`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `moe_member`
--
ALTER TABLE `moe_member`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `moe_notice`
--
ALTER TABLE `moe_notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `moe_pic`
--
ALTER TABLE `moe_pic`
  MODIFY `pic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- 使用表AUTO_INCREMENT `moe_tag`
--
ALTER TABLE `moe_tag`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- 使用表AUTO_INCREMENT `moe_type`
--
ALTER TABLE `moe_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
