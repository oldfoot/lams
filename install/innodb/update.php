<?php
define( '_VALID_DIR_', 1 );

require_once "../../config.php";

/* DATABASE CONFIGURATION */
require_once "../../db_config.php";

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../../common_config.php";

$result=$db->Query("SHOW tables FROM ".$database_name);

while ($row = $db->FetchArray($result)) {
	$sql = "alter table $row[0] Engine = INNODB";
	$db->Query($sql);
}
?>