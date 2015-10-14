<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class WorkspaceMaster {

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
		if (!ISSET($this->workspaceid)) {
			$this->debug("Failed to provide correct to info");
			$this->Errors("Invalid data 0");
			return false;
		}
		$db=$GLOBALS['db'];
		$sql="CALL sp_core_workspace_browse('".$this->workspaceid."')";
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
			return true;
		}
		else {
			return False;
		}
	}	
	
	public function Add() {
		$this->debug("Adding now");
		if (!ISSET($this->parentid) || !ISSET($this->workspacename) || !ISSET($this->logo) || !IS_NUMERIC($this->userid)) {    			
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_workspace_add('".$this->parentid."','".$this->workspacename."','".$this->logo."',".$this->userid.")";
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Added Workspace");
			$this->Errors(MessageCatalogue(86));
			return true;			
		}
		$this->Errors(MessageCatalogue(87));
		$this->Debug("Failed Adding Workspace");
		return false;
	}
	
	public function Edit() {
		$this->debug("Editing now");
		if (!ISSET($this->workspaceid) || !ISSET($this->workspacename) || !IS_NUMERIC($this->userid)) {    			
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_workspace_edit('".$this->workspaceid."','".$this->workspacename."',".$this->userid.")";
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Edited Workspace");
			$this->Errors(MessageCatalogue(92));
			return true;			
		}
		$this->Errors(MessageCatalogue(93));
		$this->Debug("Failed editing Workspace");
		return false;
	}
	
	public function Delete() {
		$this->debug("Deleting now");
		if (!ISSET($this->workspaceid) || !IS_NUMERIC($this->userid)) {    			
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_workspace_delete('".$this->workspaceid."',".$this->userid.")";
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Deleted Workspace");
			$this->Errors(MessageCatalogue(90));
			return true;			
		}
		$this->Errors(MessageCatalogue(91));
		$this->Debug("Failed Removing Workspace");
		return false;
	}
	
	public function AddModule() {
		$this->debug("Adding module now");
		if (!ISSET($this->workspaceid) || !ISSET($this->moduleid) || !IS_NUMERIC($this->userid)) {  
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_workspace_module_add('".$this->workspaceid."','".$this->moduleid."','".$this->userid."')";
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Added Module to Workspace");
			$this->Errors(MessageCatalogue(94));
			return true;			
		}
		$this->Errors(MessageCatalogue(95));
		$this->Debug("Failed Adding Module to Workspace");
		return false;
	}
	
	public function DelModule() {
		$this->debug("Deleting module now");
		if (!ISSET($this->workspaceid) || !ISSET($this->moduleid) || !IS_NUMERIC($this->userid)) {  
			$this->debug("Invalid data types or params not set");
			$this->Errors("Invalid Parameters on add");
			return false;
		}
								
		$sql = "call sp_core_workspace_module_del('".$this->workspaceid."','".$this->moduleid."','".$this->userid."')";
		$this->Debug($sql);
		$result = $GLOBALS['db']->Query($sql);
		if ($result && $GLOBALS['db']->AffectedRows($result) > 0) {
			$this->debug("Deleting Module to Workspace");
			$this->Errors(MessageCatalogue(96));
			return true;			
		}
		$this->Errors(MessageCatalogue(97));
		$this->Debug("Failed Deleting Module to Workspace");
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