<?php
function browsemodules() {
	$c = "";
	if (!ISSET($_GET['workspaceid']) && IS_NUMERIC($_GET['workspaceid'])) { die("Not auth"); }
	$_SESSION['workspaceid'] = $_GET['workspaceid'];
	
	$c .= "
	<script>
			function AddModule(moduleid) {						
								
				$.ajax({
					type:'post',
					url:'modules/core/ajax/core_workspace_module_add.php',
					data:'moduleid='+moduleid,
					beforeSend:function(){
						//alert('ok');
						ShowResponse('Working...');
					},
					complete:function(){
						//ShowResponse('Done...');
					},
					success:function(result){
						 ShowResponse(result);
						 return true;
					}
				});				
			}
			
			function DelModule(moduleid) {						
								
				$.ajax({
					type:'post',
					url:'modules/core/ajax/core_workspace_module_del.php',
					data:'moduleid='+moduleid,
					beforeSend:function(){
						//alert('ok');
						ShowResponse('Working...');
					},
					complete:function(){
						//ShowResponse('Done...');
					},
					success:function(result){
						 ShowResponse(result);
						 return true;
					}
				});				
			}
			function ShowResponse(resp) {					
				$(\"#response\" ).text(resp).show();
			};
		</script>
        
	\n";
	
	
	$sql = "CALL sp_core_workspace_modules(".$_SESSION['workspaceid'].")";
	//$c .= $sql;
	$result = $GLOBALS['db']->Query($sql);				
	$c .= "<h2>Installed</h2>";
	$c .= "<div id='response'></div>";
	if ($GLOBALS['db']->NumRows($result) == 0) {
		$c .= "<h4> - None</h4>";
	}
	while ($row = $GLOBALS['db']->FetchArray($result)) {
		//$c .= "<input value=\"".$row['Description']."\" data-icon=\"search\" data-theme=\"c\" type=\"button\" onClick=\"document.location.href='index.php?module=".$row['ModuleName']."&task=home'\">";
		$c .= "<fieldset class=\"ui-grid-b\">\n";
			$c .= "<div class=\"ui-block-a\"><h2>".$row['ModuleName']."</h2></div>\n";			
			$c .= "<div class=\"ui-block-c\"><input value=\"Remove\" type=\"button\" onClick=\"DelModule(".$row['ModuleID'].")\"></div>\n";
		$c .= "</fieldset>\n";
	}
	
	$c .= "<h2>Available</h2>";
	
	$sql = "CALL sp_core_modules()";
	//$c .= $sql;
	$result = $GLOBALS['db']->Query($sql);					
	if ($GLOBALS['db']->NumRows($result) == 0) {
		$c .= "<h4>None</h4>";
	}
	while ($row = $GLOBALS['db']->FetchArray($result)) {	
		$c .= "<fieldset class=\"ui-grid-b\">\n";
			$c .= "<div class=\"ui-block-a\"><h2>".$row['ModuleName']."</h2></div>\n";			
			$c .= "<div class=\"ui-block-c\"><input value=\"Add\" type=\"button\" onClick=\"AddModule(".$row['ModuleID'].")\"></div>\n";
		$c .= "</fieldset>\n";
	}
	
	return $c;
}
?>