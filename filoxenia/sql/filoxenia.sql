-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Jan 2016 um 13:48
-- Server-Version: 5.6.26
-- PHP-Version: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `filoxenia`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `admin_ranks`
--

CREATE TABLE IF NOT EXISTS `admin_ranks` (
  `ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `admin_ranks`
--

INSERT INTO `admin_ranks` (`ID`, `name`) VALUES
(1, 'Ticketsupporter'),
(2, 'Moderator'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_domains`
--

CREATE TABLE IF NOT EXISTS `cms_domains` (
  `ID` int(11) NOT NULL,
  `extension` varchar(40) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_domains`
--

INSERT INTO `cms_domains` (`ID`, `extension`) VALUES
(1, 'de'),
(2, 'com');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_forgotten`
--

CREATE TABLE IF NOT EXISTS `cms_forgotten` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_orders`
--

CREATE TABLE IF NOT EXISTS `cms_orders` (
  `ID` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lenght` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_payments`
--

CREATE TABLE IF NOT EXISTS `cms_payments` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `method` varchar(50) NOT NULL,
  `state` varchar(50) NOT NULL DEFAULT 'waiting',
  `pin` varchar(60) NOT NULL DEFAULT 'none',
  `transid` varchar(50) NOT NULL DEFAULT 'none',
  `value` double NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_products`
--

CREATE TABLE IF NOT EXISTS `cms_products` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `cat_id` int(10) NOT NULL,
  `highlight` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_products`
--

INSERT INTO `cms_products` (`ID`, `name`, `description`, `price`, `cat_id`, `highlight`) VALUES
(1, 'Basic', '<b>5GB</b> Storage\r\n<b>1</b> MySQL Database\r\n<b>Unlimited</b> Bandwidth\r\n<b>1</b> Free Domain\r\n<b>5</b> Users\r\n<b>10</b> Email Accounts\r\ncPanel & FTP', 9.99, 1, 0),
(2, 'Business', '<b>500GB</b> Storage\r\n<b>10</b> MySQL Database\r\n<b>Unlimited</b> Bandwidth\r\n<b>3</b> Free Domain\r\n<b>40</b> Users\r\n<b>100</b> Email Accounts\r\ncPanel & FTP', 39.99, 1, 1),
(3, 'Professional', '<b>Unlimited</b> Storage\r\n<b>Unlimited</b> MySQL Database\r\n<b>Unlimited</b> Bandwidth\r\n<b>10</b> Free Domain\r\n<b>Unlimited</b> Users\r\n<b>Unlimited</b> Email Accounts\r\ncPanel & FTP', 99.99, 1, 0),
(4, 'Mini', '<b>2</b>GB RAM\r\n<b>20</b>GB Space\r\n<b>2</b>TB Traffic\r\n<b>Fast</b> Install\r\nDebian OS 8', 5.99, 3, 0),
(5, 'Medium', '<b>4</b>GB RAM\r\n<b>100</b>GB Space\r\n<b>Unlimited</b> Traffic\r\n<b>Fast</b> Install\r\nDebian OS 8', 10.99, 3, 1),
(6, 'Advanced', 'Fast Domain\r\nNothing\r\nTest', 6.99, 2, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_product_cats`
--

CREATE TABLE IF NOT EXISTS `cms_product_cats` (
  `ID` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `isdomain` int(11) DEFAULT '0',
  `isvps` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `min_time` int(50) NOT NULL DEFAULT '30',
  `max_time` int(50) NOT NULL DEFAULT '360',
  `isweb` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_product_cats`
--

INSERT INTO `cms_product_cats` (`ID`, `name`, `isdomain`, `isvps`, `description`, `min_time`, `max_time`, `isweb`) VALUES
(1, 'Webspace', 0, 0, ' Cras justo odio, dapibus ac facilisis in, egestas eget quam. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis. Etiam porta sem malesuada magna mollis euismod. Cras justo odio, dapibus ac facilisis in, egestas eget quam.', 30, 360, 1),
(2, 'Domains', 1, 0, ' Cras justo odio, dapibus ac facilisis in, egestas eget quam. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis. Etiam porta sem malesuada magna mollis euismod. Cras justo odio, dapibus ac facilisis in, egestas eget quam.', 360, 360, 0),
(3, 'VPS', 0, 1, ' Cras justo odio, dapibus ac facilisis in, egestas eget quam. Curabitur blandit tempus porttitor. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis. Etiam porta sem malesuada magna mollis euismod. Cras justo odio, dapibus ac facilisis in, egestas eget quam.', 30, 360, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_requests`
--

CREATE TABLE IF NOT EXISTS `cms_requests` (
  `ID` int(11) NOT NULL,
  `type` varchar(60) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `product_id` int(11) NOT NULL,
  `product_type` int(11) NOT NULL,
  `os` varchar(100) NOT NULL,
  `nameserver` varchar(200) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'in progress',
  `order_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_request_answers`
--

CREATE TABLE IF NOT EXISTS `cms_request_answers` (
  `ID` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_secure_login`
--

CREATE TABLE IF NOT EXISTS `cms_secure_login` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `session_id` varchar(200) NOT NULL,
  `secure_id` varchar(200) NOT NULL,
  `expire` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_tickets`
--

CREATE TABLE IF NOT EXISTS `cms_tickets` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `title` varchar(50) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_ticket_answers`
--

CREATE TABLE IF NOT EXISTS `cms_ticket_answers` (
  `ID` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cms_vpsos`
--

CREATE TABLE IF NOT EXISTS `cms_vpsos` (
  `ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `cms_vpsos`
--

INSERT INTO `cms_vpsos` (`ID`, `name`) VALUES
(1, 'debian7'),
(2, 'debian8');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `credits` double NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_products`
--

CREATE TABLE IF NOT EXISTS `user_products` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `status` varchar(60) NOT NULL,
  `expire` int(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `domain` varchar(100) NOT NULL DEFAULT '0',
  `userlogin` varchar(100) NOT NULL DEFAULT '0',
  `panel` varchar(250) NOT NULL,
  `nameserver` varchar(300) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `os` varchar(100) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `admin_ranks`
--
ALTER TABLE `admin_ranks`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_domains`
--
ALTER TABLE `cms_domains`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_forgotten`
--
ALTER TABLE `cms_forgotten`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_orders`
--
ALTER TABLE `cms_orders`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_payments`
--
ALTER TABLE `cms_payments`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_products`
--
ALTER TABLE `cms_products`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_product_cats`
--
ALTER TABLE `cms_product_cats`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_requests`
--
ALTER TABLE `cms_requests`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_request_answers`
--
ALTER TABLE `cms_request_answers`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_secure_login`
--
ALTER TABLE `cms_secure_login`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_tickets`
--
ALTER TABLE `cms_tickets`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_ticket_answers`
--
ALTER TABLE `cms_ticket_answers`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `cms_vpsos`
--
ALTER TABLE `cms_vpsos`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `user_products`
--
ALTER TABLE `user_products`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `admin_ranks`
--
ALTER TABLE `admin_ranks`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `cms_domains`
--
ALTER TABLE `cms_domains`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `cms_forgotten`
--
ALTER TABLE `cms_forgotten`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_orders`
--
ALTER TABLE `cms_orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_payments`
--
ALTER TABLE `cms_payments`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_products`
--
ALTER TABLE `cms_products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `cms_product_cats`
--
ALTER TABLE `cms_product_cats`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `cms_requests`
--
ALTER TABLE `cms_requests`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_request_answers`
--
ALTER TABLE `cms_request_answers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_secure_login`
--
ALTER TABLE `cms_secure_login`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_tickets`
--
ALTER TABLE `cms_tickets`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_ticket_answers`
--
ALTER TABLE `cms_ticket_answers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `cms_vpsos`
--
ALTER TABLE `cms_vpsos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `user_products`
--
ALTER TABLE `user_products`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
