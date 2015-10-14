<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";
require_once $GLOBALS['dr']."include/functions/date_time/valid_date.php";
require_once $GLOBALS['dr']."include/functions/date_time/mysql_to_seconds.php";
require_once $GLOBALS['dr']."include/functions/date_time/timestamp_to_mysql.php";

require_once $GLOBALS['dr']."modules/hrms/functions/exists/public_holiday_date_exists.php";

require_once $GLOBALS['dr']."modules/leave/classes/application_workflow.php";
require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";
require_once $GLOBALS['dr']."modules/leave/classes/category_id.php";
require_once $GLOBALS['dr']."modules/leave/classes/upload_document.php";

class ApplicationID {

	function __construct() {
		$this->debug=false;
		global $leave_total_sundays_value;
		global $leave_total_saturdays_value;
		$this->count_days_debug = array("");
	}
	public function SetParameters($application_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($application_id)) { $this->Errors("Invalid application"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->application_id=$application_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info() {

		$this->db=$GLOBALS['db'];

		$sql="SELECT la.*, lcm.*, um.full_name
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."leave_category_master lcm,
					".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."leave_status_master lsm
					WHERE la.application_id = '".$this->application_id."'
					AND la.category_id = lcm.category_id
					AND la.user_id = um.user_id
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
		else {
			return "";
		}
	}

	public function Add() {

		/* CHECKS */
		$this->Debug("Starting Checks for empty fields");
		if(EMPTY($this->date_from) || !ValidDate($this->date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(EMPTY($this->date_to) || !ValidDate($this->date_to)) { $this->Errors("Invalid date to!"); return False; }
		if (EMPTY($this->category_id)) { $this->Errors("Choose a category please!"); return False; }
		$this->Debug("Ended Checks for empty fields");
		$this->Debug("Checking for end less than start");
		if (MySQLDateToSeconds($this->date_to) < MySQLDateToSeconds($this->date_from)) { $this->Errors("Please choose a to-date greater than from date!"); return False; }
		if ($this->date_from_half_day != "y") { $this->date_from_half_day = "n"; }
		if ($this->date_to_half_day != "y") { $this->date_to_half_day = "n"; }
		$this->Debug("Checking that category exists");
		if (!RowExists("leave_category_master","category_id",$this->category_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid category [1]"); return False; }
		/* CHECK THAT DATE SELECTED IS IN PERIOD */
		if (!$this->CheckAppPeriod()) { $this->Errors("Invalid date. The date must be in the current period!"); return False; }
		if (!$this->CheckAppNoRecord()) { $this->Errors("Sorry, your application conflicts with another!"); return False; }

		// AUTOMATIC APPROVAL FOR THE CATEGORY
		$obj_ci=new CategoryID;
		$obj_ci->SetParameters($this->category_id);
		if ($obj_ci->GetInfo("auto_approve") == "y") {
			$this->status_id=GetColumnValue("status_id","leave_status_master","is_approved","y","AND workspace_id=".$GLOBALS['workspace_id']);
		}
		else {
			$this->status_id=GetColumnValue("status_id","leave_status_master","is_new_default","y","AND workspace_id=".$GLOBALS['workspace_id']);
		}
		if ($this->status_id < 1) { $this->Errors("The system has not been setup yet. Status values must be set!"); return False; }

		$this->total_days=$this->CountDays();
		$this->Debug("Total days:".$this->total_days);

		/* USER HAS SUFFICIENT LEAVE IN THE CATEGORY */
		if (!$this->BalanceIsOkay()) { $this->Errors("Insifficient leave in this category!"); return False; }


		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."leave_applications
					(user_id,date_from,date_to,date_from_half_day,date_from_half_day_am_pm,date_to_half_day,date_to_half_day_am_pm,
					total_days,category_id,period_id,remarks,status_id,date_application,workspace_id,workflow_order)
					VALUES (
					".$_SESSION['user_id'].",
					'".$this->date_from."',
					'".$this->date_to."',
					'".$this->date_from_half_day."',
					'".$this->date_from_half_day_am_pm."',
					'".$this->date_to_half_day."',
					'".$this->date_to_half_day_am_pm."',
					".$this->total_days.",
					'".$this->category_id."',
					'".$GLOBALS['obj_us']->GetInfo("period_id")."',
					'".$this->remarks."',
					'".$this->status_id."',
					now(),
					".$GLOBALS['workspace_id'].",
					'2'
					)";

		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {
			$last_insert_id=$db->LastInsertID();

			/* HANDLE ANY ATTACHMENTS */
			//echo "ok";
			//print_r($_FILES);
			if (ISSET($_FILES['attachment']['name']) && !EMPTY($_FILES['attachment']['name'])) {
				$file = basename($_FILES['attachment']['name']);

				$filename=EscapeData($_FILES['attachment']['name']);
				$filetype=EscapeData($_FILES['attachment']['type']);
				$filesize=EscapeData($_FILES['attachment']['size']);

				/* READ THE FILE INTO A BINARY VARIABLE */
				//echo $_FILES['attachment']['tmp_name'];
				$handle = fopen($_FILES['attachment']['tmp_name'],"rb");
				$attachment=fread($handle, filesize ($_FILES['attachment']['tmp_name']));

				/* OBJECT TO UPLOAD */
				$ud = new UploadDocument;

				$result_upload=$ud->SetParameters($filename,$filetype,$filesize,$last_insert_id,$attachment);
				if (!$result_upload) {
				 	$result_upload=False;
				}
				else {
				 	//echo "ok";
					$result_upload=$ud->UploadFile();
				}
			}

			// IF THIS IS A PLANNING APPLICATION
			if ($obj_ci->GetInfo("planning") == "y") {
				echo "planning";
				return True;
			}

			$this->SetParameters($last_insert_id);
			$this->DoWorkflow();

			$this->AddLeaveApplicationAudit();

			LogHistory("Application ID: ".$this->application_id." created");
			return True;
		}
		else {
			return False;
		}
	}

	private function AddLeaveApplicationAudit() {
		//$pieces = split(";",$this->count_days_debug);
		$db=$GLOBALS['db'];
		foreach ($this->count_days_debug as $key => $value) {
			$sql = "INSERT INTO leave_applications_audit
							(application_id, date_leave, description)
							VALUES (
							'".$this->application_id."',
							'".$key."',
							'".$value."'
							)";
			//echo $sql."<br />";
			$db->Query($sql);
		}
	}

	public function Edit() {

		/* CHECKS */
		if (!$this->parameter_check) { $this->Errors("Invalid parameter check"); return False; }
		$this->Debug("Starting Checks for empty fields");
		if(EMPTY($this->date_from) || !ValidDate($this->date_from)) { $this->Errors("Invalid date from!"); return False; }
		if(EMPTY($this->date_to) || !ValidDate($this->date_to)) { $this->Errors("Invalid date to!"); return False; }
		if (EMPTY($this->category_id)) { $this->Errors("Choose a category please!"); return False; }
		$this->Debug("Ended Checks for empty fields");
		$this->Debug("Checking for end less than start");
		if (MySQLDateToSeconds($this->date_to) < MySQLDateToSeconds($this->date_from)) { $this->Errors("Please choose a to-date greater than from date!"); return False; }
		if ($this->date_from_half_day != "y") { $this->date_from_half_day = "n"; }
		if ($this->date_to_half_day != "y") { $this->date_to_half_day = "n"; }
		$this->Debug("Checking that category exists");
		if (!RowExists("leave_category_master","category_id",$this->category_id,"AND workspace_id=".$GLOBALS['workspace_id'])) { $this->Errors("Invalid category [1]"); return False; }
		/* CHECK THAT DATE SELECTED IS IN PERIOD */
		if (!$this->CheckAppPeriod()) { $this->Errors("Invalid date. The date must be in the current period!"); return False; }
		if ($this->CheckAppNoRecord()) { $this->Errors("Sorry, your application ID is invalid!"); return False; }

		// the status ID this application will go to dont need this for now
		//$this->status_id=GetColumnValue("status_id","leave_status_master","is_new_default","y","AND workspace_id=".$GLOBALS['workspace_id']);


		$this->Debug("Status id of current case: ".$this->GetInfo("status_id"));
		$this->allow_edit=GetColumnValue("allow_edit","leave_status_master","status_id",$this->GetInfo("status_id"),"");
		$this->Debug("Allow edit: ".$this->allow_edit);
		if ($this->allow_edit != "y") { $this->Errors("Sorry, this case can no longer be edited!"); return False; }

		$this->total_days=$this->CountDays();
		$this->Debug("Total days:".$this->total_days);

		/* USER HAS SUFFICIENT LEAVE IN THE CATEGORY */
		if (!$this->BalanceIsOkay()) { $this->Errors("Insifficient leave in this category!"); return False; }


		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_applications
					SET date_from = '".$this->date_from."',
					date_to = '".$this->date_to."',
					date_from_half_day = '".$this->date_from_half_day."',
					date_from_half_day_am_pm = '".$this->date_from_half_day_am_pm."',
					date_to_half_day = '".$this->date_to_half_day."',
					date_to_half_day_am_pm = '".$this->date_to_half_day_am_pm."',
					total_days = ".$this->total_days.",
					category_id = '".$this->category_id."',
					period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."',
					remarks = '".$this->remarks."',
					workspace_id = ".$GLOBALS['workspace_id']."
					WHERE application_id = ".$this->application_id."
					AND user_id = ".$_SESSION['user_id']."";

		//echo $sql;
		$result=$db->query($sql);
		//$result=True; // enable for debugging
		if ($result) {



			/* HANDLE ANY ATTACHMENTS */
			//echo "ok";
			//print_r($_FILES);
			if (ISSET($_FILES['attachment']['name']) && !EMPTY($_FILES['attachment']['name'])) {
				$file = basename($_FILES['attachment']['name']);

				$filename=EscapeData($_FILES['attachment']['name']);
				$filetype=EscapeData($_FILES['attachment']['type']);
				$filesize=EscapeData($_FILES['attachment']['size']);

				/* READ THE FILE INTO A BINARY VARIABLE */
				//echo $_FILES['attachment']['tmp_name'];
				$handle = fopen($_FILES['attachment']['tmp_name'],"rb");
				$attachment=fread($handle, filesize ($_FILES['attachment']['tmp_name']));

				/* OBJECT TO UPLOAD */
				$ud = new UploadDocument;

				$result_upload=$ud->SetParameters($filename,$filetype,$filesize,$this->application_id,$attachment);
				if (!$result_upload) {
				 	$result_upload=False;
				}
				else {
				 	//echo "ok";
					$result_upload=$ud->UploadFile();
				}
			}

			$this->SetParameters($this->application_id);

			$this->DoWorkflow();

			LogHistory("Application ID: ".$this->application_id." edited");

			return True;
		}
		else {
			return False;
		}
	}

	public function CountDays() {
		//$v_days=(MySQLDateToSeconds($this->date_to)-MySQLDateToSeconds($this->date_from))+86400;
		//$v_days=$v_days/86400;

		$this->Debug($this->date_from);
		$audit_date_from = $this->date_from;
		$this->Debug($this->date_to);
		$v_end_seconds=MySQLDateToSeconds($this->date_to);
		$v_start_seconds=MySQLDateToSeconds($this->date_from);

		$v_total_days=0;

		/* START WITH A HALF DAY IF SELECTED */
		if ($this->date_from_half_day=="y" && !PublicHolidayDateExists($this->date_from) && date("l",$v_start_seconds) != "Saturday" && date("l",$v_start_seconds) != "Sunday") {
			$this->Debug("start date is neither saturday nor sunday, but it is a half day and not a public holiday, starting with 0.5 days");
			$v_total_days=0.5;
			$this->count_days_debug[$audit_date_from] = "Start day !half day, !public holiday, !saturday, !sunday, add 0.5";
		}
		else {
			if (PublicHolidayDateExists($this->date_from)) {
				$this->Debug("Start date is a full day, but a public holiday, skip total");
				$this->count_days_debug[$this->date_from] = "Start day is a public holiday, add 0";
			}
			elseif (date("l",$v_start_seconds) == "Saturday") {
				$this->Debug("Start date is a saturday");
				//echo "ok";
				$v_total_days+=$GLOBALS['leave_total_saturdays_value'];
				$this->Debug($GLOBALS['leave_total_saturdays_value']);
				//echo $v_total_days;
				$this->count_days_debug[$this->date_from] = "Start day is a saturday, add ".$GLOBALS['leave_total_saturdays_value'];
			}
			elseif (date("l",$v_start_seconds) == "Sunday") {
				$this->Debug("Start date is a sunday, adding:".$GLOBALS['leave_total_sundays_value']."days");
				$v_total_days+=$GLOBALS['leave_total_sundays_value'];
				$this->count_days_debug[$this->date_from] = "Start day is a sunday, add ".$GLOBALS['leave_total_sundays_value'];
			}
			else {
				$v_total_days=1;
				$this->Debug("Starting with full day");
				$this->count_days_debug[$this->date_from] = "Start day is a week day, add 1";
			}
		}


		for ($i=($v_start_seconds+86400);$i<=($v_end_seconds-86400);$i) {
			/* VARIABLES WE USE */
			$v_date_mysql=TimestampToMySQL($i);
			$this->Debug("Middle date: $v_date_mysql");

			$this->Debug("now: ".time());
			$this->Debug("i:".$i);
			$this->Debug(date("l",$i));

			if (PublicHolidayDateExists($v_date_mysql)) {
				$this->Debug("Middle date is a public holiday, skip total");
				$i+=86400;
				$this->count_days_debug[$v_date_mysql] = "Public holiday, add 0";
			}
			else {

				if (date("l",$i) == "Sunday") {
					$v_total_days+=$GLOBALS['leave_total_sundays_value'];
					$this->count_days_debug[$v_date_mysql] = "Sunday, add ".$GLOBALS['leave_total_sundays_value'];
				}
				if (date("l",$i) == "Saturday") {
					$v_total_days+=$GLOBALS['leave_total_saturdays_value'];
					$this->count_days_debug[$v_date_mysql] = "Saturday, add ".$GLOBALS['leave_total_saturdays_value'];
				}
				if (date("l",$i) != "Saturday" && date("l",$i) != "Sunday") {
					$this->Debug("Adding a day");
					$v_total_days+=1;
					$this->count_days_debug[$v_date_mysql] = "Week day, add 1";
				}
			}

			/* SEND $i TO THE NEXT DAY */
			$i+=86400;
		}

		/* START WITH A HALF DAY IF SELECTED */
		if ($this->date_from == $this->date_to) {
			// do not add anything if it's the same day
		}
		elseif ($this->date_to_half_day=="y" && !PublicHolidayDateExists($this->date_to) && date("l",$v_end_seconds) != "Saturday" && date("l",$v_end_seconds) != "Sunday") {
			$this->Debug("end date is neither saturday nor sunday, but it is a half day and not a public holiday, adding 0.5 days");
			$v_total_days+=0.5;
			$this->count_days_debug[$this->date_to] = "End day is a half day, !public holiday, !Saturday, !Sunday add 0.5";
		}
		else {
			if (PublicHolidayDateExists($this->date_to)) {
				$this->Debug("End date is a full day, but a public holiday, skip total");
				$this->count_days_debug[$this->date_to] = "End day is a public holiday, add 0";
			}
			elseif (date("l",$v_end_seconds) == "Saturday") {
				$this->Debug("End date is a saturday");
				$v_total_days+=$GLOBALS['leave_total_saturdays_value'];
				$this->count_days_debug[$this->date_to] = "End day is a Saturday, add ".$GLOBALS['leave_total_saturdays_value'];
			}
			elseif (date("l",$v_end_seconds) == "Sunday") {
				$this->Debug("End date is a sunday, adding:".$GLOBALS['leave_total_sundays_value']."days");
				$v_total_days+=$GLOBALS['leave_total_sundays_value'];
				$this->count_days_debug[$this->date_to] = "End day is a Sunday, add ".$GLOBALS['leave_total_sundays_value'];
			}
			else {
				/* IF THE DATES ARE THE SAME THEN DO NOT ADD ANOTHER */
				if ($this->date_from != $this->date_to) {
					$this->Debug("Adding 1 day at the end date");
					$v_total_days+=1;
					$this->count_days_debug[$this->date_to] = "End day is a week day, add 1";
				}
			}
		}

		return $v_total_days;
	}

	public function GetFormPostedValues() {
		$all_form_fields=array("application_id","date_from","date_to","date_from_half_day","date_from_half_day_am_pm",
													"date_to_half_day","date_to_half_day_am_pm","category_id","period_id","remarks","application_id");

		for ($i=0;$i<count($all_form_fields);$i++) {

			//echo $_POST['application_id'];
			if (ISSET($_POST[$all_form_fields[$i]]) && !EMPTY($_POST[$all_form_fields[$i]])) {
				$this->SetVariable($all_form_fields[$i],$_POST[$all_form_fields[$i]]);
			}
			else {
				//echo "<br>".$all_form_fields[$i]."<br>";
				$this->$all_form_fields[$i]="";
			}
		}
	}

	public function SetVariable($variable,$variable_val) {
		$this->$variable=EscapeData($variable_val);
	}

	private function CheckAppPeriod() {
		if (MySQLDateToSeconds($this->date_from) > MySQLDateToSeconds($GLOBALS['obj_us']->GetInfo("date_from")) && MySQLDateToSeconds($this->date_from) < MySQLDateToSeconds($GLOBALS['obj_us']->GetInfo("date_to"))) { /* THE START DATE IS OKAY */
			if (MySQLDateToSeconds($this->date_to) > MySQLDateToSeconds($GLOBALS['obj_us']->GetInfo("date_from")) && MySQLDateToSeconds($this->date_to) < MySQLDateToSeconds($GLOBALS['obj_us']->GetInfo("date_to"))) { /* THE END DATE IS OKAY */
				return True;
			}
		}
		return False;
	}

	private function CheckAppNoRecord() {

		$db=$GLOBALS['db'];

		/* CHECK OUTER BOUNDARIES i.e DATE SMALLER AND GREATER */
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE date_from > '".$this->date_from."'
					AND date_to < '".$this->date_to."'
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$this->Debug("Outer boundary date check...FAIL");
			return False;
		}
		else {
			$this->Debug("Outer boundary date check passed...OK");
		}

		/* CHECK EXACT MATCHES */
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE (date_from = '".$this->date_from."'	OR date_to = '".$this->date_to."')
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$this->Debug("Exact date match...FAIL");
			return False;

		}
		else {
			$this->Debug("Exact date match...OK");
		}

		/* CHECK LEFT MATCH */
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE date_from >= '".$this->date_from."'
					AND date_from <= '".$this->date_to."'
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$this->Debug("Left date match...FAIL");
			return False;
		}
		else {
			$this->Debug("Left date match...OK");
		}

		/* CHECK RIGHT MATCH */
		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."leave_applications
					WHERE date_to >= '".$this->date_from."'
					AND date_to <= '".$this->date_to."'
					AND user_id = ".$_SESSION['user_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$this->Debug("Right date match...FAIL");
			return False;
		}
		else {
			$this->Debug("Right date match...OK");
		}

		/* SUCCESS */
		return True;

	}

	public function Approve($verdict) {
		if (!$this->parameter_check) { $this->Errors("Invalid parameter check"); return False; }
		if ($verdict == "y") { $verdict = "y"; } else { $verdict = "n"; }
		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."leave_application_approval
					SET approved = '$verdict', date_updated = now()
					WHERE application_id = '".$this->application_id."'
					AND user_id = ".$_SESSION['user_id']."
					";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$result=$this->DoWorkFlow();
			if (!$result) { // no workflow to do - so check if this application is all approved and update the status
				//echo "No remaining workflow<br />";
				$this->CheckRemainingApprovals();
			}
			else {
				//echo "still something do do<br />";
			}
			return True;
		}
		else {
			return False;
		}
	}

	public function CheckRemainingApprovals() {


		$db=$GLOBALS['db'];
		$sql="SELECT count(*) as total
					FROM ".$GLOBALS['database_prefix']."leave_application_approval
					WHERE application_id = '".$this->application_id."'
					AND approved IS NULL
					";
		//echo $sql."<br />";
                $result = $db->Query($sql);
		//echo $sql."<br />";
		while ($row = $db->FetchArray($result)) {
			if ($row['total'] == 0) {
				$status_id=GetColumnValue("status_id","leave_status_master","is_approved","y","AND workspace_id=".$GLOBALS['workspace_id']);
				$sql = "UPDATE ".$GLOBALS['database_prefix']."leave_applications
								SET status_id = $status_id
								WHERE application_id = ".$this->application_id."
								";
				//echo $sql;
				$db->Query($sql);
			}
		}
	}

	/* THIS METHOD CALCULATES WHETHER THE USER HAS SUFFICIENT LEAVE - IF THE CATEGORY REQUIRES IT */
	public function BalanceIsOkay() {
		$category_id=$this->category_id;

		$obj_ci=new CategoryID;
		$obj_ci->SetParameters($category_id);
		if ($obj_ci->GetInfo("allow_negative_balance") == "n") {
			$obj_ub=new UserBalances;
			$obj_ub->SetParameters($_SESSION['user_id']);
			$leave_taken=$obj_ub->LeaveTaken($category_id);
			$leave_total=$obj_ub->LeaveAvailable($category_id);
			//echo "Leave taken: ".$leave_taken."<br>";
			//echo "leave_total: ".$leave_total."<br>";
			if (($leave_total-$leave_taken) > $this->total_days) {
				return True;
			}
			else {
				return False;
			}
		}
		else {
			return True;
		}

	}

	public function DoWorkflow() {
		$db=$GLOBALS['db'];
		//echo "Doing workflow<br>";
		/* WORKFLOW WORKER CLASS */
		$obj_app_wf=new ApplicationWorkflow;
		$obj_app_wf->SetParameters($this->application_id);

		$sql="SELECT lw.perform_action, lw.workflow_order, lwd.do_next_step, lwd.is_final_step
					FROM ".$GLOBALS['database_prefix']."leave_applications la,".$GLOBALS['database_prefix']."leave_workflow lw,
					".$GLOBALS['database_prefix']."leave_workflow_detail lwd
					WHERE la.application_id = '".$this->application_id."'
					AND la.workflow_order = lw.workflow_order
					AND lw.workspace_id = ".$GLOBALS['workspace_id']."
					AND lw.perform_action = lwd.perform_action
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* FOR EACH APPLICATION LETS SEE WHAT WE HAVE TO DO IN THE WORKER CLASS */
				if ($row['perform_action']=="HOD Approval") {
					$this->Debug("Inserting HOD Approval<br>");
					$obj_app_wf->HodApproval();
				}
				elseif ($row['perform_action']=="Email HOD") {
					$this->Debug("Inserting HOD Email<br>");
					$obj_app_wf->EmailHod();
				}
				elseif ($row['perform_action']=="Email Global Approver") {
					$this->Debug("Inserting Global Email<br>");
					$obj_app_wf->EmailGlobal();
				}
				elseif ($row['perform_action']=="Global Approval") {
					$this->Debug("Inserting Global Approval<br>");
					$obj_app_wf->GlobalApproval();
				}
				else {
					$this->Debug("No workflow detected");
					return False;
				}

				/* UPDATE THE APPLICATION TO THE STAGE OF THE WORKFLOW */
				$sql1="UPDATE ".$GLOBALS['database_prefix']."leave_applications la
							SET workflow_order = workflow_order +1
							WHERE application_id = '".$this->application_id."'
							";
				$result_update_count=$db->Query($sql1);

				/* CHECK IF WE NEED TO DO THIS AGAIN */
				if ($row['do_next_step']=="y") {
					$this->DoWorkFlow();
				}
			}
		}
		else {
			$this->Debug("No workflow detected [2]");
		}
	}

	public function Debug($desc) {
		if ($this->debug==True) {
			echo $desc."<br>\n";
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