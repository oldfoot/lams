CREATE TABLE `leave_status_master` (
  `StatusID` int(11) NOT NULL AUTO_INCREMENT,
  `StatusName` varchar(30) DEFAULT NULL,
  `WorkspaceID` int(5) DEFAULT NULL,
  `UserIDLastModified` int(5) DEFAULT NULL,
  `DateTimeLastModified` datetime DEFAULT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8