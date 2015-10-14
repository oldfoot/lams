<?php
define( '_VALID_DIR_', 1 );

/* CHECK FOR AN INSTALLATION FILE */
if (!file_exists("site_config.php")) { header("Location:install/"); }
if (file_exists("install/")) { die ("Please remove the install directory before continuing"); }

require_once "config.php";

if (!ISSET($_SESSION['userid'])) { header("Location: login.php"); }

require_once "inc/head.php";
require_once "inc/menu_left.php";
?>
<body>
	<div class="demo-wrapper" data-role="page">
        <!-- panel 01 -->
        <?php
		if (ISSET($_GET['module'])) {
			$module_config_file = "modules/".$_GET['module']."/config.php";
			if (file_exists($module_config_file)) {
				//echo "ok21";
				require_once $module_config_file;
				echo DrawMenuLeft($module_menu_items);
			}
			else {
				die("Module is missing config file");
			}
		}
		else {
			echo DrawMenuLeft(array(""=>"Home",
									"module=core&task=addworkspace"=>"Add Workspace",
									"module=core&task=browseworkspaces"=>"Browse Workspaces",
									"module=core&task=signout"=>"Signout"));
		}
		echo DrawTitleBar();
		?>		
        <div class="content" data-role="content">
			Welcome, <?php echo $_SESSION['username']; echo "  [".$GLOBALS['userroles']->GetVar("RoleName")."]";?>
			<?php
			// FIRST STEP - CHOOSE A WORKSPACE
			if (!ISSET($_GET['module'])) {
				//$sql = "CALL sp_core_workspace_browse(".$GLOBALS['userroles']->GetVar("RoleID").")";
				$sql = "CALL sp_core_workspace_browse_my(".$_SESSION['userid'].")";
				//echo $sql;
				$result = $GLOBALS['db']->Query($sql);				
				while ($row = $GLOBALS['db']->FetchArray($result)) {
					echo "<input value=\"".$row['WorkspaceName']."\" data-icon=\"search\" data-theme=\"c\" type=\"button\" onClick=\"document.location.href='index.php?module=core&task=modules&workspaceid=".$row['WorkspaceID']."'\">";
				}			
			}
			// WE HAVE A MODULE AND A TASK
			if (ISSET($_GET['module']) && ISSET($_GET['task'])) {				
				$file = "modules/".$_GET['module']."/tasks/".$_GET['task'].".php";
				//echo $file;
				if (file_exists($file)) {					
					//echo "ok";
					require_once $file;					
					$f = $_GET['task'];					
					echo $f();					
				}
			}						
			?>				
        </div>
    	<!-- panel 02 -->
    	<?php 
		echo DrawMenuRight();
		?>
	</div>
</body>
</html>

