<?php
require_once "../modules/core/classes/core_mysqli.php";

$db=new mysql;//New object
$result = $db->Connect("localhost","root","root","lams_dev",3306);
if (!$result) {
	echo "Database connection failed";
	die();
}

// extract all the table designs
$sql = "show full tables from `lams_dev` where table_type = 'BASE TABLE'";
$result = $db->Query($sql);
while ($row = $db->FetchArray($result)) {
    $sql = "SHOW CREATE TABLE ".$row[0];
    $result1 = $db->Query($sql);
    while ($row1 = $db->FetchArray($result1)) {
	$file = "db/".$row1[0].".sql";
	echo "Dumping table: $file <br />\n";
	file_put_contents($file,$row1[1]);
    }
}
echo "Starting Stored Procedures <br />";
$sql = "select `SPECIFIC_NAME` from `INFORMATION_SCHEMA`.`ROUTINES` where `ROUTINE_SCHEMA` = 'lams_dev' and ROUTINE_TYPE = 'PROCEDURE'";
$result = $db->Query($sql);
while ($row = $db->FetchArray($result)) {
  $sql = "show create procedure lams_dev.".$row[0];
  $result1 = $db->Query($sql);
  while ($row1 = $db->FetchArray($result1)) {
        $file = "procedures/".$row[0].".sql";
        echo "Dumping stored proc: $file <br />\n";
        file_put_contents($file,$row1[2]);
    }
}

echo "Starting Views<br />";
$sql = "select `TABLE_NAME` from `INFORMATION_SCHEMA`.`TABLES` where `TABLE_SCHEMA` = 'lams_dev' and `TABLE_TYPE` = 'VIEW'";
$result = $db->Query($sql);
while ($row = $db->FetchArray($result)) {
  $sql = "show create view lams_dev.".$row[0];
  $result1 = $db->Query($sql);
  while ($row1 = $db->FetchArray($result1)) {
        $file = "views/".$row[0].".sql";
        echo "Dumping view: $file <br />\n";
        file_put_contents($file,$row1[1]);
    }
}

echo "Starting Triggers<br />";
$sql = "select `TABLE_NAME` from `INFORMATION_SCHEMA`.`TABLES` where `TABLE_SCHEMA` = 'lams_dev' and `TABLE_TYPE` = 'TRIGGER'";
$result = $db->Query($sql);
while ($row = $db->FetchArray($result)) {
  $sql = "show create trigger lams_dev.".$row[0];
  $result1 = $db->Query($sql);
  while ($row1 = $db->FetchArray($result1)) {
        $file = "triggers/".$row[0].".sql";
        echo "Dumping trigger: $file <br />\n";
        file_put_contents($file,$row1[2]);
    }
}
?>