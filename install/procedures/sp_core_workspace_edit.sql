CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_edit`(IN pWorkspaceID INT(5), IN pWorkspaceName VARCHAR(100), IN pUserID INT(5))
BEGIN
	UPDATE core_workspace_master
	SET WorkspaceName = pWorkspaceName,
	UserIDCreated = pUserID
	WHERE WorkspaceID = pWorkspaceID;
    END