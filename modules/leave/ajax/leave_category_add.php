<?php

define( '_VALID_DIR_', 1 );
require "../../../config.php";

require_once $dr."modules/leave/classes/leave_category_master.php";

if (!ISSET($_POST['categoryname']) || !ISSET($_SESSION['workspaceid']) || !ISSET($_SESSION['userid'])) { die("Invalid Params"); }

$categoryname = CleanVar($_POST['categoryname']);

$allownegativebalance = "y";
if (!ISSET($_POST['allownegativebalance']) || $_POST['allownegativebalance'] != "y") { $allownegativebalance = "n"; }
$allowbalancecarryforward = "y";
if (!ISSET($_POST['allowbalancecarryforward']) || $_POST['allowbalancecarryforward'] != "y") { $allowbalancecarryforward = "n"; }
$paidunpaid = "Paid";
if (!ISSET($_POST['paidunpaid']) || $_POST['paidunpaid'] != "Paid") { $paidunpaid = "Unpaid"; }
$autoapprove = "y";
if (!ISSET($_POST['autoapprove']) || $_POST['autoapprove'] != "y") { $autoapprove = "n"; }
$planning = "y";
if (!ISSET($_POST['planning']) || $_POST['planning'] != "y") { $planning = "n"; }

$obj = new LeaveCategoryMaster;
//$obj->SetVar("debug",true);
$obj->SetVar("categoryname",$categoryname);
$obj->SetVar("allownegativebalance",$allownegativebalance);
$obj->SetVar("allowbalancecarryforward",$allowbalancecarryforward);
$obj->SetVar("paidunpaid",$paidunpaid);
$obj->SetVar("autoapprove",$autoapprove);
$obj->SetVar("planning",$planning);
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