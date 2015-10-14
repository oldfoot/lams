CREATE TABLE `leave_category_balance` (
  `category_id` int(5) DEFAULT NULL,
  `workspace_id` int(5) DEFAULT NULL,
  `period_id` int(5) DEFAULT NULL,
  `default_balance` tinyint(2) DEFAULT NULL,
  KEY `period_id` (`period_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8