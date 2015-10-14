<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseWorkflow() {

	$c="";

	/* DATABASE CONNECTION */
	$db=$GLOBALS['db'];

	/* QUERY */
	$sql="SELECT perform_action
				FROM ".$GLOBALS['database_prefix']."leave_workflow
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				ORDER BY workflow_order
				";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		$c.="<table class='plain' align='center'>\n";
		while($row = $db->FetchArray($result)) {
			$c.="<tr align='center'>\n";
				$c.="<td>\n";
				$c.=$row['perform_action']."<br>";
				if ($row['perform_action'] != "End") {
					$c.="<img src='images/nuvola/22x22/actions/down.png'><br>";
				}
				$c.="</td>\n";
			$c.="</tr>\n";
		}
		$c.="</table>\n";
	}

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("Action")); /* COLS */
	$sr->Columns(array("perform_action"));
	$sr->Query("SELECT perform_action
							FROM ".$GLOBALS['database_prefix']."leave_workflow
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							");

	$sr->WrapData();
	$sr->TableTitle("nuvola/22x22/actions/gohome.png","Leave Workflow");


	//return $sr->Draw();
	return $c;


}

?>