CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_period_master_add`(IN pDateFrom DATE,IN pDateTo DATE,in pWorkspaceID INT(5),IN pUserID INT(5))
BEGIN
	INSERT INTO leave_period_master (DateFrom,DateTo,WorkspaceID,UserIDLastModified,DateTimeLastModified)
	VALUES 
	(pDateFrom,pDateTo,pWorkspaceID,pUserID,sysdate());
    END