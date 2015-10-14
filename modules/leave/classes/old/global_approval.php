<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class GlobalApproval {

	public function SetParameters($user_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* DATA CHECKING */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user!"); return False; }

		/* SET THE VARIABLE IN GLOBAL SCOPE */
		$this->user_id=$user_id;
		$this->workspace_id=$GLOBALS['ui']->WorkspaceID();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;
	}

	public function Add() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check && !$this->GlobalApproverExists()) {

			$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_global_approval
						(workspace_id, user_id)
						VALUES (
						".$this->workspace_id.",
						".$this->user_id."
						)";
			//echo $sql;
			$result=$db->query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function Delete() {

		$db=$GLOBALS['db'];

		if ($this->parameter_check) {

			$sql="DELETE FROM ".$GLOBALS['database_prefix']."leave_global_approval
						WHERE workspace_id = ".$this->workspace_id."
						AND user_id = ".$this->user_id."
						";
			$result=$db->query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function GlobalApproverExists() {

		$db=$GLOBALS['db'];
		//echo "OK-OK";
		if ($this->parameter_check) {
			//echo "Ok entering cat user priv<br>";
			$sql="SELECT 'x'
						FROM ".$GLOBALS['database_prefix']."leave_global_approval
						WHERE workspace_id = ".$this->workspace_id."
						AND user_id = ".$this->user_id."
						";
			$result=$db->query($sql);
			if ($db->NumRows($result) > 0) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			$this->Errors("Sorry, document category user parameter check failed<br>");
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