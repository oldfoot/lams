<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class CategoryID {

	function __construct() {
		$this->errors="";
	}
	public function SetParameters($category_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($category_id)) { $this->Errors("Invalid category"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->category_id=$category_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($category_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_category_master
					WHERE category_id = '".$this->category_id."'
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
		return $this->$v;
	}

	public function Add($category_name,$allow_negative_balance,$allow_balance_carry_forward,$paid_unpaid) {

		/* HANDLE CHECKBOXES */
		if ($allow_negative_balance=="y") { $allow_negative_leave_balance = "y"; } else { $allow_negative_leave_balance = "n"; }

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("leave_category_master","category_name","'".$category_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_category_master
					(category_name,allow_negative_balance,allow_balance_carry_forward,paid_unpaid,workspace_id)
					VALUES (
					'".$category_name."',
					'".$allow_negative_balance."',
					'".$allow_balance_carry_forward."',
					'".$paid_unpaid."',
					".$GLOBALS['workspace_id']."
					)";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				//echo "ok";
				LogHistory($category_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	public function Edit($category_name,$allow_negative_balance,$allow_balance_carry_forward,$paid_unpaid) {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Parameter check failed"); return False; }
		if(!preg_match("([a-zA-Z0-9])",$category_name))  { $this->Errors("Invalid category name. Please use only alpha-numeric values!"); return False; }

		//if (RowExists("leave_category_master","category_name","'".$category_name."'","AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Category name exists. Please choose another."); return False; }

		if (!$allow_negative_balance=="y" || !$allow_negative_balance=="n") { $this->Errors("Invalid value for allow negative balance"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_category_master
					SET category_name = '".$category_name."',
					allow_negative_balance = '".$allow_negative_balance."',
					allow_balance_carry_forward = '".$allow_balance_carry_forward."',
					paid_unpaid = '".$paid_unpaid."'
					WHERE category_id = ".$this->category_id."
					";
		//echo $sql."<br>";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
			LogHistory($category_name." edited");
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
						FROM ".$GLOBALS['database_prefix']."leave_category_master
						WHERE category_id = ".$this->category_id."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				LogHistory($this->category_name." deleted");
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

	public function ChangeAutoApprove() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("auto_approve") == "y") {
				$auto_approve = "n";
			}
			else {
				$auto_approve = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_category_master
						SET auto_approve = '$auto_approve'
						WHERE category_id = ".$this->category_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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

	public function ChangePlanning() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("planning") == "y") {
				$planning = "n";
			}
			else {
				$planning = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_category_master
						SET planning = '$planning'
						WHERE category_id = ".$this->category_id."
						AND workspace_id = ".$GLOBALS['workspace_id']."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
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