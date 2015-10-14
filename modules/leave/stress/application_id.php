<?php
header("Pragma: no-cache");
define( '_VALID_DIR_', 1 );

require_once "../../../config.php";

/* DATABASE CONFIGURATION */
require_once "../../../db_config.php";

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../../../common_config.php";

require_once($GLOBALS['dr']."include/functions/date_time/mysql_to_seconds.php");
require_once($GLOBALS['dr']."modules/leave/config.php");
require_once($GLOBALS['dr']."modules/leave/classes/application_id.php");

$insert = 1000;

$obj = new ApplicationID;

for ($i=0;$i<$insert;$i++) {
	$obj->SetVariable("date_from",GetRandomDate());
	$obj->SetVariable("date_to",GetRandomDate());
	$obj->SetVariable("category_id",GetCategoryID());
	$obj->SetVariable("period_id",GetPeriodID());

	$result=$obj->Add();
	if (!$result) {
		echo $obj->ShowErrors();
		echo "<br />";
	}
	else {
		echo "ok<br />";
	}
}

function GetRandomDate() {
	$year = rand(2000,2009);
	$month = rand(1,12);
	$day = rand(0,31);
	return "$year-$month-$day";
}

function GetCategoryID() {
	$db = $GLOBALS['db'];
	$sql = "SELECT category_id
					FROM leave_category_master
					ORDER BY rand()
					LIMIT 1
					";

	$result = $db->Query($sql);
	while ($row = $db->FetchArray($result)) {
		return $row['category_id'];
	}
}

function GetPeriodID() {
	$db = $GLOBALS['db'];
	$sql = "SELECT period_id
					FROM leave_period_master
					";
	$result = $db->Query($sql);
	while ($row = $db->FetchArray($result)) {
		return $row['category_id'];
	}
}

?>