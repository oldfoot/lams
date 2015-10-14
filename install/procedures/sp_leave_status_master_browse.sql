CREATE DEFINER=`root`@`%` PROCEDURE `sp_leave_status_master_browse`(IN pWorkspaceID INT(5))
BEGIN
	SELECT *
	FROM leave_status_master
	WHERE WorkspaceID = pWorkspaceID;
    END