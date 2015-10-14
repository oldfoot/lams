<?php
function periodadd() {
	
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
						url:'modules/leave/ajax/leave_period_add.php',
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
			<h3>Add Period</h3>
			<div id='response'></div>
            <form method='post' id='formworkspaceadd' action='#'>
				<label for='datefrom'>Date From:</label>
				<input data-role='date' type='text' name='datefrom' id='datefrom' placeholder=\"e.g. 2015-01-01\">
				<label for='datefrom'>Date To:</label>
				<input data-role='date' type='text' name='dateto' id='dateto' placeholder=\"e.g. 2015-12-31\">
				<input value='Add Now' data-theme='a' type='submit'>
			</form>
        </div>
	\n";
	return $c;
}
?>