CREATE TABLE `leave_applications` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `date_from_half_day` char(1) DEFAULT 'n',
  `date_from_half_day_am_pm` char(2) DEFAULT NULL,
  `date_to_half_day` char(1) DEFAULT 'n',
  `date_to_half_day_am_pm` char(2) DEFAULT NULL,
  `total_days` float DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `period_id` int(11) DEFAULT NULL,
  `remarks` text,
  `status_id` int(11) DEFAULT NULL,
  `date_application` datetime DEFAULT NULL,
  `workspace_id` int(11) DEFAULT NULL,
  `workflow_order` int(5) DEFAULT NULL,
  PRIMARY KEY (`application_id`),
  KEY `period_id` (`period_id`),
  KEY `FK_leave_applications` (`category_id`),
  CONSTRAINT `leave_applications_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `leave_period_master` (`period_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `leave_applications_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `leave_category_master` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8