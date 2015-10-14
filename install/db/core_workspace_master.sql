CREATE TABLE `core_workspace_master` (
  `WorkspaceID` int(5) NOT NULL AUTO_INCREMENT,
  `WorkspaceIDParent` int(5) DEFAULT '0',
  `WorkspaceName` varchar(100) DEFAULT NULL,
  `Logo` varchar(100) DEFAULT NULL,
  `UserIDCreated` int(5) DEFAULT NULL,
  `DateTimeCreated` datetime DEFAULT NULL,
  PRIMARY KEY (`WorkspaceID`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1