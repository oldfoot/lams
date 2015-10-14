<?php
define( '_VALID_DIR_', 1 );
require_once "../config.php";

require_once "../db_config.php";

require_once "../common_config.php";

if ($handle = opendir('../modules/')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." && substr($file,-4) != ".php") {
            echo "$file<br />\n";
            SaveDB($file);
        }
    }
    closedir($handle);
}

exit();
function SaveDB($f) {
    // extract all the table designs
    $sql = "SHOW TABLES FROM genus WHERE TABLES_IN_GENUS LIKE '".$f."_%'";
    echo "$sql<br />\n";
    $db = $GLOBALS['db'];
    $result = $db->Query($sql);
    while ($row = $db->FetchArray($result)) {
        $sql = "SHOW CREATE TABLE ".$row[0];
        $result1 = $db->Query($sql);
        while ($row1 = $db->FetchArray($result1)) {
            $file = "../modules/$f/db/".$row1[0].".sql";
            $install_file = "db/".$row1[0].".sql";
            echo "Dumping table: $file <br />\n";
            file_put_contents($file,$row1[1]);
            file_put_contents($install_file,$row1[1]);
        }
    }
}
?>