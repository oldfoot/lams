<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/core/classes/core_workspace_master.php";

if (!ISSET($_SESSION['workspaceid']) || !ISSET($_POST['moduleid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }
$moduleid = CleanVar($_POST['moduleid']);

$wm = new WorkspaceMaster;
//$wm->SetVar("debug",true);
$wm->SetVar("workspaceid",$_SESSION['workspaceid']);
$wm->SetVar("moduleid",$moduleid);
$wm->SetVar("userid",$_SESSION['userid']);

$result = $wm->DelModule();
$res = $wm->ShowErrors();
$res = str_replace("\n","",$res);

echo $res;
?>