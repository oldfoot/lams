<?php
function categoryadd() {
	
	$c = "";
	
	$c .= "
	<script>
	
			$(function() {								
				$('#formcategoryadd :submit').click(function(e) {
					e.preventDefault();
					var a=$('#formcategoryadd').serialize();
					$.ajax({
						type:'post',
						url:'modules/leave/ajax/leave_category_add.php?id=".rand(0,10000)."',
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
			<h3>Add Category</h3>
			<div id='response'></div>
            <form method='post' id='formcategoryadd' action='#'>
				<label for='categoryname'>Category Name</label>
				<input type='text' name='categoryname' id='categoryname' placeholder=\"e.g. new\">
				
				<label for='allownegativebalance'>Allow Negative Balance</label>
				<input type='checkbox' name='allownegativebalance' id='allownegativebalance' value='y'>
				<label for='allowbalancecarryforward'>Allow Balance Carry Forward</label>
				<input type='checkbox' name='allowbalancecarryforward' id='allowbalancecarryforward' value='y'>
				<label for='paidunpaid'>Paid Unpaid</label>
				<input type='text' name='paidunpaid' id='paidunpaid' placeholder=\"Paid or Unpaid\">
				<label for='autoapprove'>Auto approve</label>
				<input type='checkbox' name='autoapprove' id='autoapprove' value='y'>
				<label for='planning'>Planning</label>
				<input type='checkbox' name='planning' id='planning' value='y'>
				
				<input value='Add Now' data-theme='a' type='submit'>
			</form>
        </div>
		
	\n";
	
	$c .= "<input value=\"Browse Catgegories\" data-icon=\"arrow-l\" data-theme=\"b\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=category'\">";
	
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