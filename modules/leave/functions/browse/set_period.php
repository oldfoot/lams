<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/show_results.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function SetPeriod() {

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Date From","Date To","Activate")); /* COLS */
	$sr->Columns(array("period_id","date_from","date_to","activate"));
	$sr->Query("SELECT period_id,date_from, date_to, 'activate' AS activate
							FROM ".$GLOBALS['database_prefix']."leave_period_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							AND active = 'y'
							ORDER BY date_from");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$period_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,3,"<a href='index.php?module=leave&task=periods&task=".EscapeData($_GET['task'])."&action=set_period&period_id=".$period_id."' title='Edit'>Activate</a>");
	}
	$sr->RemoveColumn(0);

	$sr->WrapData();
	//$sr->Footer();
	$sr->TableTitle("nuvola/32x32/apps/kuser.png","Activate a period");
	return $sr->Draw();

}
?>