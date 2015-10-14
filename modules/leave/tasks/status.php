<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function status() {

	$c="<h2 style='background-color:#dedede'>Browse Statuses</h2>";
	
	$sql = "CALL sp_leave_status_master_browse(".$_SESSION['workspaceid'].")";
	//echo $sql;
	$result = $GLOBALS['db']->Query($sql);				
	while ($row = $GLOBALS['db']->FetchArray($result)) {
		//$theme = "c";
		//$icon = "plus";
		//$link = " onClick=\"document.location.href='index.php?module=leave&task=status&statusid=".$row['StatusID']."'\"";		
		//echo "<input value=\"".$row['StatusName']."\" data-icon=\"$icon\" data-theme=\"$theme\" type=\"button\" $link>";
		//echo "<input value=\"Edit\" data-icon=\"plus\" data-theme=\"a\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=editstatus&statusid=".$row['StatusID']."'\">";
		$c .= "<fieldset class=\"ui-grid-b\">\n";
			$c .= "<div class=\"ui-block-a\"><h2>".$row['StatusName']."</h2></div>\n";
			//$c .= "<div class=\"ui-block-b\"><input value=\"Edit\" type=\"button\" onClick=\"document.location.href='index.php?module=core&task=editstatus&statusid=".$row['StatusID']."'\"></div>\n";
			$c .= "<div class=\"ui-block-c\"><input value=\"Delete\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=statusdel&statusid=".$row['StatusID']."'\"></div>\n";
		$c .= "</fieldset>\n";
	}
	
	$c .= "<input value=\"Add new status\" data-icon=\"plus\" data-theme=\"a\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=statusadd'\">";
	
	
	
	return $c;
}
?>