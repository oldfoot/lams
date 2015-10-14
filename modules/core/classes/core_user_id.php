<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/core/classes/core_smtp.php";

require_once $GLOBALS['dr']."modules/core/functions/workspace_role_id_exists.php";
require_once $GLOBALS['dr']."modules/core/functions/login_exists.php";
require_once $GLOBALS['dr']."modules/core/functions/title_id_exists.php";
require_once $GLOBALS['dr']."modules/core/functions/role_id_default.php";

class UserID {

	function __construct() {
		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;
	}

	public function SetParameters($user_id) {

		/* CHECKS */
		if (!IS_NUMERIC($user_id)) { $this->Errors("Invalid user id"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->user_id=$user_id;

		/* CALL THE INFORMATION METHOD */
		$this->Info();

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	private function Info() {
		$db=$GLOBALS['db'];
		$sql="SELECT title_id,full_name,login,timezone,country_id,language
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE user_id = '".$this->user_id."'
					";
		//echo $sql."<br>";
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
		}
		else {
			return False;
		}
	}

	public function AddUser($role_id="",$login,$password,$title_id="",$full_name="",$timezone="",$country_id="",$identification_number="",$activated="n",$send_email=True) {

		if (EMPTY($role_id)) { $role_id=RoleIDDefault(); }
		if (EMPTY($login)) { $this->Errors("Login/Email cannot be empty"); return False; }
		if (EMPTY($password)) { $this->Errors("Password cannot be empty"); return False; }
		//if (EMPTY($title_id)) { $this->Errors("Title cannot be empty"); return False; }
		//if (EMPTY($full_name)) { $this->Errors("Name cannot be empty"); return False; }
		//if (!WorkspaceRoleIDExists($role_id)) { $this->Errors("Invalid Role"); return False; }
		if (LoginExists($login)) { $this->Errors("Could not create a login. This username already exists in the system."); return False; }
		//if (RowExists("core_user_master","identification_number","'".$identification_number."'","")) { $this->Errors("Invalid identification number, please enter another!"); return False; }
		//if (EMPTY($country_id)) { $this->Errors("Country cannot be empty"); return False; }

		if (EMPTY($timezone)) { $timezone=0; }

		$activation_code=md5(microtime().$login.$password);


		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_user_master
					(role_id,login,password,title_id,full_name,timezone,country_id,identification_number,activated,activation_code)
					VALUES (
					'".EscapeData($role_id)."',
					'".EscapeData($login)."',
					'".MD5(EscapeData($password))."',
					'".EscapeData($title_id)."',
					'".EscapeData($full_name)."',
					'".EscapeData($timezone)."',
					'".EscapeData($country_id)."',
					'".EscapeData($identification_number)."',
					'".$activated."',
					'".$activation_code."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			$this->user_id=$this->GetUserID(EscapeData($login));

			if ($send_email) {
				/* THE CONTENT OF THE EMAIL */

				$signup_msg = file_get_contents($GLOBALS['dr']."include/templates/signup.php");
				$signup_msg = str_replace("{{activation_code}}",$activation_code,$signup_msg);
				$signup_msg = str_replace("{{webserver}}",$GLOBALS['wb'],$signup_msg);

				//echo $signup_msg;
				$result = SendEmail($GLOBALS['email_recover_password_from'],$login,"Account verification for Genus Project",$signup_msg);
				//echo "Sending Emai: ".$result;

			}

			return True;
		}
		else {
			$this->Errors("User creation failed.");
			return False;
		}
	}

	function EditUser($title_id,$full_name,$email,$password,$password_repeat,$timezone,$country_id,$language) {

		if (!$this->parameter_check) { $this->Errors("Failed to edit user"); return False; }
		if (EMPTY($title_id)) { $this->Errors("Title can't be empty"); return False; }
		if (EMPTY($country_id)) { $country_id="NULL"; }

		if (!EMPTY($password) && !EMPTY($password_repeat)) {
			//$this->ChangePassword($password,$password_repeat);
		}

		$db=$GLOBALS['db'];

		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET title_id = ".$title_id.",
					full_name = '".$full_name."',
					email_primary = '".$email."',
					timezone = '".$timezone."',
					country_id = ".$country_id.",
					language = '".$language."'
					WHERE user_id = ".$this->user_id."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("User account not altered.");
			return True;
			//return False; // THIS IS NOT NECCESSARILY A PROBLEM IF NO DATA CHANGES
		}

	}

	function ChangePassword($password,$password_repeat) {

		$db=$GLOBALS['db'];

		if ($this->parameter_check && $password==$password_repeat) {
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
						SET password = MD5('".$password."')
						WHERE user_id = ".$this->user_id."
						";

			$result = $db->Query($sql);

			if ($db->AffectedRows($result) > 0) {
				return True;
			}
			else {
				$this->Errors("Failed to change password.");
				return False;
			}
		}
		else {
			$this->Errors("Failed to change password. Passwords equal?");
			return False;
		}
	}

	function GetUserID($login) {
		$db=$GLOBALS['db'];

		$sql="SELECT user_id
					FROM ".$GLOBALS['database_prefix']."core_user_master
					WHERE login = '".$login."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['user_id'];
			}
		}
		else {
			return False;
		}
	}

	function AddWorkspaceID($user_id,$workspace_id,$role_id) {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_space_users
					(user_id,workspace_id,role_id)
					VALUES (
					'".$user_id."',
					'".$workspace_id."',
					'".$role_id."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			return False;
		}
		else {
			return True;
		}
	}

	function PasswordRecoveryExists() {
		$db=$GLOBALS['db'];

		$sql="SELECT *
					FROM ".$GLOBALS['database_prefix']."core_user_password_recovery
					WHERE user_id = '".$this->user_id."'
					AND date_requested > DATE_ADD(now(),interval -6 hour)
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			return True;
		}
		else {
			return False;
		}
	}

	function AddPasswordRecovery($user_id,$secret_string) {
		$db=$GLOBALS['db'];
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."core_user_password_recovery
					(user_id,date_requested,secret_string)
					VALUES (
					'".$user_id."',
					now(),
					'".$secret_string."'
					)";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) == 0) {
			return False;
		}
		else {
			return True;
		}
	}

	public function SuperiorID() {
		$db=$GLOBALS['db'];
		$sql="SELECT superior_user_id
					FROM ".$GLOBALS['database_prefix']."hrms_user_reporting
					WHERE subordinate_user_id = '".$_SESSION['user_id']."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['superior_user_id'];
			}
		}
		else {
			return False;
		}
	}

	public function Approve() {
		if ($this->parameter_check) {
			$db=$GLOBALS['db'];
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_space_users
						SET approved = 'y'
						WHERE user_id = ".$this->user_id."
						";
			$result = $db->Query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function ChangeDashboard($v) {
		if ($this->parameter_check) {
			if ($v=="y") { $v="y"; } else { $v="n"; }
			$db=$GLOBALS['db'];
			$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
						SET show_dashboard = '".$v."'
						WHERE user_id = ".$this->user_id."
						";
			$result = $db->Query($sql);
			if ($result) {
				return True;
			}
			else {
				return False;
			}
		}
	}

	public function SetLanguage($lang) {
		if (!$this->parameter_check) { $this->Errors("Unable to change language 1"); return False; }
		$db=$GLOBALS['db'];
		$sql="UPDATE ".$GLOBALS['database_prefix']."core_user_master
					SET language = '".EscapeData($lang)."'
					WHERE user_id = ".$_SESSION['user_id']."
					";
		$result = $db->Query($sql);
		if ($db->AffectedRows($result) > 0) {
			return True;
		}
		else {
			$this->Errors("Unable to change language");
			return False;
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

	function Errors($err) {
		$this->errors.=$err."<br>";
	}

	function ShowErrors() {
		return $this->errors;
	}
}
?>