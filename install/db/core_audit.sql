CREATE TABLE `core_audit` (
  `LogID` int(5) NOT NULL AUTO_INCREMENT,
  `Description` varchar(255) DEFAULT NULL,
  `Action` varchar(10) DEFAULT NULL,
  `UserIDModified` int(5) DEFAULT NULL,
  `DateTimeModified` datetime DEFAULT NULL,
  PRIMARY KEY (`LogID`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1