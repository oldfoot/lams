CREATE TABLE `leave_user_settings` (
  `user_id` int(5) NOT NULL,
  `period_id` int(5) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `period_id` (`period_id`),
  CONSTRAINT `leave_user_settings_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `leave_period_master` (`period_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8