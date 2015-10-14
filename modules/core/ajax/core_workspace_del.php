<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/core/classes/core_workspace_master.php";

if (!ISSET($_POST['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }
$workspaceid = CleanVar($_POST['workspaceid']);

$wm = new WorkspaceMaster;
//$wm->SetVar("debug",true);
$wm->SetVar("workspaceid",$workspaceid);
$wm->SetVar("userid",$_SESSION['userid']);

$result = $wm->Delete();
if ($result) {
	//echo "Removing session";
	unset($_SESSION['workspaceid'])
}
$res = $wm->ShowErrors();
$res = str_replace("\n","",$res);

echo $res;
?>