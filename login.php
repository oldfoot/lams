<?php
define( '_VALID_DIR_', 1 );
require_once "config.php";
require_once "inc/head.php";
require_once "inc/menu_left.php";
?>
<body>
	<div class="demo-wrapper" data-role="page">
        <!-- panel 01 -->
        <?php		
		echo DrawMenuLeft(array("index.php"=>"Home","join.php"=>"Join"));				
		echo DrawTitleBar();
		?>
		<script>
			$(function() {
				
				$('#formlogin :submit').click(function(e) {
					e.preventDefault();
					var a=$('#formlogin').serialize();
					$.ajax({
						type:'post',
						url:'modules/core/ajax/core_user_login.php',
						data:a,
						beforeSend:function(){
							//alert('ok');
							ShowResponse('Working...');
						},
						complete:function(){
							//ShowResponse('Done...');
						},
						success:function(result){
							//alert("*"+result+"*");
							 ShowResponse(result);
							 if (result != null && result.toString() == "Login Successful!"){								
								 location.href = "index.php";
							}							
							 return true;							 	 
						}
					});
				return true;
				});
				
			});
			function ShowResponse(resp) {					
				$("#response" ).text(resp).show();
			};
		</script>
		
        <div class="content" data-role="content">
			<h3>Login</h3>
			<div id='response'></div>
            <form method="post" id="formlogin" action="#">
				<label for="userlogin">Enter username:</label>
				<input type="text" name="userlogin" id="userlogin">				
				<label for="password">Password:</label>
				<input type="password" name="password" id="password">
				
				<input value="Login Now" data-theme="a" type="submit" id="submit">
			</form>
        </div>
    	<!-- panel 02 -->
    	<?php 
		echo DrawMenuRight();
		?>
	</div>
</body>
</html>

