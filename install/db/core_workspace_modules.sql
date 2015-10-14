CREATE TABLE `core_workspace_modules` (
  `WorkspaceID` int(5) NOT NULL DEFAULT '0',
  `ModuleID` int(5) NOT NULL DEFAULT '0',
  `RoleID` int(5) NOT NULL,
  PRIMARY KEY (`WorkspaceID`,`ModuleID`,`RoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1