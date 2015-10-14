CREATE TABLE `leave_category_master` (
  `CategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(100) DEFAULT NULL,
  `AllowNegativeBalance` char(1) DEFAULT 'n',
  `WorkspaceID` int(11) DEFAULT NULL,
  `AllowBalanceCarryForward` char(1) DEFAULT 'n',
  `PaidUnpaid` varchar(6) DEFAULT 'paid',
  `AutoApprove` char(1) DEFAULT 'n',
  `Planning` char(1) DEFAULT 'n',
  `UserIDLastModified` int(5) DEFAULT NULL,
  `DateTimeLastModified` datetime DEFAULT NULL,
  PRIMARY KEY (`CategoryID`),
  UNIQUE KEY `PK` (`CategoryName`,`WorkspaceID`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8