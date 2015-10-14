<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/forms/apply.php";
require_once $GLOBALS['dr']."modules/leave/classes/application_id.php";

function LoadTask() {

	$ui=$GLOBALS['ui'];

	$c="";

	/* FORM PROCESSING */
	if (ISSET($_POST['FormSubmit'])) {

		$date_from=$_POST['date_from'];
		if (ISSET($_POST['date_from_half_day'])) { $date_from_half_day="y"; } else { $date_from_half_day="n"; }
		if (ISSET($_POST['date_from_half_day_am_pm']) && $_POST['date_from_half_day_am_pm']=="am") { $date_from_half_day_am_pm="am"; } else { $date_from_half_day_am_pm="pm"; }
		$date_to=$_POST['date_to'];
		if (ISSET($_POST['date_to_half_day'])) { $date_to_half_day="y"; } else { $date_to_half_day="n"; }
		if (ISSET($_POST['date_to_half_day_am_pm']) && $_POST['date_to_half_day_am_pm']=="am") { $date_to_half_day_am_pm="am"; } else { $date_to_half_day_am_pm="pm"; }
		$categoryid=$_POST['categoryid'];
		$remarks=$_POST['remarks'];
		$application_id=$_POST['application_id'];

		//echo "adding";
		$ai=new ApplicationID;
		$ai->GetFormPostedValues();
		//echo $_POST['application_id'];
		if (!ISSET($_POST['application_id']) || EMPTY($_POST['application_id'])) {
			//echo "Adding";
			$result_add=$ai->Add();
		}
		else {
			$ai->SetParameters($_POST['application_id']);
			$result_add=$ai->Edit();
		}
		if (!$result_add) {
			$c.=ErrorPageTop("fail",$ai->ShowErrors());
			$show_form=True;
		}
		else {
			$c.=ErrorPageTop("success","Success");
			$show_form=False;
		}
	}
	elseif (ISSET($_GET['application_id'])) {
		$ai=new ApplicationID;
		$result=$ai->SetParameters($_GET['application_id']);
		if (!$result) { return $ai->ShowErrors(); }
		$date_from=$ai->GetInfo("date_from");
		$date_from_half_day=$ai->GetInfo("date_from_half_day");
		$date_from_half_day_am_pm=$ai->GetInfo("date_from_half_day_am_pm");
		$date_to=$ai->GetInfo("date_to");
		$date_to_half_day=$ai->GetInfo("date_to_half_day");
		$date_to_half_day_am_pm=$ai->GetInfo("date_to_half_day_am_pm");
		$categoryid=$ai->GetInfo("categoryid");
		$remarks=$ai->GetInfo("remarks");
		$application_id=$_GET['application_id'];
		$show_form=True;
	}
	else {
		$date_from="";
		$date_from_half_day="";
		$date_from_half_day_am_pm="";
		$date_to="";
		$date_to_half_day="";
		$date_to_half_day_am_pm="";
		$categoryid="";
		$remarks="";
		$application_id="";
		$show_form=True;
	}

	/*
		DESIGN THE FORM
	*/
	if ($show_form) {
		$c.=Apply($date_from,$date_from_half_day,$date_from_half_day_am_pm,$date_to,$date_to_half_day,$date_to_half_day_am_pm,$categoryid,$remarks,$application_id);
	}

	return $c;
}
?>