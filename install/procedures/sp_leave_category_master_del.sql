CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_category_master_del`(IN pCategoryID VARCHAR(50),in pWorkspaceID INT(5),IN pUserID INT(5))
BEGIN
	INSERT INTO core_audit (Description,Action,UserIDModified,DateTimeModified)
	VALUES (concat('CategoryID - ',pCategoryID),'DEL',pUserID,sysdate());
	
	DELETE FROM leave_category_master 
	WHERE CategoryID = pCategoryID
	AND WorkspaceID = pWorkspaceID;
	
	
    END