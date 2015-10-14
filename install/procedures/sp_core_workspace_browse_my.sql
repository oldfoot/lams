CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_browse_my`(IN pUserID INT(5))
BEGIN
SELECT a.WorkspaceID, a.WorkspaceName
FROM core_workspace_master a, core_workspace_user_roles b
WHERE a.WorkspaceID = b.WorkspaceID
AND b.UserID = pUserID;
    END