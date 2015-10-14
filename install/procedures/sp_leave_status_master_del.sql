CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_status_master_del`(IN pStatusID VARCHAR(50),in pWorkspaceID INT(5),IN pUserID INT(5))
BEGIN
	INSERT INTO core_audit (Description,Action,UserIDModified,DateTimeModified)
	VALUES (concat('StatusID - ',pStatusID),'DEL',pUserID,sysdate());
	
	DELETE FROM leave_status_master 
	WHERE StatusID = pStatusID
	AND WorkspaceID = pWorkspaceID;
	
	
    END