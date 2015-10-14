<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/workflow.php";
require_once $GLOBALS['dr']."modules/leave/classes/attachment_id.php";
require_once $GLOBALS['dr']."modules/leave/functions/browse/leave_application_id_summary.php";

function LoadTask() {

	$c="";

	if (ISSET($_GET['attachment_id']) && $_GET['delete'] == "y") {
		$la = new AttachmentID;
		$la->SetParameters($_GET['attachment_id']);
		$result=$la->Delete();
		if ($result) {
			$c .= "Success";
		}
		else {
			$c .= "Failed";
			$c .= $la->ShowErrors();
		}

	}

	return LeaveApplicationIDSummary($_GET['application_id']);
}
?>