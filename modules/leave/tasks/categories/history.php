<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/browse/browse_history.php";

function History() {
	return CurveBox(BrowseHistory("categories"));
}

?>