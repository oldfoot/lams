<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/acl/browse_module_task_acl.php";

function LoadTask() {
	return BrowseModuleTaskACL("leave");
}
?>