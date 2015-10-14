CREATE TABLE `leave_category_balance` (
  `category_id` int(5) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `period_id` int(5) DEFAULT NULL,
  `default_balance` tinyint(2) DEFAULT NULL,
  KEY `period_id` (`period_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `leave_category_balance_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `leave_period_master` (`period_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leave_category_balance_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `leave_category_master` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8