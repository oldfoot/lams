CREATE TABLE `core_workspace_rolepriv` (
  `WorkspaceRoleID` int(5) NOT NULL,
  `WorkspaceRolePriv` varchar(25) NOT NULL,
  PRIMARY KEY (`WorkspaceRoleID`,`WorkspaceRolePriv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1