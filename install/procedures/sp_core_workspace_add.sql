CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_workspace_add`(IN pWorkspaceIDParent INT(5),IN pWorkspaceName VARCHAR(100),IN pLogo VARCHAR(100),IN pUserIDCreated INT(5))
BEGIN
	INSERT INTO core_workspace_master (WorkspaceIDParent,WorkspaceName,Logo,UserIDCreated,DateTimeCreated)
	VALUES (pWorkspaceIDParent,pWorkspaceName,pLogo,pUserIDCreated,sysdate());
	
	SELECT LAST_INSERT_ID() AS WorkspaceID INTO @WorkspaceID;
	
	INSERT INTO core_workspace_role_master (WorkspaceID,RoleName,DefaultRole)
	VALUES (@WorkspaceID,'User','n');
	INSERT INTO core_workspace_role_master (WorkspaceID,RoleName,DefaultRole)
	VALUES (@WorkspaceID,'Admin','y');
	SELECT WorkspaceRoleID into @WorkspaceRoleID 
	FROM core_workspace_role_master 
	WHERE WorkspaceID = @WorkspaceID 
	AND DefaultRole = 'y';
	INSERT INTO core_workspace_user_roles
	(WorkspaceID,UserID,WorkspaceRoleID)
	VALUES
	(@WorkspaceID,pUserIDCreated,@WorkspaceRoleID);
	
	INSERT INTO core_workspace_rolepriv
	SELECT @WorkspaceRoleID,RolePriv
	FROM core_rolepriv;
	
	SELECT @WorkspaceID AS WorkspaceID;
	
    END