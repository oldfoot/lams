CREATE TABLE `leave_status_master` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(30) DEFAULT NULL,
  `is_new` char(1) DEFAULT 'n',
  `is_new_default` char(1) DEFAULT 'n',
  `is_approved` char(1) DEFAULT 'n',
  `is_approved_default` char(1) DEFAULT 'n',
  `is_rejected` char(1) DEFAULT 'n',
  `is_rejected_default` char(1) DEFAULT 'n',
  `is_deleted` char(1) DEFAULT 'n',
  `is_deleted_default` char(1) DEFAULT 'n',
  `workspace_id` int(5) DEFAULT NULL,
  `teamspace_id` int(5) DEFAULT NULL,
  `allow_edit` char(1) DEFAULT 'n',
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8