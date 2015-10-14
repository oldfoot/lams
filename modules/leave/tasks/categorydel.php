<?php
require_once $dr."modules/leave/classes/leave_category_master.php";
	
function categorydel() {
	
	$c = "";	

	if (!ISSET($_GET['categoryid']) || !ISSET($_SESSION['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }

	$categoryid = CleanVar($_GET['categoryid']);

	$obj = new LeaveCategoryMaster;
	//$obj->SetVar("debug",true);
	$obj->SetVar("categoryid",$categoryid);
	$obj->SetVar("workspaceid",$_SESSION['workspaceid']);
	$obj->SetVar("userid",$_SESSION['userid']);


	$result = $obj->Delete();
	//if ($result) {
		header("Location: index.php?module=leave&task=category");
	//}
	$res = $obj->ShowErrors();
	$res = str_replace("\n","",$res);

	echo $res;
}
?>