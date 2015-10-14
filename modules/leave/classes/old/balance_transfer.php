<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );


class BalanceTransfer {

	function __construct() {
		$this->debug=False;
	}

	public function Start($period_id_from,$period_id_to) {

		$db=$GLOBALS['db'];

		/* CHECKS */
		if (!IS_NUMERIC($period_id_from)) { $this->Errors("Invalid period from"); return False; }
		if (!IS_NUMERIC($period_id_to)) { $this->Errors("Invalid period to"); return False; }

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_user_balances
					WHERE period_id = $period_id_from
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$sql1 = "UPDATE ".$GLOBALS['database_prefix']."leave_user_balances
								SET leave_total = leave_total + ".$row['leave_total']."
								WHERE period_id = $period_id_to
								AND user_id = ".$row['user_id']."
								AND category_id = ".$row['category_id']."
								";
				//echo $sql1."<br />";
				$result1 = $db->Query($sql1);
				if (!$result) {
					return False;
				}
			}
		}
		return True;
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
		}
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>