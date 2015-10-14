<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

require_once($GLOBALS['dr']."include/functions/misc/yes_no_image.php");
require_once($GLOBALS['dr']."include/functions/date_time/timestamptz_to_friendly.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");

require_once($GLOBALS['dr']."modules/leave/classes/application_id.php");

function LeaveApplicationIDSummary($application_id) {

	//GLOBAL $application_id;
	GLOBAL $ai;
	$ai=new ApplicationID;
	$result=$ai->SetParameters($application_id);
	if (!$result) { return $ai->ShowErrors(); }

	$c="<table border='0' cellpadding='0' width='100%' class='plain'>\n";
		$c.="<tr class='colhead'>\n";
			$c.="<td colspan='2'>Application Details</td>\n";
		$c.="</tr>\n";
		$c.=ShowRow("Applicant","full_name");
		$c.=ShowRow("Category","category_name");
		$c.=ShowRow("Status","status_name");
		$c.=ShowBreak();
		$c.=ShowRow("From","date_from");
		$c.=ShowYesNoRow("From half day?","date_from_half_day");
		$c.=ShowRow("From day am/pm","date_from_half_day_am_pm");
		$c.=ShowRow("To","date_to");
		$c.=ShowYesNoRow("To half day?","date_to_half_day");
		$c.=ShowRow("To day am/pm","date_to_half_day_am_pm");
		$c.=ShowRow("Days","total_days");
		$c.=ShowBreak();
		$c.=ShowRow("Remarks","remarks");

	$c.="</table>\n";
	$c.="<h3>Approval</h3>\n";
	/*
	$db = $GLOBALS['db'];
	$sql = "SELECT attachment_id, filename
					FROM leave_application_attachments
					WHERE application_id = $application_id
					";
	$result = $db->Query($sql);
	while ($row = $db->FetchArray($result)) {

	}
	*/
	//$c="";
	//$period_id = $GLOBALS['obj_us']->GetInfo("period_id");

	//$sql_table_joins="";

	/* FILTER VARIOUS VIEWS OF ALL THE APPLICATIONS */
	//if ($p_filter=="my") { $sql_extra="AND la.user_id = ".$_SESSION['user_id']; }
	//else { $sql_extra=""; }

	/* FILTER THE APPLICATION ID */
	//if (ISSET($_POST['application_id']) && IS_NUMERIC($_POST['application_id'])) { $sql_application_id="AND la.application_id = ".EscapeData($_POST['application_id']); } else { $sql_application_id=""; }
        
        // APPROVAL STATUS
        
        $sql="SELECT um.full_name, laa.approved, laa.date_updated
                FROM leave_application_approval laa
                LEFT JOIN core_user_master um ON
                laa.user_id = um.user_id
                WHERE laa.application_id = $application_id";

	$c .= "<table class=plain border=1 cellspacing='0' cellpadding='0'>
                <tr>
                    <td>Approver</td>
                    <td>Approved</td>
                    <td>Date</td>
                <tr>
	";
        $result = $GLOBALS['db']->Query($sql);
        
        while ($row = $GLOBALS['db']->FetchArray($result)) {
            $c .= "
                <tr>
		  <td>".$row['full_name']."</td>
		  <td>".$row['approved']."</td>
                  <td>".$row['date_updated']."</td>
	  </tr>";
        }
	$c .= "</table>";
        
        $c.="<h3>Attachments</h3>\n";
        
	$sql="SELECT concat('<a href=modules/leave/bin/download_document.php?attachment_id=',attachment_id,' title=Download>',filename,'</a>') as f1,
							concat('<a href=index.php?module=leave&task=application_details&delete=y&attachment_id=',attachment_id,'&application_id=',application_id,' title=Delete>Delete</a>') as f2
							FROM ".$GLOBALS['database_prefix']."leave_application_attachments
							WHERE application_id = $application_id
							";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c.="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:250px;' >
	<col style='width:250px;' >
	</colgroup>
	  <tr>
		  <th>Document</th>
		  <th>Delete</th>
	  </tr>
	</table>
	";

	return $c;
}

function ShowRow($desc,$val) {
	$ai=$GLOBALS['ai'];
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".$ai->GetInfo($val)."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowYesNoRow($desc,$val) {
	$ai=$GLOBALS['ai'];
	$c="<tr>\n";
		$c.="<td class='bold'>".$desc."</td>\n";
		$c.="<td>".YesNoImage($ai->GetInfo($val))."</td>\n";
	$c.="</tr>\n";
	return $c;
}

function ShowBreak() {
	$c="<tr>\n";
		$c.="<td colspan='2'><hr></td>\n";
	$c.="</tr>\n";
	return $c;
}
?>