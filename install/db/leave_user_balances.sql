CREATE TABLE `leave_user_balances` (
  `user_id` int(5) NOT NULL,
  `period_id` int(5) NOT NULL DEFAULT '0',
  `category_id` int(5) NOT NULL DEFAULT '0',
  `leave_total` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`period_id`,`category_id`),
  KEY `period_id` (`period_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8