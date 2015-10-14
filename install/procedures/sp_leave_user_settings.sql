CREATE DEFINER=`lams`@`%` PROCEDURE `sp_leave_user_settings`(IN pUserID INT(5))
BEGIN
	SELECT a.PeriodID,b.DateFrom,b.DateTo
	FROM leave_user_settings a, leave_period_master b
	WHERE a.UserID = pUserID
	AND a.PeriodID = b.PeriodID;
    END