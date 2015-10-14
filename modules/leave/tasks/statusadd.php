<?php
function statusadd() {
	
	$c = "";
	
	$c .= "
	<script>
	
			$(function() {								
				$('#formstatusadd :submit').click(function(e) {
					e.preventDefault();
					var a=$('#formstatusadd').serialize();
					$.ajax({
						type:'post',
						url:'modules/leave/ajax/leave_status_add.php',
						data:a,
						beforeSend:function(){
							//alert('ok');
							ShowResponse('Working...');
						},
						complete:function(){
							//ShowResponse('Done...');
						},
						failure:function(result){
							//alert(result);
						},
						success:function(result){							
							//alert(result);
							 ShowResponse(result);							 
							 return true;							 
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
            <form method='post' id='formstatusadd' action='#'>
				<label for='statusname'>Status Name</label>
				<input type='text' name='statusname' id='statusname' placeholder=\"e.g. new\">
				
				<input value='Add Now' data-theme='a' type='submit'>
			</form>
        </div>
		
	\n";
	
	$c .= "<input value=\"Browse Statuses\" data-icon=\"arrow-l\" data-theme=\"b\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=status'\">";
	
	return $c;
}
/*
<label for='isnew'>Is New</label>
				<input type='checkbox' name='isnew' id='isnew' value='y'>
				<label for='isnewdefault'>Is New Default</label>
				<input type='checkbox' name='isnewdefault' id='isnewdefault' value='y'>
				<label for='isapproved'>Is Approved</label>
				<input type='checkbox' name='isapproved' id='isapproved' value='y'>
				<label for='isrejected'>Is Rejected</label>
				<input type='checkbox' name='isrejected' id='isrejected' value='y'>
				<label for='isdeleted'>Is Deleted</label>
				<input type='checkbox' name='isdeleted' id='isdeleted' value='y'>
				*/
?>