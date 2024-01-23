-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2024 at 10:15 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `internetbanking`
--

-- --------------------------------------------------------

--
-- Table structure for table `ib_acc_types`
--

CREATE TABLE `ib_acc_types` (
  `acctype_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `rate` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_acc_types`
--

INSERT INTO `ib_acc_types` (`acctype_id`, `name`, `description`, `rate`, `code`) VALUES
(1, 'Savings', '<p>Savings accounts&nbsp;are typically the first official bank account anybody opens. Children may open an account with a parent to begin a pattern of saving. Teenagers open accounts to stash cash earned&nbsp;from a first job&nbsp;or household chores.</p><p>Savings accounts are an excellent place to park&nbsp;emergency cash. Opening a savings account also marks the beginning of your relationship with a financial institution. For example, when joining a credit union, your &ldquo;share&rdquo; or savings account establishes your membership.</p>', '20', 'ACC-CAT-4EZFO'),
(2, ' Retirement', '<p>Retirement accounts&nbsp;offer&nbsp;tax advantages. In very general terms, you get to&nbsp;avoid paying income tax on interest&nbsp;you earn from a savings account or CD each year. But you may have to pay taxes on those earnings at a later date. Still, keeping your money sheltered from taxes may help you over the long term. Most banks offer IRAs (both&nbsp;Traditional IRAs&nbsp;and&nbsp;Roth IRAs), and they may also provide&nbsp;retirement accounts for small businesses</p>', '10', 'ACC-CAT-1QYDV'),
(4, 'Recurring deposit', '<p><strong>Recurring deposit account or RD account</strong> is opened by those who want to save certain amount of money regularly for a certain period of time and earn a higher interest rate.&nbsp;In RD&nbsp;account a&nbsp;fixed amount is deposited&nbsp;every month for a specified period and the total amount is repaid with interest at the end of the particular fixed period.&nbsp;</p><p>The period of deposit is minimum six months and maximum ten years.&nbsp;The interest rates vary&nbsp;for different plans based on the amount one saves and the period of time and also on banks. No withdrawals are allowed from the RD account. However, the bank may allow to close the account before the maturity period.</p><p>These accounts can be opened in single or joint names. Banks are also providing the Nomination facility to the RD account holders.&nbsp;</p>', '15', 'ACC-CAT-VBQLE'),
(5, 'Fixed Deposit Account', '<p>In <strong>Fixed Deposit Account</strong> (also known as <strong>FD Account</strong>), a particular sum of money is deposited in a bank for specific&nbsp;period of time. It&rsquo;s one time deposit and one time take away (withdraw) account.&nbsp;The money deposited in this account can not be withdrawn before the expiry of period.&nbsp;</p><p>However, in case of need,&nbsp; the depositor can ask for closing the fixed deposit prematurely by paying a penalty. The penalty amount varies with banks.</p><p>A high interest rate is paid on fixed deposits. The rate of interest paid for fixed deposit vary according to amount, period and also from bank to bank.</p>', '40', 'ACC-CAT-A86GO'),
(7, 'Current account', '<p><strong>Current account</strong> is mainly for business persons, firms, companies, public enterprises etc and are never used for the purpose of investment or savings.These deposits are the most liquid deposits and there are no limits for number of transactions or the amount of transactions in a day. While, there is no interest paid on amount held in the account, banks charges certain &nbsp;service charges, on such accounts. The current accounts do not have any fixed maturity as these are on continuous basis accounts.</p>', '20', 'ACC-CAT-4O8QW');

-- --------------------------------------------------------

--
-- Table structure for table `ib_admin`
--

CREATE TABLE `ib_admin` (
  `admin_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_admin`
--

INSERT INTO `ib_admin` (`admin_id`, `name`, `email`, `number`, `password`, `profile_pic`) VALUES
(2, 'Administrator', 'admin@mail.com', 'iBank-ADM-0516', '036d0ef7567a20b5a4ad24a354ea4a945ddab676', 'admin-icn.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_bankaccounts`
--

CREATE TABLE `ib_bankaccounts` (
  `account_id` int(20) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_rates` varchar(200) NOT NULL,
  `currency_type` varchar(100) NOT NULL,
  `acc_status` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `client_adr` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_bankaccounts`
--

INSERT INTO `ib_bankaccounts` (`account_id`, `acc_name`, `account_number`, `acc_type`, `acc_rates`, `currency_type`, `acc_status`, `acc_amount`, `client_id`, `client_name`, `client_national_id`, `client_phone`, `client_number`, `client_email`, `client_adr`, `created_at`) VALUES
(20, 'Nelson Agbagah', '675913280', 'Current account ', '20', 'GH', 'Active', '0', '6', 'Johnnie J. Reyes', '147455554', '7412545454', 'iBank-CLIENT-1698', 'reyes@mail.com', '23 Hinkle Deegan Lake Road', '2024-01-18 07:48:17.510121'),
(22, 'sedem', '907182635', ' Retirement ', '10', 'GH', 'Active', '0', '11', 'john', '121233', '0559426832', 'iBank-CLIENT-126439750', 'cashf@gmail.com', 'Accra-dzodze street,', '2024-01-18 07:48:23.853339'),
(23, 'Nelson Agbagah', '679403182', 'Savings ', '20', 'GH', 'Active', '0', '12', 'mandela', '121233121', '+233559426832', 'iBank-CLIENT-5102', 'cashf@gmail.com', 'Accra-dzodze street,', '2024-01-18 07:48:33.840333'),
(24, 'courage', '271560493', ' Retirement ', '10', 'GH', 'Active', '0', '12', 'mandela', '121233121', '+233559426832', 'iBank-CLIENT-5102', 'cashf@gmail.com', 'Accra-dzodze street,', '2024-01-18 07:48:39.665997'),
(26, 'john doe', '930784512', 'Current account ', '20', 'GH', 'Active', '0', '12', 'mandela', '121233121', '+233559426832', 'iBank-CLIENT-5102', 'cashf@gmail.com', 'Accra-dzodze street,', '2024-01-18 07:48:46.630524'),
(29, 'ke', '849370165', 'Recurring deposit ', '15', 'USD', 'Active', '0', '12', 'Jack moores', '121233121', '+233559426832', 'iBank-CLIENT-5102', 'cashf@gmail.com', 'Accra-dzodze street,', '2024-01-18 07:40:22.278463');

-- --------------------------------------------------------

--
-- Table structure for table `ib_clients`
--

CREATE TABLE `ib_clients` (
  `client_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `national_id` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `client_number` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_clients`
--

INSERT INTO `ib_clients` (`client_id`, `name`, `national_id`, `phone`, `address`, `email`, `password`, `profile_pic`, `client_number`) VALUES
(10, 'Nelson Agbagah', '121233', '0559426832', 'Accra-dzodze street,', 'nelsonagbagah1002@gmail.com', 'a10a9c50fade36cf796032a46a76c4b620daa6c3', '', 'iBank-CLIENT-0679'),
(11, 'john', '121233', '0559426832', 'Accra-dzodze street,', 'cashf@gmail.com', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1', 'droplets-removebg-preview.png', 'iBank-CLIENT-126439750'),
(12, 'Jack moores', '121233121', '+233559426832', 'Accra-dzodze street,', 'cashf@gmail.com', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1', 'photo_203.jpg', 'iBank-CLIENT-5102'),
(14, 'Nelson Agbagah', '1212331212ew', '+233559426834', 'Accra-dzodze street,', 'cashf841@gmail.com', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1', '', 'iBank-CLIENT-1964');

-- --------------------------------------------------------

--
-- Table structure for table `ib_cot`
--

CREATE TABLE `ib_cot` (
  `cot_id` int(100) NOT NULL,
  `FTC_COT` varchar(100) NOT NULL,
  `AML_COT` varchar(100) NOT NULL,
  `IMF_COT` varchar(100) NOT NULL,
  `IRS_COT` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ib_cot`
--

INSERT INTO `ib_cot` (`cot_id`, `FTC_COT`, `AML_COT`, `IMF_COT`, `IRS_COT`) VALUES
(1, 'FTC2431', 'AML8976', 'IMF3375', 'IRS6901');

-- --------------------------------------------------------

--
-- Table structure for table `ib_notifications`
--

CREATE TABLE `ib_notifications` (
  `notification_id` int(20) NOT NULL,
  `account_id` int(13) NOT NULL,
  `client_id` int(11) NOT NULL,
  `notification_details` text CHARACTER SET latin2 NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_notifications`
--

INSERT INTO `ib_notifications` (`notification_id`, `account_id`, `client_id`, `notification_details`, `created_at`) VALUES
(78, 0, 12, 'mandela Has Withdrawn GH 200 From Bank Account 679403182', '2024-01-14 10:55:43.709005'),
(79, 0, 12, 'mandela Has Withdrawn GH? 200 From Bank Account 679403182', '2024-01-12 15:25:49.769094'),
(80, 0, 0, 'mandela Has Transfered GH 1000 From Bank Account 271560493 To Bank Account 930784512', '2024-01-14 10:56:59.049205'),
(81, 0, 0, 'Johnnie J. Reyes Has Deposited GH?  200 To Bank Account 675913280', '2024-01-09 16:57:51.893779'),
(82, 0, 0, 'Johnnie J. Reyes Has Deposited GH?  200 To Bank Account 675913280', '2024-01-10 11:06:13.601307'),
(86, 0, 12, 'mandela Has Transfered GH?  20 To Bank Account 271560493', '2024-01-13 12:40:01.533102'),
(87, 0, 12, 'mandela Has Transfered GH?  126 To Bank Account 679403182', '2024-01-14 09:08:29.006417'),
(88, 0, 12, 'mandela Has Transfered GH  211 To Bank Account 679403182', '2024-01-14 10:58:00.449898'),
(89, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 12345566', '2024-01-14 11:52:45.980099'),
(90, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 12345566', '2024-01-14 11:52:51.409244'),
(91, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 11:53:40.250433'),
(92, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 11:53:47.035704'),
(93, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 11:54:27.963824'),
(94, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 11:54:34.260455'),
(95, 23, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 12:21:30.491954'),
(96, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 12:22:50.175070'),
(97, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 12:22:55.079177'),
(98, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 122234566', '2024-01-14 13:06:12.634967'),
(99, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 234567890', '2024-01-14 13:06:47.394673'),
(100, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 32456708898', '2024-01-14 13:13:57.253760'),
(101, 0, 0, 'You Did WIRE Transfer Of GH  100 To Bank Account 32456708898', '2024-01-14 13:14:13.936117'),
(102, 0, 0, 'You Did WIRE Transfer Of GH  40 To Bank Account 122334555', '2024-01-14 13:32:38.967686'),
(103, 0, 0, 'Jack moores Has Deposited GH?  1000 To Bank Account 849370165', '2024-01-18 07:56:37.153987');

-- --------------------------------------------------------

--
-- Table structure for table `ib_paybill`
--

CREATE TABLE `ib_paybill` (
  `bill_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `tr_code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `client_number` varchar(100) NOT NULL,
  `bill_amt` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ib_paybill`
--

INSERT INTO `ib_paybill` (`bill_id`, `client_id`, `tr_code`, `name`, `client_number`, `bill_amt`, `description`, `phone`, `created_at`) VALUES
(6, 12, 'w8BpQqC5fX', 'mandela', 'iBank-CLIENT-5102', '200', 'water bill', '+233559426832', '2024-01-04 18:19:41'),
(7, 11, 'x13YlIX4WD', 'john', 'iBank-CLIENT-126439750', '200', 'ECG', '0559426832', '2024-01-04 18:29:32'),
(8, 11, 'bQ2Xy1oGP8', 'john', 'iBank-CLIENT-126439750', '100', 'ECG', '0559426832', '2024-01-04 19:46:03'),
(9, 12, 'ST9ZqtmW0E', 'mandela', 'iBank-CLIENT-5102', '100', 'ECG', '+233559426832', '2024-01-04 19:47:29'),
(10, 12, 'ST9ZqtmW0E', 'mandela', 'iBank-CLIENT-5102', '100', 'ECG', '+233559426832', '2024-01-06 09:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `ib_staff`
--

CREATE TABLE `ib_staff` (
  `staff_id` int(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `staff_number` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `sex` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_staff`
--

INSERT INTO `ib_staff` (`staff_id`, `name`, `staff_number`, `phone`, `email`, `password`, `sex`, `profile_pic`) VALUES
(3, 'staff1', 'iBank-STAFF-6785', '+233559426832', 'staff@mail.com', 'f8a56410845bf8c3c566e5574e720a7a25635002', 'Male', '');

-- --------------------------------------------------------

--
-- Table structure for table `ib_systemsettings`
--

CREATE TABLE `ib_systemsettings` (
  `id` int(20) NOT NULL,
  `sys_name` longtext NOT NULL,
  `sys_tagline` longtext NOT NULL,
  `sys_logo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_systemsettings`
--

INSERT INTO `ib_systemsettings` (`id`, `sys_name`, `sys_tagline`, `sys_logo`) VALUES
(1, 'Internet Banking', 'Financial success at every service we offer.', 'admin.png');

-- --------------------------------------------------------

--
-- Table structure for table `ib_transactions`
--

CREATE TABLE `ib_transactions` (
  `tr_id` int(20) NOT NULL,
  `tr_code` varchar(200) NOT NULL,
  `account_id` varchar(200) NOT NULL,
  `acc_name` varchar(200) NOT NULL,
  `account_number` varchar(200) NOT NULL,
  `acc_type` varchar(200) NOT NULL,
  `acc_amount` varchar(200) NOT NULL,
  `tr_type` varchar(200) NOT NULL,
  `depo_type` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `frontcheck_image` varchar(100) NOT NULL,
  `back_checkimage` varchar(100) NOT NULL,
  `check_number` varchar(100) NOT NULL,
  `tr_status` varchar(200) NOT NULL,
  `client_id` varchar(200) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_national_id` varchar(200) NOT NULL,
  `transaction_amt` varchar(200) NOT NULL,
  `client_phone` varchar(200) NOT NULL,
  `balance` varchar(100) NOT NULL,
  `receiving_acc_no` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `receiving_acc_name` varchar(200) NOT NULL,
  `receiving_acc_holder` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ib_transactions`
--

INSERT INTO `ib_transactions` (`tr_id`, `tr_code`, `account_id`, `acc_name`, `account_number`, `acc_type`, `acc_amount`, `tr_type`, `depo_type`, `description`, `frontcheck_image`, `back_checkimage`, `check_number`, `tr_status`, `client_id`, `client_name`, `client_national_id`, `transaction_amt`, `client_phone`, `balance`, `receiving_acc_no`, `created_at`, `receiving_acc_name`, `receiving_acc_holder`) VALUES
(273, '1bZuByRxdf', '26', 'john doe', '930784512', 'Current account ', '', 'Deposit', 'Cash', 'deposit by so so ', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-07 11:33:53.455075', '', ''),
(274, 'mC1Ev4MXjI', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', 'Cash', 'money from to abc', '', '', '', 'Success ', '12', 'mandela', '121233121', '2000', '+233559426832', '', '', '2024-01-07 11:34:36.321516', '', ''),
(275, 'MomFXC4KgZ', '24', 'courage', '271560493', ' Retirement ', '', 'Deposit', 'Cash', 'check deposit from abc', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-07 11:34:59.306831', '', ''),
(276, 'JhoLwUfPEs', '24', 'courage', '271560493', ' Retirement ', '', 'Deposit', 'Cash', 'deposit by so so ', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '1000', '', '2024-01-07 11:45:42.242041', '', ''),
(277, 'MkraqDQmn0', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', 'Cash', 'deposit by so so ', '', '', '', 'Success ', '12', 'mandela', '121233121', '200', '+233559426832', '2000', '', '2024-01-07 11:46:04.372907', '', ''),
(278, 'l6AbpPwcEL', '24', 'courage', '271560493', ' Retirement ', '', 'Deposit', 'Cash', 'check deposit from abc', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '2000', '', '2024-01-07 11:46:22.363415', '', ''),
(279, 'kSCRpEzc5L', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', 'Cash', 'check deposit from abc', '', '', '', 'Success ', '12', 'mandela', '121233121', '200', '+233559426832', '2200', '', '2024-01-07 11:48:14.293665', '', ''),
(280, 'SQJb4RMFwt', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', 'Cash', 'check deposit from abc', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '2277', '', '2024-01-07 11:52:55.056315', '', ''),
(281, '6uOCj1mqJM', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Withdrawal', '', '', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-07 11:54:25.604504', '', ''),
(283, 'T3WJFH58vw', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Withdrawal', '', '', '', '', '', 'Success ', '12', 'mandela', '121233121', '200', '+233559426832', '2077', '', '2024-01-07 12:07:52.401037', '', ''),
(284, '94aSOM5QyX', '24', 'courage', '271560493', ' Retirement ', '', 'Transfer', '', '', '', '', '', 'Success ', '12', 'mandela', '121233121', '1000', '+233559426832', '3000', '930784512', '2024-01-07 12:16:25.422258', 'john doe', 'mandela'),
(285, '5894114058249', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Withdrawal', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '211', '', '2200', '', '2024-01-07 12:42:05.339298', '', ''),
(286, '3917943923028', '26', 'john doe', '930784512', 'Current account ', '', 'Deposit', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '211', '', '1000', '', '2024-01-07 12:42:05.394475', '', ''),
(298, 'U1lJpiOx0zQrBv9', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', 'Check', 'check', 'photo_203.jpg', 'photo1.jpg', '1111111', 'Success', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-10 12:14:00.476806', '', ''),
(300, 'xWa8Ki2dLRwmCnZ', '26', 'john doe', '930784512', 'Current account ', '', 'CheckDeposit', 'Check', 'nothing less', 'photo1.jpg', 'photo1.jpg', '121265557gha', 'Reject', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-10 12:53:16.029975', '', ''),
(301, 'VIei5HTy1OY8z6v', '26', 'john doe', '930784512', 'Current account ', '', 'Deposit', 'Check', 'the such ', 'photo_2.jpg', 'photo_203.jpg', 'qw1212', 'Success', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-10 12:54:00.135936', '', ''),
(302, 'YXgoBMIjHhmGZCP', '26', 'john doe', '930784512', 'Current account ', '', 'CheckDeposit', 'Check', 'the such ', 'photo1.jpg', 'photo_2.jpg', '123456677', 'Pending', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-10 12:55:24.600941', '', ''),
(303, 'sIcgnjGXRpH0UQD', '26', 'john doe', '930784512', 'Current account ', '', 'CheckDeposit', 'Check', 'check', 'photo_203.jpg', 'photo_2.jpg', 'qw121222', 'Pending', '12', 'mandela', '121233121', '1000', '+233559426832', '', '', '2024-01-10 12:56:20.538227', '', ''),
(304, '4402063272609', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Withdrawal', '', 'text', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2989', '', '2024-01-13 12:37:17.396596', '', ''),
(305, '2647768322257', '24', 'courage', '271560493', ' Retirement ', '', 'Deposit', '', 'text', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2000', '', '2024-01-13 12:37:17.535144', '', ''),
(306, '5530222754113', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Withdrawal', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '20', '', '2863', '', '2024-01-13 12:38:36.262869', '', ''),
(307, '5015596962212', '24', 'courage', '271560493', ' Retirement ', '', 'Deposit', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '20', '', '2126', '', '2024-01-13 12:38:36.460601', '', ''),
(308, '6110226475264', '24', 'courage', '271560493', ' Retirement ', '', 'Withdrawal', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2146', '', '2024-01-13 12:39:06.550559', '', ''),
(309, '5873188680154', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2843', '', '2024-01-13 12:39:06.676644', '', ''),
(310, '7402194532257', '24', 'courage', '271560493', ' Retirement ', '', 'Withdrawal', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '20', '', '2020', '', '2024-01-13 12:40:01.452349', '', ''),
(311, '7520471015597', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Deposit', '', 'the such ', '', '', '', 'Success', '12', 'mandela', '', '20', '', '2969', '', '2024-01-13 12:40:01.576877', '', ''),
(312, '9888583408793', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Transfer', '', 'ok', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2989', '', '2024-01-14 09:08:28.869724', '', ''),
(313, '1846469464935', '24', 'courage', '271560493', ' Retirement ', '', 'Recieved Transfer', '', 'ok', '', '', '', 'Success', '12', 'mandela', '', '126', '', '2000', '', '2024-01-14 09:08:29.062075', '', ''),
(314, '5857359419125', '23', 'Nelson Agbagah', '679403182', 'Savings ', '', 'Transfer', '', 'yes sir', '', '', '', 'Success', '12', 'mandela', '', '211', '', '2863', '', '2024-01-14 10:58:00.304876', '', ''),
(315, '8294791462667', '26', 'john doe', '930784512', 'Current account ', '', 'Recieved Transfer', '', 'yes sir', '', '', '', 'Success', '12', 'mandela', '', '211', '', '2211', '', '2024-01-14 10:58:00.573314', '', ''),
(316, 'bGrMqoOgpV', '29', 'ke', '849370165', 'Recurring deposit ', '', 'Deposit', 'Cash', 'deposit by so so ', '', '', '', 'Success ', '12', 'Jack moores', '121233121', '1000', '+233559426832', '0', '', '2024-01-18 07:56:37.110043', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `ib_wire_transfer`
--

CREATE TABLE `ib_wire_transfer` (
  `wire_id` int(100) NOT NULL,
  `account_id` int(100) NOT NULL,
  `tr_code` varchar(100) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `account_number` varchar(100) NOT NULL,
  `bank_address` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip_code` int(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `routing_number` varchar(100) NOT NULL,
  `receiving_country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `transfer_amount` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transfer_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ib_wire_transfer`
--

INSERT INTO `ib_wire_transfer` (`wire_id`, `account_id`, `tr_code`, `account_name`, `account_number`, `bank_address`, `state`, `zip_code`, `bank_name`, `routing_number`, `receiving_country`, `city`, `transfer_amount`, `created_at`, `transfer_status`) VALUES
(117, 23, '6487530374211', 'qwe', '1234', 'Accra-dzodze street,', 'Volta', 233, 'qwdf', '1234', 'Ghana', 'Akatsi', '123', '2024-01-07 10:37:02', 'Success'),
(118, 23, '918656663507', 'BBBBB', '1234567890', 'VVBBB', 'DDFG', 3444, 'BBBBB', '123456', 'GHANA', 'ACC', '200', '2024-01-08 14:14:09', 'Success'),
(119, 24, '8552536973540', 'sd', 'ddd', 'accra tessano', 'accra', 233, 'ioit', '666', 'Ghana', 'accra', '67', '2024-01-08 14:51:25', 'Success'),
(120, 24, '2265161834430', 'eeee', '2222', 'Accra-dzodze street,', 'Volta', 233, 'eeee', '2222', 'Ghana', 'Akatsi', '344', '2024-01-08 14:51:33', 'Success'),
(122, 23, '3601352174439', 'dfddf', '12345566', 'accra tessano', 'accra', 233, 'ecoooo', '23rty', 'Ghana', 'accra', '100', '2024-01-14 17:02:41', 'Success'),
(123, 23, '4351868227836', 'dfddf', '12345566', 'accra tessano', 'accra', 233, 'ecoooo', '23rty', 'Ghana', 'accra', '100', '2024-01-14 17:03:00', 'Success'),
(124, 23, '9348029007246', 'dfddf', '12345566', 'accra tessano', 'accra', 233, 'ecoooo', '23rty', 'Ghana', 'accra', '100', '2024-01-14 11:52:51', 'Incomplete'),
(125, 23, '4962631147943', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 11:53:40', 'Incomplete'),
(126, 23, '691780137871', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 11:53:46', 'Incomplete'),
(127, 23, '7742990222365', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 11:54:27', 'Incomplete'),
(128, 23, '7900472192677', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 11:54:34', 'Incomplete'),
(129, 23, '5336505712163', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 12:21:30', 'Incomplete'),
(130, 23, '4658058269226', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 12:22:50', 'Incomplete'),
(131, 23, '8976599870847', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 12:22:55', 'Incomplete'),
(132, 23, '1660856673211', 'nelson', '122234566', 'accra tessano', 'accra', 233, 'eccooo', '1232345', 'Ghana', 'accra', '100', '2024-01-14 13:06:12', 'Incomplete'),
(133, 23, '1077501234414', 'ghghgh', '234567890', 'accra tessano', 'accra', 233, 'ecoo', '12344', 'Ghana', 'accra', '100', '2024-01-14 13:06:47', 'Incomplete'),
(134, 23, '3168377289318', 'ghghgh', '234567890', 'accra tessano', 'accra', 233, 'ecoo', '12344', 'Ghana', 'accra', '100', '2024-01-14 13:11:36', 'Incomplete'),
(135, 23, '7278329989734', 'hjhj', '32456708898', 'accra tessano', 'accra', 233, 'ghghdg', '121222', 'Ghana', 'accra', '100', '2024-01-14 13:12:23', 'Incomplete'),
(136, 23, '2506649865964', 'hjhj', '32456708898', 'accra tessano', 'accra', 233, 'ghghdg', '121222', 'Ghana', 'accra', '100', '2024-01-14 13:13:57', 'Incomplete'),
(137, 23, '9191568444853', 'hjhj', '32456708898', 'accra tessano', 'accra', 233, 'ghghdg', '121222', 'Ghana', 'accra', '100', '2024-01-14 13:14:13', 'Incomplete'),
(138, 23, '9297446275996', 'kjkgk', '122334555', 'Accra-dzodze street,', 'Volta', 233, 'eco nb', '499499495', 'Ghana', 'Akatsi', '40', '2024-01-14 13:32:38', 'Incomplete');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  ADD PRIMARY KEY (`acctype_id`);

--
-- Indexes for table `ib_admin`
--
ALTER TABLE `ib_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `ib_clients`
--
ALTER TABLE `ib_clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `ib_cot`
--
ALTER TABLE `ib_cot`
  ADD PRIMARY KEY (`cot_id`);

--
-- Indexes for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `ib_paybill`
--
ALTER TABLE `ib_paybill`
  ADD PRIMARY KEY (`bill_id`);

--
-- Indexes for table `ib_staff`
--
ALTER TABLE `ib_staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  ADD PRIMARY KEY (`tr_id`);

--
-- Indexes for table `ib_wire_transfer`
--
ALTER TABLE `ib_wire_transfer`
  ADD PRIMARY KEY (`wire_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ib_acc_types`
--
ALTER TABLE `ib_acc_types`
  MODIFY `acctype_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ib_admin`
--
ALTER TABLE `ib_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ib_bankaccounts`
--
ALTER TABLE `ib_bankaccounts`
  MODIFY `account_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `ib_clients`
--
ALTER TABLE `ib_clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ib_cot`
--
ALTER TABLE `ib_cot`
  MODIFY `cot_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_notifications`
--
ALTER TABLE `ib_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `ib_paybill`
--
ALTER TABLE `ib_paybill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ib_staff`
--
ALTER TABLE `ib_staff`
  MODIFY `staff_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ib_systemsettings`
--
ALTER TABLE `ib_systemsettings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ib_transactions`
--
ALTER TABLE `ib_transactions`
  MODIFY `tr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `ib_wire_transfer`
--
ALTER TABLE `ib_wire_transfer`
  MODIFY `wire_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
