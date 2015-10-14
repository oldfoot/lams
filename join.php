<?php
define( '_VALID_DIR_', 1 );
require_once "config.php";
require_once "inc/head.php";
require_once "inc/menu_left.php";

if (!$GLOBALS['allow_registration']) { die("Registration disabled. This can only be enabled by the site administrator"); }
?>
<body>
	<div class="demo-wrapper" data-role="page">
        <!-- panel 01 -->
        <?php		
		echo DrawMenuLeft(array("splash.php"=>"Home","login.php"=>"Login"));				
		echo DrawTitleBar();
		?>
		<script>
			$(function() {				
				
				//$('#formlogin').submit(function(e) {
				//$('#formlogin').live('submit', function(e) {
				$('#formjoin :submit').click(function(e) {
					e.preventDefault();
					var a=$('#formjoin').serialize();
					$.ajax({
						type:'post',
						url:'modules/core/ajax/core_user_add.php',
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
							 if (result != null && result.toString() == "Signup Successful!"){								
								 location.href = "index.php";
							}							
							 return true;
							 //UpdateCategoryGridData();							 
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
			<h3>Join <?php echo $GLOBALS['site_title'];?></h3>
			<div id='response'></div>
            <form method="post" id="formjoin" action="#">
				<label for="userlogin">Enter your email:</label>
				<input type="text" name="userlogin" id="userlogin">				
				<label for="password">Enter a password:</label>
				<input type="password" name="password" id="password">
				
				<input value="Join Now" data-theme="a" type="submit">
			</form>
        </div>
    	<!-- panel 02 -->
    	<?php 
		echo DrawMenuRight();
		?>
	</div>
</body>
</html>

