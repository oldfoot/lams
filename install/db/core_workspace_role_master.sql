CREATE TABLE `core_workspace_role_master` (
  `WorkspaceRoleID` int(5) NOT NULL AUTO_INCREMENT,
  `WorkspaceID` int(5) DEFAULT NULL,
  `RoleName` varchar(25) DEFAULT NULL,
  `DefaultRole` char(1) DEFAULT 'n',
  PRIMARY KEY (`WorkspaceRoleID`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1