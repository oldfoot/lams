<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/leave/classes/leave_period_master.php";

if (!ISSET($_POST['datefrom']) || !ISSET($_POST['dateto']) || !ISSET($_SESSION['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }
$datefrom = CleanVar($_POST['datefrom']);
$dateto = CleanVar($_POST['dateto']);

$lpm = new LeavePeriodMaster;
//$lpm->SetVar("debug",true);
$lpm->SetVar("datefrom",$datefrom);
$lpm->SetVar("dateto",$dateto);
$lpm->SetVar("workspaceid",$_SESSION['workspaceid']);
$lpm->SetVar("userid",$_SESSION['userid']);

$result = $lpm->Add();
if ($result) {
	//echo "Setting session";
	//$_SESSION['workspaceid'] = $lpm->GetVar("WorkspaceID");	
}
$res = $lpm->ShowErrors();
$res = str_replace("\n","",$res);

echo $res;

?>