-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 08, 2019 at 03:44 PM
-- Server version: 5.7.24
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ciuiscrm_153`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `bankname` varchar(255) DEFAULT NULL,
  `branchbank` varchar(255) DEFAULT NULL,
  `account` varchar(11) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `type`, `bankname`, `branchbank`, `account`, `iban`, `status_id`) VALUES
(1, 'Cash', 0, '', '', '2147483647', '', 0),
(3, 'Zurich Cantonal Bank', 1, 'Zurich Cantonal Bank', 'New York', '2147483647', 'GB23 1123 1213 4343 3444 43', 0),
(4, 'Alternative Bank', 1, 'Alternative Bank', 'Denver', '2147483647', 'GB23 1123 1213 4343 3444 43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `appconfig`
--

CREATE TABLE `appconfig` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appconfig`
--

INSERT INTO `appconfig` (`id`, `name`, `value`) VALUES
(1, 'inv_prefix', 'INV-'),
(2, 'inv_suffix', ''),
(3, 'project_prefix', 'PROJ-'),
(4, 'project_suffix', '1'),
(5, 'expense_prefix', 'EXP-'),
(6, 'expense_suffix', ''),
(7, 'proposal_prefix', 'PRO-'),
(8, 'proposal_suffix', ''),
(9, 'order_prefix', 'ODR-'),
(10, 'order_suffix', ''),
(11, 'tax_label', 'VAT');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `contact_id`, `staff_id`, `booking_date`, `start_time`, `end_time`, `status`) VALUES
(1, 23, 1, '2018-04-02', '09:00:00', '09:30:00', 1),
(2, 23, 1, '2018-04-02', '09:30:00', '10:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `branding`
--

CREATE TABLE `branding` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branding`
--

INSERT INTO `branding` (`id`, `name`, `value`) VALUES
(1, 'meta_keywords', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.'),
(2, 'meta_description', 'Ciuis, CRM, Project Management tool, client management, crm, customer, expenses, invoice system, invoices, lead, project management, recurring invoices, sales, self hosted, support tickets, task manager, ticket system'),
(3, 'admin_login_image', 'login.jpg'),
(4, 'client_login_image', 'login.jpg'),
(7, 'admin_login_text', 'Welcome! With Ciuis CRM you can easily manage your customer relationships and save time on your business.'),
(8, 'client_login_text', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.'),
(9, 'enable_support_button_on_client', '1'),
(10, 'favicon_icon', 'logo-fav.png'),
(11, 'support_button_title', 'Ciuis Support'),
(12, 'support_button_link', 'https://stellar.ladesk.com/submit_ticket'),
(13, 'title', 'Ciuis CRM'),
(14, 'nav_logo', ''),
(15, 'title', 'Ciuis CRM'),
(16, 'app_logo', '');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` mediumtext,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content`, `relation_type`, `relation`, `staff_id`, `created`) VALUES
(1, 'Lorem ipsum dolor sit amet!', 'proposal', 1, 1, '2017-09-05 06:22:36'),
(2, 'Lorem ipsum dolor sit amet!', 'proposal', 2, 1, '2017-09-05 06:22:36'),
(4, 'Lorem ipsum comment.', 'proposal', 3, 1, '2017-09-10 01:23:55'),
(5, 'test', 'proposal', 3, NULL, '2017-09-10 01:29:20'),
(6, 'Test Comment', 'proposal', 3, NULL, '2017-09-10 01:30:38'),
(7, 'Sample comment', 'proposal', 5, 1, '2017-11-25 04:55:21'),
(8, 'Sample comment', 'proposal', 2, 1, '2018-01-10 00:19:23'),
(9, 'Sample', 'proposal', 2, 1, '2018-01-10 00:22:48'),
(10, 'Make a little discount.', 'proposal', 5, 1, '2018-01-22 20:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(277) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `language` varchar(255) NOT NULL,
  `address` text,
  `skype` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `primary` int(11) DEFAULT '0',
  `admin` int(11) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `surname`, `phone`, `extension`, `mobile`, `email`, `username`, `password`, `language`, `address`, `skype`, `linkedin`, `customer_id`, `position`, `primary`, `admin`, `inactive`) VALUES
(1, 'Bartholomew', 'Nelson', '(948) 153 4877', '105', '(948) 153 4877', 'nelson@bohom.co', 'nelson', '$2y$10$TWRj/cH71QxJvknTGMEMueCGC.U2RjMH9IFl9n68gg2pn3Au/AzEm', 'english', '23442 El Toro Rd, Lake Forest, CA, 92630', 'Nelson', 'Nelson', 4, 'Genel Müdür', 1, 0, NULL),
(3, 'Gibson', 'Douchebag', '(948) 153 4877', '102', '(948) 153 4877', 'gibson@example.com', '', '$2y$10$e4qhR82Rg8iYB5U1blPbwulxma4LlXxgygnMbo.YJSqaGbdRECBW6', '', '216 E Ocean Ave, Lompoc, CA, 93436', 'Douchebag', 'Douchebag', 3, 'Sales Agent', 1, 0, NULL),
(4, 'Carnegie', 'Steve', '(948) 153 4877', '102', '(948) 153 4877', 'canegie@example.com', '', '', '', '1025 Woodruff Rd, Joliet, IL, 60432 ', 'Steve', 'Steve', 3, 'Sales Agent', 0, 0, NULL),
(5, 'Quiche', 'McKenzie', '(948) 153 4877', '102', '(948) 153 4877', 'quiche@example.com', '', '', '', '3059 Mountain View Dr, El Centro, CA, 92243', 'McKenzie', 'McKenzie', 3, 'Sales Agent', 0, 0, NULL),
(6, 'Philip Stanley', 'Douchebag', '(948) 153 4877', '102', '(948) 153 4877', 'philip@example.com', '', '$2y$10$mS0WXGoP5.qKt.puiFaYYeViHm/JARBi3eCMnsktamP6PEMfAsjda', '', '184 Nichols Rd, Sherman, TX, 75090', 'Douchebag', 'Douchebag', 3, 'Cra', 0, 0, NULL),
(8, 'Sandra', 'Bailey', '(981) 450 5274', '102', '(948) 153 4877', 'sandra-86@example.com', '', '', '', '2514 W Tanner Ranch Rd, Queen Creek, AZ, 85142', 'Bailey', 'Bailey', 1, 'Sales Agent', 0, 0, 0),
(9, 'Lurch', 'Nelson', '(158) 832 7851', '102', '(948) 153 4877', 'lurch@example.com', '', '$2y$10$ZATro4Y/9ARf9VO/os/O8eUDDjEk3aWJQl5Tk2VkZlJ3.df.2XQuC', '', '2094 E Grand Ave #50, Escondido, CA, 92027', 'Stone', 'stone', 3, 'Sales Agent', 1, 0, 0),
(11, 'Kyle', 'Romero', '(946) 610 7796', '21', '(946) 610 7796', 'kyle.romero@example.com', '', '$2y$10$PIoAOeFgAkAHb1JZkD.kVeyXcANIz3y43uCSdYpjtmXq9vDXbbeM6', '', 'USA', 'kyle', 'kyle', 5, 'Sales Agent', 1, 0, 0),
(12, 'Charles', 'Simmons', '(271) 967 5863', '', '(271) 967 5863', 'charles-91@example.com', '', '$2y$10$FrXB.1neoDBrR25q0EGa9OCuBb6rh45VsD/v/YUO5oZhwO9tyaLam', '', 'RUSSIA', 'charles2323', 'charles2323', 5, 'Sales Agent', 1, 0, 0),
(13, 'Phillip', 'Estrada', '(666) 295 5922', '', '(666) 295 5922', 'phillip85@example.com', '', '$2y$10$pqF77bPYxazC7VcyXrfJL.D/C8l4.04xVeCEKW2lY0SCYpsXaHwYO', '', 'USA', 'phillip85', 'phillip85', 1, 'Product Manager', 1, 0, 0),
(14, 'Michel', 'Kworks', '1 993 23223', '', '', 'michel@example.com', '', '$2y$10$hZCawFLfCSIjDWST/k9RjubsJCc0j3WGrVZkgZ4uiK5vTrlNkuDQK', '', 'Test', 'michel', '', 16, 'General Manager', 1, 0, NULL),
(15, 'Gerald', 'DeGroot', '626-931-8754', '23', '626-931-8754', 'chaim@example.com', '', '$2y$10$uVDLwo5P.MX89gbyAHuNSuToHYLV8B5gtTOiTdPjShxGXAKIo6iu2', '', '4521 Providence Lane', 'chaim', 'chaim', 17, 'General Manager', 1, 0, NULL),
(16, 'Terry', 'McCoy', '(467) 826 4441', '11', '(467) 826 4441', 'terry_mccoy@example.com', '', '$2y$10$54kEiZGHESg.RMspMR9ro.fvKw56ux09ArXbmYlYvfQ.ybCVocixW', '', 'United States', 'terry', 'teryy', 9, 'Sales Agent', 1, 0, NULL),
(17, 'Robert Lopez', 'Lopez', '(344) 888 6449', '', '(344) 888 6449', 'robert.lopez@example.com', '', '$2y$10$cQqH.HpkZ5YAvO3AITYbLesxS/w5Xd2o.kGCP9LOo6DoR/PN9Jzce', '', '', '', '', 9, 'General Manager', NULL, 0, NULL),
(18, 'Mary', 'Murphy', '(694) 752 3564', '', '(694) 752 3564', 'mary.murphy@example.com', '', '$2y$10$QtEg17joyKhYOMo0GDYb/OhMtnJyD900CBIqiLsplglK1xzfky4/W', '', '', '', '', 9, '', NULL, 0, NULL),
(19, 'Theresa', 'Sullivan', '(781) 560 5175', '', '(781) 560 5175', 'theresa_82@example.com', '', '$2y$10$VvekKYEU.RqtGaNRqXALLu/a6wimQF.iRlCQz07EFic9QUjK49LYy', '', '', '', '', 9, '', NULL, 0, NULL),
(20, 'Anna', 'Holland', '(221) 862 7179', '11', '(221) 862 7179', 'anna-holland@example.com', '', '$2y$10$uE/XaLobpbnPIv9tpiKex.6q7UHcbauuGTgpWElggKNZVHYT0iGd.', '', 'Address lorem ipsum.', 'anna', 'anna', 9, 'Henel', 1, 0, NULL),
(21, 'Ann', 'Arbor', '(221) 862 7179', '', '(467) 826 4441', 'quice@example.com', '', '$2y$10$lMewMEh8/3wUcT48CRUBF.bFbypReQhIvap5Je59mRz/nkulLvPbG', '', '', '', '', 17, 'Sales Agent', 1, 0, NULL),
(22, 'Indigo', 'Violet', '(221) 862 7179', '222', '(467) 826 4441', 'indigo@example.com', '', '$2y$10$fw20vep0VddOgQVPhNrW0u7OrP6zgXWmXnSdhYQzUX2q.9X9OTDwO', '', 'United states of america', 'indigo', 'indigo', 17, 'Sales Agent', 1, 0, NULL),
(23, 'Sue', 'Shei', '(221) 862 7179', '222', '(781) 560 5175', 'sue@example.com', '', '$2y$10$fw20vep0VddOgQVPhNrW0u7OrP6zgXWmXnSdhYQzUX2q.9X9OTDwO', '', '22222', '222', '22', 17, 'Sales Agent', 1, 1, NULL),
(26, 'Andiano', 'Molly', '(221) 862 7179', '', '(467) 826 4441', 'molly@example.com', '', '$2y$10$7yT2BcUNFbc1G0cSx/xhseVPZJySTNosOtMv5azjyosNnMTnA0gGa', '', '', '', '', 17, '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(5) NOT NULL,
  `isocode` char(2) DEFAULT NULL,
  `shortname` varchar(80) NOT NULL DEFAULT '',
  `longname` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un` varchar(12) DEFAULT NULL,
  `dialingcode` varchar(8) DEFAULT NULL,
  `domain` varchar(5) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `isocode`, `shortname`, `longname`, `iso3`, `numcode`, `un`, `dialingcode`, `domain`) VALUES
(1, 'AF', 'Afghanistan', 'Islamic Republic of Afghanistan', 'AFG', '004', 'yes', '93', '.af'),
(2, 'AX', 'Aland Islands', '&Aring;land Islands', 'ALA', '248', 'no', '358', '.ax'),
(3, 'AL', 'Albania', 'Republic of Albania', 'ALB', '008', 'yes', '355', '.al'),
(4, 'DZ', 'Algeria', 'People\'s Democratic Republic of Algeria', 'DZA', '012', 'yes', '213', '.dz'),
(5, 'AS', 'American Samoa', 'American Samoa', 'ASM', '016', 'no', '1+684', '.as'),
(6, 'AD', 'Andorra', 'Principality of Andorra', 'AND', '020', 'yes', '376', '.ad'),
(7, 'AO', 'Angola', 'Republic of Angola', 'AGO', '024', 'yes', '244', '.ao'),
(8, 'AI', 'Anguilla', 'Anguilla', 'AIA', '660', 'no', '1+264', '.ai'),
(9, 'AQ', 'Antarctica', 'Antarctica', 'ATA', '010', 'no', '672', '.aq'),
(10, 'AG', 'Antigua and Barbuda', 'Antigua and Barbuda', 'ATG', '028', 'yes', '1+268', '.ag'),
(11, 'AR', 'Argentina', 'Argentine Republic', 'ARG', '032', 'yes', '54', '.ar'),
(12, 'AM', 'Armenia', 'Republic of Armenia', 'ARM', '051', 'yes', '374', '.am'),
(13, 'AW', 'Aruba', 'Aruba', 'ABW', '533', 'no', '297', '.aw'),
(14, 'AU', 'Australia', 'Commonwealth of Australia', 'AUS', '036', 'yes', '61', '.au'),
(15, 'AT', 'Austria', 'Republic of Austria', 'AUT', '040', 'yes', '43', '.at'),
(16, 'AZ', 'Azerbaijan', 'Republic of Azerbaijan', 'AZE', '031', 'yes', '994', '.az'),
(17, 'BS', 'Bahamas', 'Commonwealth of The Bahamas', 'BHS', '044', 'yes', '1+242', '.bs'),
(18, 'BH', 'Bahrain', 'Kingdom of Bahrain', 'BHR', '048', 'yes', '973', '.bh'),
(19, 'BD', 'Bangladesh', 'People\'s Republic of Bangladesh', 'BGD', '050', 'yes', '880', '.bd'),
(20, 'BB', 'Barbados', 'Barbados', 'BRB', '052', 'yes', '1+246', '.bb'),
(21, 'BY', 'Belarus', 'Republic of Belarus', 'BLR', '112', 'yes', '375', '.by'),
(22, 'BE', 'Belgium', 'Kingdom of Belgium', 'BEL', '056', 'yes', '32', '.be'),
(23, 'BZ', 'Belize', 'Belize', 'BLZ', '084', 'yes', '501', '.bz'),
(24, 'BJ', 'Benin', 'Republic of Benin', 'BEN', '204', 'yes', '229', '.bj'),
(25, 'BM', 'Bermuda', 'Bermuda Islands', 'BMU', '060', 'no', '1+441', '.bm'),
(26, 'BT', 'Bhutan', 'Kingdom of Bhutan', 'BTN', '064', 'yes', '975', '.bt'),
(27, 'BO', 'Bolivia', 'Plurinational State of Bolivia', 'BOL', '068', 'yes', '591', '.bo'),
(28, 'BQ', 'Bonaire, Sint Eustatius and Saba', 'Bonaire, Sint Eustatius and Saba', 'BES', '535', 'no', '599', '.bq'),
(29, 'BA', 'Bosnia and Herzegovina', 'Bosnia and Herzegovina', 'BIH', '070', 'yes', '387', '.ba'),
(30, 'BW', 'Botswana', 'Republic of Botswana', 'BWA', '072', 'yes', '267', '.bw'),
(31, 'BV', 'Bouvet Island', 'Bouvet Island', 'BVT', '074', 'no', 'NONE', '.bv'),
(32, 'BR', 'Brazil', 'Federative Republic of Brazil', 'BRA', '076', 'yes', '55', '.br'),
(33, 'IO', 'British Indian Ocean Territory', 'British Indian Ocean Territory', 'IOT', '086', 'no', '246', '.io'),
(34, 'BN', 'Brunei', 'Brunei Darussalam', 'BRN', '096', 'yes', '673', '.bn'),
(35, 'BG', 'Bulgaria', 'Republic of Bulgaria', 'BGR', '100', 'yes', '359', '.bg'),
(36, 'BF', 'Burkina Faso', 'Burkina Faso', 'BFA', '854', 'yes', '226', '.bf'),
(37, 'BI', 'Burundi', 'Republic of Burundi', 'BDI', '108', 'yes', '257', '.bi'),
(38, 'KH', 'Cambodia', 'Kingdom of Cambodia', 'KHM', '116', 'yes', '855', '.kh'),
(39, 'CM', 'Cameroon', 'Republic of Cameroon', 'CMR', '120', 'yes', '237', '.cm'),
(40, 'CA', 'Canada', 'Canada', 'CAN', '124', 'yes', '1', '.ca'),
(41, 'CV', 'Cape Verde', 'Republic of Cape Verde', 'CPV', '132', 'yes', '238', '.cv'),
(42, 'KY', 'Cayman Islands', 'The Cayman Islands', 'CYM', '136', 'no', '1+345', '.ky'),
(43, 'CF', 'Central African Republic', 'Central African Republic', 'CAF', '140', 'yes', '236', '.cf'),
(44, 'TD', 'Chad', 'Republic of Chad', 'TCD', '148', 'yes', '235', '.td'),
(45, 'CL', 'Chile', 'Republic of Chile', 'CHL', '152', 'yes', '56', '.cl'),
(46, 'CN', 'China', 'People\'s Republic of China', 'CHN', '156', 'yes', '86', '.cn'),
(47, 'CX', 'Christmas Island', 'Christmas Island', 'CXR', '162', 'no', '61', '.cx'),
(48, 'CC', 'Cocos (Keeling) Islands', 'Cocos (Keeling) Islands', 'CCK', '166', 'no', '61', '.cc'),
(49, 'CO', 'Colombia', 'Republic of Colombia', 'COL', '170', 'yes', '57', '.co'),
(50, 'KM', 'Comoros', 'Union of the Comoros', 'COM', '174', 'yes', '269', '.km'),
(51, 'CG', 'Congo', 'Republic of the Congo', 'COG', '178', 'yes', '242', '.cg'),
(52, 'CK', 'Cook Islands', 'Cook Islands', 'COK', '184', 'some', '682', '.ck'),
(53, 'CR', 'Costa Rica', 'Republic of Costa Rica', 'CRI', '188', 'yes', '506', '.cr'),
(54, 'CI', 'Cote d\'ivoire (Ivory Coast)', 'Republic of C&ocirc;te D\'Ivoire (Ivory Coast)', 'CIV', '384', 'yes', '225', '.ci'),
(55, 'HR', 'Croatia', 'Republic of Croatia', 'HRV', '191', 'yes', '385', '.hr'),
(56, 'CU', 'Cuba', 'Republic of Cuba', 'CUB', '192', 'yes', '53', '.cu'),
(57, 'CW', 'Curacao', 'Cura&ccedil;ao', 'CUW', '531', 'no', '599', '.cw'),
(58, 'CY', 'Cyprus', 'Republic of Cyprus', 'CYP', '196', 'yes', '357', '.cy'),
(59, 'CZ', 'Czech Republic', 'Czech Republic', 'CZE', '203', 'yes', '420', '.cz'),
(60, 'CD', 'Democratic Republic of the Congo', 'Democratic Republic of the Congo', 'COD', '180', 'yes', '243', '.cd'),
(61, 'DK', 'Denmark', 'Kingdom of Denmark', 'DNK', '208', 'yes', '45', '.dk'),
(62, 'DJ', 'Djibouti', 'Republic of Djibouti', 'DJI', '262', 'yes', '253', '.dj'),
(63, 'DM', 'Dominica', 'Commonwealth of Dominica', 'DMA', '212', 'yes', '1+767', '.dm'),
(64, 'DO', 'Dominican Republic', 'Dominican Republic', 'DOM', '214', 'yes', '1+809, 8', '.do'),
(65, 'EC', 'Ecuador', 'Republic of Ecuador', 'ECU', '218', 'yes', '593', '.ec'),
(66, 'EG', 'Egypt', 'Arab Republic of Egypt', 'EGY', '818', 'yes', '20', '.eg'),
(67, 'SV', 'El Salvador', 'Republic of El Salvador', 'SLV', '222', 'yes', '503', '.sv'),
(68, 'GQ', 'Equatorial Guinea', 'Republic of Equatorial Guinea', 'GNQ', '226', 'yes', '240', '.gq'),
(69, 'ER', 'Eritrea', 'State of Eritrea', 'ERI', '232', 'yes', '291', '.er'),
(70, 'EE', 'Estonia', 'Republic of Estonia', 'EST', '233', 'yes', '372', '.ee'),
(71, 'ET', 'Ethiopia', 'Federal Democratic Republic of Ethiopia', 'ETH', '231', 'yes', '251', '.et'),
(72, 'FK', 'Falkland Islands (Malvinas)', 'The Falkland Islands (Malvinas)', 'FLK', '238', 'no', '500', '.fk'),
(73, 'FO', 'Faroe Islands', 'The Faroe Islands', 'FRO', '234', 'no', '298', '.fo'),
(74, 'FJ', 'Fiji', 'Republic of Fiji', 'FJI', '242', 'yes', '679', '.fj'),
(75, 'FI', 'Finland', 'Republic of Finland', 'FIN', '246', 'yes', '358', '.fi'),
(76, 'FR', 'France', 'French Republic', 'FRA', '250', 'yes', '33', '.fr'),
(77, 'GF', 'French Guiana', 'French Guiana', 'GUF', '254', 'no', '594', '.gf'),
(78, 'PF', 'French Polynesia', 'French Polynesia', 'PYF', '258', 'no', '689', '.pf'),
(79, 'TF', 'French Southern Territories', 'French Southern Territories', 'ATF', '260', 'no', NULL, '.tf'),
(80, 'GA', 'Gabon', 'Gabonese Republic', 'GAB', '266', 'yes', '241', '.ga'),
(81, 'GM', 'Gambia', 'Republic of The Gambia', 'GMB', '270', 'yes', '220', '.gm'),
(82, 'GE', 'Georgia', 'Georgia', 'GEO', '268', 'yes', '995', '.ge'),
(83, 'DE', 'Germany', 'Federal Republic of Germany', 'DEU', '276', 'yes', '49', '.de'),
(84, 'GH', 'Ghana', 'Republic of Ghana', 'GHA', '288', 'yes', '233', '.gh'),
(85, 'GI', 'Gibraltar', 'Gibraltar', 'GIB', '292', 'no', '350', '.gi'),
(86, 'GR', 'Greece', 'Hellenic Republic', 'GRC', '300', 'yes', '30', '.gr'),
(87, 'GL', 'Greenland', 'Greenland', 'GRL', '304', 'no', '299', '.gl'),
(88, 'GD', 'Grenada', 'Grenada', 'GRD', '308', 'yes', '1+473', '.gd'),
(89, 'GP', 'Guadaloupe', 'Guadeloupe', 'GLP', '312', 'no', '590', '.gp'),
(90, 'GU', 'Guam', 'Guam', 'GUM', '316', 'no', '1+671', '.gu'),
(91, 'GT', 'Guatemala', 'Republic of Guatemala', 'GTM', '320', 'yes', '502', '.gt'),
(92, 'GG', 'Guernsey', 'Guernsey', 'GGY', '831', 'no', '44', '.gg'),
(93, 'GN', 'Guinea', 'Republic of Guinea', 'GIN', '324', 'yes', '224', '.gn'),
(94, 'GW', 'Guinea-Bissau', 'Republic of Guinea-Bissau', 'GNB', '624', 'yes', '245', '.gw'),
(95, 'GY', 'Guyana', 'Co-operative Republic of Guyana', 'GUY', '328', 'yes', '592', '.gy'),
(96, 'HT', 'Haiti', 'Republic of Haiti', 'HTI', '332', 'yes', '509', '.ht'),
(97, 'HM', 'Heard Island and McDonald Islands', 'Heard Island and McDonald Islands', 'HMD', '334', 'no', 'NONE', '.hm'),
(98, 'HN', 'Honduras', 'Republic of Honduras', 'HND', '340', 'yes', '504', '.hn'),
(99, 'HK', 'Hong Kong', 'Hong Kong', 'HKG', '344', 'no', '852', '.hk'),
(100, 'HU', 'Hungary', 'Hungary', 'HUN', '348', 'yes', '36', '.hu'),
(101, 'IS', 'Iceland', 'Republic of Iceland', 'ISL', '352', 'yes', '354', '.is'),
(102, 'IN', 'India', 'Republic of India', 'IND', '356', 'yes', '91', '.in'),
(103, 'ID', 'Indonesia', 'Republic of Indonesia', 'IDN', '360', 'yes', '62', '.id'),
(104, 'IR', 'Iran', 'Islamic Republic of Iran', 'IRN', '364', 'yes', '98', '.ir'),
(105, 'IQ', 'Iraq', 'Republic of Iraq', 'IRQ', '368', 'yes', '964', '.iq'),
(106, 'IE', 'Ireland', 'Ireland', 'IRL', '372', 'yes', '353', '.ie'),
(107, 'IM', 'Isle of Man', 'Isle of Man', 'IMN', '833', 'no', '44', '.im'),
(108, 'IL', 'Israel', 'State of Israel', 'ISR', '376', 'yes', '972', '.il'),
(109, 'IT', 'Italy', 'Italian Republic', 'ITA', '380', 'yes', '39', '.jm'),
(110, 'JM', 'Jamaica', 'Jamaica', 'JAM', '388', 'yes', '1+876', '.jm'),
(111, 'JP', 'Japan', 'Japan', 'JPN', '392', 'yes', '81', '.jp'),
(112, 'JE', 'Jersey', 'The Bailiwick of Jersey', 'JEY', '832', 'no', '44', '.je'),
(113, 'JO', 'Jordan', 'Hashemite Kingdom of Jordan', 'JOR', '400', 'yes', '962', '.jo'),
(114, 'KZ', 'Kazakhstan', 'Republic of Kazakhstan', 'KAZ', '398', 'yes', '7', '.kz'),
(115, 'KE', 'Kenya', 'Republic of Kenya', 'KEN', '404', 'yes', '254', '.ke'),
(116, 'KI', 'Kiribati', 'Republic of Kiribati', 'KIR', '296', 'yes', '686', '.ki'),
(117, 'XK', 'Kosovo', 'Republic of Kosovo', '---', '---', 'some', '381', ''),
(118, 'KW', 'Kuwait', 'State of Kuwait', 'KWT', '414', 'yes', '965', '.kw'),
(119, 'KG', 'Kyrgyzstan', 'Kyrgyz Republic', 'KGZ', '417', 'yes', '996', '.kg'),
(120, 'LA', 'Laos', 'Lao People\'s Democratic Republic', 'LAO', '418', 'yes', '856', '.la'),
(121, 'LV', 'Latvia', 'Republic of Latvia', 'LVA', '428', 'yes', '371', '.lv'),
(122, 'LB', 'Lebanon', 'Republic of Lebanon', 'LBN', '422', 'yes', '961', '.lb'),
(123, 'LS', 'Lesotho', 'Kingdom of Lesotho', 'LSO', '426', 'yes', '266', '.ls'),
(124, 'LR', 'Liberia', 'Republic of Liberia', 'LBR', '430', 'yes', '231', '.lr'),
(125, 'LY', 'Libya', 'Libya', 'LBY', '434', 'yes', '218', '.ly'),
(126, 'LI', 'Liechtenstein', 'Principality of Liechtenstein', 'LIE', '438', 'yes', '423', '.li'),
(127, 'LT', 'Lithuania', 'Republic of Lithuania', 'LTU', '440', 'yes', '370', '.lt'),
(128, 'LU', 'Luxembourg', 'Grand Duchy of Luxembourg', 'LUX', '442', 'yes', '352', '.lu'),
(129, 'MO', 'Macao', 'The Macao Special Administrative Region', 'MAC', '446', 'no', '853', '.mo'),
(130, 'MK', 'Macedonia', 'The Former Yugoslav Republic of Macedonia', 'MKD', '807', 'yes', '389', '.mk'),
(131, 'MG', 'Madagascar', 'Republic of Madagascar', 'MDG', '450', 'yes', '261', '.mg'),
(132, 'MW', 'Malawi', 'Republic of Malawi', 'MWI', '454', 'yes', '265', '.mw'),
(133, 'MY', 'Malaysia', 'Malaysia', 'MYS', '458', 'yes', '60', '.my'),
(134, 'MV', 'Maldives', 'Republic of Maldives', 'MDV', '462', 'yes', '960', '.mv'),
(135, 'ML', 'Mali', 'Republic of Mali', 'MLI', '466', 'yes', '223', '.ml'),
(136, 'MT', 'Malta', 'Republic of Malta', 'MLT', '470', 'yes', '356', '.mt'),
(137, 'MH', 'Marshall Islands', 'Republic of the Marshall Islands', 'MHL', '584', 'yes', '692', '.mh'),
(138, 'MQ', 'Martinique', 'Martinique', 'MTQ', '474', 'no', '596', '.mq'),
(139, 'MR', 'Mauritania', 'Islamic Republic of Mauritania', 'MRT', '478', 'yes', '222', '.mr'),
(140, 'MU', 'Mauritius', 'Republic of Mauritius', 'MUS', '480', 'yes', '230', '.mu'),
(141, 'YT', 'Mayotte', 'Mayotte', 'MYT', '175', 'no', '262', '.yt'),
(142, 'MX', 'Mexico', 'United Mexican States', 'MEX', '484', 'yes', '52', '.mx'),
(143, 'FM', 'Micronesia', 'Federated States of Micronesia', 'FSM', '583', 'yes', '691', '.fm'),
(144, 'MD', 'Moldava', 'Republic of Moldova', 'MDA', '498', 'yes', '373', '.md'),
(145, 'MC', 'Monaco', 'Principality of Monaco', 'MCO', '492', 'yes', '377', '.mc'),
(146, 'MN', 'Mongolia', 'Mongolia', 'MNG', '496', 'yes', '976', '.mn'),
(147, 'ME', 'Montenegro', 'Montenegro', 'MNE', '499', 'yes', '382', '.me'),
(148, 'MS', 'Montserrat', 'Montserrat', 'MSR', '500', 'no', '1+664', '.ms'),
(149, 'MA', 'Morocco', 'Kingdom of Morocco', 'MAR', '504', 'yes', '212', '.ma'),
(150, 'MZ', 'Mozambique', 'Republic of Mozambique', 'MOZ', '508', 'yes', '258', '.mz'),
(151, 'MM', 'Myanmar (Burma)', 'Republic of the Union of Myanmar', 'MMR', '104', 'yes', '95', '.mm'),
(152, 'NA', 'Namibia', 'Republic of Namibia', 'NAM', '516', 'yes', '264', '.na'),
(153, 'NR', 'Nauru', 'Republic of Nauru', 'NRU', '520', 'yes', '674', '.nr'),
(154, 'NP', 'Nepal', 'Federal Democratic Republic of Nepal', 'NPL', '524', 'yes', '977', '.np'),
(155, 'NL', 'Netherlands', 'Kingdom of the Netherlands', 'NLD', '528', 'yes', '31', '.nl'),
(156, 'NC', 'New Caledonia', 'New Caledonia', 'NCL', '540', 'no', '687', '.nc'),
(157, 'NZ', 'New Zealand', 'New Zealand', 'NZL', '554', 'yes', '64', '.nz'),
(158, 'NI', 'Nicaragua', 'Republic of Nicaragua', 'NIC', '558', 'yes', '505', '.ni'),
(159, 'NE', 'Niger', 'Republic of Niger', 'NER', '562', 'yes', '227', '.ne'),
(160, 'NG', 'Nigeria', 'Federal Republic of Nigeria', 'NGA', '566', 'yes', '234', '.ng'),
(161, 'NU', 'Niue', 'Niue', 'NIU', '570', 'some', '683', '.nu'),
(162, 'NF', 'Norfolk Island', 'Norfolk Island', 'NFK', '574', 'no', '672', '.nf'),
(163, 'KP', 'North Korea', 'Democratic People\'s Republic of Korea', 'PRK', '408', 'yes', '850', '.kp'),
(164, 'MP', 'Northern Mariana Islands', 'Northern Mariana Islands', 'MNP', '580', 'no', '1+670', '.mp'),
(165, 'NO', 'Norway', 'Kingdom of Norway', 'NOR', '578', 'yes', '47', '.no'),
(166, 'OM', 'Oman', 'Sultanate of Oman', 'OMN', '512', 'yes', '968', '.om'),
(167, 'PK', 'Pakistan', 'Islamic Republic of Pakistan', 'PAK', '586', 'yes', '92', '.pk'),
(168, 'PW', 'Palau', 'Republic of Palau', 'PLW', '585', 'yes', '680', '.pw'),
(169, 'PS', 'Palestine', 'State of Palestine (or Occupied Palestinian Territory)', 'PSE', '275', 'some', '970', '.ps'),
(170, 'PA', 'Panama', 'Republic of Panama', 'PAN', '591', 'yes', '507', '.pa'),
(171, 'PG', 'Papua New Guinea', 'Independent State of Papua New Guinea', 'PNG', '598', 'yes', '675', '.pg'),
(172, 'PY', 'Paraguay', 'Republic of Paraguay', 'PRY', '600', 'yes', '595', '.py'),
(173, 'PE', 'Peru', 'Republic of Peru', 'PER', '604', 'yes', '51', '.pe'),
(174, 'PH', 'Phillipines', 'Republic of the Philippines', 'PHL', '608', 'yes', '63', '.ph'),
(175, 'PN', 'Pitcairn', 'Pitcairn', 'PCN', '612', 'no', 'NONE', '.pn'),
(176, 'PL', 'Poland', 'Republic of Poland', 'POL', '616', 'yes', '48', '.pl'),
(177, 'PT', 'Portugal', 'Portuguese Republic', 'PRT', '620', 'yes', '351', '.pt'),
(178, 'PR', 'Puerto Rico', 'Commonwealth of Puerto Rico', 'PRI', '630', 'no', '1+939', '.pr'),
(179, 'QA', 'Qatar', 'State of Qatar', 'QAT', '634', 'yes', '974', '.qa'),
(180, 'RE', 'Reunion', 'R&eacute;union', 'REU', '638', 'no', '262', '.re'),
(181, 'RO', 'Romania', 'Romania', 'ROU', '642', 'yes', '40', '.ro'),
(182, 'RU', 'Russia', 'Russian Federation', 'RUS', '643', 'yes', '7', '.ru'),
(183, 'RW', 'Rwanda', 'Republic of Rwanda', 'RWA', '646', 'yes', '250', '.rw'),
(184, 'BL', 'Saint Barthelemy', 'Saint Barth&eacute;lemy', 'BLM', '652', 'no', '590', '.bl'),
(185, 'SH', 'Saint Helena', 'Saint Helena, Ascension and Tristan da Cunha', 'SHN', '654', 'no', '290', '.sh'),
(186, 'KN', 'Saint Kitts and Nevis', 'Federation of Saint Christopher and Nevis', 'KNA', '659', 'yes', '1+869', '.kn'),
(187, 'LC', 'Saint Lucia', 'Saint Lucia', 'LCA', '662', 'yes', '1+758', '.lc'),
(188, 'MF', 'Saint Martin', 'Saint Martin', 'MAF', '663', 'no', '590', '.mf'),
(189, 'PM', 'Saint Pierre and Miquelon', 'Saint Pierre and Miquelon', 'SPM', '666', 'no', '508', '.pm'),
(190, 'VC', 'Saint Vincent and the Grenadines', 'Saint Vincent and the Grenadines', 'VCT', '670', 'yes', '1+784', '.vc'),
(191, 'WS', 'Samoa', 'Independent State of Samoa', 'WSM', '882', 'yes', '685', '.ws'),
(192, 'SM', 'San Marino', 'Republic of San Marino', 'SMR', '674', 'yes', '378', '.sm'),
(193, 'ST', 'Sao Tome and Principe', 'Democratic Republic of S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'STP', '678', 'yes', '239', '.st'),
(194, 'SA', 'Saudi Arabia', 'Kingdom of Saudi Arabia', 'SAU', '682', 'yes', '966', '.sa'),
(195, 'SN', 'Senegal', 'Republic of Senegal', 'SEN', '686', 'yes', '221', '.sn'),
(196, 'RS', 'Serbia', 'Republic of Serbia', 'SRB', '688', 'yes', '381', '.rs'),
(197, 'SC', 'Seychelles', 'Republic of Seychelles', 'SYC', '690', 'yes', '248', '.sc'),
(198, 'SL', 'Sierra Leone', 'Republic of Sierra Leone', 'SLE', '694', 'yes', '232', '.sl'),
(199, 'SG', 'Singapore', 'Republic of Singapore', 'SGP', '702', 'yes', '65', '.sg'),
(200, 'SX', 'Sint Maarten', 'Sint Maarten', 'SXM', '534', 'no', '1+721', '.sx'),
(201, 'SK', 'Slovakia', 'Slovak Republic', 'SVK', '703', 'yes', '421', '.sk'),
(202, 'SI', 'Slovenia', 'Republic of Slovenia', 'SVN', '705', 'yes', '386', '.si'),
(203, 'SB', 'Solomon Islands', 'Solomon Islands', 'SLB', '090', 'yes', '677', '.sb'),
(204, 'SO', 'Somalia', 'Somali Republic', 'SOM', '706', 'yes', '252', '.so'),
(205, 'ZA', 'South Africa', 'Republic of South Africa', 'ZAF', '710', 'yes', '27', '.za'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', 'South Georgia and the South Sandwich Islands', 'SGS', '239', 'no', '500', '.gs'),
(207, 'KR', 'South Korea', 'Republic of Korea', 'KOR', '410', 'yes', '82', '.kr'),
(208, 'SS', 'South Sudan', 'Republic of South Sudan', 'SSD', '728', 'yes', '211', '.ss'),
(209, 'ES', 'Spain', 'Kingdom of Spain', 'ESP', '724', 'yes', '34', '.es'),
(210, 'LK', 'Sri Lanka', 'Democratic Socialist Republic of Sri Lanka', 'LKA', '144', 'yes', '94', '.lk'),
(211, 'SD', 'Sudan', 'Republic of the Sudan', 'SDN', '729', 'yes', '249', '.sd'),
(212, 'SR', 'Suriname', 'Republic of Suriname', 'SUR', '740', 'yes', '597', '.sr'),
(213, 'SJ', 'Svalbard and Jan Mayen', 'Svalbard and Jan Mayen', 'SJM', '744', 'no', '47', '.sj'),
(214, 'SZ', 'Swaziland', 'Kingdom of Swaziland', 'SWZ', '748', 'yes', '268', '.sz'),
(215, 'SE', 'Sweden', 'Kingdom of Sweden', 'SWE', '752', 'yes', '46', '.se'),
(216, 'CH', 'Switzerland', 'Swiss Confederation', 'CHE', '756', 'yes', '41', '.ch'),
(217, 'SY', 'Syria', 'Syrian Arab Republic', 'SYR', '760', 'yes', '963', '.sy'),
(218, 'TW', 'Taiwan', 'Republic of China (Taiwan)', 'TWN', '158', 'former', '886', '.tw'),
(219, 'TJ', 'Tajikistan', 'Republic of Tajikistan', 'TJK', '762', 'yes', '992', '.tj'),
(220, 'TZ', 'Tanzania', 'United Republic of Tanzania', 'TZA', '834', 'yes', '255', '.tz'),
(221, 'TH', 'Thailand', 'Kingdom of Thailand', 'THA', '764', 'yes', '66', '.th'),
(222, 'TL', 'Timor-Leste (East Timor)', 'Democratic Republic of Timor-Leste', 'TLS', '626', 'yes', '670', '.tl'),
(223, 'TG', 'Togo', 'Togolese Republic', 'TGO', '768', 'yes', '228', '.tg'),
(224, 'TK', 'Tokelau', 'Tokelau', 'TKL', '772', 'no', '690', '.tk'),
(225, 'TO', 'Tonga', 'Kingdom of Tonga', 'TON', '776', 'yes', '676', '.to'),
(226, 'TT', 'Trinidad and Tobago', 'Republic of Trinidad and Tobago', 'TTO', '780', 'yes', '1+868', '.tt'),
(227, 'TN', 'Tunisia', 'Republic of Tunisia', 'TUN', '788', 'yes', '216', '.tn'),
(228, 'TR', 'Turkey', 'Republic of Turkey', 'TUR', '792', 'yes', '90', '.tr'),
(229, 'TM', 'Turkmenistan', 'Turkmenistan', 'TKM', '795', 'yes', '993', '.tm'),
(230, 'TC', 'Turks and Caicos Islands', 'Turks and Caicos Islands', 'TCA', '796', 'no', '1+649', '.tc'),
(231, 'TV', 'Tuvalu', 'Tuvalu', 'TUV', '798', 'yes', '688', '.tv'),
(232, 'UG', 'Uganda', 'Republic of Uganda', 'UGA', '800', 'yes', '256', '.ug'),
(233, 'UA', 'Ukraine', 'Ukraine', 'UKR', '804', 'yes', '380', '.ua'),
(234, 'AE', 'United Arab Emirates', 'United Arab Emirates', 'ARE', '784', 'yes', '971', '.ae'),
(235, 'GB', 'United Kingdom', 'United Kingdom of Great Britain and Nothern Ireland', 'GBR', '826', 'yes', '44', '.uk'),
(236, 'US', 'United States', 'United States of America', 'USA', '840', 'yes', '1', '.us'),
(237, 'UM', 'United States Minor Outlying Islands', 'United States Minor Outlying Islands', 'UMI', '581', 'no', 'NONE', 'NONE'),
(238, 'UY', 'Uruguay', 'Eastern Republic of Uruguay', 'URY', '858', 'yes', '598', '.uy'),
(239, 'UZ', 'Uzbekistan', 'Republic of Uzbekistan', 'UZB', '860', 'yes', '998', '.uz'),
(240, 'VU', 'Vanuatu', 'Republic of Vanuatu', 'VUT', '548', 'yes', '678', '.vu'),
(241, 'VA', 'Vatican City', 'State of the Vatican City', 'VAT', '336', 'no', '39', '.va'),
(242, 'VE', 'Venezuela', 'Bolivarian Republic of Venezuela', 'VEN', '862', 'yes', '58', '.ve'),
(243, 'VN', 'Vietnam', 'Socialist Republic of Vietnam', 'VNM', '704', 'yes', '84', '.vn'),
(244, 'VG', 'Virgin Islands, British', 'British Virgin Islands', 'VGB', '092', 'no', '1+284', '.vg'),
(245, 'VI', 'Virgin Islands, US', 'Virgin Islands of the United States', 'VIR', '850', 'no', '1+340', '.vi'),
(246, 'WF', 'Wallis and Futuna', 'Wallis and Futuna', 'WLF', '876', 'no', '681', '.wf'),
(247, 'EH', 'Western Sahara', 'Western Sahara', 'ESH', '732', 'no', '212', '.eh'),
(248, 'YE', 'Yemen', 'Republic of Yemen', 'YEM', '887', 'yes', '967', '.ye'),
(249, 'ZM', 'Zambia', 'Republic of Zambia', 'ZMB', '894', 'yes', '260', '.zm'),
(250, 'ZW', 'Zimbabwe', 'Republic of Zimbabwe', 'ZWE', '716', 'yes', '263', '.zw');

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `code`) VALUES
(1, 'UAE Dirham', 'AED'),
(2, 'Afghani (Ø‹)', 'AFN'),
(3, 'Lek (Lek)', 'ALL'),
(4, 'Armenian Dram (Õ¤Ö€.)', 'AMD'),
(5, 'Netherlands Antillean Guilder (NAÆ’)', 'ANG'),
(6, 'Kwanza', 'AOA'),
(7, 'Argentine Peso', 'ARS'),
(8, 'Australian Dollar (A$)', 'AUD'),
(9, 'Aruban Florin (Afl)', 'AWG'),
(10, 'Azerbaijanian Manat (â‚¼)', 'AZN'),
(11, 'Convertible Mark (KM)', 'BAM'),
(12, 'Barbados Dollar', 'BBD'),
(13, 'Taka', 'BDT'),
(14, 'Bulgarian Lev (Ð»Ð²)', 'BGN'),
(15, 'Bahraini Dinar (.Ø¯.Ø¨)', 'BHD'),
(16, 'Burundi Franc', 'BIF'),
(17, 'Bermudian Dollar (BD$)', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Boliviano (Bs.)', 'BOB'),
(20, 'Mvdol', 'BOV'),
(21, 'Brazilian Real (R$)', 'BRL'),
(22, 'Bahamian Dollar', 'BSD'),
(23, 'Ngultrum', 'BTN'),
(24, 'Pula (P)', 'BWP'),
(25, 'Belarussian Ruble (Ñ€.)', 'BYN'),
(26, 'Belarussian Ruble (Ñ€.)', 'BYR'),
(27, 'Belize Dollar (BZ$)', 'BZD'),
(28, 'Canadian Dollar (CA$)', 'CAD'),
(29, 'Congolese Franc', 'CDF'),
(30, 'WIR Euro', 'CHE'),
(31, 'Swiss Franc', 'CHF'),
(32, 'WIR Franc', 'CHW'),
(33, 'Unidad de Fomento', 'CLF'),
(34, 'Chilean Peso', 'CLP'),
(35, 'Yuan Renminbi (å…ƒ)', 'CNY'),
(36, 'Colombian Peso', 'COP'),
(37, 'Unidad de Valor Real', 'COU'),
(38, 'Cost Rican Colon (â‚¡)', 'CRC'),
(39, 'Peso Convertible', 'CUC'),
(40, 'Cuban Peso ($MN)', 'CUP'),
(41, 'Cabo Verde Escudo', 'CVE'),
(42, 'Czech Koruna (KÄ)', 'CZK'),
(43, 'Djibouti Franc', 'DJF'),
(44, 'Danish Krone', 'DKK'),
(45, 'Dominican Peso (RD$)', 'DOP'),
(46, 'Algerian Dinar (.Ø¯.Ø¬)', 'DZD'),
(47, 'Estonian Kroon', 'EEK'),
(48, 'Egyptian Pound (.Ø¬.Ù…)', 'EGP'),
(49, 'Nakfa', 'ERN'),
(50, 'Ethiopian Birr', 'ETB'),
(51, 'Euro (â‚¬)', 'EUR'),
(52, 'Fiji Dollar (FJ$)', 'FJD'),
(53, 'Falkland Islands Pound', 'FKP'),
(54, 'Pound Sterling (Â£)', 'GBP'),
(55, 'Lari', 'GEL'),
(56, 'Guernsey Pound', 'GGP'),
(57, 'Ghanaian Cedi (Â¢)', 'GHC'),
(58, 'Ghan Cedi', 'GHS'),
(59, 'Gibraltar Pound', 'GIP'),
(60, 'Dalasi', 'GMD'),
(61, 'Guine Franc', 'GNF'),
(62, 'Quetzal (Q)', 'GTQ'),
(63, 'Guyan Dollar (GY$)', 'GYD'),
(64, 'Hong Kong Dollar (HK$)', 'HKD'),
(65, 'Lempira (L)', 'HNL'),
(66, 'Croatian Kuna (kn)', 'HRK'),
(67, 'Gourde', 'HTG'),
(68, 'Forint (Ft)', 'HUF'),
(69, 'Rupiah (Rp)', 'IDR'),
(70, 'New Israeli Sheqel (â‚ª)', 'ILS'),
(71, 'Manx Pound', 'IMP'),
(72, 'Indian Rupee (â‚¹)', 'INR'),
(73, 'Iraqi Dinar (.Ø¯.Ø¹)', 'IQD'),
(74, 'Iranian Rial (.Ø±.Ø§)', 'IRR'),
(75, 'Iceland Krona', 'ISK'),
(76, 'Jersey Pound', 'JEP'),
(77, 'Jamaican Dollar (J$)', 'JMD'),
(78, 'Jordanian Dinar', 'JOD'),
(79, 'Yen (Â¥)', 'JPY'),
(80, 'Kenyan Shilling (KSh)', 'KES'),
(81, 'Som (ÑÐ¾Ð¼)', 'KGS'),
(82, 'Riel (áŸ›)', 'KHR'),
(83, 'Comoro Franc', 'KMF'),
(84, 'North Korean Won', 'KPW'),
(85, 'Won (â‚©)', 'KRW'),
(86, 'Kuwaiti Dinar (.Ø¯.Ùƒ)', 'KWD'),
(87, 'Cayman Islands Dollar (CI$)', 'KYD'),
(88, 'Tenge (â‚¸)', 'KZT'),
(89, 'Kip (â‚­)', 'LAK'),
(90, 'Lebanese Pound (.Ù„.Ù„)', 'LBP'),
(91, 'Sri Lank Rupee', 'LKR'),
(92, 'Liberian Dollar (L$)', 'LRD'),
(93, 'Loti', 'LSL'),
(94, 'Lithuanian Litas (Lt)', 'LTL'),
(95, 'Latvian Lats (Ls)', 'LVL'),
(96, 'Libyan Dinar (.Ø¯.Ù„)', 'LYD'),
(97, 'Moroccan Dirham (.Ø¯.Ù…)', 'MAD'),
(98, 'Moldovan Leu', 'MDL'),
(99, 'Malagasy riary', 'MGA'),
(100, 'Denar (Ð´ÐµÐ½)', 'MKD'),
(101, 'Kyat', 'MMK'),
(102, 'Tugrik (â‚®)', 'MNT'),
(103, 'Pataca', 'MOP'),
(104, 'Ouguiya', 'MRO'),
(105, 'Mauritius Rupee', 'MUR'),
(106, 'Rufiyaa', 'MVR'),
(107, 'Kwacha', 'MWK'),
(108, 'Mexican Peso', 'MXN'),
(109, 'Mexican Unidad de Inversion (UDI)', 'MXV'),
(110, 'Malaysian Ringgit (RM)', 'MYR'),
(111, 'Mozambique Metical (MT)', 'MZN'),
(112, 'Namibi Dollar (N$)', 'NAD'),
(113, 'Naira (â‚¦)', 'NGN'),
(114, 'Cordob Oro (C$)', 'NIO'),
(115, 'Norwegian Krone', 'NOK'),
(116, 'Nepalese Rupee', 'NPR'),
(117, 'New Zealand Dollar (NZ$)', 'NZD'),
(118, 'Rial Omani (.Ø±.Ø¹)', 'OMR'),
(119, 'Balboa (B/.)', 'PAB'),
(120, 'Nuevo Sol (S/)', 'PEN'),
(121, 'Kina', 'PGK'),
(122, 'Philippine Peso (â‚±)', 'PHP'),
(123, 'Pakistan Rupee', 'PKR'),
(124, 'Zloty (zÅ‚)', 'PLN'),
(125, 'Guarani (Gs)', 'PYG'),
(126, 'Qatari Rial (.Ø±.Ù‚)', 'QAR'),
(127, 'New Romanian Leu (lei)', 'RON'),
(128, 'Serbian Dinar (Ð”Ð¸Ð½.)', 'RSD'),
(129, 'Russian Ruble (â‚½)', 'RUB'),
(130, 'Russian Ruble (â‚½)', 'RUR'),
(131, 'Rwand Franc', 'RWF'),
(132, 'SAR: Saudi Riyal (.Ø±.Ø³)', 'SAR'),
(133, 'SBD: Solomon Islands Dollar (SI$)', 'SBD'),
(134, 'SCR: Seychelles Rupee', 'SCR'),
(135, 'SDG: Sudanese Pound', 'SDG'),
(136, 'SEK: Swedish Krona', 'SEK'),
(137, 'SGD: Singapore Dollar (S$)', 'SGD'),
(138, 'SHP: Saint Helen Pound', 'SHP'),
(139, 'SLL: Leone ', 'SLL'),
(140, 'SOS: Somali Shilling (S)', 'SOS'),
(141, 'SRD: Surinam Dollar', 'SRD'),
(142, 'SSP: South Sudanese Pound', 'SSP'),
(143, 'STD: Dobra', 'STD'),
(144, 'SVC: El Salvador Colon (C)', 'SVC'),
(145, 'SYP: Syrian Pound (.Ù„.Ø³)', 'SYP'),
(146, 'SZL: Lilangeni', 'SZL'),
(147, 'THB: Baht (à¸¿)', 'THB'),
(148, 'TJS: Somoni', 'TJS'),
(149, 'TMT: Turkmenistan New Manat', 'TMT'),
(150, 'TND: Tunisian Dinar (.Ø¯.Øª)', 'TND'),
(151, 'TOP: Paâ€™anga', 'TOP'),
(152, 'TRL: Turkish Lira', 'TRL'),
(153, 'TRY: Turkish Lira (â‚º)', 'TRY'),
(154, 'TTD: Trinidad and Tobago Dollar (TT$)', 'TTD'),
(155, 'TWD: New Taiwan Dollar (NT$)', 'TWD'),
(156, 'TZS: Tanzanian Shilling (TSh)', 'TZS'),
(157, 'UAH: Hryvnia (â‚´)', 'UAH'),
(158, 'UGX: Ugand Shilling (USh) ', 'UGX'),
(159, 'USD: US Dollar ($)', 'USD'),
(160, 'USN: US Dollar (Next day) ', 'USN'),
(161, 'UYI: Uruguay Peso en Unidades Indexadas (URUIURUI)', 'UYI'),
(162, 'UYU: Peso Uruguayo ($U)', 'UYU'),
(163, 'UZS: Uzbekistan Sum (soâ€™m)', 'UZS'),
(164, 'VEF: Bolivar (Bs)', 'VEF'),
(165, 'VND: Dong (â‚«)', 'VND'),
(166, 'VUV: Vatu', 'VUV'),
(167, 'WST: Tala', 'WST'),
(168, 'XAF: CF Franc BEAC', 'XAF'),
(169, 'XCD: East Caribbean Dollar (EC$)', 'XCD'),
(170, 'XDR: SDR (Special Drawing Right)', 'XDR'),
(171, 'XOF: CF Franc BCEAO', 'XOF'),
(172, 'XPF: CFP Franc', 'XPF'),
(173, 'XSU: Sucre', 'XSU'),
(174, 'XUA: ADB Unit of Account', 'XUA'),
(175, 'YER: Yemeni Rial (.Ø±.ÙŠ)', 'YER'),
(176, 'ZAR: Rand (R) ', 'ZAR'),
(177, 'ZMW: Zambian Kwacha', 'ZMW'),
(178, 'ZWD: Zimbabwe Dollar (Z$)', 'ZWD'),
(179, 'ZWL: Zimbabwe Dollar', 'ZWL');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `created` date NOT NULL,
  `staff_id` int(11) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `namesurname` varchar(255) DEFAULT NULL,
  `taxoffice` varchar(255) DEFAULT NULL,
  `taxnumber` int(11) DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `executive` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `billing_street` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_zip` varchar(255) NOT NULL,
  `billing_country` int(11) NOT NULL,
  `shipping_street` varchar(255) NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_zip` varchar(255) NOT NULL,
  `shipping_country` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `risk` int(11) DEFAULT '0',
  `status_id` int(11) DEFAULT '0',
  `subsidiary_parent_id` int(11) NOT NULL,
  `default_payment_method` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `type`, `created`, `staff_id`, `company`, `namesurname`, `taxoffice`, `taxnumber`, `ssn`, `executive`, `address`, `zipcode`, `country_id`, `state`, `city`, `town`, `billing_street`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `shipping_street`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `latitude`, `longitude`, `phone`, `fax`, `email`, `web`, `risk`, `status_id`, `subsidiary_parent_id`, `default_payment_method`) VALUES
(1, 0, '2017-04-16', 1, 'H&M Law Office', NULL, 'Washington DC Tax & Revenue', 456364265, NULL, 'Roger Wade', '799 E DRAGRAM SUITE 5A TUCSON AZ 85705 USA', '97311-8487', 23, 'Uk', 'Deve', 'Ata', '', '', '', '', 0, '', '', '', '', 0, '', '', '+1 (808) 136 4131', '+1 (808) 136 4131', 'info@orgon.com', 'www.example.com', 17, 0, 0, NULL),
(3, 0, '2016-11-03', 1, 'Dreamhunter Productions', NULL, 'Denver Tax & Revenue', 254235361, NULL, 'Heather Jenkins', '200 E MAIN ST PHOENIX AZ 85123 USA', '97311-8487', 31, '', '', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '+1 (777) 494 6039', '+1 (777) 494 6039', 'abaris@null.net', 'www.example.com', 100, 0, 0, NULL),
(4, 0, '2016-12-01', 1, 'Long Way INC.', NULL, 'Washington DC Tax & Revenue', 997789766, NULL, 'Craig Graham', '799 E DRAGRAM SUITE 5A TUCSON AZ 85705 USA', '97311-8487', 12, 'Test', 'Test', 'Test', '', '', '', '', 0, '', '', '', '', 0, '', '', '+1 (829) 204 6059', '+1 (829) 204 6059', 'abaris@null.net', 'www.example.com', 29, NULL, 0, NULL),
(6, 0, '2017-08-22', 2, 'Northern Star', 'Northern Star', NULL, NULL, NULL, NULL, '7110 Gum Branch Rd, Richlands, NC, 28574', '54617', 1, 'NC', 'New York', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '(140) 211 2494', NULL, 'judyyoung@example.com', 'www.example.com', 0, 0, 0, NULL),
(8, 0, '2017-08-22', 2, 'Transhex LLC.', 'Transhex LLC.', NULL, NULL, NULL, NULL, '95 Meadow St, Winsted, CT, 06098', '54617', 6, 'CT', 'New York', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '(954) 630 6210', NULL, 'nicholas-90@example.com', 'www.example.com', 0, 0, 0, NULL),
(9, 1, '2017-08-23', 1, '', 'Neuer Manuel', 'New York', 0, '234354241', 'Himself', 'Celeste Slater\n606-3727 Ullamcorper. Street\nRoseville NH 11523', '012345', 83, 'Berlin', 'Stadt', 'Dorf', '', '', '', '', 0, '', '', '', '', 0, '', '', '00493661123456', '00493661123456', 'manuel@neuer.de', 'www.neuer.de', 0, NULL, 0, NULL),
(12, 0, '2017-08-24', 3, 'Unadoncare INC.', 'Unadoncare INC.', NULL, NULL, NULL, NULL, '95 Meadow St, Winsted, CT, 06098', '54617', 3, 'CT', 'New York', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '(432) 156 5172', NULL, 'jane-85@example.com', 'www.example.com', 0, 0, 0, NULL),
(15, 0, '2017-08-24', 1, 'Perscriptorem Pictures', NULL, '', 0, NULL, '', '70 Bowman St. South Windsor, CT 06074', '54617', 2, 'GA', 'New York', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '(296) 452 9522', '', 'joycemccoy@example.com', 'www.example.com', 0, 0, 0, NULL),
(16, 0, '2017-11-12', 1, 'Donway INC.', NULL, 'London', 22112211, NULL, NULL, '5094 Vidrine Rd, Ville Platte, LA, 22356', '54617', 5, 'MI', 'New York', '', '', '', '', '', 0, '', '', '', '', 0, '', '', '+1-202-555-0160', NULL, 'lance@example.com', 'www.example.com', 0, 0, 17, NULL),
(17, 0, '2017-11-13', 1, 'DHARMA Initiative', '', 'London', 221122113, '', 'Gerald DeGroot', '4521 Providence Lane La Puente CA', '33344', 236, 'California', 'Los Angeles', 'Neiler', '4521 Providence Lane La Puente CA', 'Los Angeles', 'California', '33344', 236, '4521 Providence Lane La Puente CA', 'Los Angeles', 'California', '33344', 236, '', '', '+44 232 2322', '+44 232 2322', 'dharna@example.com', 'www.dharna.com', 48, NULL, 0, NULL),
(18, 0, '2018-03-21', 1, 'Parallax Corporation', '', 'New York', 2147483647, '', 'Melinda Gibson', 'Example Address', '2233', 236, 'California', 'Los Angeles', 'Bla', 'Example Address', 'Los Angeles', 'California', '2233', 236, 'Example Address', 'Los Angeles', 'California', '2233', 236, '', '', '1 944 444 4444', '1 944 444 4444', 'parallax@example.com', 'www.parallax.com', 0, NULL, 17, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `relation` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `active` varchar(255) NOT NULL DEFAULT 'true'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_data`
--

CREATE TABLE `custom_fields_data` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `db_backup`
--

CREATE TABLE `db_backup` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'Sales Agent'),
(3, 'IT Services');

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `show_to_customer` tinyint(1) NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `staff_id` int(11) NOT NULL DEFAULT '0',
  `contact_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `relation_type`, `relation`, `subject`, `description`, `show_to_customer`, `datecreated`, `staff_id`, `contact_id`) VALUES
(1, 'invoice', 7, 'Hi There?', 'Lorem ipsum dolor sit amet', 1, '2018-03-05 00:00:00', 1, 23),
(2, 'invoice', 7, 'Sample', 'Test', 1, '2018-03-07 21:37:02', 1, 23),
(3, 'invoice', 7, 'Test Two', 'Test detail', 1, '2018-03-07 21:40:37', 1, 26),
(4, 'invoice', 7, 'Test 3', 'Test 3', 1, '2018-03-07 21:43:00', 1, 22),
(5, 'invoice', 7, 'Example Discuss', 'test', 0, '2018-03-07 21:43:41', 1, 21),
(6, 'invoice', 7, 'Example Discuss 2', 'test', 1, '2018-03-07 21:43:59', 1, 23),
(7, 'invoice', 12, 'Test', 'Test discussion', 1, '2018-08-30 22:40:50', 1, 23);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_comments`
--

CREATE TABLE `discussion_comments` (
  `id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `content` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `contact_id` int(11) DEFAULT '0',
  `full_name` varchar(300) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discussion_comments`
--

INSERT INTO `discussion_comments` (`id`, `discussion_id`, `created`, `content`, `staff_id`, `contact_id`, `full_name`) VALUES
(1, 1, '2018-03-06 00:46:08', 'Sample', 1, 23, 'Lance Bogrol'),
(2, 1, '2018-03-06 00:46:55', 'Sample for tony', 1, 23, 'Lance Bogrol'),
(3, 1, '2018-03-06 18:19:11', 'Example for tony', 1, 23, 'Lance Bogrol'),
(4, 1, '2018-03-07 19:44:11', 'Contact message', 1, 23, 'Sue Shei'),
(5, 1, '2018-03-07 19:44:24', 'Contact message', 1, 23, 'Sue Shei'),
(6, 1, '2018-03-07 19:45:31', 'Toney contact', 1, 23, 'Sue Shei'),
(7, 1, '2018-03-07 19:57:38', 'Bla bla 8', 1, 23, 'Sue Shei'),
(8, 1, '2018-03-07 20:06:17', 'Thanks sue!', 1, 23, 'Lance Bogrol'),
(9, 1, '2018-03-07 20:07:19', 'thanks again', 1, 23, 'Lance Bogrol'),
(10, 1, '2018-03-07 20:08:26', 'ada', 1, 23, 'Lance Bogrol'),
(11, 1, '2018-03-09 16:08:39', 'adsfsa', 1, 23, 'Lance Bogrol'),
(12, 7, '2018-08-30 22:41:11', 'hi, its so expensive', 1, 23, 'Sue Shei');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `subject` text,
  `message` text,
  `attachments` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `send_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `from_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `attachment` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES
(1, 'invoice', 'invoice_message', 'Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <strong>{invoice_number}</strong></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(2, 'invoice', 'invoice_reminder', 'Send Invoice', '<h1><span xss=removed>DOCTYPE</span></h1>\n<p xss=removed>DOCTYPE</p>', 'Ciuis CRM', 1, 0, 0),
(3, 'invoice', 'invoice_payment', 'Invoice Payment Recorded', '<p><span>Hello {customer}<br><br></span>Thank you for the payment. Find the payment details below:<br><br>-------------------------------------------------<br><br>Amount: <strong>{payment_total}<br></strong>Date: <strong>{payment_date}</strong><br>Invoice number: <span><strong># {invoice_number}<br><br></strong></span>-------------------------------------------------<br><br>You can always view the invoice for this payment at the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></p><p><a>View Invoice</a><br><br>We are looking forward working with you.<br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(4, 'invoice', 'invoice_overdue', 'Invoice Overdue Notice - {invoice_number}', '<p><span>Hi {customer},</span><br><br><span>This is an overdue notice for invoice <strong># {invoice_number}</strong></span><br><br><span>This invoice was due: {invoice_duedate}</span><br><br><span>You can view the invoice on the following link: <a>{invoice_number}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 0, 0),
(6, 'customer', 'new_contact_added', 'Welcome aboard', '<p>Dear {customer},<br><br>Thank you for registering.<br><br>We just wanted to say welcome.<br><br>Please contact us if you need any help.<br><br>Click here to view your profile: <a href=\"{app_url}\" data-mce-href=\"{app_url}\" style=\"\">{app_url}</a></p><p>Your login details:</p><p>Email: <span><strong>{login_email}</strong></span></p><p>Password: <span><strong>{login_password}</strong></span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br><br>(This is an automated email, so please don\'t reply to this email address)</p>', 'Ciuis CRM', 1, 1, 0),
(7, 'customer', 'new_customer', 'New Customer Registration', '<p>Hello Admin.<br><br>New customer registration on your customer portal:<br></p><p><strong>Type</strong>: {customer_type}<br><strong>Name: </strong>{name}<br><strong>Email:</strong> {customer_email}<br><br>Best Regards<br></p>', 'Ciuis CRM', 1, 1, 0),
(8, 'staff', 'new_staff', 'New Staff Added (Welcome Email)', '<p>Hi {staff},<br><br>You are added as member on our CRM.<br><br>Please use the following logic credentials:<br><br><strong>Email:</strong> {staff_email}<br><strong>Password:</strong> {password}<br><br>Click <span><span><a href=\"{login_url}\" data-mce-href=\"{login_url}\">here </a> </span></span>to login in the dashboard.<br><br>Best Regards,<br><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(9, 'staff', 'forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {staffname},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(10, 'staff', 'password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{staff_email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(11, 'staff', 'reminder_email', 'Send Invoice', 'Im sending Invoice', 'Ciuis CRM', 1, 0, 0),
(12, 'task', 'new_task_assigned', 'New Task Assigned to You - {task_name}', '<p><span>Dear {staffname},</span><br><br><span>You have been assigned to a new task:</span><br><br><span><strong>Name:</strong> {task_name}</span></p><p><strong>Start Date:</strong> {task_startdate}</p><p><span><strong>Due date:</strong> {task_duedate}</span></p><p><span><strong>Priority:</strong> {task_priority}</span></p><p><strong>Status:</strong> {task_status}<span><br><br></span><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\" style=\"\">view</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(13, 'task', 'task_comments', 'New Comment on Task - {task_name}', '<p>Dear {staffname},<br><br>A comment has been made on the following task:<br><br><strong>Task:</strong> {task_name}<br><strong>Comment:</strong> {task_comment}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(14, 'task', 'task_attachment', 'New Attachment on Task - {task_name}', '<p>Hi {staffname},<br><br><strong>{logged_in_user}</strong> added an attachment on the following task:<br><br><strong>Name:</strong> {task_name}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(15, 'task', 'task_updated', 'Task Status Changed', '<p><span>Hi {staffname},</span><br><br><span><strong>{</strong></span><span><b>logged_in_user}</b> marked task as <strong>{task_status}</strong></span><br><br><span><strong>Name:</strong> {task_name}</span><br><span><strong>Due date:</strong> {task_duedate}</span><br><br><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">{task_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(19, 'ticket', 'new_ticket', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>New ticket has been opened.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department:</strong> {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><span><a><br></a>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(20, 'ticket', 'new_customer_ticket', 'New Ticket Created', '<p><span>A new ticket has been created.</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(21, 'ticket', 'ticket_assigned', 'New ticket has been assigned to you', '<p><span>Hi {assigned},</span></p><p><span>A new support ticket&ampampnbsphas been assigned to you.</span></p><p><br><strong>Subject:</strong> {ticket_subject}<br><strong>Department:</strong> {ticket_department}<br><strong>Priority:</strong> {ticket_priority}<br></p><p><strong>Customer:</strong> {customer}</p><p><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(22, 'ticket', 'ticket_reply_to_staff', 'Ticket Reply', '<p><span>A new support ticket reply from {customer}</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(23, 'ticket', 'ticket_autoresponse', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>Thank you for contacting our team. A ticket has now been created for your request. </span></p><p><span>You will be notified when a response is made by email.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(24, 'ticket', 'ticket_reply_to_customer', 'New Ticket Reply', '<p><span>Hi {customer},</span><br><br><span>You have a new ticket reply to ticket.</span><br><br></p><p><strong>Subject: </strong>{ticket_subject}<br><strong></strong><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(25, 'proposal', 'send_proposal', 'Proposal With Number {proposal_number} Created', '<p>Dear {proposal_to},<br><br>Please find our attached proposal.<br><br>This proposal is valid until: {open_till}</p><p><br><strong>Proposal Subject:</strong> {subject}</p><p><strong>Proposal Details:</strong></p><p>{details}</p><p><br>Please don\'t hesitate to comment online if you have any questions.<br><br>We look forward to your communication.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(26, 'proposal', 'thankyou_email', 'Thank for you accepting the proposal', '<p>Dear {proposal_to},<br><br>Thank for for accepting the proposal.<br><br>We look forward to doing business with you.<br><br>We will contact you as soon as possible<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(27, 'proposal', 'customer_accepted_proposal', 'Customer Accepted Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> accepted the following proposal:<br><br><strong>Number:</strong> {proposal_number}<br><strong>Subject</strong>: {subject}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(28, 'proposal', 'customer_rejected_proposal', 'Client Declined Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> declined the proposal <strong>{subject}</strong><br><br><strong>Proposal Number:</strong> {proposal_number}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div><div> </div><div> </div><p> <br></p><div> </div>', 'Ciuis CRM', 1, 1, 0),
(29, 'lead', 'lead_assigned', 'New lead assigned to you', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(34, 'project', 'project_notification', 'New project created', '<p>Hello {customer},</p><p>New project is assigned to your company.<br><br><strong>Project Name:</strong> {project_name}<br><strong>Project Start Date:</strong> {project_start_date}</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\" style=\"\">{project_name}</a></p><p>\n\n\n</p><p>We are looking forward hearing from you.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(35, 'project', 'staff_added', 'New project assigned to you', '<p>Hi {staff},<br><br>New project has been assigned to you.<br><br>You can view the project on the following link <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a><br></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(36, 'project', 'new_file_uploaded_to_members', 'New Project File Uploaded - {project_name}', '<p>Hello {staff},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(37, 'project', 'new_file_uploaded_to_customer', 'New Project File Uploaded - {project_name}', '<p>Hello {customer},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(38, 'project', 'new_note_to_members', 'New Note on Project - {project_name}', '<p>Hello {staff},<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>You can view the note on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(39, 'project', 'new_note_to_customers', 'New Note on Project - {project_name}', '<p><span>Hello {customer},</span><br><br><span>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong></span><br><br><span><strong>Note</strong>: </span></p><p><span>{note}</span><br><br><span>You can view the note on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(40, 'project', 'project_status_changed', 'Project Status Changed', '<p><span>Hi {customer},</span><br><br><span><strong>{loggedin_staff}</strong> marked project as <strong>{project_status}</strong></span><br><br></p><p><strong>Project Name: </strong>{project_name}<br><strong>Project End Date:</strong> {project_end_date}</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(42, 'expense', 'expense_created', 'Expense Created - {expense_number}', '<p><strong></strong>Hello {customer},<br><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p><span>{expense_title}</span></p><p><strong>Expense Category:</strong></p><p><span>{expense_category}</span></p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Description:</strong></p><p><span>{expense_description}</span></p><p><br></p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(43, 'staff', 'customer_forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {customer},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(44, 'staff', 'customer_password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(45, 'invoice', 'invoice_recurring', 'Recurring Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(46, 'lead', 'web_lead_created', 'New Online Web lead created', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 0, 1, 0),
(47, 'lead', 'lead_submitted', 'New lead has been created for you', '<p>Hello {lead_name},<br><br>New lead has been created for you.<br><br>Lead Email: {lead_email}<br><br><br>Kind Regards,<br><strong>{email_signature}</strong><br></p>', 'Ciuis CRM', 0, 1, 0),
(48, 'expense', 'expense_recurring', 'Recurring Expense with number {expense_number} created', '<p>Hello {customer},</p><p><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p>{expense_title}</p><p><strong>Expense Category:</strong></p><p>{expense_category}</p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 0, 1, 0),
(50, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(51, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(52, 'order', 'order_message', 'Order {order_number} confirmed', '<p>Hello {customer},<br></p><div><br></div><div><br></div><div>Thank you very much for your recent order with {company_name}. Your order is currently being processed and you will receive a shipping confirmation once the order has been shipped.</div><div><br></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(53, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(54, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `email_template_fields`
--

CREATE TABLE `email_template_fields` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_template_fields`
--

INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`) VALUES
(1, 1, 'email_signature', 'email_signature'),
(2, 1, 'invoice_number', 'invoice_number'),
(3, 1, 'invoice_status', 'invoice_status'),
(4, 1, 'name', 'name'),
(5, 1, 'invoice_link', 'invoice_link'),
(6, 3, 'payment_total', 'payment_total'),
(7, 3, 'payment_date', 'payment_date'),
(8, 3, 'invoice_number', 'invoice_number'),
(9, 3, 'email_signature', 'email_signature'),
(10, 3, 'customer', 'customer'),
(11, 3, 'invoice_link', 'invoice_link'),
(12, 4, 'invoice_number', 'invoice_number'),
(13, 4, 'invoice_duedate', 'invoice_duedate'),
(14, 4, 'name', 'name'),
(15, 4, 'email_signature', 'email_signature'),
(16, 4, 'customer', 'customer'),
(17, 6, 'companyname', 'companyname'),
(18, 6, 'app_url', 'app_url'),
(19, 6, 'customer', 'customer'),
(20, 6, 'name', 'name'),
(21, 6, 'email_signature', 'email_signature'),
(22, 7, 'customer_type', 'customer_type'),
(23, 7, 'name', 'name'),
(24, 7, 'customer_email', 'customer_email'),
(25, 19, 'customer', 'customer'),
(26, 19, 'ticket_subject', 'ticket_subject'),
(27, 19, 'ticket_department', 'ticket_department'),
(28, 19, 'ticket_priority', 'ticket_priority'),
(29, 19, 'ticket_message', 'ticket_message'),
(30, 19, 'name', 'name'),
(31, 19, 'email_signature', 'email_signature'),
(32, 21, 'assigned', 'assigned'),
(33, 21, 'ticket_subject', 'ticket_subject'),
(34, 21, 'ticket_department', 'ticket_department'),
(35, 21, 'ticket_priority', 'ticket_priority'),
(36, 21, 'ticket_message', 'ticket_message'),
(37, 21, 'name', 'name'),
(38, 21, 'email_signature', 'email_signature'),
(39, 24, 'customer', 'customer'),
(40, 24, 'ticket_subject', 'ticket_subject'),
(41, 24, 'ticket_message', 'ticket_message'),
(42, 24, 'name', 'name'),
(43, 24, 'email_signature', 'email_signature'),
(44, 25, 'proposal_to', 'proposal_to'),
(45, 25, 'open_till', 'open_till'),
(46, 25, 'open_till', 'open_till'),
(47, 25, 'name', 'name'),
(48, 25, 'email_signature', 'email_signature'),
(49, 25, 'subject', 'subject'),
(50, 25, 'details', 'details'),
(51, 26, 'proposal_to', 'proposal_to'),
(52, 26, 'name', 'name'),
(53, 26, 'email_signature', 'email_signature'),
(54, 26, 'subject', 'subject'),
(55, 26, 'details', 'details'),
(56, 26, 'proposal_number', 'proposal_number'),
(57, 27, 'proposal_to', 'proposal_to'),
(58, 27, 'subject', 'subject'),
(59, 27, 'proposal_number', 'proposal_number'),
(60, 27, 'proposal_total', 'proposal_total'),
(61, 27, 'name', 'name'),
(62, 27, 'email_signature', 'email_signature'),
(63, 27, 'details', 'details'),
(64, 28, 'proposal_to', 'proposal_to'),
(65, 28, 'proposal_number', 'proposal_number'),
(66, 28, 'subject', 'subject'),
(67, 28, 'proposal_total', 'proposal_total'),
(68, 28, 'name', 'name'),
(69, 28, 'email_signature', 'email_signature'),
(70, 28, 'details', 'details'),
(71, 8, 'staff', 'staff'),
(72, 8, 'staff_email', 'staff_email'),
(73, 8, 'password', 'password'),
(74, 8, 'name', 'name'),
(75, 8, 'email_signature', 'email_signature'),
(76, 8, 'login_url', 'login_url'),
(77, 9, 'staffname', 'staffname'),
(78, 9, 'password_url', 'password_url'),
(80, 9, 'email_signature', 'email_signature'),
(81, 10, 'staff_email', 'staff_email'),
(82, 10, 'email_signature', 'email_signature'),
(83, 10, 'staffname', 'staffname'),
(84, 29, 'lead_assigned_staff', 'lead_assigned_staff'),
(85, 29, 'lead_name', 'lead_name'),
(86, 29, 'lead_email', 'lead_email'),
(87, 29, 'lead_url', 'lead_url'),
(88, 29, 'name', 'name'),
(89, 29, 'email_signature', 'email_signature'),
(90, 12, 'staffname', 'staffname'),
(91, 12, 'task_name', 'task_name'),
(92, 12, 'task_startdate', 'task_startdate'),
(93, 12, 'task_duedate', 'task_duedate'),
(94, 12, 'task_priority', 'task_priority'),
(95, 12, 'task_url', 'task_url'),
(96, 12, 'name', 'name'),
(97, 12, 'email_signature', 'email_signature'),
(98, 13, 'staffname', 'staffname'),
(99, 13, 'task_name', 'task_name'),
(100, 13, 'task_startdate', 'task_startdate'),
(101, 13, 'task_duedate', 'task_duedate'),
(102, 13, 'task_priority', 'task_priority'),
(103, 13, 'task_status', 'task_status'),
(104, 13, 'task_url', 'task_url'),
(105, 13, 'task_comment', 'task_comment'),
(106, 13, 'name', 'name'),
(107, 13, 'email_signature', 'email_signature'),
(108, 14, 'staffname', 'staffname'),
(109, 14, 'task_name', 'task_name'),
(110, 14, 'task_startdate', 'task_startdate'),
(111, 14, 'task_duedate', 'task_duedate'),
(112, 14, 'task_priority', 'task_priority'),
(113, 14, 'task_url', 'task_url'),
(114, 14, 'name', 'name'),
(115, 14, 'email_signature', 'email_signature'),
(116, 14, 'task_status', 'task_status'),
(117, 13, 'task_status', 'task_status'),
(118, 12, 'task_status', 'task_status'),
(119, 14, 'logged_in_user', 'logged_in_user'),
(120, 15, 'staffname', 'staffname'),
(121, 15, 'task_name', 'task_name'),
(122, 15, 'task_startdate', 'task_startdate'),
(123, 15, 'task_duedate', 'task_duedate'),
(124, 15, 'task_priority', 'task_priority'),
(125, 15, 'task_url', 'task_url'),
(126, 15, 'name', 'name'),
(127, 15, 'task_status', 'task_status'),
(128, 15, 'logged_in_user', 'logged_in_user'),
(129, 15, 'email_signature', 'email_signature'),
(130, 34, 'customer', 'customer'),
(131, 34, 'project_name', 'project_name'),
(132, 34, 'project_start_date', 'project_start_date'),
(133, 34, 'project_value', 'project_value'),
(134, 34, 'project_url', 'project_url'),
(135, 34, 'name', 'name'),
(136, 34, 'email_signature', 'email_signature'),
(137, 34, 'project_status', 'project_status'),
(138, 34, 'project_tax', 'project_tax'),
(139, 34, 'project_end_date', 'project_end_date'),
(140, 40, 'customer', 'customer'),
(141, 40, 'project_name', 'project_name'),
(142, 40, 'project_start_date', 'project_start_date'),
(143, 40, 'project_end_date', 'project_end_date'),
(144, 40, 'project_value', 'project_value'),
(145, 40, 'project_tax', 'project_tax'),
(146, 40, 'project_url', 'project_url'),
(147, 40, 'name', 'name'),
(148, 40, 'email_signature', 'email_signature'),
(149, 40, 'loggedin_staff', 'loggedin_staff'),
(150, 35, 'customer', 'customer'),
(151, 35, 'staff', 'staff'),
(152, 35, 'project_name', 'project_name'),
(153, 35, 'project_start_date', 'project_start_date'),
(154, 35, 'project_end_date', 'project_end_date'),
(155, 35, 'project_value', 'project_value'),
(156, 35, 'project_tax', 'project_tax'),
(157, 35, 'project_url', 'project_url'),
(158, 35, 'name', 'name'),
(159, 35, 'email_signature', 'email_signature'),
(160, 35, 'loggedin_staff', 'loggedin_staff'),
(161, 36, 'customer', 'customer'),
(162, 36, 'staff', 'staff'),
(163, 36, 'project_name', 'project_name'),
(164, 36, 'project_start_date', 'project_start_date'),
(165, 36, 'project_end_date', 'project_end_date'),
(166, 36, 'project_value', 'project_value'),
(167, 36, 'project_tax', 'project_tax'),
(168, 36, 'project_url', 'project_url'),
(169, 36, 'name', 'name'),
(170, 36, 'email_signature', 'email_signature'),
(171, 36, 'loggedin_staff', 'loggedin_staff'),
(172, 37, 'customer', 'customer'),
(173, 37, 'project_name', 'project_name'),
(174, 37, 'project_start_date', 'project_start_date'),
(175, 37, 'project_end_date', 'project_end_date'),
(176, 37, 'project_value', 'project_value'),
(177, 37, 'project_tax', 'project_tax'),
(178, 37, 'project_url', 'project_url'),
(179, 37, 'name', 'name'),
(180, 37, 'email_signature', 'email_signature'),
(181, 37, 'loggedin_staff', 'loggedin_staff'),
(182, 38, 'customer', 'customer'),
(183, 38, 'note', 'note'),
(184, 38, 'project_name', 'project_name'),
(185, 38, 'project_start_date', 'project_start_date'),
(186, 38, 'project_end_date', 'project_end_date'),
(187, 38, 'project_value', 'project_value'),
(188, 38, 'project_tax', 'project_tax'),
(189, 38, 'project_url', 'project_url'),
(190, 38, 'name', 'name'),
(191, 38, 'email_signature', 'email_signature'),
(192, 38, 'loggedin_staff', 'loggedin_staff'),
(193, 39, 'customer', 'customer'),
(194, 39, 'note', 'note'),
(195, 39, 'project_name', 'project_name'),
(196, 39, 'project_start_date', 'project_start_date'),
(197, 39, 'project_end_date', 'project_end_date'),
(198, 39, 'project_value', 'project_value'),
(199, 39, 'project_tax', 'project_tax'),
(200, 39, 'project_url', 'project_url'),
(201, 39, 'name', 'name'),
(202, 39, 'email_signature', 'email_signature'),
(203, 39, 'loggedin_staff', 'loggedin_staff'),
(204, 42, 'customer', 'customer'),
(205, 42, 'expense_number', 'expense_number'),
(206, 42, 'expense_title', 'expense_title'),
(207, 42, 'expense_category', 'expense_category'),
(208, 42, 'expense_date', 'expense_date'),
(209, 42, 'expense_description', 'expense_description'),
(210, 42, 'expense_amount', 'expense_amount'),
(211, 42, 'name', 'name'),
(212, 42, 'email_signature', 'email_signature'),
(213, 20, 'ticket_subject', 'ticket_subject'),
(214, 20, 'ticket_department', 'ticket_department'),
(215, 20, 'ticket_priority', 'ticket_priority'),
(216, 20, 'ticket_message', 'ticket_message'),
(217, 20, 'name', 'name'),
(218, 20, 'email_signature', 'email_signature'),
(219, 22, 'ticket_subject', 'ticket_subject'),
(220, 22, 'ticket_department', 'ticket_department'),
(221, 22, 'ticket_priority', 'ticket_priority'),
(222, 22, 'ticket_message', 'ticket_message'),
(223, 22, 'name', 'name'),
(224, 22, 'email_signature', 'email_signature'),
(225, 23, 'customer', 'customer'),
(226, 23, 'ticket_subject', 'ticket_subject'),
(227, 23, 'ticket_department', 'ticket_department'),
(228, 23, 'ticket_priority', 'ticket_priority'),
(229, 23, 'ticket_message', 'ticket_message'),
(230, 23, 'name', 'name'),
(231, 23, 'email_signature', 'email_signature'),
(232, 43, 'customer', 'customer'),
(233, 43, 'password_url', 'password_url'),
(234, 43, 'email_signature', 'email_signature'),
(235, 44, 'email', 'email'),
(236, 44, 'password_url', 'password_url'),
(237, 44, 'email_signature', 'email_signature'),
(238, 45, 'invoice_number', 'invoice_number'),
(239, 45, 'name', 'name'),
(240, 45, 'customer', 'customer'),
(241, 45, 'invoice_link', 'invoice_link'),
(242, 45, 'invoice_status', 'invoice_status'),
(243, 45, 'email_signature', 'email_signature'),
(244, 1, 'customer', 'customer'),
(245, 46, 'lead_assigned_staff', 'lead_assigned_staff'),
(246, 46, 'lead_name', 'lead_name'),
(247, 46, 'lead_email', 'lead_email'),
(248, 46, 'lead_url', 'lead_url'),
(249, 46, 'email_signature', 'email_signature'),
(250, 46, 'name', 'name'),
(251, 47, 'lead_assigned_staff', 'lead_assigned_staff'),
(252, 47, 'lead_name', 'lead_name'),
(253, 47, 'lead_email', 'lead_email'),
(254, 47, 'email_signature', 'email_signature'),
(255, 25, 'proposal_link', 'proposal_link'),
(256, 48, 'expense_title', 'expense_title'),
(257, 48, 'email_signature', 'email_signature'),
(258, 48, 'expense_number', 'expense_number'),
(259, 48, 'customer', 'customer'),
(260, 48, 'name', 'name'),
(261, 48, 'expense_amount', 'expense_amount'),
(262, 48, 'expense_category', 'expense_category'),
(263, 48, 'expense_date', 'expense_date'),
(272, 50, 'note', 'note'),
(273, 50, 'project_name', 'project_name'),
(274, 50, 'project_start_date', 'project_start_date'),
(275, 50, 'project_end_date', 'project_end_date'),
(276, 50, 'project_value', 'project_value'),
(277, 50, 'project_tax', 'project_tax'),
(278, 50, 'project_url', 'project_url'),
(279, 50, 'name', 'name'),
(280, 50, 'email_signature', 'email_signature'),
(281, 50, 'loggedin_staff', 'loggedin_staff'),
(282, 51, 'project_name', 'project_name'),
(283, 51, 'project_start_date', 'project_start_date'),
(284, 51, 'project_end_date', 'project_end_date'),
(285, 51, 'project_value', 'project_value'),
(286, 51, 'project_tax', 'project_tax'),
(287, 51, 'project_url', 'project_url'),
(288, 51, 'name', 'name'),
(289, 51, 'email_signature', 'email_signature'),
(290, 51, 'loggedin_staff', 'loggedin_staff'),
(291, 34, 'project_description', 'project_description'),
(292, 35, 'project_description', 'project_description'),
(293, 37, 'project_description', 'project_description'),
(294, 40, 'project_description', 'project_description'),
(295, 52, 'customer', 'customer'),
(296, 52, 'order_to', 'order_to'),
(297, 52, 'email_signature', 'email_signature'),
(298, 52, 'name', 'name'),
(299, 52, 'order_number', 'order_number'),
(300, 52, 'app_name', 'app_name'),
(301, 52, 'company_name', 'company_name'),
(302, 50, 'note', 'note'),
(303, 50, 'project_name', 'project_name'),
(304, 50, 'project_start_date', 'project_start_date'),
(305, 50, 'project_end_date', 'project_end_date'),
(306, 50, 'project_value', 'project_value'),
(307, 50, 'project_tax', 'project_tax'),
(308, 50, 'project_url', 'project_url'),
(309, 50, 'name', 'name'),
(310, 50, 'email_signature', 'email_signature'),
(311, 50, 'loggedin_staff', 'loggedin_staff'),
(312, 51, 'project_name', 'project_name'),
(313, 51, 'project_start_date', 'project_start_date'),
(314, 51, 'project_end_date', 'project_end_date'),
(315, 51, 'project_value', 'project_value'),
(316, 51, 'project_tax', 'project_tax'),
(317, 51, 'project_url', 'project_url'),
(318, 51, 'name', 'name'),
(319, 51, 'email_signature', 'email_signature'),
(320, 51, 'loggedin_staff', 'loggedin_staff'),
(321, 34, 'project_description', 'project_description'),
(322, 35, 'project_description', 'project_description'),
(323, 37, 'project_description', 'project_description'),
(324, 40, 'project_description', 'project_description');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` mediumtext NOT NULL,
  `detail` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `staffname` varchar(255) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `public` varchar(255) DEFAULT '0',
  `reminder` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `detail`, `staff_id`, `staffname`, `start`, `end`, `color`, `public`, `reminder`) VALUES
(1, 'Lorem Ipsum', 'Today is good.', 1, 'Lance Bogrol', '2018-02-01 00:05:22', '2018-01-30 20:05:28', NULL, 'true', NULL),
(2, 'Sample Event', 'Today is good.', 1, 'Lance Bogrol', '2018-02-02 00:05:22', '2018-02-02 20:05:28', NULL, 'true', NULL),
(3, 'Go Client Meet', 'Today is good.', 1, 'Lance Bogrol', '2018-02-03 00:05:22', '2018-02-03 20:05:28', NULL, 'true', NULL),
(4, 'Web Design', 'Today is good.', 1, 'Lance Bogrol', '2018-02-04 00:05:22', '2018-02-04 20:05:28', NULL, 'true', NULL),
(5, 'Hola!', 'Today is good.', 1, 'Lance Bogrol', '2018-02-06 00:05:22', '2018-02-06 20:05:28', NULL, 'true', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expensecat`
--

CREATE TABLE `expensecat` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `expensecat`
--

INSERT INTO `expensecat` (`id`, `name`, `description`) VALUES
(1, 'Office Expenses', 'Office Expenses'),
(2, 'Other Expenses', 'Other Expenses'),
(3, 'Trivia', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `hash` varchar(6) NOT NULL,
  `relation_type` varchar(255) DEFAULT NULL,
  `relation` int(11) DEFAULT '0',
  `title` varchar(500) DEFAULT NULL,
  `description` text,
  `category_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `internal` tinyint(1) NOT NULL DEFAULT '0',
  `recurring` int(11) DEFAULT NULL,
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `number`, `hash`, `relation_type`, `relation`, `title`, `description`, `category_id`, `account_id`, `staff_id`, `customer_id`, `invoice_id`, `created`, `date`, `amount`, `sub_total`, `total_discount`, `total_tax`, `internal`, `recurring`, `last_recurring`, `pdf_status`) VALUES
(1, NULL, '', 'project', 1, 'Sample Expense', 'Lorem ipsum sit dolor amet.', 3, 1, 1, 17, NULL, '2018-01-05 21:22:56', '2018-01-05', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(3, NULL, '', NULL, 0, 'Food Expenses', 'Other Expenses', 1, 3, 1, 0, NULL, '2018-01-06 04:26:16', '2018-01-06', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(4, NULL, '', 'project', 1, 'Sample', 'Sample', 3, 1, 1, 17, NULL, '2018-01-25 19:30:44', '2018-01-25', '10.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(5, NULL, '33fa04', NULL, 0, 'Sample', 'Lorem ipsum dolor sit amet', 2, 1, 1, 0, NULL, '2018-04-11 22:07:27', '2018-04-11', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(7, NULL, '9bdb1f', NULL, 0, 'Test', 'Test', 1, 3, 1, 16, NULL, '2018-04-11 22:32:14', '2018-04-11', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(8, NULL, '', NULL, 0, 'Test', 'test', 3, 1, 1, 18, 15, '2018-05-20 17:29:39', '2018-05-20', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(9, NULL, '', NULL, 0, 'Test', 'Test', 2, 4, 1, 0, NULL, '2018-06-01 19:29:37', '2018-06-01', '10.00', NULL, NULL, NULL, 0, NULL, NULL, 0),
(10, NULL, '', NULL, 0, 'Test', '', 3, 1, 1, 0, NULL, '2018-11-13 19:18:34', '2018-11-13', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) DEFAULT NULL,
  `file_name` mediumtext NOT NULL,
  `is_old` tinyint(1) DEFAULT '1',
  `filetype` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `relation_type`, `relation`, `file_name`, `is_old`, `filetype`, `created`) VALUES
(2, 'project', 11, 'Document.pdf', 1, '.doc', '2017-11-04 00:00:00'),
(3, 'task', 1, 'Testfile.doc', 1, '.doc', '2017-11-04 00:00:00'),
(4, 'task', 1, 'Document.pdf', 1, '.doc', '2017-11-04 00:00:00'),
(6, 'project', 11, 'Chaim.doc', 1, '.doc', '2017-11-04 00:00:00'),
(8, 'project', 9, 'Sample_File2.png', 1, NULL, '2017-11-16 01:48:03'),
(9, 'task', 25, 'Sample_File3.png', 1, NULL, '2017-11-23 21:28:35'),
(10, 'task', 25, 'ticket.png', 1, NULL, '2017-11-23 21:29:10'),
(11, 'task', 27, 'Sample_File4.png', 1, NULL, '2017-11-23 23:16:21'),
(12, 'task', 30, 'Sample_File5.png', 1, NULL, '2017-11-23 23:43:52'),
(13, 'project', 1, 'household.png', 1, NULL, '2018-01-02 01:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoiceId` varchar(100) DEFAULT NULL,
  `token` mediumtext NOT NULL,
  `no` int(11) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `proposal_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `datesend` datetime DEFAULT NULL,
  `datepayment` date DEFAULT NULL,
  `duenote` text,
  `status_id` int(11) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `amount_paying` varchar(100) DEFAULT NULL,
  `default_payment_method` varchar(100) DEFAULT NULL,
  `CustomField` longtext NOT NULL,
  `billing_street` varchar(255) NOT NULL,
  `billing_city` varchar(255) NOT NULL,
  `billing_state` varchar(255) NOT NULL,
  `billing_zip` varchar(255) NOT NULL,
  `billing_country` int(11) NOT NULL,
  `shipping_street` varchar(255) NOT NULL,
  `shipping_city` varchar(255) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_zip` varchar(255) NOT NULL,
  `shipping_country` int(11) NOT NULL,
  `recurring` int(11) NOT NULL DEFAULT '0',
  `last_recurring` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoiceId`, `token`, `no`, `serie`, `created`, `duedate`, `customer_id`, `expense_id`, `proposal_id`, `project_id`, `staff_id`, `datesend`, `datepayment`, `duenote`, `status_id`, `sub_total`, `total_discount`, `total_tax`, `total`, `amount_paying`, `default_payment_method`, `CustomField`, `billing_street`, `billing_city`, `billing_state`, `billing_zip`, `billing_country`, `shipping_street`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `recurring`, `last_recurring`) VALUES
(1, NULL, '9289e7e9faf3b97032dc96e367e69b23', 6, 'A', '2018-01-01', '0000-00-00', 17, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 2, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(5, NULL, '2d78f01034c59352131086ab2995cd59', 2, 'A', '2018-01-05', NULL, 16, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, '400.00', '0.00', '10.00', '410.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(6, NULL, '431f5592a096e81c18de0be1b34af5bc', 23, 'A', '2018-01-06', NULL, 12, NULL, NULL, NULL, 1, '2018-01-21 16:21:09', '2018-01-06', NULL, 2, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(7, NULL, '7f2088df0ff31cb17341e29826e83eb6', 12, 'A', '2018-01-22', '2018-12-12', 17, NULL, NULL, NULL, 1, '2018-01-25 05:51:06', NULL, 'Sample', 3, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(8, NULL, 'c8031bdc83af8bf95f6e455ef12c042c', 102, 'a', '2018-02-12', '2018-04-11', 6, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 4, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(9, NULL, 'd9d400ebda1d350da0bff978ac606959', 2332, 'A', '2018-02-11', NULL, 1, NULL, NULL, NULL, 1, NULL, '2018-02-13', NULL, 2, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(10, NULL, '0dbffee1accb51b774c6274a1ba74c90', 102, 'a', '2018-02-13', '2019-02-06', 6, NULL, NULL, NULL, 2, '2018-03-18 04:42:34', NULL, 'Please pay on time.', 3, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 13, NULL),
(11, NULL, 'e4304c0de5cdc010f2b63d90def71d9b', 12, '12', '2018-03-09', '0000-00-00', 17, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 2, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(12, NULL, '5436b282ebbd7e40ba6655bbd7d0e62d', 12, 'a', '2018-03-25', '0000-00-00', 17, NULL, NULL, NULL, 1, '2018-04-08 16:59:58', NULL, 'adf', 2, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(13, NULL, '1e0aee98391a3d89525d12f09b28e19c', 23, 'A', '2018-04-22', '2018-10-30', 18, NULL, NULL, NULL, 1, NULL, NULL, 'Pay on time.', 3, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', '2233', 236, 'Example Address', 'Los Angeles', 'California', '2233', 1, 0, NULL),
(14, NULL, '', NULL, NULL, '2018-05-20', NULL, 18, 8, NULL, NULL, 1, NULL, NULL, NULL, 3, '20.00', NULL, NULL, '20.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(15, NULL, '', NULL, NULL, '2018-05-20', NULL, 18, 8, NULL, NULL, 1, NULL, NULL, NULL, 3, '20.00', NULL, NULL, '20.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(16, NULL, '5af6a98d566d216a16d9a26e8559cbb6', 0, 'A', '2018-06-20', '2018-06-27', 12, NULL, 5, NULL, 1, NULL, '0000-00-00', 'TEST', 3, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(17, NULL, 'b32c25e711a8c18f8cb7da09842a3cb8', 1, '1', '2018-08-11', '2019-08-20', 16, NULL, NULL, NULL, 1, NULL, NULL, 'Pay on time', 3, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', '', '', '', '', 0, '', '', '', '', 0, 0, NULL),
(18, NULL, 'b12880099b999862fcc47d67b90e19bf', 7, '01', '2018-09-19', NULL, 18, NULL, NULL, NULL, 1, NULL, '2018-09-19', NULL, 2, '1000.00', '0.00', '0.00', '1000.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', '2233', 236, 'Example Address', 'Los Angeles', 'California', '2233', 236, 0, NULL),
(19, NULL, '85dd8412b3f8d532f3c6da6793d1f5a5', 12, 'Test', '2018-11-12', '2019-12-24', 18, NULL, NULL, NULL, 1, NULL, NULL, 'pay on time', 3, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', '2233', 236, 'Example Address', 'Los Angeles', 'California', '2233', 236, 0, NULL),
(22, NULL, '68605ed0278edc00434f461ad4ce04f3', 0, '', '2018-11-12', '2018-11-12', 0, NULL, NULL, NULL, 1, NULL, NULL, '', 3, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '', '------', ',---- ', ',----', '----', 0, '------', ',---- ', ',----', '----', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoicestatus`
--

CREATE TABLE `invoicestatus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoicestatus`
--

INSERT INTO `invoicestatus` (`id`, `name`, `color`) VALUES
(1, 'Draft', '#7d7d7d'),
(2, 'Paid', '#26c281'),
(3, 'Unpaid', '#ff3b30'),
(4, 'Cancelled', '#dd2c00');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) DEFAULT NULL,
  `relation` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` mediumtext,
  `quantity` decimal(11,2) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `tax` decimal(11,2) DEFAULT '0.00',
  `discount` decimal(11,2) DEFAULT '0.00',
  `total` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `relation_type`, `relation`, `product_id`, `code`, `name`, `description`, `quantity`, `unit`, `price`, `tax`, `discount`, `total`) VALUES
(1, 'invoice', 1, 4, 'WEB', 'Consultance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(5, 'invoice', 5, 4, 'WEB', 'Consultance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(8, 'proposal', 2, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(10, 'proposal', 4, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(11, 'proposal', 5, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(12, 'invoice', 6, 3, 'WEB-204', 'Logo Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(13, 'invoice', 7, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(14, 'invoice', 8, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(15, 'invoice', 9, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(16, 'invoice', 10, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(17, 'invoice', 11, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(18, 'invoice', 12, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(19, 'invoice', 13, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(20, 'invoice', 5, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(21, 'invoice', 15, NULL, NULL, 'Test', 'test', '1.00', 'Unit', '20.00', '0.00', '0.00', '20.00'),
(22, 'invoice', 16, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(23, 'order', 1, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '10.00', '190.00'),
(24, 'invoice', 19, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(27, 'invoice', 22, 0, '', 'New', '', '1.00', 'Unit', '0.00', '0.00', '0.00', '0.00'),
(28, 'proposal', 6, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `langcode` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `foldername` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES
(1, 'de_De', 'german', 'german'),
(2, 'en_US', 'english', 'english'),
(3, 'es_ES', 'spanish', 'spanish'),
(4, 'fr_FR', 'french', 'french'),
(5, 'pt-pt', 'portuguese_pt', 'portuguese_pt'),
(6, 'pt-BR', 'portuguese_br', 'portuguese_br'),
(7, 'tr_TR', 'turkish', 'turkish'),
(8, 'ru_RU', 'russian', 'russian'),
(9, 'sv_SV', 'swedish', 'swedish'),
(10, 'it-ch', 'italian', 'italian');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `date_contacted` datetime NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(300) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company` varchar(300) DEFAULT NULL,
  `description` text,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `zip` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `assigned_id` int(11) NOT NULL,
  `created` date NOT NULL,
  `status` int(11) NOT NULL,
  `source` int(11) NOT NULL,
  `lastcontact` datetime DEFAULT NULL,
  `dateassigned` date DEFAULT NULL,
  `staff_id` int(11) NOT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `lost` tinyint(1) DEFAULT '0',
  `junk` int(11) DEFAULT '0',
  `public` tinyint(1) DEFAULT '0',
  `weblead` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `date_contacted`, `type`, `name`, `title`, `company`, `description`, `country_id`, `zip`, `city`, `state`, `email`, `address`, `website`, `phone`, `assigned_id`, `created`, `status`, `source`, `lastcontact`, `dateassigned`, `staff_id`, `dateconverted`, `lost`, `junk`, `public`, `weblead`) VALUES
(1, '0000-00-00 00:00:00', 0, 'Judy Youngs', 'Lorem Ipsum', 'Northern Star', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 90, '54617', 'New York', 'NC', 'judyyoung@example.com', '1333 Deerfield Dr, State College, PA, 16803', 'www.example.com', '(140) 211 2494', 2, '2017-11-11', 3, 1, NULL, '2017-11-11', 1, '2017-08-22 04:35:57', 0, 0, 0, NULL),
(2, '0000-00-00 00:00:00', 0, 'Joyce McCoy', 'Lorem Ipsum', 'Codelam LLC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 23, '54617', 'New York', 'GA', 'joycemccoy@example.com', '1398 N 80th W, Orem, UT, 84057', 'www.example.com', '(296) 452 9522', 2, '2017-11-11', 1, 2, NULL, '2017-11-11', 1, '2017-08-24 22:13:34', 0, 0, 1, NULL),
(3, '0000-00-00 00:00:00', 0, 'Jane Carpenter', 'Lorem Ipsum', 'Unadoncare INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 55, '54617', 'New York', 'CT', 'jane-85@example.com', '245 Bourbon Acres Rd, Paris, KY, 40361', 'www.example.com', '(432) 156 5172', 3, '2018-01-26', 1, 1, NULL, '2018-01-26', 2, '2017-08-24 17:45:07', NULL, NULL, 0, NULL),
(4, '0000-00-00 00:00:00', 0, 'William Patel', 'Lorem Ipsum', 'Canelectrics INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 11, '54617', 'New York', 'VA', 'abaris@null.net', '2806 Mimi Ave, Chester, VA, 23831', 'www.example.com', '(269) 364 3098', 4, '2018-09-28', 4, 2, NULL, '2018-09-28', 1, '2017-08-31 03:23:26', NULL, NULL, 0, NULL),
(5, '0000-00-00 00:00:00', 1, 'Danielle Burns', 'Lorem Ipsum', 'Donway INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 23, '54617', 'New York', 'MI', 'danielle-92@example.com', '5094 Vidrine Rd, Ville Platte, LA, 22356', 'www.example.com', '(610) 465 2198', 1, '2018-01-26', 2, 1, NULL, '2018-01-26', 2, '2017-11-12 00:17:05', NULL, NULL, 0, NULL),
(6, '0000-00-00 00:00:00', 0, 'Nicholas Walters', 'Lorem Ipsum', 'Transhex LLC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 12, '54617', 'New York', 'CT', 'nicholas-90@example.com', '1911 Crestview Dr, Johnstown, CO, 80534', 'www.example.com', '(954) 630 6210', 3, '2018-01-26', 4, 2, NULL, '2018-01-26', 2, '2017-08-22 04:40:32', NULL, NULL, 0, NULL),
(7, '0000-00-00 00:00:00', 0, 'Evelyn Bradley', 'Lorem Ipsum', 'Latdoace AG.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 32, '54617', 'New York', 'GA', 'evelyn-96@example.com', 'Po Box 961, Thibodaux, LA, 703024', 'www.example.com', '(489) 588 6002', 2, '2017-11-01', 1, 1, NULL, '2017-11-01', 1, NULL, 0, 0, 0, NULL),
(11, '0000-00-00 00:00:00', 1, 'Roger H Barnett', 'Roger H Barnett', 'Roger H Barnett', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 235, '91744', 'Denge', 'Conda', 'roger@example.com', 'Sample adress will placed here!', 'example.com', '626-931-8754', 3, '2018-11-13', 1, 6, NULL, '2018-11-13', 1, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leadssources`
--

CREATE TABLE `leadssources` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadssources`
--

INSERT INTO `leadssources` (`id`, `name`) VALUES
(1, 'WEB'),
(2, 'EMAIL'),
(6, 'TELEPHONE'),
(7, 'SOCIAL MEDIA');

-- --------------------------------------------------------

--
-- Table structure for table `leadsstatus`
--

CREATE TABLE `leadsstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) DEFAULT '#28B8DA'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadsstatus`
--

INSERT INTO `leadsstatus` (`id`, `name`, `color`) VALUES
(1, 'NEW', NULL),
(2, 'CONTACTED', '#fff3d1'),
(3, 'INPROGRESS', '#ffdc77'),
(4, 'CONVERTED', '#daf196'),
(5, 'LOST', 'pink');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `detail` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `staff_id` varchar(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`) VALUES
(1, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-03 19:27:53', '1', NULL, 0),
(2, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-03 19:28:01', '1', NULL, 0),
(3, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Customer-18', '2018-01-03 23:58:52', '1', NULL, 0),
(4, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-04 17:20:40', '1', NULL, 0),
(5, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-04 17:21:43', '1', NULL, 0),
(6, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-04 17:21:50', '1', NULL, 0),
(7, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-04 20:13:57', '1', NULL, 0),
(8, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-05 02:26:15', '1', NULL, 0),
(9, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-05 02:27:33', '1', NULL, 0),
(10, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-05 02:29:14', '1', NULL, 0),
(11, 'Lance Bogrol created new project', '2018-01-05 02:59:09', '1', NULL, 2),
(12, 'Lance Bogrol created new project', '2018-01-05 02:59:45', '1', NULL, 3),
(13, 'Lance Bogrol created new project', '2018-01-05 03:00:57', '1', NULL, 4),
(14, 'Lance Bogrol created new project', '2018-01-05 03:02:15', '1', NULL, 2),
(15, 'Lance Bogrol created new project', '2018-01-05 03:08:19', '1', NULL, 3),
(16, 'Lance Bogrol created new project', '2018-01-05 03:09:33', '1', NULL, 2),
(17, 'Lance Bogrol converted PROJECT-2 to invoice', '2018-01-05 03:30:47', '1', 16, 0),
(18, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-2', '2018-01-05 03:35:15', '1', NULL, 0),
(19, 'Lance Bogrol converted PROJECT-2 to invoice', '2018-01-05 03:35:42', '1', 16, 0),
(20, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-3', '2018-01-05 03:36:30', '1', NULL, 0),
(21, 'Lance Bogrol converted PROJECT-1 to invoice', '2018-01-05 03:37:32', '1', 17, 0),
(22, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-4', '2018-01-05 03:38:11', '1', NULL, 0),
(23, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-05 16:02:06', '1', NULL, 0),
(24, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/5\">INV-5</a>.', '2018-01-05 16:06:15', '1', 16, 0),
(25, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/1\">PRO-1</a>.', '2018-01-05 16:34:42', '1', NULL, 0),
(26, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"accounts/account/4\"> Account-4</a>', '2018-01-05 17:07:12', '1', NULL, 0),
(27, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"accounts/account/4\"> Account-4</a>', '2018-01-05 17:07:36', '1', NULL, 0),
(28, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/5\"> Account-5</a>', '2018-01-05 17:30:32', '1', NULL, 0),
(29, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/6\"> Account-6</a>', '2018-01-05 17:31:15', '1', NULL, 0),
(30, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/7\"> Account-7</a>', '2018-01-05 17:32:21', '1', NULL, 0),
(31, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/8\"> Account-8</a>', '2018-01-05 17:34:23', '1', NULL, 0),
(32, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/9\"> Account-9</a>', '2018-01-05 17:35:04', '1', NULL, 0),
(33, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/10\"> Account-10</a>', '2018-01-05 17:36:01', '1', NULL, 0),
(34, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/11\"> Account-11</a>', '2018-01-05 17:37:25', '1', NULL, 0),
(35, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/12\"> Account-12</a>', '2018-01-05 17:38:44', '1', NULL, 0),
(36, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/13\"> Account-13</a>', '2018-01-05 17:39:19', '1', NULL, 0),
(37, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/14\"> Account-14</a>', '2018-01-05 17:40:25', '1', NULL, 0),
(38, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/15\"> Account-15</a>', '2018-01-05 17:41:43', '1', NULL, 0),
(39, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Account-15', '2018-01-05 17:44:10', '1', NULL, 0),
(40, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/16\"> Account-16</a>', '2018-01-05 17:44:20', '1', NULL, 0),
(41, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Account-16', '2018-01-05 17:45:27', '1', NULL, 0),
(42, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/17\"> Account-17</a>', '2018-01-05 17:45:33', '1', NULL, 0),
(43, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Account-17', '2018-01-05 17:45:41', '1', NULL, 0),
(44, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new account <a href=\"accounts/account/18\"> Account-18</a>', '2018-01-05 17:45:49', '1', NULL, 0),
(45, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Account-18', '2018-01-05 17:45:59', '1', NULL, 0),
(47, '<a href=\"staff/staffmember/\"> Lance Bogrol</a> deleted Customer-18', '2018-01-05 20:14:53', '1', NULL, 0),
(49, '<a href=\"staff/staffmember/\"> Lance Bogrol</a> deleted Customer-19', '2018-01-05 20:18:34', '1', NULL, 0),
(50, 'Lance Bogrol added new task', '2018-01-05 20:58:49', '1', NULL, 1),
(51, 'Lance Bogrol added new task', '2018-01-05 20:58:55', '1', NULL, 1),
(52, 'Lance Bogrol added new task', '2018-01-05 21:01:47', '1', NULL, 1),
(53, 'Lance Bogrol added new task', '2018-01-05 21:05:47', '1', NULL, 1),
(54, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/1\">EXP-1</a>.', '2018-01-05 21:22:56', '1', 17, 0),
(55, 'Lance Bogrol added new member to project', '2018-01-05 21:52:44', '1', NULL, 1),
(56, 'Lance Bogrol added new member to project', '2018-01-05 22:00:53', '1', NULL, 1),
(57, 'Lance Bogrol added new member to project', '2018-01-05 22:02:33', '1', NULL, 1),
(58, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/6\">INV-6</a>.', '2018-01-06 00:46:19', '1', 12, 0),
(59, 'Lance Bogrol deleted TICKET-34.', '2018-01-06 02:16:26', '1', NULL, 0),
(60, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-06 02:47:34', '1', NULL, 0),
(61, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-06 02:49:44', '1', NULL, 0),
(62, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-06 02:51:40', '1', NULL, 0),
(63, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-06 02:52:56', '1', NULL, 0),
(64, 'Lance Bogrol added new member to project', '2018-01-06 03:54:02', '1', NULL, 1),
(65, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/2\">EXP-2</a>.', '2018-01-06 04:23:07', '1', 17, 0),
(66, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/3\">EXP-3</a>.', '2018-01-06 04:26:16', '1', 0, 0),
(67, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted EXP-2', '2018-01-06 05:54:30', '1', NULL, 0),
(68, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"expenses/receipt/1\">EXP-1</a>.', '2018-01-06 06:03:27', '1', 17, 0),
(69, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"expenses/receipt/3\">EXP-3</a>.', '2018-01-06 06:04:51', '1', 0, 0),
(70, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-06 19:15:07', '1', NULL, 0),
(71, 'Lance Bogrol added new member to project', '2018-01-06 19:24:23', '1', NULL, 1),
(72, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:27:48', '1', NULL, 0),
(73, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:28:33', '1', NULL, 0),
(74, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:28:53', '1', NULL, 0),
(75, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:29:18', '1', NULL, 0),
(76, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:29:33', '1', NULL, 0),
(77, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-06 19:30:06', '1', NULL, 0),
(78, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-07 03:32:40', '1', NULL, 0),
(79, 'Lance Bogrol added new member to project', '2018-01-07 04:20:10', '1', NULL, 1),
(80, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:24:01', '1', NULL, 0),
(81, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:25:26', '1', NULL, 0),
(82, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:34:26', '1', NULL, 0),
(83, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:36:07', '1', NULL, 0),
(84, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:39:04', '1', NULL, 0),
(85, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:40:22', '1', NULL, 0),
(86, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:42:00', '1', NULL, 0),
(87, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:42:44', '1', NULL, 0),
(88, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:43:09', '1', NULL, 0),
(89, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 04:51:41', '1', NULL, 0),
(90, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:20:49', '1', NULL, 0),
(91, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:21:24', '1', NULL, 0),
(92, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:22:19', '1', NULL, 0),
(93, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:26:40', '1', NULL, 0),
(94, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:35:52', '1', NULL, 0),
(95, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 06:38:03', '1', NULL, 0),
(96, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:34:49', '1', NULL, 0),
(97, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:37:41', '1', NULL, 0),
(98, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:41:28', '1', NULL, 0),
(99, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:42:06', '1', NULL, 0),
(100, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:43:04', '1', NULL, 0),
(101, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:43:40', '1', NULL, 0),
(102, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:44:45', '1', NULL, 0),
(103, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:48:22', '1', NULL, 0),
(104, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:49:15', '1', NULL, 0),
(105, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:50:25', '1', NULL, 0),
(106, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:51:25', '1', NULL, 0),
(107, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:52:25', '1', NULL, 0),
(108, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:53:47', '1', NULL, 0),
(109, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:55:08', '1', NULL, 0),
(110, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 07:58:13', '1', NULL, 0),
(111, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 08:00:18', '1', NULL, 0),
(112, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-07 16:13:59', '1', NULL, 0),
(113, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 16:58:30', '1', NULL, 0),
(114, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 16:59:52', '1', NULL, 0),
(115, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/\"> TICKET-</a>', '2018-01-07 17:01:43', '1', NULL, 0),
(116, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/4\"> TICKET-4</a>', '2018-01-07 17:02:45', '1', NULL, 0),
(117, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/4\"> TICKET-4</a>', '2018-01-07 17:04:14', '1', NULL, 0),
(118, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/4\"> TICKET-4</a>', '2018-01-07 17:12:14', '1', NULL, 0),
(119, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-07 17:29:00', '1', NULL, 0),
(120, 'Lance Bogrol created new project', '2018-01-07 19:21:54', '1', NULL, 3),
(121, 'Lance Bogrol added new milestone', '2018-01-07 19:22:45', '1', NULL, 3),
(122, 'Lance Bogrol added new task', '2018-01-07 19:40:28', '1', NULL, 3),
(123, 'Lance Bogrol added new task', '2018-01-07 19:48:21', '1', NULL, 3),
(124, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:06:58', '1', NULL, 0),
(125, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:07:20', '1', NULL, 0),
(126, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:10:22', '1', NULL, 0),
(127, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:13:07', '1', NULL, 0),
(128, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:13:16', '1', NULL, 0),
(129, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:13:24', '1', NULL, 0),
(130, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:15:36', '1', NULL, 0),
(131, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:17:41', '1', NULL, 0),
(132, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:18:05', '1', NULL, 0),
(133, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:18:20', '1', NULL, 0),
(134, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/8\">Task-8</a>.', '2018-01-07 20:18:40', '1', NULL, 0),
(135, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-07 20:22:35', '1', NULL, 0),
(136, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-01-07 20:22:55', '1', NULL, 0),
(137, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/2\">Task-2</a>.', '2018-01-07 20:41:47', '1', NULL, 0),
(138, 'Lance Bogrol added new task', '2018-01-07 22:11:14', '1', NULL, 3),
(139, 'Lance Bogrol added new task', '2018-01-07 22:12:29', '1', NULL, 3),
(140, 'Lance Bogrol added new task', '2018-01-07 22:18:23', '1', NULL, 1),
(141, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 00:46:22', '1', NULL, 0),
(142, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 02:11:51', '1', NULL, 0),
(143, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 02:30:59', '1', NULL, 0),
(144, 'Lance Bogrol added new member to project', '2018-01-08 03:44:14', '1', NULL, 1),
(145, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:08:28', '1', NULL, 0),
(146, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:16:21', '1', NULL, 0),
(147, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:17:54', '1', NULL, 0),
(148, 'Lance Bogrol changed Lance Bogrol\'s login password.', '2018-01-08 04:41:28', '1', NULL, 0),
(149, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:42:35', '1', NULL, 0),
(150, 'Lance Bogrol changed Lance Bogrol\'s login password.', '2018-01-08 04:42:52', '1', NULL, 0),
(151, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:42:59', '1', NULL, 0),
(152, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:43:14', '1', NULL, 0),
(153, 'Lance Bogrol changed Lance Bogrol\'s login password.', '2018-01-08 04:43:25', '1', NULL, 0),
(154, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 04:43:30', '1', NULL, 0),
(155, 'Lance Bogrol added  staff.', '2018-01-08 07:16:49', '1', NULL, 0),
(156, 'Lance Bogrol added  staff.', '2018-01-08 07:20:19', '1', NULL, 0),
(157, 'Lance Bogrol added new member to project', '2018-01-08 07:35:50', '1', NULL, 3),
(158, 'Lance Bogrol added new member to project', '2018-01-08 07:35:55', '1', NULL, 3),
(159, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-08 17:26:33', '1', NULL, 0),
(160, 'Lance Bogrol added new member to project', '2018-01-08 17:27:51', '1', NULL, 1),
(161, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-09 01:17:48', '1', NULL, 0),
(162, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-09 16:01:27', '1', NULL, 0),
(163, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-09 17:20:39', '1', NULL, 0),
(164, 'Lance Bogrol changed Sue Shei\'s password', '2018-01-09 17:21:06', '1', 17, 0),
(165, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-09 17:23:38', '1', NULL, 0),
(166, 'Lance Bogrol deleted TICKET-37.', '2018-01-09 19:48:33', '1', NULL, 0),
(167, 'Lance Bogrol deleted TICKET-7.', '2018-01-09 19:48:59', '1', NULL, 0),
(168, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/5\"> TICKET-5</a>', '2018-01-09 20:55:34', '1', NULL, 0),
(169, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-09 23:12:30', '1', NULL, 0),
(170, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"tickets/ticket/38\"> 38</a>', '2018-01-10 00:45:18', '1', NULL, 0),
(171, 'Sue Shei added <a href=\"tickets/ticket/39\"> TICKET-39</a>', '2018-01-10 01:58:04', NULL, 23, 0),
(172, 'Lance Bogrol deleted TICKET-39.', '2018-01-10 01:58:34', '1', NULL, 0),
(173, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/2\">PRO-2</a>.', '2018-01-10 02:16:48', '1', NULL, 0),
(174, 'Lance Bogrol added  staff.', '2018-01-10 02:32:48', '1', NULL, 0),
(175, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Staff-5', '2018-01-10 02:33:12', '1', NULL, 0),
(176, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-09 15:02:43', '1', NULL, 0),
(177, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:06:04', '1', NULL, 0),
(178, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:28:34', '1', NULL, 0),
(179, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:29:02', '1', NULL, 0),
(180, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:29:07', '1', NULL, 0),
(181, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:29:19', '1', NULL, 0),
(182, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:29:45', '1', NULL, 0),
(183, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:31:03', '1', NULL, 0),
(184, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:31:50', '1', NULL, 0),
(185, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:32:04', '1', NULL, 0),
(186, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:32:46', '1', NULL, 0),
(187, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:32:55', '1', NULL, 0),
(188, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:33:19', '1', NULL, 0),
(189, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:36:28', '1', NULL, 0),
(190, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:36:53', '1', NULL, 0),
(191, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-10 04:37:32', '1', NULL, 0),
(192, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-10 04:56:22', '1', NULL, 0),
(193, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-10 19:50:16', '1', NULL, 0),
(194, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-4', '2018-01-10 22:36:39', '1', NULL, 0),
(195, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/3\"> Product3</a>', '2018-01-11 00:44:29', '1', NULL, 0),
(196, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/3\"> Product3</a>', '2018-01-11 00:44:48', '1', NULL, 0),
(197, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/3\"> Product3</a>', '2018-01-11 00:49:21', '1', NULL, 0),
(198, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/5\"> Product5</a>', '2018-01-11 01:15:45', '1', NULL, 0),
(199, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/5\"> Product5</a>', '2018-01-11 01:16:54', '1', NULL, 0),
(200, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/3\">PRO-3</a>.', '2018-01-11 01:23:24', '1', NULL, 0),
(201, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/4\">PRO-4</a>.', '2018-01-11 01:24:47', '1', NULL, 0),
(202, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/5\">PRO-5</a>.', '2018-01-11 01:25:39', '1', NULL, 0),
(203, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"proposals/proposal/5\">PRO-5</a>.', '2018-01-11 01:26:24', '1', 0, 0),
(204, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-11 02:16:09', '1', NULL, 0),
(205, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 00:42:28', '1', NULL, 0),
(206, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-16 00:42:42', '2', NULL, 0),
(207, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 00:42:57', '1', NULL, 0),
(208, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 20:07:07', '1', NULL, 0),
(209, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-16 20:08:38', '2', NULL, 0),
(210, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 20:09:32', '1', NULL, 0),
(211, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 20:11:17', '1', NULL, 0),
(212, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 20:12:42', '1', NULL, 0),
(213, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-16 20:14:20', '1', NULL, 0),
(214, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 01:51:56', '1', NULL, 0),
(215, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/6\"> Product6</a>', '2018-01-17 01:53:13', '1', NULL, 0),
(216, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 02:52:07', '1', NULL, 0),
(217, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-17 02:52:17', '2', NULL, 0),
(218, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 02:52:24', '1', NULL, 0),
(219, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/6\">INV-6</a>.', '2018-01-17 02:59:23', '1', 12, 0),
(220, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 05:59:27', '1', NULL, 0),
(221, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 05:59:44', '1', NULL, 0),
(222, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:05:21', '1', NULL, 0),
(223, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:06:32', '1', NULL, 0),
(224, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:06:50', '1', NULL, 0),
(225, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:08:47', '1', NULL, 0),
(226, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:10:44', '1', NULL, 0),
(227, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:11:54', '1', NULL, 0),
(228, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:12:13', '1', NULL, 0),
(229, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:13:26', '1', NULL, 0),
(230, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:19:26', '1', NULL, 0),
(231, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:27:10', '1', NULL, 0),
(232, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:28:06', '1', NULL, 0),
(233, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:31:14', '1', NULL, 0),
(234, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:32:34', '1', NULL, 0),
(235, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:33:31', '1', NULL, 0),
(236, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:36:13', '1', NULL, 0),
(237, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:36:36', '1', NULL, 0),
(238, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:41:15', '1', NULL, 0),
(239, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:46:04', '1', NULL, 0),
(240, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:53:33', '1', NULL, 0),
(241, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 06:59:57', '1', NULL, 0),
(242, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 07:03:23', '1', NULL, 0),
(243, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 07:04:45', '1', NULL, 0),
(244, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 07:37:15', '1', NULL, 0),
(245, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 08:26:55', '1', NULL, 0),
(246, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 08:27:06', '1', NULL, 0),
(247, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:09:53', '1', NULL, 0),
(248, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:12:07', '1', NULL, 0),
(249, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:14:23', '1', NULL, 0),
(250, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:21:33', '1', NULL, 0),
(251, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:22:31', '1', NULL, 0),
(252, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:24:29', '1', NULL, 0),
(253, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:24:55', '1', NULL, 0),
(254, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:25:22', '1', NULL, 0),
(255, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:26:14', '1', NULL, 0),
(256, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:27:03', '1', NULL, 0),
(257, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:28:01', '1', NULL, 0),
(258, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:29:56', '1', NULL, 0),
(259, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:32:16', '1', NULL, 0),
(260, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 09:33:27', '1', NULL, 0),
(261, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 19:55:42', '1', NULL, 0),
(262, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 20:13:10', '1', NULL, 0),
(263, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 20:18:57', '1', NULL, 0),
(264, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 20:19:38', '1', NULL, 0),
(265, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 20:22:06', '1', NULL, 0),
(266, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 20:51:23', '1', NULL, 0),
(267, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:22:11', '1', NULL, 0),
(268, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:30:11', '1', NULL, 0),
(269, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:33:26', '1', NULL, 0),
(270, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:33:44', '1', NULL, 0),
(271, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:33:54', '1', NULL, 0),
(272, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:34:24', '1', NULL, 0),
(273, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:34:30', '1', NULL, 0),
(274, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:35:08', '1', NULL, 0),
(275, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 21:45:05', '1', NULL, 0),
(276, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 22:36:15', '1', NULL, 0),
(277, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 22:51:48', '1', NULL, 0),
(278, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 22:52:16', '1', NULL, 0),
(279, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 23:00:28', '1', NULL, 0),
(280, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 23:08:34', '1', NULL, 0),
(281, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-17 23:14:52', '2', NULL, 0),
(282, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 23:21:27', '1', NULL, 0),
(283, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 23:21:37', '1', NULL, 0),
(284, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-17 23:21:55', '1', NULL, 0),
(285, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-17 23:56:57', '1', NULL, 0),
(286, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-17 23:57:14', '1', NULL, 0),
(287, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 00:00:56', '1', NULL, 0),
(288, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-18 00:35:37', '2', NULL, 0),
(289, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 00:36:14', '1', NULL, 0),
(290, '<a href=\"staff/staffmember/4\"> Ruby Von Rails</a> logged in the system', '2018-01-18 00:38:53', '4', NULL, 0),
(291, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 00:51:31', '1', NULL, 0),
(292, '<a href=\"staff/staffmember/3\"> Guy Mann</a> logged in the system', '2018-01-18 00:52:45', '3', NULL, 0),
(293, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 00:53:58', '1', NULL, 0),
(294, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 00:55:26', '1', NULL, 0),
(295, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 00:56:13', '1', NULL, 0),
(296, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 00:56:51', '1', NULL, 0),
(297, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> tarafından ayarlar güncellendi', '2018-01-18 01:04:10', '1', NULL, 0),
(298, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 01:06:05', '1', NULL, 0),
(299, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 01:06:43', '1', NULL, 0),
(300, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 02:16:47', '1', NULL, 0),
(301, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 02:17:20', '1', NULL, 0),
(302, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 03:19:46', '1', NULL, 0),
(303, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:23:58', '1', NULL, 0),
(304, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:36:47', '1', NULL, 0),
(305, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:37:05', '1', NULL, 0),
(306, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> Einstellungen aktualisiert', '2018-01-18 03:38:28', '1', NULL, 0),
(307, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:38:32', '1', NULL, 0),
(308, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:39:09', '1', NULL, 0),
(309, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 03:40:30', '1', NULL, 0),
(310, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 06:38:10', '1', NULL, 0),
(311, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 06:38:19', '1', NULL, 0),
(312, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 06:38:32', '1', NULL, 0),
(313, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 06:38:41', '1', NULL, 0),
(314, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 06:38:44', '1', NULL, 0),
(315, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 06:44:07', '1', NULL, 0),
(316, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 07:08:06', '1', NULL, 0),
(317, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 07:09:59', '1', NULL, 0),
(318, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 07:10:41', '1', NULL, 0),
(319, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 07:11:39', '1', NULL, 0),
(320, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 07:12:30', '1', NULL, 0),
(321, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 07:12:35', '1', NULL, 0),
(322, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 08:55:56', '1', NULL, 0),
(323, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 16:47:39', '1', NULL, 0),
(324, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 16:56:12', '1', NULL, 0),
(325, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 16:56:36', '1', NULL, 0),
(326, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-18 16:56:52', '1', NULL, 0),
(327, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 16:58:52', '1', NULL, 0),
(328, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-18 17:01:35', '1', NULL, 0),
(329, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-18 17:05:09', '2', NULL, 0),
(330, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 00:14:22', '1', NULL, 0),
(331, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 01:52:59', '1', NULL, 0),
(332, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 01:53:21', '1', NULL, 0),
(333, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 01:58:37', '1', NULL, 0),
(334, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 01:59:07', '1', NULL, 0),
(335, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 02:00:13', '1', NULL, 0),
(336, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 02:00:26', '1', NULL, 0),
(337, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 02:02:39', '1', NULL, 0),
(338, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 02:03:01', '1', NULL, 0),
(339, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 05:45:34', '1', NULL, 0),
(340, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 05:45:49', '1', NULL, 0),
(341, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 05:46:31', '1', NULL, 0),
(342, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 06:35:28', '1', NULL, 0),
(343, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 11:09:31', '1', NULL, 0),
(344, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 22:04:34', '1', NULL, 0),
(345, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 23:27:05', '1', NULL, 0),
(346, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-19 23:27:10', '1', NULL, 0),
(347, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-19 23:27:21', '1', NULL, 0),
(348, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-20 16:11:49', '1', NULL, 0),
(349, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-20 22:52:45', '1', NULL, 0),
(350, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 03:20:25', '1', NULL, 0),
(351, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 15:49:15', '1', NULL, 0),
(352, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 16:21:24', '1', NULL, 0),
(353, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 18:58:43', '1', NULL, 0),
(354, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-21 18:58:57', '1', NULL, 0),
(355, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 18:59:15', '1', NULL, 0),
(356, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-21 18:59:28', '1', NULL, 0),
(357, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-21 19:06:45', '2', NULL, 0),
(358, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-21 19:07:03', '1', NULL, 0),
(359, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-22 11:53:22', '1', NULL, 0),
(360, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-22 19:04:03', '1', NULL, 0),
(361, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/7\">INV-7</a>.', '2018-01-22 19:22:37', '1', 17, 0),
(362, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-22 20:48:41', '1', NULL, 0),
(363, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/7\">INV-7</a>.', '2018-01-22 22:10:56', '1', 17, 0),
(364, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 00:53:31', '1', NULL, 0),
(365, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 07:12:45', '1', NULL, 0),
(366, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 13:21:35', '1', NULL, 0),
(367, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 13:30:45', '1', NULL, 0),
(368, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-23 13:31:15', '1', NULL, 0),
(369, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 13:31:45', '1', NULL, 0),
(370, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-23 13:32:03', '1', NULL, 0),
(371, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 13:32:06', '1', NULL, 0),
(372, 'Sue Shei added <a href=\"tickets/ticket/1\"> TICKET-1</a>', '2018-01-23 14:06:46', NULL, 23, 0),
(373, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/1\"> TICKET-1</a>', '2018-01-23 14:07:45', '1', NULL, 0),
(374, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 15:46:14', '1', NULL, 0),
(375, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-23 15:46:35', '1', NULL, 0),
(376, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"tickets/ticket/2\"> TICKET-2</a>', '2018-01-23 15:47:56', '1', NULL, 0),
(377, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 17:09:45', '1', NULL, 0),
(378, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 17:11:01', '1', NULL, 0),
(379, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> tarafından ayarlar güncellendi', '2018-01-23 17:11:23', '1', NULL, 0),
(380, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-23 17:26:40', '2', NULL, 0),
(381, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 17:26:58', '1', NULL, 0),
(382, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> tarafından ayarlar güncellendi', '2018-01-23 17:32:52', '1', NULL, 0),
(383, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 17:34:45', '1', NULL, 0),
(384, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> tarafından ayarlar güncellendi', '2018-01-23 17:35:14', '1', NULL, 0),
(385, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 17:48:20', '1', NULL, 0),
(386, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-23 20:25:03', '1', NULL, 0),
(387, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-23 20:29:09', '1', NULL, 0),
(388, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-24 02:40:29', '1', NULL, 0),
(389, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-24 16:08:19', '1', NULL, 0),
(390, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-24 18:10:25', '1', NULL, 0),
(391, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-24 18:10:56', '1', NULL, 0),
(392, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-24 22:57:30', '1', NULL, 0),
(393, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-01-24 22:57:42', '1', NULL, 0),
(394, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-25 05:49:40', '1', NULL, 0),
(395, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-25 19:25:27', '1', NULL, 0),
(396, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/4\">EXP-4</a>.', '2018-01-25 19:30:44', '1', 17, 0),
(397, 'Lance Bogrol added new milestone', '2018-01-26 01:12:13', '1', NULL, 1),
(398, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-26 01:34:42', '2', NULL, 0),
(399, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-26 01:34:52', '1', NULL, 0),
(400, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-26 01:43:27', '2', NULL, 0),
(401, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-26 02:29:23', '1', NULL, 0),
(402, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-26 07:32:15', '1', NULL, 0),
(403, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-27 04:13:31', '1', NULL, 0),
(404, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-27 04:15:54', '1', NULL, 0),
(405, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-27 04:29:52', '1', NULL, 0),
(406, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-27 16:06:21', '1', NULL, 0),
(407, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-27 18:19:37', '1', NULL, 0),
(408, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-28 22:30:33', '1', NULL, 0),
(409, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-29 23:33:13', '1', NULL, 0),
(410, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-30 03:55:33', '2', NULL, 0),
(411, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-30 03:57:49', '1', NULL, 0),
(412, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-30 15:16:36', '1', NULL, 0),
(413, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-01-30 18:57:06', '2', NULL, 0),
(414, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-30 18:58:08', '1', NULL, 0),
(415, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-30 22:49:17', '1', NULL, 0),
(416, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-31 01:10:00', '1', NULL, 0),
(417, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-31 06:10:29', '1', NULL, 0),
(418, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-01-31 19:45:10', '1', NULL, 0),
(419, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 00:50:53', '1', NULL, 0),
(420, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:00:55', '1', NULL, 0),
(421, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:23:14', '1', NULL, 0);
INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`) VALUES
(422, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:23:37', '1', NULL, 0),
(423, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:24:20', '1', NULL, 0),
(424, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:24:55', '1', NULL, 0),
(425, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:29:07', '1', NULL, 0),
(426, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:32:50', '1', NULL, 0),
(427, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-02-01 06:33:29', '2', NULL, 0),
(428, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 06:33:42', '1', NULL, 0),
(429, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-02-01 07:02:52', '1', NULL, 0),
(430, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 20:09:51', '1', NULL, 0),
(431, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 21:37:59', '1', NULL, 0),
(432, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 21:38:43', '1', NULL, 0),
(433, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 21:40:44', '1', NULL, 0),
(434, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 21:41:10', '1', NULL, 0),
(435, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-01 21:44:05', '1', NULL, 0),
(436, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-03 01:13:03', '1', NULL, 0),
(437, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-03 13:31:19', '1', NULL, 0),
(438, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-03 23:53:03', '1', NULL, 0),
(439, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-05 02:01:42', '1', NULL, 0),
(440, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-07 01:50:45', '1', NULL, 0),
(441, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-07 12:40:50', '1', NULL, 0),
(442, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-08 01:44:38', '1', NULL, 0),
(443, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-08 01:46:27', '1', NULL, 0),
(444, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-08 01:47:07', '1', NULL, 0),
(445, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-08 01:48:06', '1', NULL, 0),
(446, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-08 21:09:42', '1', NULL, 0),
(447, 'Lance Bogrol added new milestone', '2018-02-08 21:10:30', '1', NULL, 3),
(448, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-09 22:02:43', '1', NULL, 0),
(449, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-10 17:46:22', '1', NULL, 0),
(450, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-10 18:47:12', '1', NULL, 0),
(451, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-10 21:03:58', '1', NULL, 0),
(452, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-11 04:11:56', '1', NULL, 0),
(453, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-12 00:18:02', '1', NULL, 0),
(454, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-12 12:57:23', '1', NULL, 0),
(455, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-13 03:50:30', '1', NULL, 0),
(456, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/8\">INV-8</a>.', '2018-02-13 04:59:34', '1', 6, 0),
(457, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/8\">INV-8</a>.', '2018-02-13 05:02:44', '1', 6, 0),
(458, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/8\">INV-8</a>.', '2018-02-13 05:03:00', '1', 6, 0),
(459, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/8\">INV-8</a>.', '2018-02-13 05:04:12', '1', 6, 0),
(460, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/8\">INV-8</a>.', '2018-02-13 05:05:21', '1', 6, 0),
(461, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-13 11:01:30', '1', NULL, 0),
(462, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/9\">INV-9</a>.', '2018-02-13 11:21:22', '1', 1, 0),
(463, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/9\">INV-9</a>.', '2018-02-13 11:22:05', '1', 1, 0),
(464, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/9\">INV-9</a>.', '2018-02-13 11:22:26', '1', 1, 0),
(465, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-13 11:58:48', '1', NULL, 0),
(466, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-13 15:02:29', '1', NULL, 0),
(467, '<a href=\"#\"> Ciuis CRM Recurring</a>  <a href=\"invoices/invoice/10\">-10</a>.', '2018-02-13 14:49:03', '0', 6, 0),
(468, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/9\">INV-9</a>.', '2018-02-13 16:51:06', '1', 1, 0),
(469, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/9\">INV-9</a>.', '2018-02-13 17:26:44', '1', 1, 0),
(470, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-14 01:05:09', '1', NULL, 0),
(471, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-14 14:48:07', '1', NULL, 0),
(472, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-15 02:33:17', '1', NULL, 0),
(473, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-21 00:23:43', '1', NULL, 0),
(474, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-22 21:49:02', '1', NULL, 0),
(475, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-24 13:23:17', '1', NULL, 0),
(476, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-24 21:06:15', '1', NULL, 0),
(477, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-27 18:14:54', '1', NULL, 0),
(478, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-28 03:38:58', '1', NULL, 0),
(479, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-02-28 18:06:09', '1', NULL, 0),
(480, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-01 14:34:37', '1', NULL, 0),
(481, 'Lance Bogrol added new member to project', '2018-03-01 14:37:17', '1', NULL, 3),
(482, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-01 14:38:29', '1', NULL, 0),
(483, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-02 17:02:20', '1', NULL, 0),
(484, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-02 22:23:46', '1', NULL, 0),
(485, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-05 14:23:05', '1', NULL, 0),
(486, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-05 18:35:26', '1', NULL, 0),
(487, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-05 18:45:16', '1', NULL, 0),
(488, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-05 23:46:41', '1', NULL, 0),
(489, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-06 00:58:45', '1', NULL, 0),
(490, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-06 01:55:05', '1', NULL, 0),
(491, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-06 16:43:04', '1', NULL, 0),
(492, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-06 20:47:33', '1', NULL, 0),
(493, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-07 15:29:25', '1', NULL, 0),
(494, 'Lance Bogrol added Oeko okep contacts.', '2018-03-07 18:56:10', '1', 17, 0),
(495, 'Lance Bogrol added adfasf afdsa contacts.', '2018-03-07 18:57:13', '1', 17, 0),
(496, 'Lance Bogrol added test es contacts.', '2018-03-07 18:58:57', '1', 17, 0),
(497, 'Lance Bogrol added dsafaf afdsa contacts.', '2018-03-07 19:00:23', '1', 17, 0),
(498, 'Lance Bogrol added dana afdsa contacts.', '2018-03-07 19:00:45', '1', 17, 0),
(499, 'Lance Bogrol added Kezban Doruk contacts.', '2018-03-07 19:01:32', '1', 17, 0),
(500, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-07 19:40:32', '1', NULL, 0),
(501, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-08 02:29:13', '1', NULL, 0),
(502, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-09 00:36:53', '1', NULL, 0),
(503, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/11\">INV-11</a>.', '2018-03-09 04:13:21', '1', 17, 0),
(504, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-09 16:08:06', '1', NULL, 0),
(505, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-09 19:34:37', '1', NULL, 0),
(506, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-03-09 19:39:36', '2', NULL, 0),
(507, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-09 19:40:00', '1', NULL, 0),
(508, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-10 17:48:30', '1', NULL, 0),
(509, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-10 19:20:00', '1', NULL, 0),
(510, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-10 20:02:37', '1', NULL, 0),
(511, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-11 16:25:25', '1', NULL, 0),
(512, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-12 23:31:59', '1', NULL, 0),
(513, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-13 03:04:42', '1', NULL, 0),
(514, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-16 15:35:04', '1', NULL, 0),
(515, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-16 19:17:05', '1', NULL, 0),
(516, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-17 01:52:38', '1', NULL, 0),
(517, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-18 01:57:52', '1', NULL, 0),
(518, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-18 04:13:47', '1', NULL, 0),
(519, 'Lance Bogrol added new member to project', '2018-03-18 04:47:17', '1', NULL, 1),
(520, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-19 01:43:26', '1', NULL, 0),
(521, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-19 20:56:24', '1', NULL, 0),
(522, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-20 18:52:48', '1', NULL, 0),
(523, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-20 23:19:35', '1', NULL, 0),
(524, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-21 01:50:46', '1', NULL, 0),
(525, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-21 15:38:15', '1', NULL, 0),
(526, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-03-21 16:31:44', '2', NULL, 0),
(527, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-21 16:32:42', '1', NULL, 0),
(528, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-21 19:05:14', '1', NULL, 0),
(529, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a customer <a href=\"customers/customer/18\">Customer-18</a>', '2018-03-21 19:07:59', '1', NULL, 0),
(530, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-22 18:22:44', '1', NULL, 0),
(531, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-22 22:23:10', '1', NULL, 0),
(532, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-23 01:32:14', '1', NULL, 0),
(533, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-23 18:28:16', '1', NULL, 0),
(534, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a customer <a href=\"customers/customer/19\">Customer-19</a>', '2018-03-23 19:39:02', '1', NULL, 0),
(535, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-25 13:42:45', '1', NULL, 0),
(536, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-25 16:40:23', '1', NULL, 0),
(537, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/12\">INV-12</a>.', '2018-03-25 18:51:33', '1', 17, 0),
(538, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-26 18:33:47', '1', NULL, 0),
(539, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-26 18:37:18', '1', NULL, 0),
(540, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-28 00:45:11', '1', NULL, 0),
(541, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-29 19:28:09', '1', NULL, 0),
(542, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-03-31 01:37:19', '1', NULL, 0),
(543, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-01 20:37:51', '1', NULL, 0),
(544, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-02 12:58:12', '1', NULL, 0),
(545, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-04 00:50:53', '1', NULL, 0),
(546, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-04 18:43:49', '1', NULL, 0),
(547, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-05 03:53:27', '1', NULL, 0),
(548, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-05 19:12:23', '1', NULL, 0),
(549, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-05 22:40:41', '1', NULL, 0),
(550, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-06 00:52:02', '1', NULL, 0),
(551, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-06 17:16:51', '1', NULL, 0),
(552, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-06 21:51:10', '1', NULL, 0),
(553, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-07 16:51:45', '1', NULL, 0),
(554, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-08 14:35:56', '1', NULL, 0),
(555, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-08 16:50:24', '1', NULL, 0),
(556, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:41:06', '1', 16, 0),
(557, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:42:05', '1', 16, 0),
(558, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:42:52', '1', 16, 0),
(559, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:52:09', '1', 16, 0),
(560, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:53:31', '1', 16, 0),
(561, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:55:20', '1', 16, 0),
(562, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:56:41', '1', 16, 0),
(563, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 17:57:25', '1', 16, 0),
(564, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 18:00:00', '1', 16, 0),
(565, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 18:05:18', '1', 16, 0),
(566, 'Lance Bogrol changed Michel Kworks\'s password', '2018-04-08 18:11:07', '1', 16, 0),
(567, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-04-08 19:08:00', '2', NULL, 0),
(568, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-09 03:55:31', '1', NULL, 0),
(569, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-09 16:55:56', '1', NULL, 0),
(570, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 02:12:35', '1', NULL, 0),
(571, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 05:20:09', '1', NULL, 0),
(572, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 10:08:09', '1', NULL, 0),
(573, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 17:48:09', '1', NULL, 0),
(574, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 18:42:34', '1', NULL, 0),
(575, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 18:44:46', '1', NULL, 0),
(576, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 19:22:44', '1', NULL, 0),
(577, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 23:25:10', '1', NULL, 0),
(578, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 23:28:19', '1', NULL, 0),
(579, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-10 23:46:00', '1', NULL, 0),
(580, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 00:21:44', '1', NULL, 0),
(581, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 04:53:23', '1', NULL, 0),
(582, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 15:03:45', '1', NULL, 0),
(583, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 16:01:36', '1', NULL, 0),
(584, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 18:28:55', '1', NULL, 0),
(585, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 20:52:36', '1', NULL, 0),
(586, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 21:34:11', '1', NULL, 0),
(587, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 21:34:30', '1', NULL, 0),
(588, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-11 22:03:23', '1', NULL, 0),
(589, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/5\">EXP-5</a>.', '2018-04-11 22:07:27', '1', 0, 0),
(590, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/6\">EXP-6</a>.', '2018-04-11 22:30:10', '1', 17, 0),
(591, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted EXP-6', '2018-04-11 22:31:35', '1', NULL, 0),
(592, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/7\">EXP-7</a>.', '2018-04-11 22:32:14', '1', 16, 0),
(593, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/10\">INV-10</a>.', '2018-04-11 22:38:59', '1', 6, 0),
(594, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/7\">INV-7</a>.', '2018-04-11 22:39:32', '1', 17, 0),
(595, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-12 04:04:31', '1', NULL, 0),
(596, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-12 04:05:32', '1', NULL, 0),
(597, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-12 04:06:03', '1', NULL, 0),
(598, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-13 00:37:38', '1', NULL, 0),
(599, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 01:39:26', '1', NULL, 0),
(600, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-14 03:59:24', '1', NULL, 0),
(601, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-14 03:59:47', '1', NULL, 0),
(602, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 07:40:33', '1', NULL, 0),
(603, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 10:40:06', '1', NULL, 0),
(604, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 13:03:18', '1', NULL, 0),
(605, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-14 13:23:12', '1', NULL, 0),
(606, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 19:56:15', '1', NULL, 0),
(607, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-14 23:10:41', '1', NULL, 0),
(608, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-15 15:20:20', '1', NULL, 0),
(609, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-15 17:46:31', '1', NULL, 0),
(610, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-15 17:53:24', '1', NULL, 0),
(611, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-15 22:22:50', '1', NULL, 0),
(612, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-16 03:48:59', '1', NULL, 0),
(613, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-20 01:23:37', '1', NULL, 0),
(614, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-20 01:25:55', '1', NULL, 0),
(615, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-20 15:19:52', '1', NULL, 0),
(616, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-22 02:58:19', '1', NULL, 0),
(617, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/13\">INV-13</a>.', '2018-04-22 06:45:56', '1', 18, 0),
(618, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-22 17:10:21', '1', NULL, 0),
(619, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-22 18:02:33', '1', NULL, 0),
(620, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-22 19:47:25', '1', NULL, 0),
(621, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-22 23:27:07', '1', NULL, 0),
(622, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-04-22 23:59:05', '2', NULL, 0),
(623, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-23 00:00:17', '1', NULL, 0),
(624, '<a href=\"staff/staffmember/2\"> Emma Durst</a> logged in the system', '2018-04-23 00:00:49', '2', NULL, 0),
(625, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-23 00:03:29', '1', NULL, 0),
(626, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 01:01:21', '1', NULL, 0),
(627, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 01:13:43', '1', NULL, 0),
(628, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 01:18:37', '1', NULL, 0),
(629, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 01:31:28', '1', NULL, 0),
(630, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-23 02:00:53', '1', NULL, 0),
(631, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 02:03:52', '1', NULL, 0),
(632, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 02:04:34', '1', NULL, 0),
(633, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 02:04:42', '1', NULL, 0),
(634, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-04-23 02:34:53', '1', NULL, 0),
(635, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-24 16:34:09', '1', NULL, 0),
(636, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-25 02:21:07', '1', NULL, 0),
(637, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-25 19:02:19', '1', NULL, 0),
(638, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-28 05:25:00', '1', NULL, 0),
(639, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-28 07:32:50', '1', NULL, 0),
(640, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-04-29 21:14:02', '1', NULL, 0),
(641, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-04 06:39:23', '1', NULL, 0),
(642, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-04 14:18:01', '1', NULL, 0),
(643, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-04 17:36:37', '1', NULL, 0),
(644, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-05 15:20:46', '1', NULL, 0),
(645, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-05 15:29:34', '1', NULL, 0),
(646, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-08 09:34:03', '1', NULL, 0),
(647, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:35:13', '1', NULL, 0),
(648, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:36:20', '1', NULL, 0),
(649, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:36:32', '1', NULL, 0),
(650, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:36:42', '1', NULL, 0),
(651, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:36:52', '1', NULL, 0),
(652, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:38:22', '1', NULL, 0),
(653, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:38:50', '1', NULL, 0),
(654, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:39:00', '1', NULL, 0),
(655, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:39:10', '1', NULL, 0),
(656, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:39:24', '1', NULL, 0),
(657, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:39:33', '1', NULL, 0),
(658, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:40:42', '1', NULL, 0),
(659, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:41:00', '1', NULL, 0),
(660, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:41:29', '1', NULL, 0),
(661, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:42:26', '1', NULL, 0),
(662, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:42:37', '1', NULL, 0),
(663, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:42:46', '1', NULL, 0),
(664, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:42:56', '1', NULL, 0),
(665, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:43:08', '1', NULL, 0),
(666, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:43:17', '1', NULL, 0),
(667, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:43:33', '1', NULL, 0),
(668, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:43:41', '1', NULL, 0),
(669, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:43:51', '1', NULL, 0),
(670, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:04', '1', NULL, 0),
(671, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:15', '1', NULL, 0),
(672, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:24', '1', NULL, 0),
(673, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:40', '1', NULL, 0),
(674, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:48', '1', NULL, 0),
(675, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:44:57', '1', NULL, 0),
(676, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:45:13', '1', NULL, 0),
(677, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:45:54', '1', NULL, 0),
(678, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:46:18', '1', NULL, 0),
(679, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:46:34', '1', NULL, 0),
(680, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:47:19', '1', NULL, 0),
(681, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:47:57', '1', NULL, 0),
(682, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:48:11', '1', NULL, 0),
(683, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:48:20', '1', NULL, 0),
(684, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:50:00', '1', NULL, 0),
(685, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:51:52', '1', NULL, 0),
(686, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:52:25', '1', NULL, 0),
(687, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:52:38', '1', NULL, 0),
(688, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:54:42', '1', NULL, 0),
(689, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:54:54', '1', NULL, 0),
(690, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 09:57:44', '1', NULL, 0),
(691, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:02:42', '1', NULL, 0),
(692, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:02:58', '1', NULL, 0),
(693, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:03:23', '1', NULL, 0),
(694, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:03:49', '1', NULL, 0),
(695, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:03:58', '1', NULL, 0),
(696, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:04:07', '1', NULL, 0),
(697, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:04:16', '1', NULL, 0),
(698, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:07:05', '1', NULL, 0),
(699, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:07:16', '1', NULL, 0),
(700, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:07:24', '1', NULL, 0),
(701, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:09:10', '1', NULL, 0),
(702, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:10:39', '1', NULL, 0),
(703, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:11:03', '1', NULL, 0),
(704, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:13:23', '1', NULL, 0),
(705, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:13:31', '1', NULL, 0),
(706, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:14:32', '1', NULL, 0),
(707, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:17:16', '1', NULL, 0),
(708, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:17:31', '1', NULL, 0),
(709, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:18:05', '1', NULL, 0),
(710, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-05-08 10:18:30', '1', NULL, 0),
(711, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-10 01:12:44', '1', NULL, 0),
(712, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-10 01:13:59', '1', NULL, 0),
(713, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-10 17:29:06', '1', NULL, 0),
(714, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-14 01:52:11', '1', NULL, 0),
(715, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-14 23:41:08', '1', NULL, 0),
(716, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-15 03:27:11', '1', NULL, 0),
(717, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-15 16:30:45', '1', NULL, 0),
(718, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-19 17:38:46', '1', NULL, 0),
(719, 'Lance Bogrol added  staff.', '2018-05-19 18:26:23', '1', NULL, 0),
(720, 'Lance Bogrol added  staff.', '2018-05-19 18:29:10', '1', NULL, 0),
(721, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-19 20:31:24', '1', NULL, 0),
(722, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/5\">INV-5</a>.', '2018-05-19 23:32:00', '1', 16, 0),
(723, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-20 15:37:00', '1', NULL, 0),
(724, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/8\">EXP-8</a>.', '2018-05-20 17:29:39', '1', 18, 0),
(725, 'Lance Bogrol converted EXP-8 to invoice', '2018-05-20 17:42:07', '1', 18, 0),
(726, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-20 23:07:07', '1', NULL, 0),
(727, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-23 21:17:33', '1', NULL, 0),
(728, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-27 18:17:39', '1', NULL, 0),
(729, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-31 16:27:02', '1', NULL, 0),
(730, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-05-31 17:40:13', '1', NULL, 0),
(731, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-01 17:06:28', '1', NULL, 0),
(732, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/9\">EXP-9</a>.', '2018-06-01 19:29:37', '1', 0, 0),
(733, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Staff-5', '2018-06-01 20:35:45', '1', NULL, 0),
(734, 'Lance Bogrol created new project', '2018-06-01 20:48:24', '1', NULL, 1),
(735, 'Lance Bogrol added new task', '2018-06-01 20:51:46', '1', NULL, 1),
(736, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-06-01 20:52:09', '1', NULL, 0),
(737, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-06-01 21:01:34', '1', NULL, 0),
(738, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-02 03:33:42', '1', NULL, 0),
(739, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-12 03:04:02', '1', NULL, 0),
(740, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-12 15:57:55', '1', NULL, 0),
(741, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-12 16:43:36', '1', NULL, 0),
(742, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-12 22:35:11', '1', NULL, 0),
(743, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-13 05:55:08', '1', NULL, 0),
(744, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-13 16:58:45', '1', NULL, 0),
(745, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-13 19:23:16', '1', NULL, 0),
(746, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-17 14:28:37', '1', NULL, 0),
(747, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-18 00:34:58', '1', NULL, 0),
(748, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-20 15:44:17', '1', NULL, 0),
(749, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/16\">INV-16</a>.', '2018-06-20 16:44:43', '1', 12, 0),
(750, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/16\">INV-16</a>.', '2018-06-20 16:45:32', '1', 12, 0),
(751, 'Lance Bogrol added new member to project', '2018-06-20 16:57:14', '1', NULL, 1),
(752, 'Lance Bogrol added new member to project', '2018-06-20 16:57:19', '1', NULL, 1),
(753, 'Lance Bogrol added new member to project', '2018-06-20 16:57:23', '1', NULL, 1),
(754, 'Lance Bogrol added new member to project', '2018-06-20 16:57:27', '1', NULL, 1),
(755, 'Sue Shei added <a href=\"tickets/ticket/3\"> TICKET-3</a>', '2018-06-20 19:23:49', NULL, 23, 0),
(756, 'Lance Bogrol updated project.', '2018-06-20 19:42:36', '1', NULL, 1),
(757, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-06-20 19:42:52', '1', NULL, 0),
(758, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-20 20:24:35', '1', NULL, 0),
(759, 'Lance Bogrol updated project.', '2018-06-20 20:26:59', '1', NULL, 1),
(760, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-25 15:58:19', '1', NULL, 0),
(761, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-25 16:34:07', '1', NULL, 0),
(762, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-28 14:50:37', '1', NULL, 0),
(763, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:36:47', '1', NULL, 0),
(764, 'Lance Bogrol changed Ruby Von Rails\'s login password.', '2018-06-29 20:37:23', '1', NULL, 0),
(765, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:38:32', '1', NULL, 0),
(766, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:38:46', '1', NULL, 0),
(767, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-30 17:03:04', '1', NULL, 0),
(768, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-30 17:45:49', '1', NULL, 0),
(769, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-02 16:46:02', '1', NULL, 0),
(770, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-03 12:56:03', '1', NULL, 0),
(771, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-04 16:42:35', '1', NULL, 0),
(772, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-06 19:01:38', '1', NULL, 0),
(773, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-06 19:46:36', '1', NULL, 0),
(774, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-07-06 19:46:57', '1', NULL, 0),
(775, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-7', '2018-07-06 19:47:11', '1', NULL, 0),
(776, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-08 00:40:50', '1', NULL, 0),
(777, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-11 21:59:15', '1', NULL, 0),
(778, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-12 17:22:16', '1', NULL, 0),
(779, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/2\"> TICKET-2</a>', '2018-07-12 17:22:36', '1', NULL, 0),
(780, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-13 16:02:14', '1', NULL, 0),
(781, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:13:32', '1', NULL, 0),
(782, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-15 13:13:43', '1', NULL, 0),
(783, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:13:48', '1', NULL, 0),
(784, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:18:55', '1', NULL, 0),
(785, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:21:05', '1', NULL, 0),
(786, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-15 13:21:13', '1', NULL, 0),
(787, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:21:18', '1', NULL, 0),
(788, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-16 04:12:00', '1', NULL, 0),
(789, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-21 22:24:12', '1', NULL, 0),
(790, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 02:22:13', '1', NULL, 0),
(791, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 04:12:13', '1', NULL, 0),
(792, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 21:25:22', '1', NULL, 0),
(793, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-29 22:41:08', '1', NULL, 0),
(794, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:08', '1', NULL, 0),
(795, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:20', '1', NULL, 0),
(796, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:31', '1', NULL, 0),
(797, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:39', '1', NULL, 0),
(798, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:51', '1', NULL, 0),
(799, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:04', '1', NULL, 0),
(800, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:13', '1', NULL, 0),
(801, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:25', '1', NULL, 0),
(802, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:46', '1', NULL, 0),
(803, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:44:49', '1', NULL, 0),
(804, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:14', '1', NULL, 0),
(805, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:34', '1', NULL, 0),
(806, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:46', '1', NULL, 0),
(807, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:57', '1', NULL, 0),
(808, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:46:07', '1', NULL, 0),
(809, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:46:15', '1', NULL, 0),
(810, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:56:23', '1', NULL, 0),
(811, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:58:54', '1', NULL, 0),
(812, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:05', '1', NULL, 0),
(813, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:13', '1', NULL, 0),
(814, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:21', '1', NULL, 0),
(815, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:32', '1', NULL, 0),
(816, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:45', '1', NULL, 0),
(817, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:54', '1', NULL, 0),
(818, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:04', '1', NULL, 0),
(819, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:12', '1', NULL, 0),
(820, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:46', '1', NULL, 0),
(821, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:55', '1', NULL, 0),
(822, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:03', '1', NULL, 0),
(823, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:17', '1', NULL, 0),
(824, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:34', '1', NULL, 0),
(825, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:45', '1', NULL, 0),
(826, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:54', '1', NULL, 0),
(827, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:12', '1', NULL, 0),
(828, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:23', '1', NULL, 0),
(829, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:32', '1', NULL, 0),
(830, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:40', '1', NULL, 0),
(831, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:49', '1', NULL, 0),
(832, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:59', '1', NULL, 0),
(833, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:32', '1', NULL, 0),
(834, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:46', '1', NULL, 0),
(835, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:51', '1', NULL, 0),
(836, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:59', '1', NULL, 0),
(837, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:07', '1', NULL, 0),
(838, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:15', '1', NULL, 0),
(839, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:36', '1', NULL, 0),
(840, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:45', '1', NULL, 0),
(841, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:53', '1', NULL, 0),
(842, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:06:02', '1', NULL, 0),
(843, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:12:45', '1', NULL, 0),
(844, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:13:04', '1', NULL, 0),
(845, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:13:31', '1', NULL, 0),
(846, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-11 04:13:29', '1', NULL, 0),
(847, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/17\">INV-17</a>.', '2018-08-11 06:16:05', '1', 16, 0),
(848, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/10\">INV-10</a>.', '2018-08-11 06:23:26', '1', 6, 0),
(849, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-18 22:08:29', '1', NULL, 0),
(850, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 00:44:38', '1', NULL, 0),
(851, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 12:39:40', '1', NULL, 0),
(852, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 15:40:29', '1', NULL, 0),
(853, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-20 16:06:25', '1', NULL, 0),
(854, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-22 02:23:08', '1', NULL, 0),
(855, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-23 22:52:31', '1', NULL, 0),
(856, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-24 01:42:54', '1', NULL, 0);
INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`) VALUES
(857, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-24 21:32:46', '1', NULL, 0),
(858, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-25 22:37:03', '1', NULL, 0),
(859, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-27 04:33:51', '1', NULL, 0),
(860, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-27 17:01:47', '1', NULL, 0),
(861, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 03:05:56', '1', NULL, 0),
(862, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:38:59', '1', NULL, 0),
(863, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:39:31', '1', NULL, 0),
(864, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:47:27', '1', NULL, 0),
(865, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-30 22:31:21', '1', NULL, 0),
(866, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-02 19:02:17', '1', NULL, 0),
(867, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-03 01:39:48', '1', NULL, 0),
(868, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 18:59:46', '1', NULL, 0),
(869, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 19:00:31', '1', NULL, 0),
(870, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 19:02:12', '1', NULL, 0),
(871, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 03:37:48', '1', NULL, 0),
(872, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/18\">INV-18</a>.', '2018-09-19 03:47:05', '1', 18, 0),
(873, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:52:16', '1', NULL, 0),
(874, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:52:32', '1', NULL, 0),
(875, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:54:36', '1', NULL, 0),
(876, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:54:58', '1', NULL, 0),
(877, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:06', '1', NULL, 0),
(878, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:14', '1', NULL, 0),
(879, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:23', '1', NULL, 0),
(880, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:28', '1', NULL, 0),
(881, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:38', '1', NULL, 0),
(882, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:46', '1', NULL, 0),
(883, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:59', '1', NULL, 0),
(884, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 18:01:05', '1', NULL, 0),
(885, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 21:31:03', '1', NULL, 0),
(886, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-20 01:19:38', '1', NULL, 0),
(887, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-25 17:38:45', '1', NULL, 0),
(888, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 06:41:16', '1', NULL, 0),
(889, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 11:25:21', '1', NULL, 0),
(890, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 20:35:53', '1', NULL, 0),
(891, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-09-29 03:47:43', '1', NULL, 0),
(892, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/8\"> Product8</a>', '2018-09-29 04:00:39', '1', NULL, 0),
(893, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-29 09:29:02', '1', NULL, 0),
(894, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 16:23:56', '1', NULL, 0),
(895, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 22:18:54', '1', NULL, 0),
(896, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 22:49:45', '1', NULL, 0),
(897, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/9\"> Product9</a>', '2018-09-30 22:59:59', '1', NULL, 0),
(898, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/10\"> Product10</a>', '2018-09-30 23:13:12', '1', NULL, 0),
(899, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/11\"> Product11</a>', '2018-09-30 23:13:24', '1', NULL, 0),
(900, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/12\"> Product12</a>', '2018-09-30 23:13:58', '1', NULL, 0),
(901, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/13\"> Product13</a>', '2018-09-30 23:14:34', '1', NULL, 0),
(902, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/14\"> Product14</a>', '2018-09-30 23:15:47', '1', NULL, 0),
(903, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/15\"> Product15</a>', '2018-09-30 23:17:37', '1', NULL, 0),
(904, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/16\"> Product16</a>', '2018-09-30 23:19:42', '1', NULL, 0),
(905, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/17\"> Product17</a>', '2018-09-30 23:20:36', '1', NULL, 0),
(906, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/18\"> Product18</a>', '2018-09-30 23:21:03', '1', NULL, 0),
(907, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/19\"> Product19</a>', '2018-09-30 23:23:21', '1', NULL, 0),
(908, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/20\"> Product20</a>', '2018-09-30 23:25:02', '1', NULL, 0),
(909, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/21\"> Product21</a>', '2018-09-30 23:27:10', '1', NULL, 0),
(910, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/22\"> Product22</a>', '2018-10-01 00:10:06', '1', NULL, 0),
(911, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/23\"> Product23</a>', '2018-10-01 00:12:29', '1', NULL, 0),
(912, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/24\"> Product24</a>', '2018-10-01 00:26:09', '1', NULL, 0),
(913, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/25\"> Product25</a>', '2018-10-01 00:27:50', '1', NULL, 0),
(914, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/26\"> Product26</a>', '2018-10-01 00:28:57', '1', NULL, 0),
(915, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-26', '2018-10-01 00:33:38', '1', NULL, 0),
(916, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-25', '2018-10-01 00:33:41', '1', NULL, 0),
(917, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-24', '2018-10-01 00:33:44', '1', NULL, 0),
(918, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-23', '2018-10-01 00:33:46', '1', NULL, 0),
(919, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-22', '2018-10-01 00:33:48', '1', NULL, 0),
(920, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 04:12:04', '1', NULL, 0),
(921, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/27\"> Product27</a>', '2018-10-01 04:38:18', '1', NULL, 0),
(922, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:10:24', '1', NULL, 0),
(923, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:10:39', '1', NULL, 0),
(924, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:11:14', '1', NULL, 0),
(925, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:11:30', '1', NULL, 0),
(926, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:12:47', '1', NULL, 0),
(927, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:14:32', '1', NULL, 0),
(928, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:16:47', '1', NULL, 0),
(929, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:19:28', '1', NULL, 0),
(930, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:20:21', '1', NULL, 0),
(931, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:20:54', '1', NULL, 0),
(932, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:22:10', '1', NULL, 0),
(933, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:23:39', '1', NULL, 0),
(934, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:24:01', '1', NULL, 0),
(935, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:24:22', '1', NULL, 0),
(936, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:15', '1', NULL, 0),
(937, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:29', '1', NULL, 0),
(938, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:33', '1', NULL, 0),
(939, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:45', '1', NULL, 0),
(940, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:27:32', '1', NULL, 0),
(941, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:28:53', '1', NULL, 0),
(942, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:29:14', '1', NULL, 0),
(943, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:29:31', '1', NULL, 0),
(944, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:35:26', '1', NULL, 0),
(945, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:16', '1', NULL, 0),
(946, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:28', '1', NULL, 0),
(947, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:40', '1', NULL, 0),
(948, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:56', '1', NULL, 0),
(949, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:41:48', '1', NULL, 0),
(950, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:42:50', '1', NULL, 0),
(951, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:43:54', '1', NULL, 0),
(952, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:52:56', '1', NULL, 0),
(953, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:53:45', '1', NULL, 0),
(954, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:56:45', '1', NULL, 0),
(955, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-27', '2018-10-01 05:57:37', '1', NULL, 0),
(956, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/28\"> Product28</a>', '2018-10-01 05:58:52', '1', NULL, 0),
(957, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:29:17', '1', NULL, 0),
(958, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:30:30', '1', NULL, 0),
(959, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:30:58', '1', NULL, 0),
(960, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:31:08', '1', NULL, 0),
(961, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:32:42', '1', NULL, 0),
(962, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:40:10', '1', NULL, 0),
(963, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:40:34', '1', NULL, 0),
(964, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:41:10', '1', NULL, 0),
(965, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:43:01', '1', NULL, 0),
(966, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/29\"> Product29</a>', '2018-10-01 06:45:12', '1', NULL, 0),
(967, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 06:47:10', '1', NULL, 0),
(968, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:04:19', '1', NULL, 0),
(969, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:05:27', '1', NULL, 0),
(970, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:05:52', '1', NULL, 0),
(971, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:06:45', '1', NULL, 0),
(972, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:08:04', '1', NULL, 0),
(973, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:12:52', '1', NULL, 0),
(974, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:15:59', '1', NULL, 0),
(975, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:16:12', '1', NULL, 0),
(976, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:16:37', '1', NULL, 0),
(977, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:17:13', '1', NULL, 0),
(978, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:17:33', '1', NULL, 0),
(979, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:19:54', '1', NULL, 0),
(980, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:20:35', '1', NULL, 0),
(981, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-30', '2018-10-01 07:21:57', '1', NULL, 0),
(982, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-29', '2018-10-01 07:22:00', '1', NULL, 0),
(983, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-28', '2018-10-01 07:22:03', '1', NULL, 0),
(984, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:22:26', '1', NULL, 0),
(985, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:26:26', '1', NULL, 0),
(986, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:35:38', '1', NULL, 0),
(987, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:36:08', '1', NULL, 0),
(988, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:46:22', '1', NULL, 0),
(989, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:47:51', '1', NULL, 0),
(990, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:50:33', '1', NULL, 0),
(991, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-01 08:46:32', '1', NULL, 0),
(992, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 08:46:36', '1', NULL, 0),
(993, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 12:54:55', '1', NULL, 0),
(994, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 20:29:40', '1', NULL, 0),
(995, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-02 00:11:54', '1', NULL, 0),
(996, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-02 21:39:36', '1', NULL, 0),
(997, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 00:16:12', '1', NULL, 0),
(998, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 00:16:30', '1', NULL, 0),
(999, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:14:33', '1', NULL, 0),
(1000, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:14:50', '1', NULL, 0),
(1001, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:20:22', '1', NULL, 0),
(1002, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:23:40', '1', NULL, 0),
(1003, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 12:15:25', '1', NULL, 0),
(1004, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 13:05:51', '1', NULL, 0),
(1005, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 16:53:10', '1', NULL, 0),
(1006, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 16:53:23', '1', NULL, 0),
(1007, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/31\"> Product31</a>', '2018-10-04 20:10:32', '1', NULL, 0),
(1008, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/32\"> Product32</a>', '2018-10-04 20:10:44', '1', NULL, 0),
(1009, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/33\"> Product33</a>', '2018-10-04 20:12:01', '1', NULL, 0),
(1010, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-32', '2018-10-04 20:13:30', '1', NULL, 0),
(1011, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-33', '2018-10-04 20:13:33', '1', NULL, 0),
(1012, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-31', '2018-10-04 20:13:35', '1', NULL, 0),
(1013, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/34\"> Product34</a>', '2018-10-04 20:34:13', '1', NULL, 0),
(1014, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/35\"> Product35</a>', '2018-10-04 20:35:03', '1', NULL, 0),
(1015, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/36\"> Product36</a>', '2018-10-04 21:51:24', '1', NULL, 0),
(1016, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-36', '2018-10-04 21:53:36', '1', NULL, 0),
(1017, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-35', '2018-10-04 21:53:38', '1', NULL, 0),
(1018, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-34', '2018-10-04 21:53:41', '1', NULL, 0),
(1019, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/37\"> Product37</a>', '2018-10-04 21:53:54', '1', NULL, 0),
(1020, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-37', '2018-10-04 21:54:12', '1', NULL, 0),
(1021, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/38\"> Product38</a>', '2018-10-04 21:54:27', '1', NULL, 0),
(1022, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/39\"> Product39</a>', '2018-10-04 21:55:29', '1', NULL, 0),
(1023, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-39', '2018-10-04 21:56:01', '1', NULL, 0),
(1024, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-38', '2018-10-04 21:56:08', '1', NULL, 0),
(1025, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/40\"> Product40</a>', '2018-10-04 21:56:19', '1', NULL, 0),
(1026, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-40', '2018-10-04 21:57:17', '1', NULL, 0),
(1027, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/41\"> Product41</a>', '2018-10-04 21:57:32', '1', NULL, 0),
(1028, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-41', '2018-10-04 21:57:40', '1', NULL, 0),
(1029, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/42\"> Product42</a>', '2018-10-04 21:58:17', '1', NULL, 0),
(1030, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-42', '2018-10-04 21:58:52', '1', NULL, 0),
(1031, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/43\"> Product43</a>', '2018-10-04 21:59:09', '1', NULL, 0),
(1032, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/44\"> Product44</a>', '2018-10-04 22:01:08', '1', NULL, 0),
(1033, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/45\"> Product45</a>', '2018-10-04 22:02:26', '1', NULL, 0),
(1034, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/46\"> Product46</a>', '2018-10-04 22:03:31', '1', NULL, 0),
(1035, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/47\"> Product47</a>', '2018-10-04 22:04:08', '1', NULL, 0),
(1036, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/48\"> Product48</a>', '2018-10-04 22:04:52', '1', NULL, 0),
(1037, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-48', '2018-10-04 22:10:07', '1', NULL, 0),
(1038, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-47', '2018-10-04 22:10:10', '1', NULL, 0),
(1039, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-46', '2018-10-04 22:10:12', '1', NULL, 0),
(1040, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-45', '2018-10-04 22:10:15', '1', NULL, 0),
(1041, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-44', '2018-10-04 22:10:18', '1', NULL, 0),
(1042, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-43', '2018-10-04 22:10:21', '1', NULL, 0),
(1043, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/49\"> Product49</a>', '2018-10-04 22:10:49', '1', NULL, 0),
(1044, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/50\"> Product50</a>', '2018-10-04 22:12:13', '1', NULL, 0),
(1045, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/51\"> Product51</a>', '2018-10-04 22:13:55', '1', NULL, 0),
(1046, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/52\"> Product52</a>', '2018-10-04 22:14:13', '1', NULL, 0),
(1047, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/53\"> Product53</a>', '2018-10-04 22:16:03', '1', NULL, 0),
(1048, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/54\"> Product54</a>', '2018-10-04 22:16:55', '1', NULL, 0),
(1049, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-54', '2018-10-04 22:27:13', '1', NULL, 0),
(1050, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-53', '2018-10-04 22:27:15', '1', NULL, 0),
(1051, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-52', '2018-10-04 22:27:18', '1', NULL, 0),
(1052, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-51', '2018-10-04 22:27:21', '1', NULL, 0),
(1053, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-50', '2018-10-04 22:27:24', '1', NULL, 0),
(1054, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-49', '2018-10-04 22:27:27', '1', NULL, 0),
(1055, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-6', '2018-10-04 22:27:29', '1', NULL, 0),
(1056, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-5', '2018-10-04 22:27:32', '1', NULL, 0),
(1057, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-3', '2018-10-04 22:27:35', '1', NULL, 0),
(1058, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-2', '2018-10-04 22:27:38', '1', NULL, 0),
(1059, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-1', '2018-10-04 22:28:05', '1', NULL, 0),
(1060, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-10-04 22:30:21', '1', NULL, 0),
(1061, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-7', '2018-10-04 22:31:00', '1', NULL, 0),
(1062, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/8\"> Product8</a>', '2018-10-04 22:31:11', '1', NULL, 0),
(1063, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-8', '2018-10-04 22:33:40', '1', NULL, 0),
(1064, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/9\"> Product9</a>', '2018-10-04 22:44:41', '1', NULL, 0),
(1065, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-9', '2018-10-04 22:45:03', '1', NULL, 0),
(1066, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 22:47:09', '1', NULL, 0),
(1067, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-05 07:08:13', '1', NULL, 0),
(1068, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-05 07:37:17', '1', NULL, 0),
(1069, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-05 16:07:30', '1', NULL, 0),
(1070, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-07 15:52:26', '1', NULL, 0),
(1071, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-07 15:53:38', '1', NULL, 0),
(1072, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-08 17:48:03', '1', NULL, 0),
(1073, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-10 18:12:41', '1', NULL, 0),
(1074, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:45:05', '1', NULL, 0),
(1075, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:54:33', '1', NULL, 0),
(1076, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:55:18', '1', NULL, 0),
(1077, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:56:24', '1', NULL, 0),
(1078, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 11:03:53', '1', NULL, 0),
(1079, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 11:08:34', '1', NULL, 0),
(1080, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/1\">-1</a>.', '2018-10-14 14:52:18', '1', NULL, 0),
(1081, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/2\">-2</a>.', '2018-10-14 14:52:28', '1', NULL, 0),
(1082, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/3\">-3</a>.', '2018-10-14 14:55:26', '1', NULL, 0),
(1083, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted PRO-3', '2018-10-14 15:25:33', '1', NULL, 0),
(1084, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted PRO-1', '2018-10-14 15:25:45', '1', NULL, 0),
(1085, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted -3', '2018-10-14 15:26:58', '1', NULL, 0),
(1086, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted -2', '2018-10-14 15:27:06', '1', NULL, 0),
(1087, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 21:21:01', '1', NULL, 0),
(1088, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 21:23:12', '1', NULL, 0),
(1089, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-15 00:27:07', '1', NULL, 0),
(1090, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-15 01:40:21', '1', NULL, 0),
(1091, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-16 12:55:29', '1', NULL, 0),
(1092, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:32:41', '1', NULL, 0),
(1093, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:33:02', '1', NULL, 0),
(1094, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:35:07', '1', NULL, 0),
(1095, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:27:53', '1', NULL, 0),
(1096, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-26 11:57:37', '1', NULL, 0),
(1097, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-26 11:57:53', '1', NULL, 0),
(1098, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:58:24', '1', NULL, 0),
(1099, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:59:15', '1', NULL, 0),
(1100, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:59:51', '1', NULL, 0),
(1101, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 12:00:25', '1', NULL, 0),
(1102, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-27 16:10:59', '1', NULL, 0),
(1103, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-07 12:50:39', '1', NULL, 0),
(1104, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-12 02:24:38', '1', NULL, 0),
(1105, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-11-12 02:25:50', '1', NULL, 0),
(1106, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/19\">INV-19</a>.', '2018-11-12 02:27:01', '1', 18, 0),
(1107, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/20\">INV-20</a>.', '2018-11-12 02:27:58', '1', 0, 0),
(1108, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-20', '2018-11-12 02:28:07', '1', NULL, 0),
(1109, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/21\">INV-21</a>.', '2018-11-12 02:28:21', '1', 0, 0),
(1110, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-21', '2018-11-12 02:28:48', '1', NULL, 0),
(1111, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/22\">INV-22</a>.', '2018-11-12 02:29:01', '1', 0, 0),
(1112, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/23\">INV-23</a>.', '2018-11-12 02:31:24', '1', 18, 0),
(1113, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-23', '2018-11-12 02:32:19', '1', NULL, 0),
(1114, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/19\">INV-19</a>.', '2018-11-12 02:37:27', '1', 18, 0),
(1115, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-12 15:53:24', '1', NULL, 0),
(1116, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-13 19:05:56', '1', NULL, 0),
(1117, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:06:36', '1', NULL, 0),
(1118, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:17:43', '1', 0, 0),
(1119, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:17:58', '1', 0, 0),
(1120, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/10\">EXP-10</a>.', '2018-11-13 19:18:34', '1', 0, 0),
(1121, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"expenses/receipt/10\">EXP-10</a>.', '2018-11-13 19:24:52', '1', 0, 0),
(1122, 'Lance Bogrol updated project.', '2018-11-13 19:46:19', '1', NULL, 1),
(1123, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-11-13 19:48:43', '1', NULL, 0),
(1124, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-08 18:30:45', '1', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `title`, `description`, `customer_id`, `staff_id`, `date`, `start`, `end`) VALUES
(1, 'Toyota Dallas Meet', 'Im going to DHARMA Initiative this day.', 17, 1, '2018-04-16', '13:00:00', '14:00:00'),
(2, 'Wallmart Dallas Meet', 'Im going to Parallax Corporation this day.', 18, 1, '2018-04-17', '16:00:00', '17:00:00'),
(3, 'Test', 'Test', 16, 2, '2018-04-20', '13:00:00', '15:00:00'),
(4, 'asdfsa', '', 0, 0, '2018-04-16', '04:43:07', '04:43:07'),
(5, 'Customer Visit', 'Visit', 17, 1, '2018-05-02', '15:00:00', '17:00:00'),
(6, 'Customer Visit', 'Visit', 18, 1, '2018-05-03', '15:00:00', '17:00:00'),
(7, 'Bla Bla', 'Test', 17, 1, '2018-05-30', '15:00:00', '18:00:00'),
(8, 'Test', 'Tes', 16, 1, '2018-05-15', '16:35:33', '18:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `main_menu` int(11) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `show_staff` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `order_id`, `name`, `description`, `main_menu`, `icon`, `url`, `show_staff`) VALUES
(1, 1, 'x_menu_panel', NULL, 0, NULL, 'panel', 0),
(2, 2, 'x_menu_finance', NULL, 0, NULL, NULL, 0),
(3, 3, 'x_menu_customers_and_lead', NULL, 0, NULL, NULL, 0),
(4, 4, 'x_menu_track', NULL, 0, NULL, NULL, 0),
(5, 5, 'x_menu_others', NULL, 0, NULL, NULL, 1),
(6, 6, 'x_menu_calendar', NULL, 0, NULL, 'calendar', 0),
(7, 1, 'x_menu_invoices', 'manage_invoices', 2, 'ico-ciuis-invoices', 'invoices', 0),
(8, 2, 'x_menu_proposals', 'manage_proposals', 2, 'ico-ciuis-proposals', 'proposals', 0),
(9, 3, 'x_menu_expenses', 'manage_expenses', 2, 'ico-ciuis-expenses', 'expenses', 0),
(10, 4, 'x_menu_accounts', 'manage_accounts', 2, 'ico-ciuis-accounts', 'accounts', 1),
(11, 1, 'x_menu_customers', 'manage_customers', 3, 'ico-ciuis-customers', 'customers', 0),
(12, 2, 'x_menu_leads', 'manage_leads', 3, 'ico-ciuis-leads', 'leads', 0),
(13, 1, 'x_menu_projects', 'manage_projects', 4, 'ico-ciuis-projects', 'projects', 0),
(14, 2, 'x_menu_tasks', 'manage_tasks', 4, 'ico-ciuis-tasks', 'tasks', 0),
(15, 3, 'x_menu_tickets', 'manage_tickets', 4, 'ico-ciuis-supports', 'tickets', 0),
(16, 4, 'x_menu_products', 'manage_products', 4, 'ico-ciuis-products', 'products', 0),
(17, 1, 'x_menu_staff', 'manage_staff', 5, 'ico-ciuis-staff', 'staff', 1),
(18, 2, 'x_menu_reports', 'manage_reports', 5, 'ico-ciuis-reports', 'report', 1),
(19, 5, 'x_menu_orders', 'manage_orders', 2, 'ico-ciuis-proposals', 'orders', 1),
(20, 3, 'x_menu_emails', 'manage_emails', 5, 'ion-ios-email-outline', 'emails', 1),
(21, 4, 'x_menu_timesheets', 'manage_timesheets', 5, 'ion-ios-clock-outline', 'timesheets', 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(109);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `name` varchar(400) NOT NULL,
  `description` text,
  `duedate` date NOT NULL,
  `project_id` int(11) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `created` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `order`, `name`, `description`, `duedate`, `project_id`, `color`, `created`) VALUES
(1, 1, 'Make a Design', 'Make a amazing design.', '2018-01-31', 1, 'green', '2018-01-02'),
(2, 2, 'Develop', 'Develop', '2018-01-31', 1, 'green', '2018-01-02'),
(3, 3, 'Start Coding', 'Start Coding', '2018-01-31', 1, 'green', '2018-01-02'),
(5, 1, 'Begin', 'Test', '2018-01-07', 3, 'green', '2018-01-07'),
(7, 2, 'Finish', 'Finish this job.', '2018-02-08', 3, 'green', '2018-02-08');

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `description` text,
  `addedfrom` int(11) DEFAULT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `relation_type`, `relation`, `description`, `addedfrom`, `customer_id`, `created`) VALUES
(1, 'customer', 3, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:26:38'),
(2, 'customer', 2, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:26:46'),
(3, 'customer', 4, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:26:53'),
(4, 'customer', 5, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:27:00'),
(5, 'customer', 6, 'Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:27:12'),
(6, 'customer', 3, 'Mauris fringilla tincidunt mi at faucibus.', 1, NULL, '2017-08-25 21:18:02'),
(7, 'customer', 5, 'Quisque commodo ornare nisi sed sagittis. Donec vitae feugiat odio.', 1, NULL, '2017-08-25 21:18:13'),
(9, 'customer', 6, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 1, NULL, '2017-08-26 15:51:45'),
(10, 'customer', 7, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-08-27 08:09:38'),
(11, 'customer', 8, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-28 20:55:19'),
(13, 'proposal', 2, 'Test', 1, NULL, '2017-08-31 00:46:45'),
(15, 'proposal', 2, 'Test', 1, NULL, '2017-08-31 00:48:55'),
(16, 'lead', 1, 'Test', 1, NULL, '2017-08-31 03:49:26'),
(22, 'lead', 6, 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur.', 1, NULL, '2017-09-03 02:27:14'),
(19, 'customer', 16, 'Test', 1, NULL, '2017-08-31 03:56:39'),
(25, 'lead', 2, 'Test', 1, NULL, '2017-09-11 04:59:12'),
(24, 'customer', 1, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-09-06 17:29:26'),
(35, 'project', 11, 'Sample Details', 1, NULL, '2017-11-14 02:10:46'),
(67, 'lead', 11, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-12-12 03:59:35'),
(47, 'proposal', 7, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-11-23 02:29:09'),
(38, 'project', 0, 'Test', 1, NULL, '2017-11-23 01:48:24'),
(39, 'proposal', 0, 'Test', 1, NULL, '2017-11-23 02:05:42'),
(40, 'proposal', 0, 'test', 1, NULL, '2017-11-23 02:07:03'),
(46, 'proposal', 7, 'Sample proposal note.', 1, NULL, '2017-11-23 02:28:59'),
(45, 'customer', 9, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-11-23 02:24:09'),
(48, 'proposal', 7, 'Another note.', 2, NULL, '2017-11-23 02:29:59'),
(51, 'lead', 7, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-11-23 02:39:28'),
(50, 'lead', 7, 'Sample lead note', 1, NULL, '2017-11-23 02:39:19'),
(52, 'customer', 17, 'This page of this web site has a good collection of Zen Tales. Why are we suggesting that you tell these stories to your neighbors?', 1, NULL, '2017-11-23 03:54:59'),
(53, 'customer', 17, 'Or maybe it\'s just because they are fun to tell.', 1, NULL, '2017-11-23 03:55:13'),
(54, 'customer', 17, 'Think of these tales as conversation pieces, as handy tools that you can lift out of your pocket to help you and others talk, think and laugh about the wondrous and mysterious details of this thing we call Life. ', 1, NULL, '2017-11-23 03:55:34'),
(55, 'customer', 17, 'Lorem ipsum dolor sit amet', 1, NULL, '2017-11-23 03:58:58'),
(56, 'proposal', 5, 'Sample proposal note', 1, NULL, '2017-11-25 05:54:50'),
(64, 'proposal', 5, 'Sample note', 1, NULL, '2017-12-11 14:51:56'),
(63, 'project', 11, 'Sample note', 1, NULL, '2017-12-11 14:50:32'),
(66, 'lead', 11, 'Sample note', 1, NULL, '2017-12-11 14:52:10'),
(69, 'customer', 1, 'Sample', 1, NULL, '2018-01-01 16:36:05'),
(70, 'proposal', 1, 'Lorem ipsum dolor sit amet.', 1, NULL, '2018-01-01 23:15:32'),
(71, 'invoice', 10, 'Lorem ipsum sit dolor amet.', 1, NULL, '2018-03-05 00:00:00'),
(72, 'project', 1, 'Sample test note.', 1, NULL, '2018-06-25 15:59:56');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `markread` tinyint(2) NOT NULL DEFAULT '0',
  `customerread` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `detail` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `public` int(11) DEFAULT NULL,
  `perres` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `token` mediumtext NOT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `content` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(255) DEFAULT NULL,
  `relation` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `token`, `subject`, `content`, `date`, `created`, `opentill`, `relation_type`, `relation`, `assigned`, `addedfrom`, `datesend`, `comment`, `status_id`, `invoice_id`, `dateconverted`, `sub_total`, `total_discount`, `total_tax`, `total`) VALUES
(1, 'ad794d7fa6507aa3fd93218c26d9b6f0', 'Sample Order', 'Order is added.', '2018-10-14', '2018-10-14', '2018-10-14', 'customer', 18, 2, 1, '0000-00-00 00:00:00', 0, 1, NULL, NULL, '200.00', '20.00', '10.00', '190.00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transactiontype` int(11) NOT NULL,
  `is_transfer` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `expense_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `not` text NOT NULL,
  `attachment` mediumtext,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `transactiontype`, `is_transfer`, `invoice_id`, `expense_id`, `customer_id`, `amount`, `account_id`, `mode`, `date`, `not`, `attachment`, `staff_id`) VALUES
(1, 0, 0, 5, 0, 16, '100.00', 1, NULL, '2018-01-05 00:00:00', 'Cash', NULL, 1),
(2, 1, 0, 0, 1, 17, '20.00', 1, NULL, '2018-01-05 00:00:00', 'Payment for <a href=\"http://localhost:8888/ciuis/expenses/edit/1\">EXP-1</a>', NULL, 1),
(3, 0, 0, 6, 0, 12, '200.00', 3, NULL, '2018-01-06 00:00:00', 'Payment for INV-6.', NULL, 1),
(5, 1, 0, 0, 3, 0, '20.00', 3, NULL, '2018-01-06 00:00:00', 'Payment for <a href=\"http://localhost:8888/ciuis/expenses/edit/3\">EXP-3</a>', NULL, 1),
(6, 0, 0, 7, 0, 17, '100.00', 1, NULL, '2018-01-22 19:22:38', 'Sample', NULL, 1),
(7, 1, 0, 0, 4, 17, '10.00', 1, NULL, '2018-01-25 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/4\">EXP-4</a>', NULL, 1),
(8, 0, 0, 9, 0, 1, '210.00', 1, NULL, '2018-02-13 00:00:00', 'Payment for INV-9.', NULL, 1),
(9, 0, 0, 1, 0, 17, '200.00', 1, NULL, '2018-03-09 01:11:22', 'Payment for INV-Array.', NULL, 1),
(10, 0, 0, 11, 0, 17, '100.00', 1, NULL, '2018-03-09 01:14:12', 'Payment for INV-Array.', NULL, 1),
(11, 1, 0, 0, 5, 0, '20.00', 1, NULL, '2018-04-11 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/5\">EXP-5</a>', NULL, 1),
(13, 1, 0, 0, 7, 16, '20.00', 3, NULL, '2018-04-11 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/7\">EXP-7</a>', NULL, 1),
(17, 0, 0, 12, 0, 17, '210.00', 3, NULL, '2018-04-22 22:41:05', 'Payment for INV-12.', NULL, 1),
(18, 0, 0, 13, 0, 18, '20.00', 1, NULL, '2018-04-25 19:02:44', '232', NULL, 1),
(19, 0, 0, 10, 0, 6, '23.00', 1, NULL, '2018-04-25 19:03:44', '232', NULL, 1),
(20, 0, 0, 13, 0, 18, '10.00', 1, NULL, '2018-04-25 19:05:36', 'Sale', NULL, 1),
(21, 1, 0, 0, 8, 18, '20.00', 1, NULL, '2018-05-20 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/8\">EXP-8</a>', NULL, 1),
(22, 0, 1, 0, 0, 0, '20.00', 1, NULL, '2018-06-01 06:59:56', 'Money transfer transaction between accounts.', NULL, 1),
(23, 1, 1, 0, 0, 0, '20.00', 3, NULL, '2018-06-01 06:59:56', 'Money transfer transaction between accounts.', NULL, 1),
(24, 1, 0, 0, 9, 0, '10.00', 4, NULL, '2018-06-01 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/9\">EXP-9</a>', NULL, 1),
(25, 0, 1, 0, 0, 0, '100.00', 4, NULL, '2018-06-01 07:31:05', 'Money transfer transaction between accounts.', NULL, 1),
(26, 1, 1, 0, 0, 0, '100.00', 1, NULL, '2018-06-01 07:31:05', 'Money transfer transaction between accounts.', NULL, 1),
(27, 0, 1, 0, 0, 0, '13.00', 4, NULL, '2018-06-01 07:40:06', 'Money transfer transaction between accounts.', NULL, 1),
(28, 1, 1, 0, 0, 0, '13.00', 1, NULL, '2018-06-01 07:40:06', 'Money transfer transaction between accounts.', NULL, 1),
(29, 0, 0, 18, 0, 18, '1000.00', 1, NULL, '2018-09-19 00:00:00', 'Payment for INV-18.', NULL, 1),
(30, 1, 0, 0, 10, 0, '20.00', 1, NULL, '2018-11-13 00:00:00', 'Payment for <a href=\"http://localhost:8888/ciuis/expenses/edit/10\">EXP-10</a>', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_modes`
--

CREATE TABLE `payment_modes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_modes`
--

INSERT INTO `payment_modes` (`id`, `name`, `value`) VALUES
(1, 'authorize_aim_active', '0'),
(2, 'authorize_aim_api_login_id', ''),
(3, 'authorize_aim_api_transaction_key', ''),
(4, 'authorize_aim_payment_record_account', ''),
(5, 'paypal_active', '0'),
(6, 'paypal_username', ''),
(7, 'paypal_currency', ''),
(8, 'paypal_test_mode_enabled', '1'),
(9, 'paypal_payment_record_account', ''),
(10, 'stripe_active', '0'),
(11, 'stripe_api_secret_key', ''),
(12, 'stripe_api_publishable_key', ''),
(13, 'payu_money_active', '0'),
(14, 'payu_money_key', ''),
(15, 'payu_money_salt', ''),
(16, 'payu_money_test_mode_enabled', '1'),
(17, 'stripe_record_account', ''),
(18, 'ccavenue_record_account', ''),
(19, 'ccavenue_active', '0'),
(20, 'ccavenue_marchent_key', ''),
(21, 'ccavenue_working_key', ''),
(22, 'ccavenue_access_code', ''),
(23, 'ccavenue_test_mode', '1'),
(24, 'payu_money_record_account', ''),
(25, 'stripe_test_mode_enabled', '1'),
(26, 'razorpay_active', '0'),
(27, 'razorpay_key', ''),
(28, 'razorpay_test_mode_enabled', '1'),
(29, 'razorpay_payment_record_account', '0'),
(30, 'primary_bank_account', '0'),
(31, 'authorize_test_mode_enabled', '1'),
(32, 'razorpay_key_secret', '');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `type`, `key`) VALUES
(1, 'x_menu_invoices', 'common', 'invoices'),
(2, 'x_menu_proposals', 'common', 'proposals'),
(3, 'x_menu_expenses', 'non-common', 'expenses'),
(4, 'x_menu_accounts', 'non-common', 'accounts'),
(5, 'x_menu_customers', 'non-common', 'customers'),
(6, 'x_menu_leads', 'non-common', 'leads'),
(7, 'x_menu_projects', 'common', 'projects'),
(8, 'x_menu_tasks', 'non-common', 'tasks'),
(9, 'x_menu_tickets', 'common', 'tickets'),
(10, 'x_menu_products', 'non-common', 'products'),
(11, 'x_menu_staff', 'non-common', 'staff'),
(12, 'x_menu_reports', 'non-common', 'report'),
(13, 'x_menu_orders', 'non-common', 'orders'),
(14, 'x_menu_emails', 'non-common', 'emails');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `relation` int(5) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `permission_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`relation`, `relation_type`, `permission_id`) VALUES
(1, 'staff', 3),
(1, 'staff', 4),
(1, 'staff', 5),
(1, 'staff', 6),
(1, 'staff', 7),
(1, 'staff', 8),
(1, 'staff', 9),
(3, 'staff', 3),
(15, 'contact', 1),
(15, 'contact', 2),
(15, 'contact', 7),
(15, 'contact', 9),
(2, 'staff', 1),
(2, 'staff', 2),
(2, 'staff', 3),
(2, 'staff', 4),
(2, 'staff', 5),
(2, 'staff', 6),
(2, 'staff', 7),
(2, 'staff', 8),
(2, 'staff', 9),
(2, 'staff', 10),
(2, 'staff', 11),
(2, 'staff', 12),
(23, 'contact', 1),
(23, 'contact', 2),
(23, 'contact', 7),
(23, 'contact', 9),
(4, 'staff', 1),
(1, 'staff', 2),
(1, 'staff', 1),
(1, 'staff', 11),
(1, 'staff', 12),
(1, 'staff', 10),
(1, 'staff', 13),
(1, 'staff', 14);

-- --------------------------------------------------------

--
-- Table structure for table `productcategories`
--

CREATE TABLE `productcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `productname` varchar(255) DEFAULT NULL,
  `categoryid` int(11) NOT NULL,
  `description` text NOT NULL,
  `productimage` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(11,2) NOT NULL,
  `sale_price` decimal(11,2) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `vat` decimal(11,2) NOT NULL,
  `status_id` enum('0','1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `productname`, `categoryid`, `description`, `productimage`, `purchase_price`, `sale_price`, `stock`, `vat`, `status_id`) VALUES
(1, 'WEB', 'Web Design', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '20.00', '200.00', 200, '5.00', NULL),
(2, 'WEB', 'Seo Consultant', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '10.00', '200.00', 200, '0.00', NULL),
(3, 'WEB-204', 'Logo Design', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '10.00', '200.00', 200, '0.00', NULL),
(5, 'GRA-24', 'Graphic Design', 0, 'Graphic Design Services', NULL, '0.00', '100.00', 10000, '0.00', NULL),
(6, 'SRV-2324', 'Server Customization', 0, 'Server Customization.', NULL, '0.00', '1000.00', 0, '0.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projectmembers`
--

CREATE TABLE `projectmembers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projectmembers`
--

INSERT INTO `projectmembers` (`id`, `project_id`, `staff_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `projectvalue` decimal(10,2) DEFAULT '0.00',
  `tax` decimal(10,2) DEFAULT '0.00',
  `status_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `created` date NOT NULL,
  `finished` datetime DEFAULT NULL,
  `pinned` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `template` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `projectvalue`, `tax`, `status_id`, `customer_id`, `invoice_id`, `start_date`, `deadline`, `created`, `finished`, `pinned`, `staff_id`, `template`) VALUES
(1, 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '0.00', '0.00', 5, 17, 0, '2018-06-01', '2018-08-02', '2018-06-01', NULL, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `projectservices`
--

CREATE TABLE `projectservices` (
  `id` int(11) NOT NULL,
  `projectid` int(11) DEFAULT NULL,
  `categoryid` int(11) DEFAULT NULL,
  `categoryname` varchar(255) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `servicename` varchar(255) DEFAULT NULL,
  `serviceprice` decimal(10,2) DEFAULT NULL,
  `quantity` varchar(20) DEFAULT '1',
  `unit` varchar(255) DEFAULT 'Unit',
  `servicetax` decimal(10,2) DEFAULT NULL,
  `servicedescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `token` mediumtext NOT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `content` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(255) DEFAULT NULL,
  `relation` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(11) DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `token`, `subject`, `content`, `date`, `created`, `opentill`, `relation_type`, `relation`, `assigned`, `addedfrom`, `datesend`, `comment`, `status_id`, `invoice_id`, `dateconverted`, `sub_total`, `total_discount`, `total_tax`, `total`) VALUES
(2, '2d5525f66e8e3bdc29d6b2c1bb834e48', 'Seo Consultant', 'Seo Consultant', '2018-01-10', '2018-01-10', '2018-03-13', 'customer', 17, 2, 1, '0000-00-00 00:00:00', 1, 2, NULL, NULL, '200.00', '0.00', '0.00', '200.00'),
(4, '23fc708ebbc362838b681fac51046ab8', 'Poster Design', 'Poster design.', '2018-01-11', '2018-01-11', '2018-03-22', 'customer', 16, 4, 1, '0000-00-00 00:00:00', 0, 4, NULL, NULL, '100.00', '0.00', '0.00', '100.00'),
(5, '31e3fe3b03d23ad26a774feb4553694a', 'Mobile Application', 'Lorem ipsum dolor sit amet, id quaeque disputationi est. Sit quando recteque ad, inermis vivendo mei te, minim feugait adversarium eu qui. Id utamur placerat adolescens est. Eu eam postea commodo corpora, no ius vidisse tibique sensibus. Duo noster euismod ex, in tale aeterno epicurei mei. Tota cotidieque cum ex, mea no reque dolorum deserunt, soluta phaedrum vix ea. Errem tollit concludaturque vix no.\n\nAd pro accumsan adipiscing. No duo equidem delicata, no dolorum electram sed, quo quis tota facilisis cu. Ut reque velit tincidunt has, per an case option utamur, ad est dolorem ponderum inciderint. His ea prompta expetenda, no aliquid facilisis dissentias vim.\n\nUt deleniti electram mel, sit te suas assum laboramus, sit id erat verterem interpretaris. Ei vix debet dissentias, vero accusata evertitur usu ut. Qui audiam accusamus id. An vix maiestatis interpretaris, est dictas inermis mediocrem no. Inermis referrentur pri id, iudico integre copiosae mea ex.', '2017-08-24', '2017-08-24', '2018-06-19', 'customer', 12, 1, 1, '2018-10-01 08:15:07', 1, 6, 16, '2018-06-20 16:44:43', '200.00', '0.00', '10.00', '210.00'),
(6, 'baa13724cf4a341b1b9ea9749195c441', 'Test', 'Test', '2018-03-21', '2018-03-21', '2019-08-27', 'customer', 18, 1, 1, '0000-00-00 00:00:00', 1, 1, NULL, NULL, '200.00', '0.00', '10.00', '210.00');

-- --------------------------------------------------------

--
-- Table structure for table `recurring`
--

CREATE TABLE `recurring` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL DEFAULT '0',
  `period` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 = day | 1 = week | 2 = month | 3 = years',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` varchar(250) DEFAULT 'Invalid date',
  `status` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recurring`
--

INSERT INTO `recurring` (`id`, `relation_type`, `relation`, `period`, `type`, `created_at`, `end_date`, `status`) VALUES
(13, 'invoice', 8, 1, 0, '2018-02-11 21:00:00', '2018-04-28 00:00:00', 0),
(14, 'invoice', 9, 1, 0, '2018-02-13 08:21:22', '2018-02-13 00:00:00', 1),
(15, 'invoice', 10, 0, 0, '2018-04-11 19:38:59', '1970-01-01 00:00:00', 1),
(16, 'invoice', 7, 0, 0, '2018-04-11 19:39:32', 'Invalid date', 0),
(17, 'invoice', 5, 0, 0, '2018-05-19 20:32:00', 'Invalid date', 0),
(18, 'invoice', 16, 0, 0, '2018-06-20 13:45:32', 'Invalid date', 0),
(19, 'invoice', 19, 0, 0, '2018-11-11 23:37:27', 'Invalid date', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `description` text,
  `date` datetime NOT NULL,
  `isnotified` int(11) NOT NULL DEFAULT '0',
  `addedfrom` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `relation_type`, `relation`, `staff_id`, `description`, `date`, `isnotified`, `addedfrom`) VALUES
(1, 'lead', 1, 1, 'Donec nisl risus, dignissim at.', '2017-09-05 17:16:25', 1, 1),
(13, 'proposal', 2, 1, 'Lorem ipsum dolor sit amet.', '2017-09-07 01:20:00', 1, 1),
(19, 'proposal', 1, 2, 'Test reminder for this proposal.', '2017-09-11 10:35:00', 1, 1),
(9, 'lead', 4, 4, 'Test', '2017-03-30 11:55:00', 0, 1),
(10, 'lead', 2, 3, 'Lorem ipsum sit dolor amet.', '2017-09-30 12:50:00', 0, 1),
(11, 'lead', 6, 3, 'Please check proposals.', '2017-09-03 03:25:00', 0, 1),
(12, 'lead', 7, 2, 'Meet Evelyn Bradley.', '2017-09-03 03:35:00', 0, 1),
(15, 'customer', 3, 1, 'Test Reminder for Dreamhunter Productions.', '2017-09-08 06:10:00', 1, 1),
(16, 'customer', 16, 1, 'Test', '2017-09-11 05:01:00', 1, 1),
(20, 'lead', 5, 1, 'Test', '2017-11-12 01:30:00', 1, 1),
(21, 'lead', 11, 1, 'Sample reminder for lead', '2018-08-02 02:10:00', 1, 1),
(24, 'customer', 17, 1, 'Test reminder', '2017-12-02 03:35:00', 1, 1),
(63, 'customer', 17, 1, 'Sample reminder for me.', '2018-01-01 23:10:28', 1, 1),
(62, 'proposal', 5, 3, 'Lorem ipsum dolor sit amet ectum veritas.', '2017-12-12 00:50:52', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `status_id`, `customer_id`, `staff_id`, `total`, `date`) VALUES
(1, 1, 2, 17, 1, '200.00', '2018-01-01'),
(7, 7, 3, 17, 1, '200.00', '2018-01-22'),
(9, 9, 2, 1, 1, '210.00', '2018-02-13'),
(10, 10, 3, 6, 1, '200.00', '2018-02-13'),
(11, 11, 2, 17, 1, '100.00', '2018-03-09'),
(12, 12, 2, 17, 1, '210.00', '2017-03-25'),
(13, 13, 3, 18, 1, '210.00', '2018-04-22'),
(14, 15, 3, 18, 1, '20.00', '2018-05-20'),
(15, 16, 3, 12, 1, '210.00', '2018-06-20'),
(16, 17, 3, 16, 1, '100.00', '2018-08-11'),
(17, 18, 2, 18, 1, '1000.00', '2018-09-19'),
(18, 19, 3, 18, 1, '100.00', '2018-11-12'),
(21, 22, 3, 0, 1, '0.00', '2018-11-12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('4vlnitbtg045jsv7u23lbft5apf6iie3', '::1', 1554737519, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535343733373433393b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('66vk12tn8042t170tvbfder6to8g9qsl', '127.0.0.1', 1549291508, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534393239313530373b7573725f69647c733a313a2231223b73746166666e616d657c733a343a22526f6f74223b656d61696c7c733a31343a22726f6f744063697569732e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b4c6f67696e4f4b7c623a313b),
('l8k6hruk870e88codk2i3tu5u7g95898', '127.0.0.1', 1549291550, 0x5f5f63695f6c6173745f726567656e65726174657c693a313534393239313530373b7573725f69647c733a313a2231223b73746166666e616d657c733a343a22526f6f74223b656d61696c7c733a31343a22726f6f744063697569732e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b4c6f67696e4f4b7c623a313b);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settingname` varchar(255) NOT NULL,
  `crm_name` varchar(255) NOT NULL DEFAULT 'Ciuisâ„¢ CRM',
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `zipcode` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) NOT NULL,
  `taxoffice` varchar(255) DEFAULT NULL,
  `vatnumber` varchar(255) DEFAULT NULL,
  `unitseparator` varchar(255) NOT NULL DEFAULT '.',
  `currencyid` int(11) NOT NULL,
  `currencyposition` varchar(255) NOT NULL,
  `termtitle` varchar(255) NOT NULL,
  `termdescription` varchar(255) NOT NULL,
  `dateformat` varchar(255) DEFAULT NULL,
  `default_timezone` varchar(255) NOT NULL,
  `languageid` varchar(255) NOT NULL,
  `smtphost` varchar(255) DEFAULT NULL,
  `smtpport` varchar(255) DEFAULT NULL,
  `emailcharset` varchar(255) DEFAULT NULL,
  `email_encryption` int(11) NOT NULL,
  `smtpusername` varchar(255) DEFAULT NULL,
  `smtppassoword` varchar(255) DEFAULT NULL,
  `sendermail` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `app_logo` varchar(255) DEFAULT NULL,
  `accepted_files_formats` varchar(255) NOT NULL DEFAULT 'jpg,jpeg,doc,png,txt,docx',
  `allowed_ip_adresses` varchar(255) DEFAULT NULL,
  `pushState` int(11) NOT NULL,
  `voicenotification` int(11) NOT NULL,
  `paypalenable` int(11) NOT NULL,
  `paypalemail` varchar(255) DEFAULT NULL,
  `paypalsandbox` int(11) DEFAULT NULL,
  `paypalcurrency` varchar(255) DEFAULT NULL,
  `paypal_record_account` int(11) NOT NULL,
  `converted_lead_status_id` int(11) DEFAULT NULL,
  `two_factor_authentication` int(11) DEFAULT NULL,
  `authorize_login_id` varchar(255) DEFAULT NULL,
  `authorize_transaction_key` varchar(255) DEFAULT NULL,
  `authorize_record_account` int(11) NOT NULL,
  `authorize_enable` int(11) NOT NULL,
  `is_demo` tinyint(1) DEFAULT '0',
  `is_mysql` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingname`, `crm_name`, `company`, `email`, `address`, `country_id`, `state`, `city`, `town`, `zipcode`, `phone`, `fax`, `taxoffice`, `vatnumber`, `unitseparator`, `currencyid`, `currencyposition`, `termtitle`, `termdescription`, `dateformat`, `default_timezone`, `languageid`, `smtphost`, `smtpport`, `emailcharset`, `email_encryption`, `smtpusername`, `smtppassoword`, `sendermail`, `sender_name`, `logo`, `app_logo`, `accepted_files_formats`, `allowed_ip_adresses`, `pushState`, `voicenotification`, `paypalenable`, `paypalemail`, `paypalsandbox`, `paypalcurrency`, `paypal_record_account`, `converted_lead_status_id`, `two_factor_authentication`, `authorize_login_id`, `authorize_transaction_key`, `authorize_record_account`, `authorize_enable`, `is_demo`, `is_mysql`) VALUES
('ciuis', 'CiuisCRM', 'Acme Business INC', 'info@businessaddress.com', 'P.O. Box 929 4189 Nunc RoadLebanon KY 69409', 236, 'DC', 'New York', 'New Jersey', '34400', '+1 (389) 737-2852', '+1 (389) 737-2852', 'New York Tax Office', '4530685631', '.', 159, 'before', 'Terms & Conditions', 'Maecenas facilisis ultrices purus non vehicula. Nulla dignissim enim a libero tincidunt, consequat molestie nisi mattis. Phasellus scelerisque fringilla lectus vel tempor.', 'dd.mm.yy', 'Europe/Istanbul', 'english', 'mail.ciuis.com', '587', 'utf-8', 0, 'noreply@ciuis.com', 'NKsm25Z0', 'noreply@ciuis.com', NULL, 'ciuis-icon.png', 'ciuis-icon.png', 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt', '', 0, 1, 1, 'asd@ciuis.com', 1, 'USD', 1, 4, 0, '', '', 3, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `language` varchar(255) NOT NULL,
  `staffname` varchar(255) DEFAULT NULL,
  `staffavatar` varchar(300) DEFAULT 'n-img.jpg',
  `department_id` int(11) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthday` date NOT NULL,
  `password` varchar(100) NOT NULL,
  `root` tinyint(1) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `staffmember` tinyint(1) DEFAULT NULL,
  `other` tinyint(1) DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `appointment_availability` int(11) NOT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `google_calendar_enable` int(11) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL,
  `google_calendar_api_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `role_id`, `language`, `staffname`, `staffavatar`, `department_id`, `phone`, `address`, `email`, `birthday`, `password`, `root`, `admin`, `staffmember`, `other`, `last_login`, `appointment_availability`, `inactive`, `google_calendar_enable`, `google_calendar_id`, `google_calendar_api_key`) VALUES
(1, 1, 'english', 'Lance Bogrol', 'pm.jpg', 1, '+1-202-555-0160', '71 Pilgrim Avenue Chevy Chase, MD 20815', 'lance@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, 1, NULL, NULL, '2017-08-05 03:02:42', 1, NULL, 0, 'abaris@sdf.net', 'AIzaSyA1sWdokA3dVTzk7gNN58NztVw3kbPhJX8'),
(2, 0, 'english', 'Emma Durst', 'emma.jpg', 2, '+1-202-555-0158', '70 Bowman St. South Windsor, CT 06074', 'emma@example.com', '2017-07-03', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, NULL, '0000-00-00 00:00:00', 1, NULL, 0, '', ''),
(3, 0, 'english', 'Guy Mann', 'guy.jpg', 2, '+1-202-555-0129', '123 6th St. Melbourne, FL 32904', 'guy@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, NULL, '0000-00-00 00:00:00', 0, NULL, 0, '', ''),
(4, 0, 'english', 'Ruby Von Rails', 'ruby.jpg', 3, '+1-202-555-0143', '4 Goldfield Rd. Honolulu, HI 96815', 'ruby@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, NULL, '0000-00-00 00:00:00', 0, NULL, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `staff_work_plan`
--

CREATE TABLE `staff_work_plan` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `work_plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_work_plan`
--

INSERT INTO `staff_work_plan` (`id`, `staff_id`, `work_plan`) VALUES
(1, 1, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(2, 2, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(3, 3, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(4, 4, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(5, 5, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `finished` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `staff_id` int(11) NOT NULL,
  `complete` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subtasks`
--

INSERT INTO `subtasks` (`id`, `taskid`, `description`, `finished`, `created`, `staff_id`, `complete`) VALUES
(51, 35, 'Check availability of the most common tlds including com, net, org. Also, checks the hyphenated version of your preferred domain name.', 0, '2017-11-25 06:12:16', 2, 1),
(50, 32, 'Send customer', 0, '2017-11-24 04:53:55', 1, 1),
(49, 32, 'Change color', 0, '2017-11-24 04:53:48', 1, 1),
(48, 32, 'Make smiler logo', 0, '2017-11-24 04:53:46', 1, 1),
(56, 31, 'Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc, litot Europa usa li sam vocabular.', 0, '2017-11-25 06:18:44', 2, 1),
(45, 30, 'One day in the year of the fox', 0, '2017-11-23 23:40:15', 1, 1),
(44, 30, 'Loose yourself to dance', 0, '2017-11-23 23:39:58', 1, 1),
(43, 30, 'Hello darkness my old friend', 0, '2017-11-23 23:39:25', 1, 1),
(42, 29, 'Hello darkness my old friend.', 0, '2017-11-23 23:38:07', 1, 1),
(41, 29, 'There is no pain you are receding.', 0, '2017-11-23 23:37:57', 1, 0),
(40, 29, 'Sample Subtask', 0, '2017-11-23 23:37:44', 1, 0),
(39, 27, 'Hello darkness my old friend.', 0, '2017-11-23 23:15:44', 1, 0),
(38, 27, 'Lorem ipsum dolor sit amet.', 0, '2017-11-23 23:15:36', 1, 1),
(37, 27, 'Sample sub task.', 0, '2017-11-23 23:15:30', 1, 1),
(53, 35, 'Preserve the main keywords entered in the final domain name and use the following to preserve SEO', 0, '2017-11-25 06:12:40', 2, 1),
(54, 35, 'Simple Portmanteau - smartly merge popular suffixes like ly, ify, er, ish, ism and many more', 0, '2017-11-25 06:12:51', 2, 1),
(55, 32, '\"What\'s happened to me?\" he thought. It wasn\'t a dream. His room, a proper human', 0, '2017-11-25 06:18:25', 2, 0),
(57, 31, 'Li nov lingua franca va esser plu simplic e regulari quam li existent Europan', 0, '2017-11-25 06:19:04', 2, 1),
(58, 36, 'Altera perfecto philosophia et eum. Facete aliquip epicurei sed te, ex sed clita legendos atomorum.', 0, '2017-11-25 17:03:08', 1, 1),
(59, 36, 'Mei no alia cibo virtute. Has minim labores te, no quo viderer menandri, ut autem regione delicatissimi mea. Choro pertinacia in vis.', 0, '2017-11-25 17:03:16', 1, 1),
(60, 36, 'Mazim admodum epicurei pro cu. Per at veritus torquatos. Legimus laoreet persecuti ius ex, cu summo falli ius.', 0, '2017-11-25 17:03:25', 1, 1),
(68, 1, 'Test', 0, '2018-08-27 05:16:19', 1, 1),
(62, 1, 'Sample sub task', 0, '2018-01-06 02:43:49', 1, 0),
(63, 1, 'There is no pain', 0, '2018-01-06 02:43:57', 1, 1),
(64, 11, 'Test', 0, '2018-01-07 22:16:35', 1, 0),
(65, 2, 'Lorem ipsum dolor sit amet.', 0, '2018-01-07 22:53:08', 1, 1),
(66, 2, 'Populo honestatis vel eu, duo in inani possim scriptorem', 0, '2018-01-07 22:53:15', 1, 1),
(67, 2, 'Mundi dolores antiopam eam id.', 0, '2018-01-07 22:53:21', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `relation_type`, `relation`, `data`) VALUES
(1, 'lead', 11, '[\"test\"]'),
(2, 'lead', 12, '[]'),
(3, 'lead', 13, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` mediumtext,
  `description` text,
  `priority` int(11) DEFAULT NULL,
  `assigned` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `startdate` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `datefinished` datetime NOT NULL,
  `addedfrom` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '0',
  `relation` int(11) DEFAULT NULL,
  `relation_type` varchar(30) DEFAULT NULL,
  `public` tinyint(1) DEFAULT '0',
  `billable` tinyint(1) DEFAULT '0',
  `billed` tinyint(1) DEFAULT '0',
  `hourly_rate` decimal(11,2) DEFAULT '0.00',
  `milestone` int(11) DEFAULT '0',
  `visible` tinyint(1) DEFAULT '0',
  `timer` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `priority`, `assigned`, `created`, `startdate`, `duedate`, `datefinished`, `addedfrom`, `status_id`, `relation`, `relation_type`, `public`, `billable`, `billed`, `hourly_rate`, `milestone`, `visible`, `timer`) VALUES
(1, 'Sample Test', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 1, 1, '2018-06-01 20:51:46', '2018-06-01', '2019-06-14', '0000-00-00 00:00:00', 1, 4, 1, 'project', 1, 1, 0, '10.00', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasktimer`
--

CREATE TABLE `tasktimer` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `start` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `end` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `timed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasktimer`
--

INSERT INTO `tasktimer` (`id`, `status`, `task_id`, `staff_id`, `start`, `end`, `timed`, `note`, `project_id`) VALUES
(14, 1, 1, 1, '2018-06-01 20:59', '2018-06-01 21:01', '2018-06-01 17:59:25', 'test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticketreplies`
--

CREATE TABLE `ticketreplies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `message` text,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ticketreplies`
--

INSERT INTO `ticketreplies` (`id`, `ticket_id`, `staff_id`, `name`, `contact_id`, `date`, `message`, `attachment`) VALUES
(1, 1, 1, 'Lance Bogrol', 23, '2018-01-23 02:07:45', 'Hi, how can i help you?', ''),
(2, 1, 1, 'Sue', 23, '2018-02-01 08:04:27', 'Hi i have a question.', ''),
(3, 2, 1, 'Lance Bogrol', 14, '2018-07-12 05:22:36', 'Test', ''),
(4, 3, 1, 'Sue', 23, '2018-08-02 20:52:45', 'Sample', ''),
(5, 3, 1, 'Sue', 23, '2018-08-02 20:53:19', 'Tets', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `customer_id` int(11) NOT NULL,
  `email` text,
  `department_id` int(11) NOT NULL,
  `priority` enum('1','2','3') NOT NULL,
  `status_id` enum('1','2','3','4') NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text,
  `date` datetime NOT NULL,
  `lastreply` datetime DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `staff_id` int(11) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `contact_id`, `customer_id`, `email`, `department_id`, `priority`, `status_id`, `relation`, `relation_id`, `subject`, `message`, `date`, `lastreply`, `attachment`, `staff_id`) VALUES
(1, 23, 17, 'sue@example.com', 3, '3', '4', NULL, NULL, 'Hi Please Help Me', 'Hi could you help me?', '2018-01-23 14:06:46', '2018-02-01 08:04:27', '', 1),
(2, 14, 0, NULL, 3, '1', '3', NULL, NULL, 'Sample Ticket', 'Sample ticket detail.', '2018-01-23 15:47:56', '2018-07-12 17:22:36', '', 1),
(3, 23, 17, 'sue@example.com', 3, '3', '1', NULL, NULL, 'Hi, Can you help me?', 'Hi, i need some help.', '2018-06-20 19:23:49', '2018-08-02 20:53:19', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `description`, `staff_id`, `date`, `done`) VALUES
(2, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 1, '2017-08-25 21:17:08', 0),
(5, 'Mauris fringilla tincidunt mi at faucibus.', 1, '2017-08-25 21:17:53', 1),
(6, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 2, '2017-08-25 21:19:09', 1),
(7, 'Cras felis elit, vehicula id consectetur eu, cursus vel elit.', 2, '2017-08-25 21:19:17', 1),
(8, 'Vestibulum dolor felis, porta id auctor sollicitudin', 2, '2017-08-25 21:19:27', 0),
(9, 'Maecenas vel ultrices justo, nec consequat ipsum.', 2, '2017-08-25 21:19:35', 0),
(12, 'Test', 1, '2017-12-22 20:12:48', 1),
(13, 'Lorem ipsum dolor sit amet.', 1, '2018-01-21 19:02:18', 0),
(14, 'Sample to-do item.', 1, '2018-10-15 00:44:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `contact_id` int(10) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `user_id`, `contact_id`, `created`) VALUES
(1, '46e684b53f71f476b8f152f0cf41ae', 0, 23, '2018-09-26');

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `id` int(11) NOT NULL,
  `versions_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_version` varchar(255) DEFAULT NULL,
  `last_updated` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`id`, `versions_name`, `created_at`, `last_version`, `last_updated`) VALUES
(1, '1.5.3', '2019-02-26 15:09:08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `webleads`
--

CREATE TABLE `webleads` (
  `id` int(11) NOT NULL,
  `token` mediumtext NOT NULL,
  `lead_source` int(11) NOT NULL,
  `lead_status` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `submit_text` varchar(255) NOT NULL,
  `success_message` text NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `duplicate` tinyint(1) NOT NULL DEFAULT '1',
  `assigned_id` int(11) NOT NULL,
  `form_data` longtext NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appconfig`
--
ALTER TABLE `appconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branding`
--
ALTER TABLE `branding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_backup`
--
ALTER TABLE `db_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensecat`
--
ALTER TABLE `expensecat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadssources`
--
ALTER TABLE `leadssources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_modes`
--
ALTER TABLE `payment_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productcategories`
--
ALTER TABLE `productcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectmembers`
--
ALTER TABLE `projectmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectservices`
--
ALTER TABLE `projectservices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recurring`
--
ALTER TABLE `recurring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingname`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasktimer`
--
ALTER TABLE `tasktimer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webleads`
--
ALTER TABLE `webleads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appconfig`
--
ALTER TABLE `appconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branding`
--
ALTER TABLE `branding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `db_backup`
--
ALTER TABLE `db_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=325;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `expensecat`
--
ALTER TABLE `expensecat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `leadssources`
--
ALTER TABLE `leadssources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1125;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payment_modes`
--
ALTER TABLE `payment_modes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `productcategories`
--
ALTER TABLE `productcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `projectmembers`
--
ALTER TABLE `projectmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `projectservices`
--
ALTER TABLE `projectservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `recurring`
--
ALTER TABLE `recurring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasktimer`
--
ALTER TABLE `tasktimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `webleads`
--
ALTER TABLE `webleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
