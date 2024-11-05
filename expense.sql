-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 05, 2024 at 02:16 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_authorities_sp` (IN `_userId` VARCHAR(250) CHARSET utf8)   BEGIN

SELECT 
category.id category_id,category.name category_name, category.role, system_links.id link_id,system_links.name link_name, system_actions.id action_id,system_actions.name action_name FROM `user_authority` LEFT JOIN 
system_actions ON user_authority.action = system_actions.id LEFT JOIN 
system_links on system_actions.link_id = system_links.id LEFT JOIN
category on system_links.category_id = category.id where user_authority.user_id = _userId ORDER by category.role, system_links.id, system_actions.id;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_menu_sp` (IN `_userId` VARCHAR(250) CHARSET utf8)   BEGIN

SELECT 
category.id category_id,category.name category_name, category.role, system_links.id link_id,system_links.name link_name FROM `user_authority` LEFT JOIN 
system_actions ON user_authority.action = system_actions.id LEFT JOIN 
system_links on system_actions.link_id = system_links.id LEFT JOIN
category on system_links.category_id = category.id where
user_authority.user_id = _userId group BY system_links.id
ORDER by category.role, system_links.id, system_actions.id;



END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_statement_sp` (IN `_userId` VARCHAR(50) CHARSET utf8, IN `_from` DATE, IN `_to` DATE)   BEGIN

SET @tbalance = 0;
SET @expense = 0;
SET @income = 0;

if (_from = '0000-00-00')THEN
CREATE TEMPORARY TABLE tb SELECT expense.date, expense.user_id,
if(type='Income',amount,0)'Income', if (type = 'Expense', amount, 0) 'Expense',
if(type = 'Income',@tbalance:=@tbalance+amount,@tabalance:=@tbalance-amount)
'Balance'FROM expense WHERE expense.user_id = _userId ORDER BY expense.date ASC;

SELECT * FROM tb

UNION

SELECT '','', SUM(Income),SUM(Expense), @tbalance FROM tb;

ELSE

CREATE TEMPORARY TABLE tb SELECT expense.date, expense.user_id,
if(type='Income',amount,0)Income, if (type = 'Expense', amount, 0) 'Expense',
if(type = 'Income',@tbalance:=@tbalance+amount,@tabalance:=@tbalance-amount)
'Balance'FROM expense WHERE expense.user_id = _userId AND 
expense.date BETWEEN _from AND _to ORDER BY expense.date ASC;

SELECT * FROM tb

UNION

SELECT '','', SUM(Income),SUM(Expense), @tbalance FROM tb;

END IF;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `register_expense_sp` (IN `_id` INT, IN `_amount` FLOAT(11,2), IN `_type` VARCHAR(50) CHARSET utf8, IN `_desc` TEXT CHARSET utf8, IN `_userId` VARCHAR(50) CHARSET utf8)   BEGIN

IF(_type = 'Expense') THEN
IF((SELECT get_user_balance_fn(_userId) < _amount))THEN

SELECT 'Deny' as Message;

ELSE

INSERT INTO expense(expense.amount,expense.type,expense.description,expense.user_id)
VALUES (_amount,_type,_desc,_userId);

SELECT 'Registered' as Message;

END if;

ELSE

INSERT INTO expense(expense.amount,expense.type,expense.description,expense.user_id)
VALUES (_amount,_type,_desc,_userId);

SELECT 'Registered' as Message;

END if;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `get_user_balance_fn` (`_userId` VARCHAR(50) CHARSET utf8) RETURNS FLOAT(11,2)  BEGIN

SET @balance = 0.00;

SET @income = (SELECT SUM(expense.amount) FROM
expense WHERE expense.type = 'Income' AND
expense.user_id = _userId);

SET @expense = (SELECT SUM(expense.amount) FROM
expense WHERE expense.type = 'Expense' AND
expense.user_id = _userId);
                
Set @balance = ifnull(@income,0.00)-
ifnull(@expense,0.00);
                
RETURN @balance;

End$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `role` varchar(250) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `icon`, `role`, `date`) VALUES
(6, 'Subscriber', 'fa-home', 'Subscriber', '2024-08-22 06:53:15'),
(8, 'Super Amin', 'fa-settings', 'SuperAdmin', '2024-08-20 05:50:29'),
(10, 'Dashboard', 'fa-home', 'Dashboard', '2024-08-22 06:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `amount` float(11,2) NOT NULL,
  `type` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`id`, `amount`, `type`, `description`, `user_id`, `date`) VALUES
(4, 90.00, 'Income', '123', 'USR001', '2024-07-17 08:08:03'),
(5, 400.00, 'Income', 'wa salary', 'USR001', '2024-07-16 09:50:25'),
(6, 500.00, 'Income', 'wa salary', 'USR001', '2024-07-16 09:50:46'),
(15, 400.00, 'Income', 'salary', 'USR001', '2024-07-18 06:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `system_actions`
--

CREATE TABLE `system_actions` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `action` varchar(250) NOT NULL,
  `link_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_actions`
--

INSERT INTO `system_actions` (`id`, `name`, `action`, `link_id`, `date`) VALUES
(1, 'register', 'register_system_action', 1, '2024-08-20 10:11:39'),
(3, 'Register category', 'register_category', 5, '2024-08-22 07:00:08'),
(4, 'Read category', 'read_all_category', 5, '2024-08-22 07:00:26'),
(5, 'get category info', 'get_category_info', 5, '2024-08-22 07:00:49'),
(6, 'update category ', 'update_category', 5, '2024-08-22 07:01:09'),
(7, 'delete category', 'delete_category_info', 5, '2024-08-22 07:01:28'),
(8, 'Header', 'view_system_header', 8, '2024-11-04 12:55:20');

-- --------------------------------------------------------

--
-- Stand-in structure for view `system_authority_view`
-- (See below for the actual view)
--
CREATE TABLE `system_authority_view` (
`id` int(11)
,`category` varchar(250)
,`icon` varchar(50)
,`role` varchar(250)
,`link_id` int(11)
,`name` varchar(250)
,`action_id` int(11)
,`action_name` varchar(250)
);

-- --------------------------------------------------------

--
-- Table structure for table `system_links`
--

CREATE TABLE `system_links` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `link` varchar(250) NOT NULL,
  `category_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_links`
--

INSERT INTO `system_links` (`id`, `name`, `link`, `category_id`, `date`) VALUES
(1, 'Dashboard', 'expense.php', 6, '2024-08-20 08:49:53'),
(3, 'Expense ', 'expense.php', 6, '2024-08-22 06:57:37'),
(4, 'system actions', 'system_actions.php', 8, '2024-08-22 06:57:56'),
(5, 'Category', 'category.php', 8, '2024-08-22 06:58:15'),
(6, 'system links', 'system_links.php', 8, '2024-08-22 06:58:36'),
(7, 'User authority', 'user_authority.php', 8, '2024-08-22 06:58:57'),
(8, 'Header', 'header.php', 10, '2024-11-04 12:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(250) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `status` varchar(250) NOT NULL DEFAULT 'Active',
  `image` varchar(250) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `image`, `date`) VALUES
('USR001', 'e', 'e1671797c52e15f763380b45e841ec32', 'Active', 'USR001.png', '2024-09-17 10:44:44'),
('USR002', 'y', '6f8f57715090da2632453988d9a1501b', 'Active', 'USR002.png', '2024-09-17 10:46:08'),
('USR003', 'zain', '827ccb0eea8a706c4c34a16891f84e7b', 'Active', 'USR003.png', '2024-09-17 10:46:55'),
('USR004', '78', '6f8f57715090da2632453988d9a1501b', 'Active', 'USR004.png', '2024-09-17 10:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `user_authority`
--

CREATE TABLE `user_authority` (
  `id` int(11) NOT NULL,
  `user_id` varchar(15) NOT NULL,
  `action` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_authority`
--

INSERT INTO `user_authority` (`id`, `user_id`, `action`) VALUES
(1, 'USR002', '1'),
(2, 'USR002', '1'),
(3, 'USR002', '3'),
(5, 'USR003', '1'),
(6, 'USR003', '1'),
(7, 'USR003', '3'),
(9, 'USR003', '4'),
(10, 'USR003', '5'),
(11, 'USR003', '3'),
(12, 'USR003', '4'),
(13, 'USR003', '5'),
(14, 'USR003', '6'),
(15, 'USR003', '7'),
(16, 'USR003', '6'),
(18, 'USR003', '7'),
(19, 'USR003', 'null'),
(20, 'USR003', '1'),
(21, 'USR003', '3'),
(22, 'USR003', '4'),
(23, 'USR003', '5'),
(24, 'USR003', '6'),
(25, 'USR003', '7');

-- --------------------------------------------------------

--
-- Structure for view `system_authority_view`
--
DROP TABLE IF EXISTS `system_authority_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `system_authority_view`  AS SELECT `category`.`id` AS `id`, `category`.`name` AS `category`, `category`.`icon` AS `icon`, `category`.`role` AS `role`, `system_links`.`id` AS `link_id`, `system_links`.`name` AS `name`, `system_actions`.`id` AS `action_id`, `system_actions`.`name` AS `action_name` FROM ((`category` left join `system_links` on(`category`.`id` = `system_links`.`category_id`)) left join `system_actions` on(`system_links`.`id` = `system_actions`.`link_id`)) ORDER BY `category`.`role` ASC, `system_links`.`id` ASC, `system_actions`.`id` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_actions`
--
ALTER TABLE `system_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_links`
--
ALTER TABLE `system_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_authority`
--
ALTER TABLE `user_authority`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system_actions`
--
ALTER TABLE `system_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `system_links`
--
ALTER TABLE `system_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_authority`
--
ALTER TABLE `user_authority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
