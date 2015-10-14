<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {

	$c="";

	$sql="SELECT ch.description, um.full_name, ch.log_date, ch.task
				FROM ".$GLOBALS['database_prefix']."core_history ch, ".$GLOBALS['database_prefix']."core_user_master um
				WHERE ch.module = 'leave'
				AND ch.user_id = um.user_id
				ORDER BY history_id DESC
				";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="";
	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:250px;' >\n";
	$c.="<col style='width:90px;' >\n";
	$c.="<col style='width:90px;' >\n";
	$c.="<col style='width:80px;' >\n";
	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>Description</th>\n";
		  $c.="<th>User</th>\n";
		  $c.="<th>Date</th>\n";
		  $c.="<th>Task</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

}

?>