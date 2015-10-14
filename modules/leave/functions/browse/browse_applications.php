<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function Browseapplications($p_filter="",$show_edit=true,$show_records=true) {

	$c="";
	$period_id = $GLOBALS['obj_us']->GetInfo("period_id");

	$sql_table_joins="";

	/* FILTER VARIOUS VIEWS OF ALL THE APPLICATIONS */
	if ($p_filter=="my") {
		$sql_extra="AND la.user_id = ".$_SESSION['user_id'];
	}
	else { $sql_extra=""; }

	/* FILTER THE APPLICATION ID */
	if (ISSET($_POST['application_id']) && IS_NUMERIC($_POST['application_id'])) { $sql_application_id="AND la.application_id = ".EscapeData($_POST['application_id']); } else { $sql_application_id=""; }


	$sql="SELECT concat('<a href=index.php?module=leave&task=application_details&application_id=',application_id,' title=Browse>',application_id,'</a>') as f1,
							la.date_from, la.total_days, lcm.category_name, lsm.status_name,
							if(allow_edit='y',concat('<a href=index.php?module=leave&task=apply&application_id=',application_id,' title=Edit>Edit</a>'),'Closed') as f2
							FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."leave_status_master lsm,
							".$GLOBALS['database_prefix']."leave_category_master lcm
							$sql_table_joins
							WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
							AND la.period_id = $period_id
							AND la.status_id = lsm.status_id
							AND la.category_id = lcm.category_id
							$sql_extra
							$sql_application_id
							ORDER BY application_id DESC
							";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c="";
	if ($show_records) {
		$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
	}
	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:80px;' >\n";
	$c.="<col style='width:60px;' >\n";
	$c.="<col style='width:160px;' >\n";
	$c.="<col style='width:60px;' >\n";
	if ($show_edit) {
		$c.="<col style='width:50px;' >\n";
	}
	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>From</th>\n";
		  $c.="<th>Days</th>\n";
		  $c.="<th>Category</th>\n";
		  $c.="<th>Status</th>\n";
		  if ($show_edit) {
		  	$c.="<th>Edit</th>\n";
		  }
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

}
?>