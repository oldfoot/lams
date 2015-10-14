<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/balance_transfer.php";
require_once $GLOBALS['dr']."classes/form/show_results.php";


function LoadTask() {

	$c="";
	GLOBAL $obj_us;

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	if (ISSET($_GET['period_from']) && ISSET($_GET['period_to'])) {

		$obj_bt=new BalanceTransfer;
		$result=$obj_bt->Start($_GET['period_from'],$_GET['period_to']);
		if (!$result) { $c."failed"; $c.=$obj_bt->ShowErrors(); return $c; }

	}

	if (ISSET($_POST['user_id'])) {
		$user_id=$_POST['user_id'];
	}
	else {
		$user_id="";
	}

	$c.="<h2>Warning!</h2>\n";
	$c.="<table class=warning><tr><td>
	This screen will transfer the leave balance of users from a period selected below.
	What this means is that if for example, a user has 5 days of annual leave from the previous year(period), it will be added to the current year.
	At present there is no undo until transactions are implemented. If an error is made, you can use the grid to undo the changes.
	</td></tr></table>\n";

	/* LAYOUT OF GUI */
	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Date From","Date To","Activate")); /* COLS */
	$sr->Columns(array("period_id","date_from","date_to","activate"));
	$sr->Query("SELECT period_id,date_from, date_to, 'activate' AS activate
							FROM ".$GLOBALS['database_prefix']."leave_period_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND period_id <> ".$obj_us->GetInfo("period_id")."
							ORDER BY date_from");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$period_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,3,"<a href='index.php?module=leave&task=balance_transfer&period_from=".$period_id."&period_to=".$obj_us->GetInfo("period_id")."' title='Do Transfer'>Transfer from here</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	//$sr->Footer();
	//$sr->TableTitle("nuvola/32x32/apps/kuser.png","Transfer user balances from one of these periods to current period");
	$sr->TableTitle("","");
	$c.=$sr->Draw();

	return $c;
}
?>