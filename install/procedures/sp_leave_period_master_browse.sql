CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_period_master_browse`(IN pWorkspaceID INT(5))
BEGIN
	SELECT *
	FROM leave_period_master
	WHERE WorkspaceID = 1;
    END