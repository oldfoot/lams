<?php
function browseworkspaces() {
	$c = "";
	
	$sql = "CALL sp_core_workspace_browse_my(".$_SESSION['userid'].")";
	//echo $sql;
	$result = $GLOBALS['db']->Query($sql);					
	while ($row = $GLOBALS['db']->FetchArray($result)) {	
		$c .= "<fieldset class=\"ui-grid-b\">\n";
			$c .= "<div class=\"ui-block-a\"><h2><a href='index.php?module=core&task=browsemodules&workspaceid=".$row['WorkspaceID']."'>".$row['WorkspaceName']."</a></h2></div>\n";
			$c .= "<div class=\"ui-block-b\"><input value=\"Edit\" type=\"button\" onClick=\"document.location.href='index.php?module=core&task=editworkspace&workspaceid=".$row['WorkspaceID']."'\"></div>\n";
			$c .= "<div class=\"ui-block-c\"><input value=\"Delete\" type=\"button\" onClick=\"document.location.href='index.php?module=core&task=deleteworkspace&workspaceid=".$row['WorkspaceID']."'\"></div>\n";
		$c .= "</fieldset>\n";
	}
	return $c;
}
?>