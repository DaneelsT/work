CREATE TABLE `months` (
  `id` int(11) NOT NULL,
  `month` int(2) NOT NULL,
  `hoursWorked` decimal(4,1) NOT NULL,
  `earnings` decimal(10,2) NOT NULL,
  `sundaysWorked` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `startTime` int(10) UNSIGNED NOT NULL,
  `endTime` int(10) UNSIGNED NOT NULL,
  `isSunday` tinyint(4) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
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

CREATE TABLE `users_pay` (
  `userid` int(11) NOT NULL,
  `hourly_pay` decimal(4,2) NOT NULL,
  `sunday_fee` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

ALTER TABLE `months`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `shifts`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tokens`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;