<?php
require_once $dr."modules/core/classes/core_workspace_master.php";

function deleteworkspace() {
	$c = "Workspace deletion result: <br />";	

	if (!ISSET($_GET['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }
	$workspaceid = CleanVar($_GET['workspaceid']);

	$wm = new WorkspaceMaster;
	$wm->SetVar("debug",true);
	$wm->SetVar("workspaceid",$workspaceid);
	$wm->SetVar("userid",$_SESSION['userid']);

	$result = $wm->Delete();
	if ($result) {
		//echo "Removing session";
		//unset($_SESSION['workspaceid'])
	}
	$res = $wm->ShowErrors();
	$res = str_replace("\n","",$res);
	
	//header("Location: index.php?module=core&task=browseworkspaces");
	// CAN SHOW THE RESULT
	return $res;
}
?>