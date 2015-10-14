<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/period_id.php";
require_once $GLOBALS['dr']."modules/leave/functions/forms/periods.php";

function Add() {

	$c="";

	if (ISSET($_GET['subtask']) && $_GET['subtask'] == "edit" && ISSET($_GET['period_id'])) {

		$period_id=EscapeData($_GET['period_id']);

		$obj_pi=new PeriodID;
		$obj_pi->SetParameters($period_id);

		$date_from=$obj_pi->GetInfo("date_from");
		$date_to=$obj_pi->GetInfo("date_to");
	}
	else {
		$period_id="";
		$date_from="";
		$date_to="";
	}

	/* SHOW THE FORM */
	$c.=Periods($period_id,$date_from,$date_to);

	return $c;
}
?>