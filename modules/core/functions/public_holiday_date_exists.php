<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function PublicHolidayDateExists($p_date) {
	$db=$GLOBALS['db'];

	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."hrms_public_holiday_master
				WHERE date_pub_hol = '".$p_date."'
				AND workspace_id = ".$GLOBALS['workspace_id']."
				AND teamspace_id ".$GLOBALS['teamspace_sql']."
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		return True;
	}
	else {
		return False;
	}
}
?>