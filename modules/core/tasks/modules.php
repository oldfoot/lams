<?php
function modules() {
	$c = "";
	if (!ISSET($_GET['workspaceid']) && IS_NUMERIC($_GET['workspaceid'])) { die("Not auth"); }
	$_SESSION['workspaceid'] = $_GET['workspaceid'];
		
	$sql = "CALL sp_core_workspace_modules(".$_SESSION['workspaceid'].")";	
	//$c .= $sql;
	$result = $GLOBALS['db']->Query($sql);				
	
	$c .= "<div id='response'></div>";
	if ($GLOBALS['db']->NumRows($result) == 0) {
		$c .= "<h4>No available modules in this workspace</h4>";
	}
	while ($row = $GLOBALS['db']->FetchArray($result)) {		
		//$c .= "<fieldset class=\"ui-grid-b\">\n";
			//$c .= "<div class=\"ui-block-a\"><h2><a href='index.php?module=".$row['ModuleName']."&task=home&moduleid=".$row['ModuleID']."'>".$row['ModuleName']."</a></h2></div>\n";			
			$c .= "<input value=\"".$row['ModuleName']."\" data-icon=\"search\" data-theme=\"c\" type=\"button\" onClick=\"document.location.href='index.php?module=".strtolower($row['ModuleName'])."&task=home&moduleid=".$row['ModuleID']."'\">";
		//$c .= "</fieldset>\n";
	}
	
	
	return $c;
}
?>