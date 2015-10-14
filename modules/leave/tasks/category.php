<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function category() {

	$c="<h2 style='background-color:#dedede'>Browse Categories</h2>";
	
	$sql = "CALL sp_leave_category_master_browse(".$_SESSION['workspaceid'].")";
//	echo $sql;
	$result = $GLOBALS['db']->Query($sql);				
	while ($row = $GLOBALS['db']->FetchArray($result)) {		
		$c .= "<fieldset class=\"ui-grid-b\">\n";
			$c .= "<div class=\"ui-block-a\"><h2>".$row['CategoryName']."</h2></div>\n";			
			$c .= "<div class=\"ui-block-c\"><input value=\"Delete\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=categorydel&categoryid=".$row['CategoryID']."'\"></div>\n";
		$c .= "</fieldset>\n";}
	
	$c .= "<input value=\"Add New Category\" data-icon=\"plus\" data-theme=\"a\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=categoryadd'\">";
	
	return $c;
}
?>