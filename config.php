<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

ob_start();

// TURN OFF ERRORS IN PRODUCTION
if ($_SERVER['SERVER_NAME'] == "localhost" || $_SERVER['SERVER_NAME'] == "192.168.2.4" || preg_match("/tst_/",$_SERVER['SCRIPT_NAME'])) {
	//ini_set("display_errors","Off");		
	error_reporting(E_ALL);
	//error_reporting(0);
}
else {
	error_reporting(0);
}

require "modules/core/classes/core_offline.php";	
$offline = new offline;

if (file_exists("siteoffline")) {
	$offline->SetVar("message_extra","We are doing a bit of maintenance, check back shortly.");
	echo $offline->Show();
	die();
}

require "modules/core/classes/core_session.php";	

require_once "site_config.php";

$session = new session();

session_set_save_handler(array($session,"open"),
                         array($session,"close"),
                         array($session,"read"),
                         array($session,"write"),
                         array($session,"destroy"),
                         array($session,"gc")); 

session_start();

require "modules/core/classes/core_mysqli.php";	

$db = new MySQL;
$db->Connect($database_hostname,$database_user,$database_password,$database_name,$database_port);

require_once "functions/core_messagecatalog.php";

function CleanVar($var) {
	return htmlentities($var, ENT_QUOTES);
}

function GetSafeVar($from,$name) {
	if ($from == "get") {
		if (ISSET($_GET[$name])) {
			return addslashes($_GET[$name]);
		}
	}
	if ($from == "post") {
		if (ISSET($_POST[$name])) {			
			return addslashes($_POST[$name]);
		}
	}
}
// GLOBAL ERROR HANDLING
require_once "modules/core/classes/core_errors.php";
$errors = new errors;

// CURRENT LOGGED IN USER DATA
if (ISSET($_SESSION['userid'])) {
	
	require_once "modules/core/classes/core_usermaster.php";
	$user = new UserMaster;
	//$user->SetVar("debug",true);
	$user->SetParameters($_SESSION['userid']); // legacy
	$user->SetVar("userid",$_SESSION['userid']);
	
	// SET A WORKSPACE ID IF ONE IS PRESENT
	if (ISSET($_GET['workspaceid']) &&  $_GET['workspaceid'] != $_SESSION['workspace_id']) {
		
		$user->SetVar("workspace_id",$_GET['workspaceid']);
		$user->SetWorkspaceID();
	}
	//echo $_SESSION['workspace_id'];
	
	// ROLE PRIVILEGES
	
	require_once "modules/core/classes/core_userroles.php";	
	$userroles = new UserRoles;
	$userroles->SetVar("userid",$_SESSION['userid']);	
	//$userroles->SetVar("debug",true);
	$userroles->Info();
}

// LOG
$url = "";
if (ISSET($_SERVER['SCRIPT_NAME'])) {
	$url .= $_SERVER['SCRIPT_NAME'];
}
if (ISSET($_SERVER['QUERY_STRING'])) {
	$url .= "?".$_SERVER['QUERY_STRING'];
}
$sessionid = session_id();

$ignore_log_urls = array();

foreach ($ignore_log_urls as $url) {
	if (!preg_match("/$url/",$_SERVER['SCRIPT_NAME'])) {
		$sql = "INSERT INTO core_log (SCRIPT_NAME,DateTimeLogged,SessionID) 
				VALUES (
				'".mysql_real_escape_string($url)."',
				sysdate(),
				'".$sessionid."'
				)";
		$db->Query($sql);
	}
}
?>