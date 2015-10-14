CREATE TABLE `core_module_master` (
  `ModuleID` int(5) NOT NULL AUTO_INCREMENT,
  `ModuleName` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Logo` varchar(100) DEFAULT NULL,
  `CoreModule` char(1) DEFAULT 'y',
  `AvailableTeamSpaces` char(1) DEFAULT 'n',
  `AnonymousAccess` char(1) DEFAULT 'n',
  PRIMARY KEY (`ModuleID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1