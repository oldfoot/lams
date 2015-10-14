<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/core/classes/core_workspace_master.php";

if (!ISSET($_POST['workspacename']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }
$workspacename = CleanVar($_POST['workspacename']);

$wm = new WorkspaceMaster;
//$wm->SetVar("debug",true);
$wm->SetVar("parentid",0);
$wm->SetVar("workspacename",$workspacename);
$wm->SetVar("logo","");
$wm->SetVar("userid",$_SESSION['userid']);

if (!ISSET($_POST['workspaceid'])) {
	$result = $wm->Add();
}
else {
	$workspaceid = CleanVar($_POST['workspaceid']);
	$wm->SetVar("workspaceid",$workspaceid);
	$result = $wm->Edit();
}
if ($result) {
	//echo "Setting session";
	$_SESSION['workspaceid'] = $wm->GetVar("WorkspaceID");	
}
$res = $wm->ShowErrors();
$res = str_replace("\n","",$res);

echo $res;

?>