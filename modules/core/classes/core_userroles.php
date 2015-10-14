<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class UserRoles {

	function __construct() {
		$this->errors = "";		
		$this->debug = false;
		
		$this->role_priv_array = array();
		//$this->GenUserRolePriv();		
		
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
		$this->$v = trim($val);
	}
	public function Info() {
		if (!ISSET($this->userid)) {
			$this->debug("Failed to provide correct to info");
			$this->Errors("Invalid data 0");
			return false;
		}
		$db=$GLOBALS['db'];
		$sql="CALL sp_core_userrole_browse('".$this->userid."')";
		$this->debug($sql);
		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$arr_cols=$db->GetColumns($result);
				for ($i=1;$i<count($arr_cols);$i++) {
					$col_name=$arr_cols[$i];
					$this->$col_name=$row[$col_name];
				}
			}
			//return true;
		}
		else {
			return False;
		}
		
		if (ISSET($this->workspaceid)) {
			
			$sql="CALL sp_core_workspace_userrole_browse('".$this->userid."','".$this->workspaceid."')";
			$this->debug($sql);
			echo $sql;
			$result = $db->Query($sql);
			if ($db->NumRows($result) > 0) {
				while($row = $db->FetchArray($result)) {
					/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
					$arr_cols=$db->GetColumns($result);
					for ($i=1;$i<count($arr_cols);$i++) {
						$col_name=$arr_cols[$i];
						$this->$col_name=$row[$col_name];
					}
				}
				//return true;
			}
		}
	}	
	
	public function Add() {
		$this->debug("Adding now");
		if (!ISSET($this->userid) || !IS_NUMERIC($this->userid) || !ISSET($this->roleid) || !IS_NUMERIC($this->roleid)) {    			
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_userrole_add(".$this->userid.",".$this->roleid.")";				
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Added User To Role");
			$this->Errors("User add to role succeeded");
			return true;			
		}
		$this->Errors("User add to role failed");
		$this->Debug("Failed to add User To Role");
		return false;
	}
	private function GenUserRolePriv() {
		$sql = "call sp_userrole_priv(".$this->userid.")";		
		$arr = array();
		$this->debug($sql);		
		$result = $GLOBALS['db']->Query($sql);						
		if ($result) {			
			//$this->debug("ok");
			while ($row = $GLOBALS['db']->FetchArray($result)) {				
				$arr[] = $row['RolePriv'];
			}
		}		
		$this->role_priv_array = $arr;
	}
	public function CheckUserRolePriv($priv) {
		
		if (ISSET($priv)) {
			if (in_array($priv,$this->role_priv_array)) {
				$this->debug("Found priv for user");
				return true;
			}
			/*
			foreach ($this->role_priv_array as $key=>$val) {
				if ($priv == $val) {
					return true;
				}
				else {
					echo "$priv is not equal to $val <br />";
				}
			}
			*/
		}
		$this->debug("Priv \"$priv\" does not exist for user");		
		return false;
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