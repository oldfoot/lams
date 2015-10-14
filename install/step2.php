<?php
$step2_continue=False; /* START WITH A FAILURE BECAUSE WE HAVE NOT PROBED */
$show_create_db_checkbox=True;
$readonly="";

/* VARS FROM STEP 1 */
$server_name="";
$server_port="";
$script_name="";

/* VARS USED IN THIS SCRIPT */
$database_server="";
$database_port="";
$db_user="";
$db_user_password="";
$database_use="";
$app_user="";
$app_user_password="";

if (ISSET($_GET['test']) && $_GET['test'] == "y") {

	if (ISSET($_POST['database_server']) &&
			ISSET($_POST['database_port']) &&
			ISSET($_POST['db_user']) &&
			ISSET($_POST['database_use']) &&
			ISSET($_POST['app_user'])
			) {


		$database_server=$_POST['database_server'];
		$database_port=$_POST['database_port'];
		$db_user=$_POST['db_user'];
		$db_user_password=$_POST['db_user_password'];
		$database_use=$_POST['database_use'];
		$app_user=$_POST['app_user'];
		$app_user_password=$_POST['app_user_password'];

		if ($_POST['create_database']=="y") {
			$d_result=mysql_connect($database_server.":".$database_port,$db_user,$db_user_password);
			mysql_query("create database ".$database_use);
			$show_create_db_checkbox=False;
		}

		include "../classes/db/mysql.php";
		$db = new mysql;//New object
		$result=$db->Connect($database_server,$database_port,$database_use,$db_user,$db_user_password);//Set credentials ready to connect,
		if (!$result) { //Connect to the database using a non persistant connection
			echo "Sorry, couldnt connect. Did you create the database? Check your parameters again<br>";
		}
		else {
			echo "Success, connected, you may now continue!";
			$step2_continue=True;
			$readonly="readonly";
		}
	}
}
if (ISSET($_POST['server_name']) && ISSET($_POST['server_port']) && ISSET($_POST['script_name'])) {
	$server_name=$_POST['server_name'];
	$server_port=$_POST['server_port'];
	$script_name=$_POST['script_name'];
}

echo "<table class='summary'>\n";
	if ($step2_continue) {
		echo "<form method='post' action='index.php?step=3'>\n";
	}
	else {
		echo "<form method='post' action='index.php?step=2&test=y'>\n";
	}
	echo "<input type='hidden' name='server_name' value='".$server_name."'>\n";
	echo "<input type='hidden' name='server_port' value='".$server_port."'>\n";
	echo "<input type='hidden' name='script_name' value='".$script_name."'>\n";

	//echo "<input type='hidden' name='document_root' value='".$_POST['document_root']."'>\n";

	echo DrawHeader("Database Server Information");
	echo DrawRow("Database Server","<input type='text' name='database_server' value='".$database_server."' size=25 $readonly>");
	echo DrawRow("Database Port","<input type='text' name='database_port' value='".$database_port."' size=25 $readonly>");
	echo DrawRow("Database To Use","<input type='text' name='database_use' value='".$database_use."' size=25 $readonly>");
	if ($show_create_db_checkbox) {
		echo DrawRow("Create database now","<input type='checkbox' name='create_database' value='y'>");
	}
	//echo DrawHeader("<li><b>Please create the database before continuing</b></li>","plain");

	echo DrawHeader("Database Creation User");
	echo DrawRow("User","<input type='text' name='db_user' value='".$db_user."' size=25 $readonly>");
	echo DrawRow("Password","<input type='password' name='db_user_password' value='".$db_user_password."' size=25 $readonly>");
	echo DrawHeader("<li>This user will be used to connect and create your database structure","plain");

	echo DrawHeader("Database Application User");
	echo DrawRow("Application User","<input type='text' name='app_user' value='".$app_user."' size=25 $readonly>");
	echo DrawRow("Password","<input type='password' name='app_user_password' value='".$app_user_password."' size=25 $readonly>");
	echo DrawHeader("<li>This user will be created in your database","plain");



	/* TAKEN OUT TEMPORARILY
	if ($step2_continue) {
		echo "<tr>\n";
			echo "<td>Create Database</td>\n";
			echo "<td><input type='checkbox' name='create_database' value='yes'></td>\n";
			echo "</td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
			echo "<td>Drop Tables (if they exist)</td>\n";
			echo "<td><input type='checkbox' name='drop_tables' value='yes'></td>\n";
			echo "</td>\n";
		echo "</tr>\n";
	}
	*/


	if ($step2_continue) {
		$show_button="Continue";
	}
	else {
		$show_button="Test";
	}
	echo DrawHeader("<input type='submit' value='".$show_button."'>");
	echo "</form>\n";
echo "</table>\n";
?>