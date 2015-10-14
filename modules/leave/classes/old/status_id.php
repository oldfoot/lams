<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class StatusID {

	function __construct() {

		$this->errors="";
	}
	public function SetParameters($status_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($status_id)) { $this->Errors("Invalid status"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->status_id=$status_id;

		/* CALL THE periodID INFORMATION */
		$this->Info($status_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_status_master
					WHERE status_id = '".$this->status_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$this->db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
		}
	}

	public function GetInfo($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
	}

	public function Add($status_name) {

		/* CHECKS */
		if(!preg_match("/^[\w\s]+$/",$status_name)) { $this->Errors("Invalid status name!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_status_master
					(status_name,workspace_id)
					VALUES (
					'".$status_name."',
					".$GLOBALS['workspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory($status_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($status_name) {

		/* CHECKS */
		if(!preg_match("/^[\w\s]+$/",$status_name)) { $this->Errors("Invalid status name!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
					SET status_name = '".$status_name."'
					WHERE status_id = ".$this->status_id."
					AND workspace_id = ".$GLOBALS['workspace_id']."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
			LogHistory($status_name." edited");
			return True;
		}
		else {
			return False;
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			$sql="DELETE
						FROM ".$GLOBALS['database_prefix']."leave_status_master
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->status_name." deleted");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function ChangeIsNew() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("is_new") == "y") {
				$is_new = "n";
			}
			else {
				$is_new = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
						SET is_new = '$is_new'
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory("Status ".$this->status_name." modified is_new = $is_new");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function ChangeIsNewDefault() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("is_new_default") == "y") {
				$is_new_default = "n";
			}
			else {
				$is_new_default = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
						SET is_new_default = '$is_new_default'
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory("Status ".$this->status_name." modified is_new_default = $is_new_default");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function ChangeIsApproved() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("is_approved") == "y") {
				$is_approved = "n";
			}
			else {
				$is_approved = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
						SET is_approved = '$is_approved'
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory("Status ".$this->status_name." modified is_approved = $is_approved");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function ChangeIsRejected() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("is_rejected") == "y") {
				$is_rejected = "n";
			}
			else {
				$is_rejected = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
						SET is_rejected = '$is_rejected'
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory("Status ".$this->status_name." modified is_rejected = $is_rejected");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
		}
	}

	public function ChangeIsDeleted() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("is_deleted") == "y") {
				$is_deleted = "n";
			}
			else {
				$is_deleted = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_status_master
						SET is_deleted = '$is_deleted'
						WHERE status_id = ".$this->status_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory("Status ".$this->status_name." modified is_deleted = $is_deleted");
				return True;
			}
			else {
				$this->Errors("Failed to delete");
				return False;
			}
		}
		else {
			$this->Errors("Sorry, parameter check failed<br>");
			return False;
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