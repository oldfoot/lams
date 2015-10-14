CREATE TABLE `leave_application_approval` (
  `ApplicationID` int(5) NOT NULL DEFAULT '0',
  `UserID` int(5) NOT NULL DEFAULT '0',
  `Approved` char(1) DEFAULT NULL,
  `DateUpdated` datetime DEFAULT NULL,
  PRIMARY KEY (`ApplicationID`,`UserID`),
  KEY `application_id` (`ApplicationID`),
  KEY `user_id` (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8