CREATE TABLE `leave_user_settings` (
  `UserID` int(5) NOT NULL,
  `PeriodID` int(5) DEFAULT NULL,
  PRIMARY KEY (`UserID`),
  KEY `period_id` (`PeriodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8