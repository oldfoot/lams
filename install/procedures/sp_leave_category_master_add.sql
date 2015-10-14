CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_category_master_add`(IN pCategoryName VARCHAR(100),
								IN pAllowNegativeBalance CHAR(1),
								IN pAllowBalanceCarryForward CHAR(1),
								IN pPaidUnpaid CHAR(6),
								IN pAutoApprove CHAR(1),
								IN pPlanning CHAR(1),
								in pWorkspaceID INT(5),
								IN pUserID INT(5))
BEGIN
	INSERT INTO leave_category_master (CategoryName,AllowNegativeBalance,AllowBalanceCarryForward,
	PaidUnpaid,AutoApprove,Planning,WorkspaceID,UserIDLastModified,DateTimeLastModified)
	VALUES 
	(pCategoryName,pAllowNegativeBalance,pAllowBalanceCarryForward,
	pPaidUnpaid,pAutoApprove,pPlanning,pWorkspaceID,pUserID,sysdate());
	
	INSERT INTO core_audit (Description,ACTION,UserIDModified,DateTimeModified)
	VALUES (CONCAT('Category - ',pCategoryName,' with ID - ',Last_INSERT_ID()),'ADD',pUserID,SYSDATE());	
	
    END