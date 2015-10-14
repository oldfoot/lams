<?php
define( '_VALID_DIR_', 1 );
require_once "config.php";

if (ISSET($_SESSION['userid'])) { header("Location: index.php"); }

require_once "inc/head.php";
require_once "inc/menu_left.php";
?>
<body>
	<div class="demo-wrapper" data-role="page">
        <!-- panel 01 -->
        <?php		
		echo DrawMenuLeft(array("login.php"=>"Login","join.php"=>"Join"));				
		echo DrawTitleBar();
		?>		
        <div class="content" data-role="content">
			<center><img src="img/icon_256.png"></center>
			<br />			
			<input value="Login" data-icon="check" data-theme="a" type="button" onClick="document.location.href='login.php'">
			<input value="Join" data-icon="check" data-theme="a" type="button" onClick="document.location.href='join.php'">
        </div>
    	<!-- panel 02 -->
    	<?php 
		echo DrawMenuRight();
		?>
	</div>
</body>
</html>

