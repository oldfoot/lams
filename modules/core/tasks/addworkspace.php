<?php
function addworkspace() {
	
	$c = "";
	
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
			<h3>Create Workspace</h3>
			<div id='response'></div>
            <form method='post' id='formworkspaceadd' action='#'>
				<label for='userlogin'>Enter a name:</label>
				<input type='text' name='workspacename' id='userlogin'>				
				<input value='Add Now' data-theme='a' type='submit'>
			</form>
        </div>
	\n";
	return $c;
}
?>