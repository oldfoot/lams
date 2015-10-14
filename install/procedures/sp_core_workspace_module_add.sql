CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_module_add`(IN pWorkspaceID INT(5),IN pModuleID INT(5),IN pUserID INT(5))
BEGIN
INSERT INTO core_workspace_modules
(WorkspaceID,ModuleID)
VALUES
(pWorkspaceID,pModuleID);
    END