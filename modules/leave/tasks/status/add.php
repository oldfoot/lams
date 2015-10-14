<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/status_id.php";
require_once $GLOBALS['dr']."modules/leave/functions/forms/status.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['status_id'])) {

		$status_id=EscapeData($_GET['status_id']);

		$obj_si=new StatusID;
		$obj_si->SetParameters($status_id);

		$status_name=$obj_si->GetInfo("status_name");

	}
	else {
		$status_id="";
		$status_name="";

	}

	/* SHOW THE FORM */
	$c.=Status($status_id,$status_name);

	return $c;
}
?>