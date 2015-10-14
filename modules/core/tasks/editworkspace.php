<?php
require_once $dr."modules/core/classes/core_workspace_master.php";

function editworkspace() {
	
	$c = "";
	if (!ISSET($_GET['workspaceid']) && !IS_NUMERIC($_GET['workspaceid'])) { return "Invalid data"; }
	$wm = new WorkspaceMaster;
	//$wm->SetVar("debug",true);
	$wm->SetVar("workspaceid",$_GET['workspaceid']);
	$result = $wm->Info();
	if (!$result) { return "Failed to get data"; }
		
	$c .= "
	<script>
			$(function() {				
				
				//$('#formlogin').submit(function(e) {
				//$('#formlogin').live('submit', function(e) {
				$('#formworkspaceadd :submit').click(function(e) {
					e.preventDefault();
					var a=$('#formworkspaceadd').serialize();
					$.ajax({
						type:'post',
						url:'modules/core/ajax/core_workspace_add.php',
						data:a,
						beforeSend:function(){
							//alert('ok');
							ShowResponse('Working...');
						},
						complete:function(){
							//ShowResponse('Done...');
						},
						success:function(result){							
							 ShowResponse(result);
							 if (result != null && result.toString() == \"Signup Successful!\"){								
								 location.href = \"index.php\";
							}							
							 return true;
							 //UpdateCategoryGridData();							 
						}
					});
				return true;
				});
				
			});
			function ShowResponse(resp) {					
				$(\"#response\" ).text(resp).show();
			};
		</script>
		
        <div class='content' data-role='content'>
			<h3>Edit Workspace</h3>
			<div id='response'></div>
            <form method='post' id='formworkspaceadd' action='#'>
				<label for='userlogin'>Enter a name:</label>
				<input type='text' name='workspacename' id='userlogin' value='".$wm->GetVar("WorkspaceName")."'>
				<input type='hidden' name='workspaceid' id='workspaceid' value='".$_GET['workspaceid']."'>
				<input value='Edit Now' data-theme='a' type='submit'>
			</form>
        </div>
	\n";
	return $c;
}
?>