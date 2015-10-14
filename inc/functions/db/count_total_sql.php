<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function CountTotalSQL($sql) {

	$db=$GLOBALS['db'];
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			return $row['total'];
	 	}
	}
	else {
		return 0;
	}
}
?>