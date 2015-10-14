CREATE TABLE `leave_applications_audit` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `application_id` int(5) DEFAULT NULL,
  `date_leave` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1