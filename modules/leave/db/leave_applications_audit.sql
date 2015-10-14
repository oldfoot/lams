CREATE TABLE `leave_applications_audit` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `application_id` int(5) DEFAULT NULL,
  `date_leave` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `leave_applications_audit_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `leave_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1