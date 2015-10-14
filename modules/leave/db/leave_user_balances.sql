CREATE TABLE `leave_user_balances` (
  `user_id` int(5) NOT NULL,
  `period_id` int(5) NOT NULL DEFAULT '0',
  `category_id` int(5) NOT NULL DEFAULT '0',
  `leave_total` smallint(2) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`period_id`,`category_id`),
  KEY `period_id` (`period_id`),
  CONSTRAINT `leave_user_balances_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `leave_period_master` (`period_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8