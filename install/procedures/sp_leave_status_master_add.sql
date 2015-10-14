CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_status_master_add`(IN pStatus VARCHAR(50),in pWorkspaceID INT(5),IN pUserID INT(5))
BEGIN
	INSERT INTO leave_status_master (StatusName,WorkspaceID,UserIDLastModified,DateTimeLastModified)
	VALUES 
	(pStatus,pWorkspaceID,pUserID,sysdate());
	
	INSERT INTO core_audit (Description,ACTION,UserIDModified,DateTimeModified)
	VALUES (CONCAT('Status - ',pStatus,' with ID - ',Last_INSERT_ID()),'ADD',pUserID,SYSDATE());	
	
    END