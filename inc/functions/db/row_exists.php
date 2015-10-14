<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function RowExists($tb, $rw, $fld, $extra="") {
	if (EMPTY($fld)) { $fld="NULL"; } /* REQUIRED FOR POSTGRESQL */
	$db=$GLOBALS['db'];

	$sql="SELECT 'x'
				FROM ".$GLOBALS['database_prefix']."$tb
				WHERE $rw = $fld
				$extra";
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