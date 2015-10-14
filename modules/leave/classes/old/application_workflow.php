<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."classes/smtp/smtp.php";
require_once $GLOBALS['dr']."modules/hrms/classes/user_reporting.php";
require_once $GLOBALS['dr']."modules/hrms/classes/user_id.php";

class ApplicationWorkflow {

	function __construct() {

	}

	public function SetParameters($application_id) {
		$this->application_id=$application_id;
	}

	/* ADD APPROVAL FOR THE HOD */
	public function HodApproval() {

		$user_id=$GLOBALS['ui']->SuperiorID();

		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_application_approval
					(application_id,user_id)
					VALUES (
					'".$this->application_id."',
					'".$user_id."'
					)";
		$result = $db->Query($sql);

	}

	public function EmailHOD() {
		/* FIND THE CURRENT USER'S SUPERIOR */
		$obj_ur=new UserReporting;
		$obj_ur->SetParameters($_SESSION['user_id']);
		$superior_user_id=$obj_ur->GetInfo("superior_user_id");
		/* GET THE SUPERIOR EMAIL ADDRESS */
		$obj_uis=new UserID;
		$obj_uis->SetParameters($superior_user_id);
		$superior_email_address=$obj_uis->GetInfo("login"); /* THIS IS THE EMAIL ADDRESS */
		$superior_full_name=$obj_uis->GetInfo("full_name");

		//echo "Superior email address:".$email_address."<br>";
		$from=$GLOBALS['ui']->GetInfo("login");
		$to=$superior_email_address;
		$subject="[LAMS] New leave application to approve";
		$body="Dear ".$superior_full_name.", There's a new leave application to approve from ".$GLOBALS['ui']->GetInfo("full_name").".\n\n Please login to the leave application system to approve it.";
		SendEmail($from,$to,$subject,$body);
	}

	public function EmailGlobal() {
		/* FIRST GET ALL THE GLOBAL APPROVERS AS THERE COULD BE MORE THAN 1 */
		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."leave_global_approval
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* GET THE SUPERIOR EMAIL ADDRESS */
				$obj_uis=new UserID;
				$obj_uis->SetParameters($row['user_id']);
				$global_user_email_address=$obj_uis->GetInfo("login"); /* THIS IS THE EMAIL ADDRESS */
				$global_user_full_name=$obj_uis->GetInfo("full_name");
				/* SEND EMAIL */
				$from=$GLOBALS['ui']->GetInfo("login");
				$to=$global_user_email_address;
				$subject="[LAMS] New leave application to approve";
				$body="Dear ".$global_user_full_name.", There's a new leave [global] application to approve from ".$GLOBALS['ui']->GetInfo("full_name").".\n\n Please login to the leave application system to approve it.";
				SendEmail($from,$to,$subject,$body);
			}
		}

	}

	/* ADD APPROVAL FOR THE HOD */
	public function GlobalApproval() {

		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."leave_global_approval
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {

				$sql1="INSERT INTO ".$GLOBALS['database_prefix']."leave_application_approval
							(application_id,user_id)
							VALUES (
							'".$this->application_id."',
							'".$row['user_id']."'
							)";
				//echo $sql."<br>";
				$result1 = $db->Query($sql1);
			}
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