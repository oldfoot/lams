<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class UserSettings {

	function __construct() {
		$this->debug  = false;
		$this->errors="";		
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

	public function Info() {		
		if (!ISSET($this->userid) || !IS_NUMERIC($this->userid)) { $this->Errors("Invalid user id"); return False; }
		$this->db=$GLOBALS['db'];
		
		$sql="CALL sp_leave_user_settings('".$this->userid."')";		
		$this->debug($sql);
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
		/* NO RECORD IN THE TABLE */
		else {
			$sql="REPLACE INTO ".$GLOBALS['database_prefix']."leave_user_settings (UserID) VALUES ('".$_SESSION['userid']."')";
			$result = $this->db->Query($sql);
		}
		return true;
	}	

	public function Edit() {

		/* CHECKS */
		if(!IS_NUMERIC($this->periodid) || !IS_NUMERIC($this->userid)) { $this->Errors("Invalid period!"); return False; }
		//if (!RowExists("leave_period_master","PeriodID",$this->periodid,"AND workspace_id ='".$_SESSION['workspaceid']."'")) { $this->Errors("Invalid period [1]."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="CALL sp_leave_user_settings_edit( ".$this->userid.",".$this->periodid.")";
		$this->debug($sql);
		$result=$db->query($sql);
		if ($db->AffectedRows($result) > 0) {
				//LogHistory($period_name." added");
				return True;
		}
		else {
			return False;
		}
	}

	private function Errors($err) {
		$this->errors.=$err."\n";
	}

	public function ShowErrors() {
		return $this->errors;
	}
	private function debug($msg) {
		if ($this->debug) {
			echo $msg."<br />\n";
		}
	}
}
?>