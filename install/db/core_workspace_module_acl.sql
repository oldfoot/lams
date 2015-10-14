CREATE TABLE `core_workspace_module_acl` (
  `ModuleID` int(5) DEFAULT NULL,
  `WorkspaceID` int(5) DEFAULT NULL,
  `RoleID` int(5) DEFAULT NULL,
  `Feature` varchar(30) DEFAULT NULL,
  `Permission` varchar(1) DEFAULT 'r'
) ENGINE=InnoDB DEFAULT CHARSET=latin1