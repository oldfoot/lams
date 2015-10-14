CREATE TABLE `leave_applications` (
  `ApplicationID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) DEFAULT NULL,
  `DateFrom` date DEFAULT NULL,
  `DateTo` date DEFAULT NULL,
  `TotalHours` float DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `PeriodID` int(11) DEFAULT NULL,
  `Remarks` text,
  `StatusID` int(11) DEFAULT NULL,
  `DateApplication` datetime DEFAULT NULL,
  `WorkspaceID` int(11) DEFAULT NULL,
  `WorkflowOrder` int(5) DEFAULT NULL,
  PRIMARY KEY (`ApplicationID`),
  KEY `period_id` (`PeriodID`),
  KEY `FK_leave_applications` (`CategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8