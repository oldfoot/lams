<?php
$subfolder=SUBSTR(STR_REPLACE("/modules/setup/index.php", "", $_SERVER['SCRIPT_NAME']), 1);
$continue=True;
echo "<table class='summary'>\n";
	echo "<form method='post' action='index.php?step=2'>\n";

	echo DrawHeader("PHP Configuration");

	/* PHP VERSION CHECK */
	if (!phpversion() > '5') { $continue=False; $td2="Error"; } else { $td2="Ok"; }
	echo DrawRow("PHP version >= 5",$td2);
	/* POSTGRESQL INSTALLED */
	//if (!function_exists("pg_connect")) { $td2="Error"; } else { $td2="Ok"; }
	//echo DrawRow("Postgres Support",$td2);
	/* MYSQL INSTALLED */
	if (!function_exists("mysql_connect")) { $td2="Error"; } else { $td2="Ok"; }
	echo DrawRow("MySQL Support",$td2);
	/* GD INSTALLED */
	if (!function_exists("imagecreatetruecolor")) { $td2="Error"; } else { $td2="Ok"; }
	echo DrawRow("GD Support",$td2);

	echo DrawHeader("Server Configuration");

	/* FORM VALUES */
	echo DrawRow("Server Name","<input type='text' name='server_name' value='".$_SERVER['SERVER_NAME']."' size=40>");
	echo DrawRow("Port","<input type='text' name='server_port' value='".$_SERVER['SERVER_PORT']."' size=4>");
	echo DrawRow("Sub Folders","<input type='text' name='script_name' value='".STR_REPLACE("index.php","",$_SERVER['SCRIPT_NAME'])."' size=40>");

	/* WRITEABLE DIRECTORIES */
	echo DrawHeader("Configuration Files");
	$filename="../../site_config.php";
	if (file_exists($filename)) {
		if (is_writable("../")) {
			$osite_config="Writeable";
		}
		else {
			$site_config="Un-writeable";
			$continue=False;
		}
	}
	else {
		if (is_writable("../")) {
			$site_config="Writeable";
		}
		else {
			$site_config="Un-writeable";
			$continue=False;
		}
	}
	echo DrawRow("Configuration File and Directory",$site_config);

	if ($continue) {
		echo DrawHeader("<input type='submit' value=':: Continue ::' class='buttonstyle'>");
	}
	else {
		echo DrawHeader("One or more of the above need to be corrected first");
	}
	echo "</form>\n";
echo "</table>\n";
?>