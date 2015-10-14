CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_usermaster_browse`(IN pUserID INT(5))
BEGIN
	SELECT *
	FROM core_usermaster
	WHERE UserID = pUserID;
    END