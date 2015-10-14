<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class LeaveStatusMaster {

	function __construct() {
		$this->errors = "";		
		$this->debug  = false;
	}
	
	public function GetVar($v) {
		if (ISSET($this->$v)) {
			return $this->$v;
		}
		else {
			return "";
		}
	}	
	public function SetVar($v,$val) {
		$this->$v = $val;
	}
	// UNUSED
	private function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_status_master
					WHERE StatusID = '".$this->StatusID."'
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

	public function Add() {

		if (!ISSET($this->statusname) || !ISSET($this->workspaceid) || !ISSET($this->userid)) {
			$this->debug("Invalid data types");
			$this->Errors("Invalid data");
			return false;
		}

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		
		$sql="CALL sp_leave_status_master_add(
					'".$this->statusname."',
					'".$this->workspaceid."',
					'".$this->userid."'
					)";
		$this->debug($sql);
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
				//LogHistory("$date_from to $date_to added");
				$this->Errors(MessageCatalogue(98));
				return True;
		}
		else {
			$this->Errors(MessageCatalogue(99));
			return False;
		}
	}
	// not implemented
	public function Edit($date_from,$date_to) {

		/* CHECKS */
		if(!ValidDate($date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(!ValidDate($date_to)) { $this->Errors("Invalid date to!"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_period_master
					SET date_from = '".$date_from."',
					date_to = '".$date_to."'
					WHERE StatusID = ".$this->StatusID."
					";
		$result=$db->query($sql);
		if ($db->AffectedRows() > 0) {
				LogHistory("$date_from to $date_to edited");
				return True;
		}
		else {
			return False;
		}
	}

	public function Delete() {
		
		if (!ISSET($this->statusid) || !ISSET($this->workspaceid) || !ISSET($this->userid)) {
			$this->debug("Invalid data types");
			$this->Errors("Invalid data");
			return false;
		}

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		
		$sql="CALL sp_leave_status_master_del(
					'".$this->statusid."',
					'".$this->workspaceid."',
					'".$this->userid."'
					)";
		$this->debug($sql);
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
				//LogHistory("$date_from to $date_to added");
				$this->Errors(MessageCatalogue(100));
				return True;
		}
		else {
			$this->Errors(MessageCatalogue(101));
			return False;
		}		
	}

	public function ChangeActiveStatus() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {
			if ($this->GetInfo("active") == "y") {
				$active = "n";
			}
			else {
				$active = "y";
			}
			$sql="UPDATE ".$GLOBALS['database_prefix']."leave_period_master
						SET active = '$active'
						WHERE StatusID = ".$this->StatusID."
						";
			//echo $sql;
			$result=$db->query($sql);
			if ($db->AffectedRows($result) > 0) {
				//LogHistory($this->period_name." deleted");
				// UPDATE ALL USERS TO NULL WHO HAVE THIS AS A SETTING
				$sql = "UPDATE ".$GLOBALS['database_prefix']."leave_user_settings
								SET StatusID = NULL
								WHERE StatusID = '".$this->StatusID."'
								";
				$result=$db->query($sql);
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

	function Errors($err) {
		$this->errors.=$err."\n";
	}

	function ShowErrors() {
		return $this->errors;
	}
	private function debug($msg) {
		if ($this->debug) {
			echo $msg."<br />\n";
		}
	}
}
?>