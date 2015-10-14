<?php
class leave {
  public function homecount() {
    $db = $GLOBALS['db'];
    $sql = "SELECT count(*) as total
            FROM leave_application_approval
            WHERE user_id = ".$_SESSION['user_id']."
            AND approved IS NULL
            ";
    $result = $db->Query($sql);
    while ($row = $db->FetchArray($result)) {
        return $row['total'];
    }
    return "0";
  }
}
?>
