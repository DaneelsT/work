CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` int(11) NOT NULL,
  `apikey` varchar(50) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `months` (
  `id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `hoursWorked` decimal(4,1) NOT NULL,
  `daysWorked` int(11) NOT NULL,
  `earnings` decimal(10,2) NOT NULL,
  `sundaysWorked` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `shifts` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `startTime` int(10) unsigned NOT NULL,
  `endTime` int(10) unsigned NOT NULL,
  `isSunday` tinyint(4) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users_language` (
  `userid` int(11) NOT NULL,
  `lang` varchar(5) NOT NULL DEFAULT 'en_US'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_pay` (
  `userid` int(11) NOT NULL,
  `hourly_pay` decimal(4,2) NOT NULL,
  `sunday_fee` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `api_keys`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `months`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `shifts`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `tokens`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `users_pay`
    ADD PRIMARY KEY (`userid`);
ALTER TABLE `users_language`
    ADD PRIMARY KEY (`userid`);
