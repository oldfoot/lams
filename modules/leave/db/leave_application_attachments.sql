CREATE TABLE `leave_application_attachments` (
  `attachment_id` int(5) NOT NULL AUTO_INCREMENT,
  `application_id` int(5) DEFAULT NULL,
  `filetype` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `filesize` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `filename` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `attachment` longblob,
  PRIMARY KEY (`attachment_id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `leave_application_attachments_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `leave_applications` (`application_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci