CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_delete`(IN pWorkspaceID INT(5),IN pUserID INT(5))
BEGIN
DELETE FROM core_workspace_master
WHERE WorkspaceID = pWorkspaceID
AND WorkspaceID IN (
SELECT WorkspaceID 
FROM core_workspace_user_roles b, core_workspace_rolepriv c
WHERE b.UserID = pUserID
AND b.WorkspaceRoleID = c.WorkspaceRoleID
AND c.WorkspaceRolePriv = 'Delete Workspace' );
    END