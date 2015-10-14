CREATE DEFINER=`root`@`%` PROCEDURE `sp_leave_category_master_browse`(IN pWorkspaceID INT(5))
BEGIN
	SELECT *
	FROM leave_category_master
	WHERE WorkspaceID = pWorkspaceID;
    END