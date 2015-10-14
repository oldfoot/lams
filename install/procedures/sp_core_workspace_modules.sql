CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_modules`(in pWorkspaceID INT(5))
BEGIN
SELECT a.ModuleID, a.ModuleName, a.Description, a.Logo
FROM core_module_master a, core_workspace_modules b
WHERE b.ModuleID = a.ModuleID
AND b.WorkspaceID = pWorkspaceID;
    END