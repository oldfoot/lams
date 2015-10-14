<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class UserBalances {

	function __construct() {
		$this->parameter_check=False;
	}
	public function SetParameters($user_id) {

		/* CHECKS */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user"); return False; }
		if (!RowExists("core_space_users","user_id",$user_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid user[2]"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->user_id=$user_id;

		/* CALL THE CATEGORYID INFORMATION */
		//$this->Info($category_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Update($category_id,$leave_total) {

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category"); return False; }
		if (!IS_NUMERIC($leave_total)) { $this->Errors("Invalid total"); return False; }

		$period_id = $GLOBALS['obj_us']->GetInfo("period_id");

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* DELETE THEN ADD - NOT A REPLACE FOR DB COMPATIBILITY */
		$sql="DELETE FROM ".$GLOBALS['database_prefix']."leave_user_balances
					WHERE user_id = ".$this->user_id."
					AND category_id = ".$category_id."
					AND period_id = ".$period_id."
					";
		//echo $sql."<br />";
		$db->query($sql);

		/* NOW INSERT */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_user_balances
					(user_id,period_id,category_id,leave_total)
					VALUES (
					".$this->user_id.",
					".$period_id.",
					".$category_id.",
					".$leave_total."
					)";
		//echo $sql."<br />";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory($leave_total." added for user ".$this->user_id." in category ".$category_id." in period: ".$period_id);
				return True;
		}
		else {
			return False;
		}
	}

	public function LeaveTaken($category_id) {
		$period_id = $GLOBALS['obj_us']->GetInfo("period_id");
		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT sum(total_days) AS total_days
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE user_id = ".$_SESSION['user_id']."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					AND category_id = ".$category_id."
					AND period_id = $period_id
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				if ($row['total_days'] > 0) {
					return $row['total_days'];
				}
				else {
					return 0;
				}
			}
		}
		else {
			return "0";
		}
	}

	public function LeaveTakenPeriod($period_id) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="SELECT sum(total_days) AS total_days
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE user_id = ".$this->user_id."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					AND period_id = ".$period_id."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				if ($row['total_days'] > 0) {
					return $row['total_days'];
				}
				else {
					return 0;
				}
			}
		}
		else {
			return "0";
		}
	}

	public function LeaveAvailable($category_id) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		$period_id=$GLOBALS['obj_us']->GetInfo("period_id");

		$sql="SELECT leave_total
					FROM ".$GLOBALS['database_prefix']."leave_user_balances
					WHERE user_id = ".$this->user_id."
					AND period_id = ".$period_id."
					AND category_id = ".$category_id."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				return $row['leave_total'];
			}
		}
		else {
			return "0";
		}
	}

	public function LeaveAvailableCatName($category_name) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		$period_id=$GLOBALS['obj_us']->GetInfo("period_id");

		$sql="SELECT leave_total
					FROM ".$GLOBALS['database_prefix']."leave_user_balances
					WHERE user_id = ".$_SESSION['user_id']."
					AND period_id = ".$period_id."
					AND category_name = ".$category_name."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				return $row['leave_total'];
			}
		}
		else {
			return "0";
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