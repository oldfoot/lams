<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/user_settings.php";

/* LANGUAGE */
if (file_exists($GLOBALS['dr']."modules/leave/language/".$GLOBALS['user']->GetVar("Language").".php")) {
	$language_file=$GLOBALS['dr']."modules/leave/language/".$GLOBALS['user']->GetVar("Language").".php";
	require_once $language_file;
}
else {
	require_once $GLOBALS['dr']."modules/leave/language/en.php";
}

/* MENU */
$module_menu_items = array( ""=>"Home",
							"module=leave&task=dashboard"=>"Dashboard",
							"module=leave&task=apply"=>"Apply",
							"module=leave&task=myapplications"=>"My Applications",
							"module=leave&task=balance"=>"Balance",
							"module=leave&task=category"=>"Categories",
							"module=leave&task=periods"=>"Periods",
							"module=leave&task=status"=>"Status",
							"module=leave&task=userbalances"=>"User Balances",
							"module=leave&task=userbalancesgrid"=>"User Balances Grid",
							"module=leave&task=hodapproval"=>"HOD Approval",
							"module=leave&task=globalapproval"=>"Global Approval",
							"module=leave&task=reports"=>"Reports",
							"module=leave&task=reportsadmin"=>"Reports Admin",
							"module=leave&task=balancetransfer"=>"Balance Transfer",
							"module=leave&task=workflow"=>"Workflow",
							"module=leave&task=history"=>"History",
							"module=leave&task=acl"=>"ACL"
							);


/* USER SETTINGS */
GLOBAL $obj_us;
$obj_us=new UserSettings;
$obj_us->SetVar("userid",$_SESSION['userid']);
//$obj_us->SetVar("debug",true);
$result = $obj_us->Info();
if (!$result) { die($obj_us->ShowErrors()); }

/* LEAVE SETTINGS */
global $leave_total_saturdays_value;
$leave_total_saturdays_value = 0.5;

/* THIS DETERMINS HOW MANY DAYS A SUNDAY IS WORTH */

global $leave_total_sundays_value;
$leave_total_sundays_value = 0;

/* THIS WILL RECORD EXACTLY HOW THE TOTAL DAYS ARE CALCULATED FOR EACH APPLICATION */
global $enable_debugging_leave;
$enable_debugging_leave = true;

?>