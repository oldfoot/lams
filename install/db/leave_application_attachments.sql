CREATE TABLE `leave_application_attachments` (
  `AttachmentID` int(5) NOT NULL AUTO_INCREMENT,
  `ApplicationID` int(5) DEFAULT NULL,
  `Filetype` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `Filesize` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `Filename` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `Attachment` longblob,
  PRIMARY KEY (`AttachmentID`),
  KEY `application_id` (`ApplicationID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci