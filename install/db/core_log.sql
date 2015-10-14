CREATE TABLE `core_log` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `SCRIPT_NAME` varchar(255) DEFAULT NULL,
  `DateTimeLogged` datetime DEFAULT NULL,
  `SessionID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1