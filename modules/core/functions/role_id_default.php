<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."inc/functions/db/count_total_sql.php";

function RoleIDDefault() {
	$db=$GLOBALS['db'];

	/* CHECK IF THERE ARE NO OTHER USERS IN THE SYSTEM THEN WE MUST GIVE THE USER MAX PRIVILEGES TO START WITH */
	$found_users=CountTotalSQL("SELECT count(*) as total FROM core_user_master");
	if ($found_users > 0) {
		$sql="SELECT role_id
					FROM ".$GLOBALS['database_prefix']."core_role_master
					WHERE default_role = 'y'
					";
	}
	else {
		$sql="SELECT role_id
					FROM ".$GLOBALS['database_prefix']."core_role_master
					WHERE manage_core_workspaces = 'y'
					";
	}
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['role_id'];
		}
	}
	else {
		return False;
	}
}
?>