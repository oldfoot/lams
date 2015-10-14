CREATE TABLE `leave_report_master` (
  `report_name` varchar(50) NOT NULL,
  `version` varchar(10) DEFAULT NULL,
  `activated` char(1) DEFAULT 'y',
  PRIMARY KEY (`report_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1