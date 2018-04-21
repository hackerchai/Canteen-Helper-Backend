-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-04-17 16:02:20
-- 服务器版本： 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canteen_helper`
--

-- --------------------------------------------------------

--
-- 表的结构 `ch_address`
--
drop table if exists ch_address;
CREATE TABLE `ch_address` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `garden` int(1) NOT NULL,
  `building` int(1) NOT NULL,
  `room` varchar(5) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `user_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ch_admin`
--
CREATE TABLE `ch_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ch_menu`
--
CREATE TABLE `ch_menu` (
  `id` int(11) NOT NULL,
  `meal_name` varchar(50) NOT NULL,
  `meal_price` decimal(5,2) NOT NULL,
  `sales_count` int(11) NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `is_sold_out` tinyint(1) NOT NULL,
  `picture` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ch_merchant`
--
CREATE TABLE `ch_merchant` (
  `id` int(11) NOT NULL,
  `merchant_name` varchar(50) NOT NULL,
  `merchant_desc` text NOT NULL,
  `merchant_pic` text NOT NULL,
  `phone_reg` varchar(15) NOT NULL,
  `merchant_loc` text NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `sales` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ch_merchant_member`
--
CREATE TABLE `ch_merchant_member` (
  `id` int(11) NOT NULL,
  `open_id` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `reg_date` datetime NOT NULL,
  `token` varchar(50) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `merchant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ch_order`
--
drop table if exists ch_order;
CREATE TABLE `ch_order` (
  `id` int(11) NOT NULL,
  `order_num` varchar(30) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `order_type` tinyint(1) NOT NULL,
  `address` int(11) NOT NULL,
  `goods` text NOT NULL,
  `nums` text NOT NULL,
  `create_time` datetime NOT NULL,
  `complete_time` datetime NOT NULL,
  `money` decimal(2,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ch_setting`
--
drop table if exists ch_setting;
CREATE TABLE `ch_setting` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(30) NOT NULL,
  `setting_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `ch_student`
--

CREATE TABLE `ch_student` (
  `id` int(11) NOT NULL,
  `open_id` varchar(50) NOT NULL,
  `stu_id` varchar(15) NOT NULL,
  `stu_pass` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `deposit` decimal(5,2) NOT NULL,
  `reg_date` datetime NOT NULL,
  `is_deliver` tinyint(1) NOT NULL,
  `is _available` tinyint(1) NOT NULL,
  `deliver_name` varchar(50) NOT NULL,
  `token` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ch_timetable`
--
drop table if exists ch_timetable;
CREATE TABLE `ch_timetable` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `week_day` tinyint(1) NOT NULL,
  `meal_time` tinyint(1) NOT NULL,
  `garden` tinyint(1) NOT NULL,
  `building` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ch_address`
--
ALTER TABLE `ch_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_admin`
--
ALTER TABLE `ch_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_menu`
--
ALTER TABLE `ch_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_merchant`
--
ALTER TABLE `ch_merchant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_merchant_member`
--
ALTER TABLE `ch_merchant_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_order`
--
ALTER TABLE `ch_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_setting`
--
ALTER TABLE `ch_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_student`
--
ALTER TABLE `ch_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_timetable`
--
ALTER TABLE `ch_timetable`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ch_address`
--
ALTER TABLE `ch_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_admin`
--
ALTER TABLE `ch_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_menu`
--
ALTER TABLE `ch_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_merchant`
--
ALTER TABLE `ch_merchant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_merchant_member`
--
ALTER TABLE `ch_merchant_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_order`
--
ALTER TABLE `ch_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_setting`
--
ALTER TABLE `ch_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_student`
--
ALTER TABLE `ch_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ch_timetable`
--
ALTER TABLE `ch_timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
