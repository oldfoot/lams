<?php
$step2_continue=False; /* START WITH A FAILURE BECAUSE WE HAVE NOT PROBED */

echo "<table class='summary'>\n";

if (ISSET($_POST['database_server']) &&
			ISSET($_POST['database_port']) &&
			ISSET($_POST['db_user']) &&
			ISSET($_POST['database_use']) &&
			ISSET($_POST['app_user'])) {

		$database_server=$_POST['database_server'];
		$database_port=$_POST['database_port'];
		$db_user=$_POST['db_user'];
		$db_user_password=$_POST['db_user_password'];
		$database_use=$_POST['database_use'];
		$app_user=$_POST['app_user'];
		$app_user_password=$_POST['app_user_password'];

		$server_name=$_POST['server_name'];
		$server_port=$_POST['server_port'];
		$script_name=$_POST['script_name'];

		include "../classes/db/mysql.php";

		$db = new mysql;//New object
		$result=$db->Connect($database_server,$database_port,$database_use,$db_user,$db_user_password);//Set credentials ready to connect,
		if (!$result) { //Connect to the database using a non persistant connection
			echo DrawHeader("Database connection failed","Sorry, couldnt connect to database. Check your parameters again<br>");
		}
		else {
			echo DrawHeader("Success, starting database installation now...");
		}


		/* CREATE ALL THE TABLES */
		echo DrawHeader("Creating Database Structure Now...","");
		CreateDBStructure();

    echo DrawHeader("Creating Database Stored Procedures...","");
		CreateDBStoredProcs();

    echo DrawHeader("Creating Database Functions...","");
		CreateDBFunctions();
		
		echo DrawHeader("Creating Database Views...","");
		CreateDBViews();

		echo DrawHeader("Creating Database Triggers...","");
		CreateDBTriggers();

		echo DrawHeader("Dumping Database Data Now...","");
		InsertDBData();

		//echo DrawHeader("Attempting to use transactions (innodb)...","");
		//UpdateInnoDB();
		echo DrawHeader("Creating the application configuration file now...","");
		CreateConfigFile();

		echo DrawHeader("Creating environment file now");
		WriteEnvFile();
		
		echo DrawHeader("Creating ODISS config file");
		WebclientSiteconfig();
		
		/* ADD THE USER TO THE SYSTEM */
		$db->query("GRANT select,insert,update,delete ON ".$database_use.".* TO ".$app_user."@'%' IDENTIFIED BY '".$app_user_password."'");

		/*
		if ($result) {
			echo DrawHeader("Success!","plain");
		}
		else {
			echo DrawHeader("FAILED!","plain");
		}
		*/


	//}
	echo DrawRow("<a href='../index.php'>Done</a> - login using 'admin' and 'admin' as the username and password respectively.","");
}
else {
	echo DrawRow("You missed out some data in step 2. Please go back now");
}

echo "</table>\n";



function CreateDBStructure() {
	// GET AND SORT DIRECTORY
	$dirfiles = array();
	if ($handle = opendir('db/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$dirfiles[] = $file;
			}
		}
	}
	sort($dirfiles);
	

	$db=$GLOBALS['db'];
	echo "Setting foreign key checks to zero<br />\n";
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	foreach ($dirfiles as $file) {
	      	$data=file_get_contents("db/".$file);
       	//echo $data;
       	//echo "<hr>";
		echo "Creating table $file ...\n";		
       	$db->Query($data);       
		echo "done<br />\n";
	 }
	echo "Setting foreign key checks to one<br />\n";
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
	echo "Done with DB Structure<br />\n";
}

function InsertDBData() {
	$db=$GLOBALS['db'];
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	if ($handle = opendir('data/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
       	$data=file_get_contents("data/".$file);
       	//echo $data;
       	//echo "<hr>";
       	if (strlen($data) >2) {
       		$db->Query($data);
       	}
       }
	   }
	 }
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
}

function CreateDBStoredProcs() {
	$db=$GLOBALS['db'];
	echo "Setting foreign key checks to zero<br />\n";
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	if ($handle = opendir('procedures/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
       	$data=file_get_contents("procedures/".$file);
       	//echo $data;
       	//echo "<hr>";
       	$db->Query($data);
       }
	   }
	 }
	echo "Setting foreign key checks to one<br />\n";
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
	echo "Done with Stored Procs<br />\n";
}

function CreateDBFunctions() {
	$db=$GLOBALS['db'];
	echo "Setting foreign key checks to zero<br />\n";
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	if ($handle = opendir('functions/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
       	$data=file_get_contents("functions/".$file);
       	//echo $data;
       	//echo "<hr>";
       	$db->Query($data);
       }
	   }
	 }
	echo "Setting foreign key checks to one<br />\n";
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
	echo "Done with Functions<br />\n";
}

function CreateDBViews() {
	$db=$GLOBALS['db'];
	echo "Starting Views<br />\n";
	echo "Setting foreign key checks to zero<br />\n";
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	if ($handle = opendir('views/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
       	$data=file_get_contents("views/".$file);
       	//echo $data;
       	//echo "<hr>";
       	$db->Query($data);
       }
	   }
	 }
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
	echo "Done with Views<br />\n";
}

function CreateDBTriggers() {
	$db=$GLOBALS['db'];
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");
	if ($handle = opendir('triggers/')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
       			$data=file_get_contents("triggers/".$file);
		       	//echo $data;
		       	//echo "<hr>";
		       	$db->Query($data);
		       }
		}
	 }
	 $db->Query("SET FOREIGN_KEY_CHECKS = 1");
	echo "Done with Triggers<br />\n";
}

// not used now
function UpdateInnoDB() {
	$db=$GLOBALS['db'];
	$db->Query("SET FOREIGN_KEY_CHECKS = 0");

	$data=file("innodb/sql.sql");
	foreach ($data as $sql) {
  		$db->Query($sql);
	}
	$db->Query("SET FOREIGN_KEY_CHECKS = 1");
}

function CreateConfigFile() {
	$db_setup="<?php\n";
	$db_setup.="/** ensure this file is being included by a parent file */\n";
	$db_setup.="defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );\n";
	$db_setup.="\n";
	$db_setup.=ConfigRow("Website URL","wb",GetServerName());
	$db_setup.=ConfigRow("Website Directory","dr",GetServerRoot());
	$db_setup.="\n";
	$db_setup.=ConfigRow("Database Type","database_type","mysql");
	$db_setup.=ConfigRow("Authentication Type","authentication_type","mysql");
	$db_setup.=ConfigRow("Database Server","database_hostname",$_POST['database_server']);
	$db_setup.=ConfigRow("Database Port","database_port",$_POST['database_port']);
	$db_setup.=ConfigRow("Database User","database_user",$_POST['app_user']);
	$db_setup.=ConfigRow("Database Password","database_password",$_POST['app_user_password']);
	$db_setup.=ConfigRow("Database Name","database_name",$_POST['database_use']);
	$db_setup.=ConfigRow("Database Prefix","database_prefix",$_POST['database_use'].".");
	$db_setup.="\n";
	$db_setup.=ConfigRow("Who should emails be sent from?","email_recover_password_from","admin@myserver.com");
	$db_setup.="\n";
	$db_setup.=ConfigRow("Mail Type either PHP's mail function or SMTP","mail_type","mail");
	$db_setup.=ConfigRow("SMTP Server","smtp_server","localhost");
	$db_setup.=ConfigRow("SMTP Requires authentication","smtp_require_auth","n");
	$db_setup.=ConfigRow("SMTP User","smtp_user","anonymous");
	$db_setup.=ConfigRow("SMTP Password","smtp_password","anon@ymous.com");
	$db_setup.="\n";
	$db_setup.=ConfigRow("Site Logo","site_logo","<img src='images/home/genusproject.gif' border='0'>");
	$db_setup.=ConfigRow("Site Title","site_title","My Website");
	$db_setup.=ConfigRow("Site Theme","css_theme","portal");
	//$db_setup.=ConfigRow("Show links on home page","show_home_more_info","False");
	$db_setup.="\n";
	$db_setup.=ConfigRow("Copyright Notice","copyright_notice","&copy;2004-2007");
	$db_setup.=ConfigRow("Use Wiki Help","use_wiki_help","True");
	$db_setup.=ConfigRow("Use Wiki URL","wiki_url","http://genusproject.org/wiki");
	$db_setup.="\n";
	$db_setup.=ConfigRow("Enable Site Registration","allow_registration","True");
  $db_setup.="\n";
	$db_setup.=ConfigRow("MyHalo Integration","myhalo_reg_url","http://myhalo.org/webservices/rest/register.php?format=xml");


	$db_setup.="?>\n";

	$filename="../site_config.php";
	if (!file_exists($filename)) {
		$handle = fopen($filename,"w");
		fwrite($handle, "");
		fclose($handle);
	}
	if (is_writable($filename)) {
		if (!$handle = fopen($filename,"w")) {
			echo DrawRow("Writing config file failed. Check file permissions.");
      exit;
    }
    if (fwrite($handle, $db_setup) === FALSE) {
      echo DrawRow("Cannot write to config file. Check file permissions.");
      exit;
    }
    echo DrawRow("Success, config file created");

  	fclose($handle);

	}
	else {
    echo DrawRow("Config file is missing or not writeable.");
	}
}

function ConfigRow($desc,$var,$val) {
	$v="/*".$desc."*/\n";
	$v.="$".$var."=\"".$val."\";\n";
	//echo nl2br($v)."<hr>";
	return $v;

}

function GetServerName() {
	$v="http://".$_POST['server_name'];
	if ($_POST['server_port'] != "80") {
		$v.=":".$_POST['server_port'];
	}
	$v.=str_replace("install/","",$_POST['script_name']);
	return $v;
}

function GetServerRoot() {
	$v=strtolower($_SERVER['DOCUMENT_ROOT']);
	$v.=str_replace("install/","",$_POST['script_name']);
	return $v;
}

function WriteEnvFile() {
	$c = "<?php\n";
	$c .= "class production {\n";
	$c .= "var \$database_hostname;\n";
	$c .= "var \$database_port;\n";
	$c .= "var \$database_name;\n";
	$c .= "var \$database_user;\n";
	$c .= "var \$database_password;\n";
	$c .= "public function __construct() {\n";
	
    $c .= "\$this->database_hostname = \"".$GLOBALS['database_server']."\";\n";
	$c .= "\$this->database_port = \"".$GLOBALS['database_port']."\";\n";
	$c .= "\$this->database_name = \"".$GLOBALS['database_use']."\";\n";
	$c .= "\$this->database_user = \"".$GLOBALS['app_user']."\";\n";
	$c .= "\$this->database_password = \"".$GLOBALS['app_user_password']."\";\n";

	$c .= "}\n";

	$c .= "public function GetVar(\$v) {\n";
    $c .= "return \$this->\$v;\n";
	$c .= "}\n";

	$c .= "public function SetVar(\$v) {\n";
    $c .= "\$this->\$v = \$v;\n";
	$c .= "}\n";
	
	$c .= "}\n";
	$c .= "?>\n";
	
	file_put_contents("../modules/core/db/environments/production.php",$c);
}

function WebclientSiteconfig() {
	$c = "<?php\n";
	$c .= "\$dr = \"".GetServerRoot()."\";\n";
	$c .= "\$website_directory = \"".GetServerRoot()."modules/webclient/\";\n";
	$c .= "?>\n";
	
	file_put_contents("../modules/webclient/site_config.php",$c);
}
?>