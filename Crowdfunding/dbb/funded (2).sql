-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2025 at 07:40 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `funded`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE IF NOT EXISTS `admin_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `welcome_text` varchar(200) NOT NULL,
  `welcome_subtitle` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `result_request` int(10) UNSIGNED NOT NULL COMMENT 'The max number of shots per request',
  `status_page` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0 Offline, 1 Online',
  `email_verification` enum('0','1') NOT NULL COMMENT '0 Off, 1 On',
  `email_no_reply` varchar(200) NOT NULL,
  `email_admin` varchar(200) NOT NULL,
  `captcha` enum('on','off') NOT NULL DEFAULT 'on',
  `file_size_allowed` int(11) UNSIGNED NOT NULL COMMENT 'Size in Bytes',
  `google_analytics` text NOT NULL,
  `paypal_account` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `facebook` varchar(200) NOT NULL,
  `googleplus` varchar(200) NOT NULL,
  `instagram` varchar(200) NOT NULL,
  `google_adsense` text NOT NULL,
  `currency_symbol` char(10) NOT NULL,
  `currency_code` varchar(20) NOT NULL,
  `min_donation_amount` int(11) UNSIGNED NOT NULL,
  `min_campaign_amount` int(11) UNSIGNED NOT NULL,
  `max_campaign_amount` int(11) UNSIGNED NOT NULL,
  `payment_gateway` enum('Paypal','Stripe') NOT NULL DEFAULT 'Paypal',
  `paypal_sandbox` enum('true','false') NOT NULL DEFAULT 'true',
  `min_width_height_image` varchar(100) NOT NULL,
  `fee_donation` int(10) UNSIGNED NOT NULL,
  `auto_approve_campaigns` enum('0','1') NOT NULL DEFAULT '1',
  `stripe_secret_key` varchar(255) NOT NULL,
  `stripe_public_key` varchar(255) NOT NULL,
  `max_donation_amount` int(10) UNSIGNED NOT NULL,
  `enable_paypal` enum('0','1') NOT NULL DEFAULT '0',
  `enable_stripe` enum('0','1') NOT NULL DEFAULT '0',
  `enable_bank_transfer` enum('0','1') NOT NULL DEFAULT '0',
  `bank_swift_code` varchar(250) NOT NULL,
  `account_number` varchar(250) NOT NULL,
  `branch_name` varchar(250) NOT NULL,
  `branch_address` varchar(250) NOT NULL,
  `account_name` varchar(250) NOT NULL,
  `iban` varchar(250) NOT NULL,
  `date_format` varchar(200) NOT NULL,
  `link_privacy` varchar(200) NOT NULL,
  `link_terms` varchar(200) NOT NULL,
  `currency_position` enum('left','right') NOT NULL DEFAULT 'left',
  `facebook_login` enum('on','off') NOT NULL DEFAULT 'off',
  `google_login` enum('on','off') NOT NULL DEFAULT 'off',
  `decimal_format` enum('comma','dot') NOT NULL DEFAULT 'dot',
  `registration_active` enum('on','off') NOT NULL DEFAULT 'on',
  `color_default` varchar(100) NOT NULL,
  `version` varchar(5) NOT NULL,
  `captcha_on_donations` enum('on','off') NOT NULL DEFAULT 'on',
  `status_pwa` tinyint(1) NOT NULL DEFAULT 0,
  `paymentdescription` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `max_campaign_amount` (`max_campaign_amount`),
  KEY `type` (`status_pwa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `title`, `description`, `welcome_text`, `welcome_subtitle`, `keywords`, `result_request`, `status_page`, `email_verification`, `email_no_reply`, `email_admin`, `captcha`, `file_size_allowed`, `google_analytics`, `paypal_account`, `twitter`, `facebook`, `googleplus`, `instagram`, `google_adsense`, `currency_symbol`, `currency_code`, `min_donation_amount`, `min_campaign_amount`, `max_campaign_amount`, `payment_gateway`, `paypal_sandbox`, `min_width_height_image`, `fee_donation`, `auto_approve_campaigns`, `stripe_secret_key`, `stripe_public_key`, `max_donation_amount`, `enable_paypal`, `enable_stripe`, `enable_bank_transfer`, `bank_swift_code`, `account_number`, `branch_name`, `branch_address`, `account_name`, `iban`, `date_format`, `link_privacy`, `link_terms`, `currency_position`, `facebook_login`, `google_login`, `decimal_format`, `registration_active`, `color_default`, `version`, `captcha_on_donations`, `status_pwa`, `paymentdescription`) VALUES
(1, 'Fundme | Crowdfunding Platform', 'add many things to this payment option', 'Little help, for big cause!', 'Crowdfunding Platform', 'Crowdfunding,crowfund,fundme,campaign', 21, '1', '0', 'no-reply@yousite.com', 'admin@admin.com', 'off', 1024, '', 'paypal@yousite.com', 'https://www.twitter.com/', 'https://www.facebook.com/fundme', 'https://plus.google.com/', 'https://www.instagram.com/', '', '$', 'USD', 5, 100, 100000000, 'Paypal', 'true', '800x400', 3, '0', '', '', 100000, '0', '0', '0', '', '', '', '', '', '', 'M d, Y', 'https://yousite.com/p/privacy', 'https://yousite.com/p/terms-of-service', 'left', 'off', 'off', 'dot', 'on', '#2ea336', '5.5', 'off', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `blogs_slug_index` (`slug`(191)),
  KEY `blogs_tags_index` (`tags`(191))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `image`, `content`, `tags`, `user_id`, `date`) VALUES
(1, 'Welcome to Fundme', 'welcome-to-fundme', 'FA1qJYQ3eVMYTRBuO9o4BzqzuOQ9vzIax0hZ0gIdf5mu0mft0Qh31dz.jpg', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodoconsequat. Duis aute irure dolor in reprehenderit in voluptate velit essecillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat nonproident, sunt in culpa qui officia deserunt mollit anim id est laborum.&nbsp;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br />\r\nconsequat.uis aute irure dolor in reprehenderit in voluptate velit essecillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat nonproident, sunt in culpa qui officia deserunt mollit anim id est laborum.<br />\r\n<br />\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodtempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br />\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit essecillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<br />\r\n<br />\r\n&nbsp;', '#donation #funding #fundme', 1, '2024-12-31 19:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE IF NOT EXISTS `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `small_image` varchar(255) NOT NULL,
  `large_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `status` enum('active','pending') NOT NULL DEFAULT 'active',
  `token_id` varchar(255) NOT NULL,
  `goal` int(11) UNSIGNED NOT NULL,
  `location` varchar(200) NOT NULL,
  `finalized` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No 1 Yes',
  `categories_id` int(10) UNSIGNED NOT NULL,
  `featured` enum('0','1') NOT NULL DEFAULT '0',
  `deadline` varchar(200) NOT NULL DEFAULT '',
  `video` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_id` (`token_id`),
  KEY `author_id` (`user_id`,`status`,`token_id`),
  KEY `image` (`small_image`),
  KEY `goal` (`goal`),
  KEY `categories_id` (`categories_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `small_image`, `large_image`, `title`, `description`, `user_id`, `date`, `status`, `token_id`, `goal`, `location`, `finalized`, `categories_id`, `featured`, `deadline`, `video`) VALUES
(1, '11735416414gnzzzdsyhkyu2rtyiuahk8epjnw581xlepa.jpg', '11735416414yaf2afogiv08qvfg6fkurobpqxgxnslxdgxeb1np.jpg', 'Trap Homiez City Design', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br> tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,<br> quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br> consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse<br> cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non<br> proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1, '2024-12-28 11:06:58', 'active', 'cnbq0TGP2kkPcHoNon910v2qYx1QT3Am1KquuArM3itbnwEh9T3s7e0WK4zqzs6d7Eo0PVpPqamXXkeDGNmHxO7b77MtK0FUdM2O9T97mK23s8efY6dKhYqdqbDxV41HS2pdnS5X0YawvZLtxKmZiss6kR4sFoak2eGHBlzt2PS1SlSJeVIOiGkbjhW709jMPALCI7lM', 500000, 'UNITED STATES', '0', 13, '1', '31-03-2025', ''),
(2, '21735737461srtqrntxu6oxnjalzdf0cehystllyomzu8i.jpg', '21735737461dxw9bcfi5ngpyabamcxqdlpsk11kuvqgwhtliush.jpg', 'Hospital for Charity', 'Young Done dolor sit amet, consectetur adipisicing elit, sed do eiusmod<br> tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,<br> quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo<br> consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse<br> cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non<br> proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2, '2024-12-31 10:53:14', 'active', 'EQ16Z6SfMYRJlX9jGpMpu78EKwCOlRpxHoGc0MsHitbEX9LtK5MR1iQuWZdmhu0phiX6JDUfNgciykoVAeI9SHnVO7QtZR68MWBimNsTviq7GddZ4qZdV4lHwcxg9ro2NuWqAs4bGLqhJLCFeGWW2hQxlEb2sK2awEXHMUtaRCKbBcFNPXs979YyIf6lojDwHMDeufYg', 50000, 'Lagos State', '1', 12, '1', '30-01-2025', ''),
(3, '421738407077w2sy9wispd6bzvb4agokvfh5agkt9jnau4e.png', '421738407077xbpyjajargauyx5lxioquroacb1lholq3ccqmpio.png', 'developer help', 'amazing and good for people', 42, '2025-02-01 10:51:24', 'active', '5aJn7iqA6u7l1Up1SDPLN0xY6spUkDQylgAQN66aq89UxujSsSbMmg37mYBfsmbF7jkQaQZAmrvg7Lz9GBxZkJjtkGWPC3XV6rvXVC0Fld978o9SFmx5XmRgTrCektRMObPZ70YriH67gpi92pOSyHJrUybu4WUYBGvjsxLwiSr8rtmS9kWhNbKB1gbxeRZVe99M5laA', 700000, 'lagos NIgeria', '1', 6, '1', '08-02-2025', ''),
(4, '11738999186qqplyszpkkstqbi4sphfbhqw0voi0f4rymt.png', '11738999186kiiki9afk9gsgm9uxykwclrbga9nxotu19gwrwls.png', 'everyone gig', '5690 ths for the help pf our people in nigeria and for the people of all people yes u grab that shii', 1, '2025-02-08 07:19:48', 'active', 'blIp309CYT5EsGXjGG9nBKzf6izPOjmVhpPAVNjDTUk056fYhsbo2mqqF6wgyOjo2B8BnpQJzfvPxbyUWNoe2cSCZsT1lihT3o8wyoAAzzTLI1LjKuKmypyf5NnsaAnpjpTtQfU1PiomLgPFg9Tg0JlGjXoCe7sjbdcVkwbEKyOTdJJlFy6V6TVJinGNhrOGbWLiz7SI', 700000, 'lagos', '0', 17, '1', '15-02-2025', ''),
(7, '21739184288prf5hymktgzysnqyzgh2qovvprydev3rado.png', '21739184288gajipdihn5kpmkzrofwllxiierz2jvvwdky6pubp.png', 'goals', 'ijioooiiiiiiiiiiiiiiiiiiiiiiiiiiukkkkkkk', 2, '2022-02-25 00:00:00', 'active', '8OkpxobSWGc6S861ov0cxSlfkpP3GGQfpBG25M6gnxf9b1e5qtwvBvXXPXaiVxdc0gC5g3yqiWUsLFvFsOXB73EXPGkALULjOZm8uPjeGF1CJKqfahZNLORcYg7UNr5kPxFTHZo0xy8PH7DagjZEHdEmPQkHC6mA6sWArSMo61LwF3f9Iky2HNPydU6ylcepKGp9LtVc', 8888, 'nigeria', '0', 1, '1', '2025-02-25', ''),
(8, '217393673523hcaq2g4vfedkkj63odzi0i8fcxkxozdbni.jpg', '217393673522jkc2ztpdo241kahily1l44puafrsyioebkafupw.jpg', 'bully', 'faufhcuscbjkbcbcccccccccccccccccccccccccccccccccccccccccccccccc', 2, '2025-11-20 00:00:00', 'pending', 'CMkt8w4tj6q4MVOZpnEGpmOkFSLiSKFf9tqwYD55eAsDf4w7Il2MNVWFdyBTJ1LbB9tLzNiQTxkwZhh7yXirSDwg2Cv9z7ISbbItX9sE4mBEfeb1cYKtAuZmJknTNxqH62TWZUPSxBNGmZWOz4QxwHUYpquSWS6GIstBYskAY2xxKUHGmyTCt8sribKHMIsKA8p0tqGp', 20000, 'nigeria', '1', 17, '0', '2025-02-13', '');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_reported`
--

CREATE TABLE IF NOT EXISTS `campaigns_reported` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `mode` enum('on','off') NOT NULL DEFAULT 'on',
  `image` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `mode`, `image`) VALUES
(1, 'Business', 'Business', 'on', ''),
(2, 'Charity', 'Charity', 'on', ''),
(3, 'Community', 'Community', 'on', ''),
(4, 'Competitions', 'Competitions', 'on', ''),
(5, 'Creative', 'Creative', 'on', ''),
(6, 'Events', 'Events', 'on', ''),
(7, 'Faith', 'Faith', 'on', ''),
(8, 'Family', 'Family', 'on', ''),
(9, 'Other', 'Other', 'on', ''),
(10, 'Travel', 'Travel', 'on', ''),
(11, 'Wishes', 'Wishes', 'on', ''),
(12, 'Medical', 'Medical', 'on', ''),
(13, 'Emergencies', 'Emergencies', 'on', ''),
(14, 'Education', 'Education', 'on', ''),
(15, 'Memorials', 'Memorials', 'on', ''),
(16, 'Sports', 'Sports', 'on', ''),
(17, 'Animals', 'Animals', 'on', '');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(2) NOT NULL DEFAULT '',
  `country_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=243 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `country_code`, `country_name`) VALUES
(1, 'US', 'United States'),
(2, 'CA', 'Canada'),
(3, 'AF', 'Afghanistan'),
(4, 'AL', 'Albania'),
(5, 'DZ', 'Algeria'),
(6, 'DS', 'American Samoa'),
(7, 'AD', 'Andorra'),
(8, 'AO', 'Angola'),
(9, 'AI', 'Anguilla'),
(10, 'AQ', 'Antarctica'),
(11, 'AG', 'Antigua and/or Barbuda'),
(12, 'AR', 'Argentina'),
(13, 'AM', 'Armenia'),
(14, 'AW', 'Aruba'),
(15, 'AU', 'Australia'),
(16, 'AT', 'Austria'),
(17, 'AZ', 'Azerbaijan'),
(18, 'BS', 'Bahamas'),
(19, 'BH', 'Bahrain'),
(20, 'BD', 'Bangladesh'),
(21, 'BB', 'Barbados'),
(22, 'BY', 'Belarus'),
(23, 'BE', 'Belgium'),
(24, 'BZ', 'Belize'),
(25, 'BJ', 'Benin'),
(26, 'BM', 'Bermuda'),
(27, 'BT', 'Bhutan'),
(28, 'BO', 'Bolivia'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British lndian Ocean Territory'),
(34, 'BN', 'Brunei Darussalam'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CV', 'Cape Verde'),
(41, 'KY', 'Cayman Islands'),
(42, 'CF', 'Central African Republic'),
(43, 'TD', 'Chad'),
(44, 'CL', 'Chile'),
(45, 'CN', 'China'),
(46, 'CX', 'Christmas Island'),
(47, 'CC', 'Cocos (Keeling) Islands'),
(48, 'CO', 'Colombia'),
(49, 'KM', 'Comoros'),
(50, 'CG', 'Congo'),
(51, 'CK', 'Cook Islands'),
(52, 'CR', 'Costa Rica'),
(53, 'HR', 'Croatia (Hrvatska)'),
(54, 'CU', 'Cuba'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czech Republic'),
(57, 'DK', 'Denmark'),
(58, 'DJ', 'Djibouti'),
(59, 'DM', 'Dominica'),
(60, 'DO', 'Dominican Republic'),
(61, 'TP', 'East Timor'),
(62, 'EC', 'Ecuador'),
(63, 'EG', 'Egypt'),
(64, 'SV', 'El Salvador'),
(65, 'GQ', 'Equatorial Guinea'),
(66, 'ER', 'Eritrea'),
(67, 'EE', 'Estonia'),
(68, 'ET', 'Ethiopia'),
(69, 'FK', 'Falkland Islands (Malvinas)'),
(70, 'FO', 'Faroe Islands'),
(71, 'FJ', 'Fiji'),
(72, 'FI', 'Finland'),
(73, 'FR', 'France'),
(74, 'FX', 'France, Metropolitan'),
(75, 'GF', 'French Guiana'),
(76, 'PF', 'French Polynesia'),
(77, 'TF', 'French Southern Territories'),
(78, 'GA', 'Gabon'),
(79, 'GM', 'Gambia'),
(80, 'GE', 'Georgia'),
(81, 'DE', 'Germany'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GR', 'Greece'),
(85, 'GL', 'Greenland'),
(86, 'GD', 'Grenada'),
(87, 'GP', 'Guadeloupe'),
(88, 'GU', 'Guam'),
(89, 'GT', 'Guatemala'),
(90, 'GN', 'Guinea'),
(91, 'GW', 'Guinea-Bissau'),
(92, 'GY', 'Guyana'),
(93, 'HT', 'Haiti'),
(94, 'HM', 'Heard and Mc Donald Islands'),
(95, 'HN', 'Honduras'),
(96, 'HK', 'Hong Kong'),
(97, 'HU', 'Hungary'),
(98, 'IS', 'Iceland'),
(99, 'IN', 'India'),
(100, 'ID', 'Indonesia'),
(101, 'IR', 'Iran (Islamic Republic of)'),
(102, 'IQ', 'Iraq'),
(103, 'IE', 'Ireland'),
(104, 'IL', 'Israel'),
(105, 'IT', 'Italy'),
(106, 'CI', 'Ivory Coast'),
(107, 'JM', 'Jamaica'),
(108, 'JP', 'Japan'),
(109, 'JO', 'Jordan'),
(110, 'KZ', 'Kazakhstan'),
(111, 'KE', 'Kenya'),
(112, 'KI', 'Kiribati'),
(113, 'KP', 'Korea, Democratic People\'s Republic of'),
(114, 'KR', 'Korea, Republic of'),
(115, 'XK', 'Kosovo'),
(116, 'KW', 'Kuwait'),
(117, 'KG', 'Kyrgyzstan'),
(118, 'LA', 'Lao People\'s Democratic Republic'),
(119, 'LV', 'Latvia'),
(120, 'LB', 'Lebanon'),
(121, 'LS', 'Lesotho'),
(122, 'LR', 'Liberia'),
(123, 'LY', 'Libyan Arab Jamahiriya'),
(124, 'LI', 'Liechtenstein'),
(125, 'LT', 'Lithuania'),
(126, 'LU', 'Luxembourg'),
(127, 'MO', 'Macau'),
(128, 'MK', 'Macedonia'),
(129, 'MG', 'Madagascar'),
(130, 'MW', 'Malawi'),
(131, 'MY', 'Malaysia'),
(132, 'MV', 'Maldives'),
(133, 'ML', 'Mali'),
(134, 'MT', 'Malta'),
(135, 'MH', 'Marshall Islands'),
(136, 'MQ', 'Martinique'),
(137, 'MR', 'Mauritania'),
(138, 'MU', 'Mauritius'),
(139, 'TY', 'Mayotte'),
(140, 'MX', 'Mexico'),
(141, 'FM', 'Micronesia, Federated States of'),
(142, 'MD', 'Moldova, Republic of'),
(143, 'MC', 'Monaco'),
(144, 'MN', 'Mongolia'),
(145, 'ME', 'Montenegro'),
(146, 'MS', 'Montserrat'),
(147, 'MA', 'Morocco'),
(148, 'MZ', 'Mozambique'),
(149, 'MM', 'Myanmar'),
(150, 'NA', 'Namibia'),
(151, 'NR', 'Nauru'),
(152, 'NP', 'Nepal'),
(153, 'NL', 'Netherlands'),
(154, 'AN', 'Netherlands Antilles'),
(155, 'NC', 'New Caledonia'),
(156, 'NZ', 'New Zealand'),
(157, 'NI', 'Nicaragua'),
(158, 'NE', 'Niger'),
(159, 'NG', 'Nigeria'),
(160, 'NU', 'Niue'),
(161, 'NF', 'Norfork Island'),
(162, 'MP', 'Northern Mariana Islands'),
(163, 'NO', 'Norway'),
(164, 'OM', 'Oman'),
(165, 'PK', 'Pakistan'),
(166, 'PW', 'Palau'),
(167, 'PA', 'Panama'),
(168, 'PG', 'Papua New Guinea'),
(169, 'PY', 'Paraguay'),
(170, 'PE', 'Peru'),
(171, 'PH', 'Philippines'),
(172, 'PN', 'Pitcairn'),
(173, 'PL', 'Poland'),
(174, 'PT', 'Portugal'),
(175, 'PR', 'Puerto Rico'),
(176, 'QA', 'Qatar'),
(177, 'RE', 'Reunion'),
(178, 'RO', 'Romania'),
(179, 'RU', 'Russian Federation'),
(180, 'RW', 'Rwanda'),
(181, 'KN', 'Saint Kitts and Nevis'),
(182, 'LC', 'Saint Lucia'),
(183, 'VC', 'Saint Vincent and the Grenadines'),
(184, 'WS', 'Samoa'),
(185, 'SM', 'San Marino'),
(186, 'ST', 'Sao Tome and Principe'),
(187, 'SA', 'Saudi Arabia'),
(188, 'SN', 'Senegal'),
(189, 'RS', 'Serbia'),
(190, 'SC', 'Seychelles'),
(191, 'SL', 'Sierra Leone'),
(192, 'SG', 'Singapore'),
(193, 'SK', 'Slovakia'),
(194, 'SI', 'Slovenia'),
(195, 'SB', 'Solomon Islands'),
(196, 'SO', 'Somalia'),
(197, 'ZA', 'South Africa'),
(198, 'GS', 'South Georgia South Sandwich Islands'),
(199, 'ES', 'Spain'),
(200, 'LK', 'Sri Lanka'),
(201, 'SH', 'St. Helena'),
(202, 'PM', 'St. Pierre and Miquelon'),
(203, 'SD', 'Sudan'),
(204, 'SR', 'Suriname'),
(205, 'SJ', 'Svalbarn and Jan Mayen Islands'),
(206, 'SZ', 'Swaziland'),
(207, 'SE', 'Sweden'),
(208, 'CH', 'Switzerland'),
(209, 'SY', 'Syrian Arab Republic'),
(210, 'TW', 'Taiwan'),
(211, 'TJ', 'Tajikistan'),
(212, 'TZ', 'Tanzania, United Republic of'),
(213, 'TH', 'Thailand'),
(214, 'TG', 'Togo'),
(215, 'TK', 'Tokelau'),
(216, 'TO', 'Tonga'),
(217, 'TT', 'Trinidad and Tobago'),
(218, 'TN', 'Tunisia'),
(219, 'TR', 'Turkey'),
(220, 'TM', 'Turkmenistan'),
(221, 'TC', 'Turks and Caicos Islands'),
(222, 'TV', 'Tuvalu'),
(223, 'UG', 'Uganda'),
(224, 'UA', 'Ukraine'),
(225, 'AE', 'United Arab Emirates'),
(226, 'GB', 'United Kingdom'),
(227, 'UM', 'United States minor outlying islands'),
(228, 'UY', 'Uruguay'),
(229, 'UZ', 'Uzbekistan'),
(230, 'VU', 'Vanuatu'),
(231, 'VA', 'Vatican City State'),
(232, 'VE', 'Venezuela'),
(233, 'VN', 'Vietnam'),
(234, 'VG', 'Virgin Islands (British)'),
(235, 'VI', 'Virgin Islands (U.S.)'),
(236, 'WF', 'Wallis and Futuna Islands'),
(237, 'EH', 'Western Sahara'),
(238, 'YE', 'Yemen'),
(239, 'YU', 'Yugoslavia'),
(240, 'ZR', 'Zaire'),
(241, 'ZM', 'Zambia'),
(242, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE IF NOT EXISTS `donations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(11) UNSIGNED NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `postal_code` varchar(100) NOT NULL,
  `donation` int(11) UNSIGNED NOT NULL,
  `payment_gateway` varchar(100) NOT NULL,
  `oauth_uid` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `anonymous` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No, 1 Yes',
  `rewards_id` int(10) UNSIGNED NOT NULL,
  `bank_swift_code` varchar(250) NOT NULL,
  `account_number` varchar(250) NOT NULL,
  `branch_name` varchar(250) NOT NULL,
  `branch_address` varchar(250) NOT NULL,
  `account_name` varchar(250) NOT NULL,
  `iban` varchar(250) NOT NULL,
  `approved` enum('0','1') NOT NULL DEFAULT '1',
  `bank_transfer` text NOT NULL,
  `order_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `campaigns_id` (`campaigns_id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `campaigns_id`, `txn_id`, `fullname`, `email`, `country`, `postal_code`, `donation`, `payment_gateway`, `oauth_uid`, `comment`, `date`, `anonymous`, `rewards_id`, `bank_swift_code`, `account_number`, `branch_name`, `branch_address`, `account_name`, `iban`, `approved`, `bank_transfer`, `order_id`) VALUES
(1, 1, 'null', 'Sammy', 'unlockwealthdna@gmail.com', 'Nigeria', '101283', 1000, 'Bank Transfer', '', '', '2024-12-31 00:37:26', '0', 0, '', '', '', '', '', '', '1', '0035920750\r\nSTANBIC IBTC BANK\r\nALIU SAMUEL OLAMILEKAN', NULL),
(2, 2, 'null', 'Young Done Tech', 'Youngdone@gmail.com', 'Bahamas', '565465', 5000, 'Bank Transfer', '', '', '2024-12-31 19:05:44', '0', 0, '', '', '', '', '', '', '1', '0035920750\r\nSTANBIC IBTC BANK\r\nALIU SAMUEL OLAMILEKAN', NULL),
(5, 1, 'null', 'Samhuncho', 'sam@gmail.com', 'Nigeria', '1083', 500, 'Bank Transfer', '', '', '2025-01-01 12:04:18', '0', 0, '', '', '', '', '', '', '1', '6464777474747\r\nSam Bank Transfer', NULL),
(6, 2, 'null', 'Young Done', 'youngdone@gmail.com', 'United Kingdom', '101283', 10000, 'Bank Transfer', '', '', '2025-01-01 12:04:32', '0', 0, '', '', '', '', '', '', '1', '0035920750\r\nSTANBIC IBTC BANK', NULL),
(7, 3, 'null', 'Young Done', 'usmanbalogun044@gmail.com', 'Nigeria', '008485', 50000, 'Bank Transfer', '', '', '2025-02-05 02:14:40', '0', 0, '', '', '', '', '', '', '1', 'done and dusted', NULL),
(8, 3, 'null', 'Young Done', 'usmanbalogun044@gmail.com', 'Nigeria', '008485', 50000, 'Bank Transfer', '', '', '2025-02-05 02:14:48', '0', 0, '', '', '', '', '', '', '1', 'done and dusted', NULL),
(9, 3, 'null', 'Young Done', 'usmanbalogun044@gmail.com', 'Nigeria', '008485', 50000, 'Bank Transfer', '', '', '2025-02-05 02:15:03', '0', 0, '', '', '', '', '', '', '1', 'done and dusted', NULL),
(10, 3, 'null', 'Young Done', 'usmanbalogun044@gmail.com', 'Nigeria', '008485', 50000, 'Bank Transfer', '', '', '2025-02-05 02:15:07', '0', 0, '', '', '', '', '', '', '1', 'done and dusted', NULL),
(13, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100000, 'card', '', '', '2025-02-06 23:45:07', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(14, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100000, 'card', '', '', '2025-02-06 23:45:19', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(15, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100000, 'card', '', '', '2025-02-07 00:05:55', '0', 0, '', '', '', '', '', '', '', '', NULL),
(16, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 260, 'card', '', '', '2025-02-07 03:48:41', '0', 0, '', '', '', '', '', '', '', '', NULL),
(17, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 70, 'card', '', '', '2025-02-07 04:20:01', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(18, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 8098, 'card', '', '', '2025-02-07 04:21:38', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(19, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'Bank Transfer', '', '', '2025-02-07 17:14:46', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/h0WtkOo9gQrZFtkoJBotsBLutQbTwQto9Og9XWfb.jpg', NULL),
(20, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'Bank Transfer', '', '', '2025-02-07 17:15:05', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/nh8j635TXtVGUg9bwDiLqY7G9ywquBpXbBcdFNAB.jpg', NULL),
(23, 3, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 908, 'wiretransfer', '', '', '2025-02-07 23:42:09', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/BkVgg9xd6ZWAgrCGzvfZEBBPhchn1qpZVnKeayeQ.jpg', NULL),
(24, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'paypal', '', '', '2025-02-08 16:58:48', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(25, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'paypal', '', '', '2025-02-09 02:56:18', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/fML9iTBPKcARAlKxPEqNoA5IIHVbM84LbeoZ51NI.jpg', NULL),
(26, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 70, 'vemo', '', '', '2025-02-09 04:36:00', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/i2CNbpV8Qdo8VKSr0ppz1XFar2qUqRNmEFQbq0Vi.jpg', NULL),
(27, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 90, 'vemo', '', '', '2025-02-09 05:26:23', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/lNF6lCiSdK0T9jQpK9AwUysUI4ktXO9KnPg0JPX3.jpg', NULL),
(28, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-09 05:28:09', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/17Vy0EeDmsGFIQfMu2dbRpzVuq4RNGr4saPCLOLL.jpg', NULL),
(29, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-09 05:28:51', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/9z5UGAGmAxo18mhvqQ4oRcUdLHwI8XHiNvc0G0Iw.jpg', NULL),
(45, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-10 06:10:54', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(46, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'coinpayments', '', '', '2025-02-10 06:13:27', '0', 0, '', '', '', '', '', '', '1', '', 'ORD-67a9288715a9d'),
(47, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100, 'coinpayments', '', '', '2025-02-10 10:43:56', '0', 0, '', '', '', '', '', '', '0', '', 'ORD-67a967ec969b8'),
(48, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-10 19:32:07', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(49, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 700, 'card', '', '', '2025-02-10 19:33:19', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(50, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-10 19:35:04', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(51, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-10 19:42:56', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(52, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-10 19:45:12', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/HWEaNf9X9n5M9cllnjgDYEYiUpC1y9qfyJuNAj3r.jpg', NULL),
(53, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-10 19:49:20', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/RA3YQiyl0lIIsu8JlgqoQmroTxLP0qH7rZ75u8P6.jpg', NULL),
(54, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-10 19:53:34', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/oCWLEAC5NnXrQ44SV7wLc5VQHDKoAT0BHfd7JvBA.jpg', NULL),
(55, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'coinpayments', '', '', '2025-02-10 20:23:24', '0', 0, '', '', '', '', '', '', '0', '', 'ORD-67a9efbc4a598'),
(56, 4, '', 'usman balogun', 'user@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-10 20:24:48', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/siBG3eFBOrpvRKY3Q3uM2JL0wnMgYsInnFOvYvD7.jpg', NULL),
(57, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100, 'coinpayments', '', '', '2025-02-11 02:18:23', '0', 0, '', '', '', '', '', '', '0', '', 'ORD-67aa42ef036eb'),
(58, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:27:33', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(59, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 100, 'card', '', '', '2025-02-11 02:29:50', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(60, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:39:16', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(61, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:45:06', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(62, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:48:23', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(63, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:49:47', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(64, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:51:32', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(65, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:53:02', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(66, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-11 02:53:54', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(67, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:00:31', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/cjxgdloDiimot8ri41MhVju1GeojJKnIKu33RhbX.jpg', NULL),
(68, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:02:20', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/CdzuDXRwSJBlgJPMPBQWcGs76j2qszdivvLDM32z.jpg', NULL),
(69, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:05:20', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/6n82FisUkVKRd22uveaMtAkqS6SYFRM1r2KmGatM.jpg', NULL),
(70, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:05:37', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/F075eL9DIQlMku5CC8xAIZcPXFHOy2FKJJRw0Owq.jpg', NULL),
(71, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:05:59', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/uZktxcRS7YrkyuqVU6Lm21xvEkhWrN7Lu6xa9XDV.jpg', NULL),
(72, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:06:32', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/bQPcQSKmhifdNb17oN5HTI0F1s16QY5QPWWqy6qa.jpg', NULL),
(73, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:06:46', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/BIxVkBHVPQCnFpcyfghCm1NRQw7ATIE8jzmBn5L9.jpg', NULL),
(74, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:07:02', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/z2V20KxSLSNLhrlN3XtEcc36KAk0SjqzaQNX5ndq.jpg', NULL),
(75, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:10:15', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/ot855SJE4LIg7acSBZwCka3TThvePpRkkTiQsM0J.jpg', NULL),
(76, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:14:13', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/0bMuWtW9Rg9skz7IUfPE4PHyHHRJIBfHyveXDbzp.jpg', NULL),
(77, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:16:02', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/8aRvTvnhRsB4ttUc4e6qPZE1QrXQd6i8vgwNwY5V.jpg', NULL),
(78, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:25:57', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/rf2RNppxiHtWXOnW1zNJpbeJupZjNxUJ5PASg0Nq.jpg', NULL),
(79, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:27:34', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/01B9gGvXg2Z5XeYPKLXR351eG3zUIpO4Yt6m0u92.jpg', NULL),
(80, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:29:49', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/h5iu7HParR4YkbAEldy4dx9IAZRBVkGmQgLUFXvF.jpg', NULL),
(82, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-11 03:41:23', '0', 0, '', '', '', '', '', '', '1', 'bank_transfer/jpYK0pX2rPHBFaq7B8yaldocan3UrIlmeo9LIRbv.jpg', NULL),
(83, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 7000, 'coinpayments', '', '', '2025-02-12 01:31:35', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8977eecb9'),
(84, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 7000, 'coinpayments', '', '', '2025-02-12 01:35:33', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8a65b7ae1'),
(85, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 1000, 'coinpayments', '', '', '2025-02-12 01:40:38', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8b96bbda0'),
(86, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 1000, 'coinpayments', '', '', '2025-02-12 01:41:28', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8bc81eaf0'),
(87, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 1000, 'coinpayments', '', '', '2025-02-12 01:44:03', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8c63c0194'),
(88, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 1000, 'coinpayments', '', '', '2025-02-12 01:44:14', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8c6eb0c3b'),
(89, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 1000, 'coinpayments', '', '', '2025-02-12 01:47:03', '1', 0, '', '', '', '', '', '', '0', '', 'ORD-67ab8d1785b4f'),
(91, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 800, 'card', '', '', '2025-02-12 02:10:07', '1', 0, '', '', '', '', '', '', '1', '', NULL),
(94, 7, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 300, 'card', '', '', '2025-02-12 02:14:18', '1', 0, '', '', '', '', '', '', '1', '', NULL),
(95, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 670, 'card', '', '', '2025-02-12 02:25:45', '1', 0, '', '', '', '', '', '', '1', '', NULL),
(96, 4, '', 'usmann', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 300, 'card', '', '', '2025-02-12 22:16:23', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(97, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:18:10', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(98, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 300, 'card', '', '', '2025-02-12 22:19:26', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(99, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 300, 'card', '', '', '2025-02-12 22:19:26', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(100, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:22:00', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(101, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:24:49', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(102, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:32:19', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(103, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:35:51', '0', 0, '', '', '', '', '', '', '0', '', NULL),
(104, 4, '', 'usman balogun', 'unlockwealthdna@gmail.com', 'nigeria', '1234', 10, 'card', '', '', '2025-02-12 22:37:32', '0', 0, '', '', '', '', '', '', '1', '', NULL),
(105, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'zelle', '', '', '2025-02-14 05:43:32', '1', 0, '', '', '', '', '', '', '0', 'bank_transfer/6TWBjVLSiG5JCSh70LoXYijXJ63ybG4owNoBe67N.jpg', NULL),
(106, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'vemo', '', '', '2025-02-14 05:44:53', '1', 0, '', '', '', '', '', '', '0', 'bank_transfer/aGV6sFVc2BhZ0NgcpEs94T2VI563Yk8oAZvXdyTM.jpg', NULL),
(107, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'paypal', '', '', '2025-02-14 05:58:08', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/pn6LmGMHO41yoED6uIqrqEOqZpVcs9T35jD3oPBG.jpg', NULL),
(108, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'cashapp', '', '', '2025-02-14 05:59:19', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/nVaoE4ap2ED3MNmKXEk7MozBAETAiqa8tBU7YLnI.jpg', NULL),
(109, 4, '', 'usman balogun', 'usmanbalogun044@gmail.com', 'nigeria', '1234', 10, 'paypal', '', '', '2025-02-14 06:22:57', '0', 0, '', '', '', '', '', '', '0', 'bank_transfer/SZdvxeI55BOB0HnZLTdcCiZY3a4oNZEQxQQLCu6i.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `abbreviation` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `abbreviation`) VALUES
(1, 'English', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `campaigns_id`, `status`, `date`) VALUES
(1, 1, 1, '1', '2024-12-28 20:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(100) NOT NULL,
  `show_navbar` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 No, 1 Yes',
  `lang` char(10) NOT NULL DEFAULT 'en',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `show_navbar`, `lang`) VALUES
(2, 'Terms', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \r\n\r\n<br/><br/>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \r\n\r\n<br/><br/>\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets ', 'terms-of-service', '0', 'en'),
(3, 'Privacy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets \n\n<br/><br/>\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'privacy', '0', 'en'),
(5, 'About us', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets<br />\r\n<br />\r\nLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'about', '0', 'en'),
(7, 'Support', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets <br /><br /> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>', 'support', '0', 'en'),
(8, 'How it works', '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'how-it-works', '1', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `token` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_hash` (`token`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `token`, `email`, `created_at`) VALUES
(20, '$2y$10$j2v38S7Gk3nC7uG/lD2vK.1sLm4w/vKb18B2xVT2XyTnZES/NxG06', 'usmanbalogun044@gmail.com', '2025-02-12 22:22:53');

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE IF NOT EXISTS `payment_gateways` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` varchar(255) NOT NULL,
  `enabled` enum('1','0') NOT NULL DEFAULT '1',
  `sandbox` enum('true','false') NOT NULL DEFAULT 'true',
  `fee` decimal(3,1) NOT NULL,
  `fee_cents` decimal(2,2) NOT NULL,
  `email` varchar(80) NOT NULL,
  `token` varchar(200) NOT NULL,
  `key` varchar(255) NOT NULL,
  `key_secret` varchar(255) NOT NULL,
  `bank_info` text NOT NULL,
  `paypal_form` varchar(200) NOT NULL,
  `client_id` text NOT NULL,
  `client_secret` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `type`, `enabled`, `sandbox`, `fee`, `fee_cents`, `email`, `token`, `key`, `key_secret`, `bank_info`, `paypal_form`, `client_id`, `client_secret`) VALUES
(1, 'PayPal', 'normal', '1', 'true', 5.4, 0.30, 'paypal@yoursite.com', '12bGGfD9bHevK3eJN06CdDvFSTXsTrTG44yGdAONeN1R37jqnLY1PuNF0mJRoFnsEygyf28yePSCA1eR0alQk4BX89kGG9Rlha2D2KX55TpDFNR5o774OshrkHSZLOFo2fAhHzcWKnwsYDFKgwuaRg', '', '', '377\r\nusmanbalogun@111', 'normal', 'Ae0Ccq-u3r0Lk4RLxCato0YBOr26f1Vgb2ZtXDK3MBkNc-_VzOE1ksfg0bgx1y4w_NC1rvJQd-_F7-ap', 'EO3jNc6sDAYlxgMZQtY7kIIhg3fnHINdlAVbVBSqDZbQUBd_bzBwq9uY1_AWfCNKM81frrYKqS5N3tK4'),
(2, 'card', 'card', '1', 'false', 2.9, 0.30, '', 'asfQSGRvYzS1P0X745krAAyHeU7ZbTpHbYKnxI2abQsBUi48EpeAu5lFAU2iBmsUWO5tpgAn9zzussI4Cce5ZcANIAmfBz0bNR9g3UfR4cserhkJwZwPsETiXiZuCixXVDHhCItuXTPXXSA6KITEoT', 'pk_test_51QpE8TBuVRtWOkxPQuzPHecHYsEqzZCy12fLbMAUjrz0Oabgo4ydercH4XjSj58pyivikjC3ZxJ01Cy2hN6BzFLo00JNWqFKI5', 'sk_test_51QpE8TBuVRtWOkxPDk8lKH6dqiIXFbcPqPhBij9q7HNyQ2rdSspQV9rB2LbvnpgGazU3i8bVYTOPjz0rHqdXHAF500M3N1lJLv', '', '', '', ''),
(3, 'Bank Transfer', 'bank', '1', 'false', 0.0, 0.00, '', 'zzzdH5811lZSjioHrg3zLD69DAAMvPLiwdzTouAdc7HbtaqgujPEZjH3i7RGeRtFKrY2baT7rXd6CaBtsRpo4XtgHvqCyCWiW5BlCrg1uSMCOSdi1tzPjCPx8px280YEyLvNtiRzWHJJk8WRegfTms', '', '', '0035920750\r\nSTANBIC IBTC BANK \r\nALIU SAMUEL OLAMILEKAN', '', '', ''),
(4, 'wiretransfer', '', '1', 'false', 4.0, 0.00, '', '', '', '', 'owpejjfsd\r\n0813-84\r\n1741u4nadlsh', '', '', ''),
(5, 'vemo', '', '1', 'true', 0.0, 0.00, '', '', '', '', 'ghvv \\\r\n65', '', '', ''),
(6, 'zelle', '', '1', 'true', 0.0, 0.00, '', '', '', '', 'kkhjnkby\r\nbbyubh', '', '', ''),
(7, 'cashapp', '', '1', 'true', 0.0, 0.00, '', '', '', '', 'h9009unkjji\r\nng', '', '', ''),
(8, 'coinpayments', 'coinpayments', '1', 'true', 0.0, 0.00, '', '', 'MDMRH4-PHETY8-YFEZGT-MWGM6S', '123456', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `reserved`
--

CREATE TABLE IF NOT EXISTS `reserved` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reserved`
--

INSERT INTO `reserved` (`id`, `name`) VALUES
(14, 'account'),
(31, 'api'),
(2, 'app'),
(46, 'blog'),
(30, 'bootstrap'),
(37, 'campaigns'),
(34, 'categories'),
(36, 'collections'),
(29, 'comment'),
(42, 'config'),
(25, 'contact'),
(41, 'database'),
(35, 'featured'),
(32, 'freebies'),
(45, 'gallery'),
(9, 'goods'),
(1, 'gostock1'),
(11, 'jobs'),
(21, 'join'),
(16, 'latest'),
(20, 'login'),
(33, 'logout'),
(27, 'members'),
(13, 'messages'),
(19, 'notifications'),
(15, 'popular'),
(6, 'porn'),
(26, 'programs'),
(12, 'projects'),
(3, 'public'),
(23, 'register'),
(40, 'resources'),
(39, 'routes'),
(17, 'search'),
(7, 'sex'),
(44, 'storage'),
(8, 'tags'),
(38, 'tests'),
(24, 'upgrade'),
(28, 'upload'),
(4, 'vendor'),
(5, 'xxx');

-- --------------------------------------------------------

--
-- Table structure for table `rewards`
--

CREATE TABLE IF NOT EXISTS `rewards` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `delivery` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `token_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_id` (`token_id`),
  KEY `author_id` (`token_id`),
  KEY `image` (`image`),
  KEY `category_id` (`campaigns_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `countries_id` char(25) NOT NULL,
  `password` char(60) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar` varchar(70) NOT NULL,
  `status` enum('pending','active','suspended','delete') NOT NULL DEFAULT 'active',
  `role` enum('normal','admin') NOT NULL DEFAULT 'normal',
  `remember_token` varchar(100) NOT NULL,
  `token` varchar(80) NOT NULL,
  `confirmation_code` varchar(125) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `paypal_account` varchar(200) NOT NULL,
  `payment_gateway` varchar(50) NOT NULL,
  `bank` text NOT NULL,
  `oauth_uid` varchar(200) NOT NULL,
  `oauth_provider` varchar(200) NOT NULL,
  `username` varchar(50) NOT NULL,
  `phone` int(10) UNSIGNED NOT NULL,
  `street` varchar(255) NOT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `passport` varchar(255) DEFAULT NULL,
  `passport_front` varchar(255) DEFAULT NULL,
  `passport_back` varchar(255) DEFAULT NULL,
  `national_id_front` varchar(255) DEFAULT NULL,
  `national_id_back` varchar(255) DEFAULT NULL,
  `homeaddress` varchar(255) DEFAULT NULL,
  `streetaddress` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `zelle` varchar(255) DEFAULT NULL,
  `venmo` varchar(255) DEFAULT NULL,
  `apple_pay` varchar(255) DEFAULT NULL,
  `crypto_wallet` varchar(255) DEFAULT NULL,
  `international_bank` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `username` (`status`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `countries_id`, `password`, `email`, `email_verified`, `date`, `avatar`, `status`, `role`, `remember_token`, `token`, `confirmation_code`, `updated_at`, `created_at`, `paypal_account`, `payment_gateway`, `bank`, `oauth_uid`, `oauth_provider`, `username`, `phone`, `street`, `national_id`, `passport`, `passport_front`, `passport_back`, `national_id_front`, `national_id_back`, `homeaddress`, `streetaddress`, `city`, `state`, `zipcode`, `is_verified`, `zelle`, `venmo`, `apple_pay`, `crypto_wallet`, `international_bank`) VALUES
(1, 'Admin', '1', '$2y$10$w/laxDgcmRoIfwcCpKOtzOZrfXPGJE1iHjxf08wh2akHYQ9ZLgC7S', 'admin@gmail.com', 0, '2025-01-27 04:25:41', 'admin.jpg', 'active', 'admin', 'tBzS6qzRP7Wk78AnHRYrjCn0zpnZSzEcaG2NeESdfxV9cubuOUTXHPyD3IAN', 'Wy4VkAl2dxHb9WHoXjTowSGPXFPnEQHca6RBe2yeqqmRafs0hSbCEobhNkZZAbCDIru60ceLzAAOI3fj', '', '2025-02-11 21:34:04', '2016-09-09 15:34:42', 'sammy.fundme@gmail.com', 'Venmo', 'stanbic ibtc bank\r\n003592077788\r\nchecking account', '', '', '', 0, '', '1_national_id_1738095469qg7xtuuwf7lol6k.png', '1_passport_1738095468fgfjgwo89vivb1d.png', 'passport/1738999057_passport_front.jpeg', NULL, 'national_id/1738999057_national_id_front.jpeg', 'national_id/1738999057_national_id_back.jpg', '17 otunowo', '87acrade', 'lagos', 'lagos', '123456', 1, '{\"zelle_name\":\"677\",\"zelle_contact\":\"usman@gmail.com\"}', '{\"name\":\"vemo\",\"phone\":\"0908890890\"}', NULL, NULL, NULL),
(2, 'Young Done', '159', '$2y$10$CG79hpbK7Pw.vj5f89De8e1x6dXlybc2WfMsWg1GSwI3qJFgwGnhO', 'usmanbalogun044@gmail.com', 0, '2024-12-31 01:33:31', '21736093568ftxlliechoysohf.png', 'active', 'normal', 'aipzM7Dg5GE9s6FoywbXprtfDreBlebjGCS1fmxry83RMJ2V0jBwek3Jeglq', 'IUA9OQO6YsFIAuBHvMj265VyJi1L33WxmdxEF2mNOzoYBpJxYjxAVBHaQ0gF1RpJoNDqlc3tBoT', '', '2025-02-14 14:36:44', '2024-12-31 00:33:31', 'usmanbalogun044@gmail.com', 'Applepay', '2323234424w4\r\nGrove Bank\r\nSavings Account', '', '', '', 0, '', NULL, NULL, 'passport/1738356429_passport_front.jpg', NULL, 'national_id/1738356429_national_id_front.jpg', 'national_id/1738356429_national_id_back.jpg', '17,otunowo stret eleshin ikorodu lagos', '17,otunowo street eleshin ikorodu lagos', 'lagos', 'lagos', '100313', 1, '{\"zelle_name\":\"usman\",\"zelle_contact\":\"usmanbalogun044@gmail.com\"}', '{\"name\":\"usmanbalogun\",\"phone\":\"07044060102\"}', '{\"apple_name\":\"usman\",\"apple_id\":\"8907798\"}', '{\"crypto_type\":\"doge\",\"crypto_network\":\"27g378ro837\",\"crypto_wallet\":\"jjur8cu94hr938459yc\"}', NULL),
(42, 'dollarhunter', '159', '$2y$10$AMhOne45XLy5/N5.YIBJ5.EvNxAa4.khzZaHO/qMdp/w7Ps0hZT.6', 'dollarhunter044@gmail.com', 0, '2025-02-01 10:42:19', 'default.jpg', 'active', 'normal', 'XE6eHZmYnHX1BL2LfbJVHEC4UpKqczzYdjUe8wz4hthEPDjwrTng7rrGdw6J', '', '', '2025-02-14 14:35:02', '2025-02-01 18:42:19', 'dollarhunter044@gmail.com', 'Bank', 'uba 7044060102\r\nbalogunusman temitope', '', '', '', 0, '', NULL, NULL, 'passport/1738406696_passport_front.jpeg', NULL, 'national_id/1738406696_national_id_front.png', 'national_id/1738406696_national_id_back.png', '17,0tunowo street eleshin ikorodu lagos', '17,0tunowo street eleshin ikorodu lagos', 'ikorodu', 'lagos', '123456', 1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `campaigns_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `amount` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `gateway` varchar(100) NOT NULL,
  `account` text NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `txn_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campaings_id` (`campaigns_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `campaigns_id`, `status`, `amount`, `date`, `gateway`, `account`, `date_paid`, `txn_id`) VALUES
(2, 2, 'paid', '14,550.00', '2025-01-30 15:45:37', 'Paypal', 'user@gmail.com', '2025-01-31 00:12:21', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
