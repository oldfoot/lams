CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_module_del`(IN pWorkspaceID INT(5),IN pModuleID INT(5),IN pUserID INT(5))
BEGIN
DELETE FROM core_workspace_modules
WHERE WorkspaceID = pWorkspaceID
AND ModuleID = pModuleID;
    END