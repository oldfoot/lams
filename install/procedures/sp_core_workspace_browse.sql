CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_browse`(IN pWorkspaceID INT(5))
BEGIN
SELECT *
FROM core_workspace_master a
WHERE WorkspaceID = pWorkspaceID;
    END