<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/leave/classes/leave_status_master.php";

if (!ISSET($_POST['statusname']) || !ISSET($_SESSION['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }

$statusname = CleanVar($_POST['statusname']);

$obj = new LeaveStatusMaster;
//$obj->SetVar("debug",true);
$obj->SetVar("statusname",$statusname);
$obj->SetVar("workspaceid",$_SESSION['workspaceid']);
$obj->SetVar("userid",$_SESSION['userid']);


$result = $obj->Add();
if ($result) {
	//echo "Setting session";
	//$_SESSION['workspaceid'] = $obj->GetVar("WorkspaceID");	
}
$res = $obj->ShowErrors();
$res = str_replace("\n","",$res);

echo $res;

?>