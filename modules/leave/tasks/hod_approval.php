<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/browse/hod_approval.php";
require_once $GLOBALS['dr']."modules/leave/classes/application_id.php";

function LoadTask() {

	/* FORM PROCESSING */

	if (ISSET($_GET['approve']) && IS_NUMERIC($_GET['application_id'])) {


		/* ADD */
		$application_id=$_GET['application_id'];

		$obj_ai=new ApplicationID;
		$obj_ai->SetParameters($application_id);
		$result=$obj_ai->Approve($_GET['approve']);
	}
	/* SHOW THE FORM */
	return HODApproval();
}
?>