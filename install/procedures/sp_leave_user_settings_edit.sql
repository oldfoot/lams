CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_user_settings_edit`(IN pUserID INT(5),IN pPeriodID INT(5))
BEGIN
	UPDATE leave_user_settings
	SET PeriodID = pPeriodID
	WHERE UserID = pUserID;
    END