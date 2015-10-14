CREATE TABLE `leave_period_master` (
  `PeriodID` int(5) NOT NULL AUTO_INCREMENT,
  `DateFrom` date DEFAULT NULL,
  `DateTo` date DEFAULT NULL,
  `WorkspaceID` int(5) DEFAULT NULL,
  `Active` char(1) DEFAULT 'y',
  `UserIDLastModified` int(5) DEFAULT NULL,
  `DateTimeLastModified` datetime DEFAULT NULL,
  PRIMARY KEY (`PeriodID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8