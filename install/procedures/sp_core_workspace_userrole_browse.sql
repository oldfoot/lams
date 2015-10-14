CREATE DEFINER=`lams`@`localhost` PROCEDURE `sp_core_workspace_userrole_browse`(IN pUserID INT(5),IN pWorkspaceID INT(5))
BEGIN
	SELECT a.RoleName, a.WorkspaceRoleID
	FROM core_workspace_role_master a, core_workspace_user_roles b
	WHERE b.UserID = pUserID
	AND b.WorkspaceID = pWorkspaceID
	AND b.WorkspaceRoleID = a.WorkspaceRoleID;
    END