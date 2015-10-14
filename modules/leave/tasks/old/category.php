<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {

	$c="";

	$_SESSION['category_master1']="yes";

	$c .= "<iframe src='modules/leave/bin/leave_category_master.php' width='100%' height='400' frameborder='0' border='0' style='border:0px solid #000000' ></iframe>";

	return $c;
}
?>