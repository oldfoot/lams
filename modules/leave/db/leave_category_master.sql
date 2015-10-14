CREATE TABLE `leave_category_master` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) DEFAULT NULL,
  `allow_negative_balance` char(1) DEFAULT 'n',
  `workspace_id` int(11) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  `allow_balance_carry_forward` char(1) DEFAULT 'n',
  `paid_unpaid` varchar(6) DEFAULT 'paid',
  `auto_approve` char(1) DEFAULT 'n',
  `planning` char(1) DEFAULT 'n',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `PK` (`category_name`,`workspace_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8