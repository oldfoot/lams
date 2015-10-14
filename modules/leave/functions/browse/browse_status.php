<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function BrowseStatus() {

	$c="";

	$sql="SELECT status_id,status_name,
				concat('<a href=index.php?module=leave&task=status&subtask=is_new&status_id=',status_id,' title=ChangeIsNew>',is_new,'</a>') as f1,
				concat('<a href=index.php?module=leave&task=status&subtask=is_new_default&status_id=',status_id,' title=ChangeIsNewDefault>',is_new_default,'</a>') as f2,
				concat('<a href=index.php?module=leave&task=status&subtask=is_approved&status_id=',status_id,' title=ChangeApproved>',is_approved,'</a>') as f3,
				concat('<a href=index.php?module=leave&task=status&subtask=is_rejected&status_id=',status_id,' title=ChangeRejected>',is_rejected,'</a>') as f4,
				concat('<a href=index.php?module=leave&task=status&subtask=is_deleted&status_id=',status_id,' title=ChangeIsRejected>',is_deleted,'</a>') as f5,
				concat('<a href=index.php?module=leave&task=status&subtask=edit&status_id=',status_id,' title=Edit>Edit</a>') as f2,
				concat('<a href=index.php?module=leave&task=status&subtask=delete&status_id=',status_id,' title=Delete>Delete</a>') as f3
				FROM ".$GLOBALS['database_prefix']."leave_status_master
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				ORDER BY status_name
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
	$c.="<col style='width:30px;' >\n";
	$c.="<col style='width:70px;' >\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:80px;' >\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:40px;' >\n";
	$c.="<col style='width:30px;' >\n";
	$c.="<col style='width:40px;' >\n";

	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>Status</th>\n";
		  $c.="<th>New</th>\n";
		  $c.="<th>Default New</th>\n";
		  $c.="<th>Approved</th>\n";
		  $c.="<th>Completed</th>\n";
		  $c.="<th>Reject</th>\n";
		  $c.="<th>Del</th>\n";
		  $c.="<th>Edit</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

}

?>