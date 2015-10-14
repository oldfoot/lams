<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/reporting/gen_xml_file_pie3d.php");
require_once($GLOBALS['dr']."classes/reporting/gen_data.php");


function SubTask() {

	$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

	$sql="SELECT la.application_id, um.full_name, la.date_from, la.date_to, cm.category_name
				FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."leave_category_master cm
				WHERE la.user_id = um.user_id
				AND la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
				AND period_id = $current_period
				AND la.category_id = cm.category_id ";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$GLOBALS['head']->IncludeFile("rico2rc2");

	//require "include/rico2rc2/examples/php/chklang2.php";

	$c="
	<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
	<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
	<colgroup>
	<col style='width:40px;' >
	<col style='width:180px;' >
	<col style='width:100px;' >
	<col style='width:100px;' >
	<col style='width:90px;' >
	</colgroup>
	  <tr>
		  <th>ID</th>
		  <th>User</th>
		  <th>From</th>
		  <th>To</th>
		  <th>Category</th>
	  </tr>
	</table>
	";

	return $c;
}
?>
