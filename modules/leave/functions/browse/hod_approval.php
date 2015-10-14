<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function HODApproval() {


	$sql="SELECT la.application_id, um.full_name, la.date_from, la.date_to, la.total_days,
				concat('<a href=index.php?module=leave&task=hod_approval&approve=y&application_id=',la.application_id,' title=Approve>Approve</a>') as approve,
				concat('<a href=index.php?module=leave&task=hod_approval&approve=n&application_id=',la.application_id,' title=Reject>Reject</a>') as reject
				FROM ".$GLOBALS['database_prefix']."leave_application_approval laa, ".$GLOBALS['database_prefix']."leave_applications la,	".$GLOBALS['database_prefix']."core_user_master um
				WHERE laa.user_id = ".$_SESSION['user_id']."
				AND laa.approved IS NULL
				AND laa.application_id = la.application_id
				AND la.workspace_id = ".$GLOBALS['workspace_id']."
				AND la.user_id = um.user_id
				ORDER BY la.application_id
				";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c="

	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:30px;' >
	<col style='width:120px;' >
	<col style='width:80px;' >
	<col style='width:80px;' >
	<col style='width:40px;' >
	<col style='width:60px;' >
	<col style='width:60px;' >
	</colgroup>
	  <tr>
		  <th>ID</th>
		  <th>Name</th>
		  <th>From</th>
		  <th>To</th>
		  <th>Days</th>
		  <th>Approve</th>
		  <th>Reject</th>
	  </tr>
	</table>
	";

	return $c;
}
?>