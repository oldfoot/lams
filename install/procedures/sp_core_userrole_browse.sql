CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_userrole_browse`(IN pUserID INT(5))
BEGIN
SELECT a.RoleID, a.RoleName
FROM core_rolemaster a, core_userroles b
WHERE b.UserID = pUserID
AND b.RoleID = a.RoleID;
    END