<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowsePeriods() {

	$c="";

	$sql="SELECT period_id,date_from, date_to,
				concat('<a href=index.php?module=leave&task=periods&subtask=active&period_id=',period_id,' title=ActiveStatus>',active,'</a>') as f1,
				concat('<a href=index.php?module=leave&task=periods&subtask=edit&period_id=',period_id,' title=Edit>Edit</a>') as f1,
				concat('<a href=index.php?module=leave&task=periods&subtask=delete&period_id=',period_id,' title=Delete>Delete</a>') as f2
				FROM ".$GLOBALS['database_prefix']."leave_period_master
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				ORDER BY date_from
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
	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:30px;' >\n";
	$c.="<col style='width:110px;' >\n";
	$c.="<col style='width:110px;' >\n";
	$c.="<col style='width:70px;' >\n";
	$c.="<col style='width:70px;' >\n";
	$c.="<col style='width:70px;' >\n";

	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>From</th>\n";
		  $c.="<th>To</th>\n";
		  $c.="<th>Active</th>\n";
		  $c.="<th>Edit</th>\n";
		  $c.="<th>Delete</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

}

?>