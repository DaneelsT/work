/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(11) NOT NULL,
  `apikey` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `months` (
  `id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `months_data` (
  `id` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  `hoursWorked` decimal(4,1) NOT NULL,
  `daysWorked` int(11) NOT NULL,
  `sundaysWorked` int(11) NOT NULL,
  `earnings` decimal(10,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shifts` (
  `shift_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shifts_data` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `startTime` int(10) NOT NULL,
  `endTime` int(10) NOT NULL,
  `isSunday` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `gender` int(1) NOT NULL,
  `disabled` int(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(45) NOT NULL,
  `admin` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users_language` (
  `userid` int(11) NOT NULL,
  `lang` varchar(5) NOT NULL DEFAULT 'en_US'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_pay` (
  `userid` int(11) NOT NULL,
  `hourly_pay` decimal(4,2) NOT NULL,
  `sunday_fee` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `years` (
  `id` int(11) NOT NULL,
  `year` int(4) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `years_data` (
  `id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `monthsWorked` int(2) NOT NULL,
  `hoursWorked` decimal(4,1) NOT NULL,
  `daysWorked` int(11) NOT NULL,
  `sundaysWorked` int(11) NOT NULL,
  `earnings` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `months`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `months_data`
  ADD PRIMARY KEY (`id`), ADD KEY `month_id` (`month_id`);

ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

ALTER TABLE `shifts_data`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users_language`
  ADD PRIMARY KEY (`userid`), ADD UNIQUE KEY `userid` (`userid`);

ALTER TABLE `users_pay`
  ADD PRIMARY KEY (`userid`);

ALTER TABLE `years`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `years_data`
  ADD PRIMARY KEY (`id`), ADD KEY `year_id` (`year_id`);


ALTER TABLE `months`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `months_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `shifts_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `years_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `months_data`
ADD CONSTRAINT `months_data_ibfk_1` FOREIGN KEY (`month_id`) REFERENCES `months` (`id`);

ALTER TABLE `years_data`
ADD CONSTRAINT `years_data_ibfk_1` FOREIGN KEY (`year_id`) REFERENCES `years` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
