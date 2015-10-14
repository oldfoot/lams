<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class Workflow {

	function __construct() {

	}
	public function SetParameters($contact_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($contact_id)) { $this->Errors("Invalid account"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->contact_id=$contact_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($contact_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function SetVar($v_name,$v_val) {
		$this->$v_name=$v_val;
	}

	public function Add() {


		/* CHECKS */
		if (EMPTY($this->perform_action)) { $this->Errors("Invalid process"); return False; }
		/*
		if ($this->perform_action != "Start" || $this->perform_action != "HOD Approval" ||
				$this->perform_action != "Global Approval" || $this->perform_action != "End") { $this->Errors("Invalid action"); return False; }
		*/
		//if (RowExists("leave_workflow","workspace_id",$GLOBALS['workspace_id'],"AND perform_action='".$this->perform_action."'")) { $this->Errors("You can't insert the same process."); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_workflow
					(perform_action,workflow_order,workspace_id)
					VALUES (
					'".$this->perform_action."',
					".$this->GetNextOrder().",
					".$GLOBALS['workspace_id']."
					)";
		//echo $sql."<br>";
		$result=$db->Query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	private function GetNextOrder() {

		$v_getnextorder="0";

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];
		/* QUERY */
		$sql="SELECT MAX(workflow_order) + 1 AS getnextorder
					FROM ".$GLOBALS['database_prefix']."leave_workflow
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$v_getnextorder=$row['getnextorder'];
				if ($row['getnextorder']==Null) { $v_getnextorder="1"; }
			}
		}
		return $v_getnextorder;
	}

	public function Delete() {
		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="DELETE FROM ".$GLOBALS['database_prefix']."leave_workflow
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
	}

	private function Errors($err) {
		$this->errors.=$err."<br>";
	}

	public function ShowErrors() {
		return $this->errors;
	}
}
?>