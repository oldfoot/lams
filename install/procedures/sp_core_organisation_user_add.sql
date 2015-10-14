CREATE DEFINER=`lams`@`%` PROCEDURE `sp_core_organisation_user_add`(IN pAPIAuthCode VARCHAR(100),IN pOrganisationID INT(5),IN pUserID INT(5))
BEGIN
	SELECT count(*) INTO @count_priv
	FROM core_usermaster a, core_userroles b, core_rolepriv c
	WHERE a.APIAuthCode = pAPIAuthCode
	AND a.UserID = b.UserID
	AND b.RoleID = c.RoleID
	AND c.RolePriv = 'Add User To Org';
	
	IF (@count_priv = 1) THEN
		SELECT UserID INTO @userid FROM core_usermaster WHERE APIAuthCode = pApiAuthCode;
		SELECT COUNT(*) INTO @count_priv1
		FROM core_organisation_users
		WHERE OrganisationID = pOrganisationID
		AND UserID = pUserID;
		IF (@count_priv1 = 1) THEN			
			REPLACE INTO core_organisation_users (UserID,OrganisationID,DateTimeUpdated,UserIDUpdated)
			VALUES (pUserID,pOrganisationID,SYSDATE(),pUserID);
		END IF;
	END IF;
	
	END