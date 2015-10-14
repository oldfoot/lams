<?php
header("Pragma: no-cache");
define( '_VALID_DIR_', 1 );

/*** REQUIRED FILES ***/
require_once "../../../config.php";
require_once $GLOBALS['dr']."db_config.php";
require_once $GLOBALS['dr']."common_config.php";
/*** REQUIRED FILES ***/

require_once $dr."include/functions/db/row_exists.php";
require_once $dr."include/functions/misc/alert.php";

$attachment_id=EscapeData($_GET['attachment_id']);
//$di=new DocumentID;
//$di->SetParameters($document_id);

/* CHECK THAT THE DOCUMENT EXISTS */
if (!RowExists("leave_application_attachments","attachment_id",$attachment_id,"")) { die(ErrorMessages(21)); }

/* CREATE THE OBJECT */

/*
echo $di->GetColVal("filetype")."<br>";
echo $di->GetColVal("filesize")."<br>";
echo FormatSpaces($di->GetColVal("filename"))."<br>";
*/
$sql = "SELECT *
					FROM leave_application_attachments
					WHERE attachment_id = $attachment_id
					";
$result = $db->Query($sql);
while ($row = $db->FetchArray($result)) {

	header("Content-Type: ".$row['filetype']);
	header("Content-Length: ".$row['filesize']);
	header("Content-Disposition: attachment; filename=".FormatSpaces($row['filename']));
	echo $row['attachment'];
}
function FormatSpaces($v) {
	return STR_REPLACE(" ","%20",$v);
}
?>