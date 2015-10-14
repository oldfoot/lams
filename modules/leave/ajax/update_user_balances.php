<?php
header("Pragma: no-cache");
define( '_VALID_DIR_', 1 );

require_once "../../../config.php";

/* DATABASE CONFIGURATION */
require_once "../../../db_config.php";

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../../../common_config.php";

require_once($GLOBALS['dr']."modules/leave/config.php");
require_once($GLOBALS['dr']."modules/leave/classes/user_balances.php");

$ub = new UserBalances;
$ub->SetParameters($_GET['user_id']);
$result = $ub->Update($_GET['cat'],$_GET['bal']);

if ($result) {
	echo "Success";
}
else {
	echo "Failed";
}
?>