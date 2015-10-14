<?php
require_once $dr."modules/leave/classes/leave_status_master.php";
	
function statusdel() {
	
	$c = "";	

	if (!ISSET($_GET['statusid']) || !ISSET($_SESSION['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }

	$statusid = CleanVar($_GET['statusid']);

	$obj = new LeaveStatusMaster;
	//$obj->SetVar("debug",true);
	$obj->SetVar("statusid",$statusid);
	$obj->SetVar("workspaceid",$_SESSION['workspaceid']);
	$obj->SetVar("userid",$_SESSION['userid']);


	$result = $obj->Delete();
	//if ($result) {
		header("Location: index.php?module=leave&task=status");
	//}
	$res = $obj->ShowErrors();
	$res = str_replace("\n","",$res);

	echo $res;
}
?>