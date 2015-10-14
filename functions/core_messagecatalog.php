<?php
function MessageCatalogue($id) {
	// TODO: CHANGE TO STORED PROC
	
	$db = $GLOBALS['db'];
	$db->NextResult();
	$sql = "SELECT MessageID, Message
			FROM core_messagecatalogue
			WHERE MessageID = $id
			";
	//echo $sql;
	$result = $db->Query($sql);
	while ($row = $db->FetchArray($result)) {
		return $row['Message'];
	}	
}
?>