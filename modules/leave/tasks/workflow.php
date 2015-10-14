<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/workflow.php";
require_once $GLOBALS['dr']."modules/leave/functions/browse/browse_workflow.php";

function LoadTask() {
	$c="";
	/* PROCESS THE ACTIONS */
	if (ISSET($_GET['subtask'])) {
		$wf=new Workflow;
		if ($_GET['subtask']=="insert") {
			$wf->SetVar("perform_action",$_GET['item']);
			$result=$wf->Add();
			if ($result) {
				$c.="Success";
			}
			else {
				$c.="Failed";
				$c.=$wf->ShowErrors();
			}
		}
		elseif ($_GET['subtask']=="delete") {
			$wf->Delete();
		}
	}
	$c.="<table class='plain' width='100%'>\n";
		$c.="<tr class='formbuttonrow'>\n";
			$c.="<td colspan='4' align='right' valign='top'><input type='button' value='Reset Workflow' class='buttonstyle1' onClick=\"location.href='index.php?module=leave&task=workflow&subtask=delete'\"></td>\n";
		$c.="</tr>\n";

		$c.="<tr>\n";
			$c.="<td valign='top'>\n";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Application Submission'>Application Submission</a><br>";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=End'>Insert End</a>";
			$c.="</td>\n";

			$c.="<td valign='top'>\n";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=HOD Approval'>Insert HOD Approver</a><br>";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Global Approval'>Insert Global Approver</a>";
			$c.="</td>\n";

			$c.="<td valign='top'>\n";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Email User'>Email Applicant</a><br>";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Email HOD'>Email HOD</a><br>";
			$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Email Global Approver'>Email Global Approver</a>";
			$c.="</td>\n";

			//$c.="<td valign='top'>\n";
			//$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=HOD Success'>HOD Success</a><br>";
			//$c.="<li><a href='index.php?module=leave&task=workflow&subtask=insert&item=Global Success'>Global Success</a>";
			//$c.="</td>\n";
		$c.="</tr>\n";

	$c.="</table>\n";
	/* INCLUDE THE BROWSE FUNCTION */
	$c.=BrowseWorkflow();

	return $c;
}
?>