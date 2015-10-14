<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."include/functions/db/get_sequence_currval.php";

class AccountID {

	function __construct() {

	}
	public function SetParameters($account_id) {

		/* SET CHECKING TO FALSE */
		$this->parameter_check=False;

		/* CHECKS */
		if (!IS_NUMERIC($account_id)) { $this->Errors("Invalid account"); return False; }

		/* SET SOME COMMON VARIABLES */
		$this->account_id=$account_id;

		/* CALL THE CATEGORYID INFORMATION */
		$this->Info($account_id);

		/* PARAMETER CHECK SUCCESSFUL */
		$this->parameter_check=True;

		return True;
	}

	public function Info($account_id) {

		$this->db=$GLOBALS['db'];

		$sql="SELECT account_name,industry_id,user_id_assigned,phone_number,fax_number,email_address,account_type_id,
					billing_address,billing_city,billing_state,billing_postal_code,billing_country_id,
					shipping_address,shipping_city,shipping_state,shipping_postal_code,shipping_country_id,
					other_info,
					industry_name,
					account_type_name,
					full_name,
					country_name as billing_country_name,
					country_name as shipping_country_name
					FROM ".$GLOBALS['database_prefix']."v_crm_accounts
					WHERE account_id = '".$account_id."'
					";
		//echo $sql."<br>";
		$result = $this->db->Query($sql);
		if ($this->db->NumRows($result) > 0) {
			while($row = $this->db->FetchArray($result)) {
				/* HERE WE CALL THE FIELDS AND SET THEM INTO DYNAMIC VARIABLES */
				$i=pg_num_fields($result);
				for ($j=0;$j<$i;$j++) {
					$fieldname=pg_field_name($result,$j);
					$this->$fieldname=$row[pg_field_name($result,$j)];
				}
			}
		}
	}

	public function GetInfo($v) {
		return $this->$v;
	}

	public function Add($account_name,$industry_id,$user_id_assigned,$phone_number,$fax_number,$email_address,$account_type_id,
											$billing_address,$billing_city,$billing_state,$billing_postal_code,$billing_country_id,
											$shipping_address,$shipping_city,$shipping_state,$shipping_postal_code,$shipping_country_id,
											$other_info) {

		/* CHECKS */
		if(!preg_match("([a-zA-Z0-9])",$account_name))  { $this->Errors("Invalid account name. Please use only alpha-numeric values!"); return False; }
		if (RowExists("crm_account_master","account_name","'".$account_name."'","AND workspace_id=".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'])) { $this->Errors("Account name exists. Please choose another."); return False; }
		if (!IS_NUMERIC($industry_id)) { $this->Errors("Invalid Industry"); return False; }
		if (!IS_NUMERIC($user_id_assigned)) { $this->Errors("Invalid User Assigned"); return False; }
		if (!IS_NUMERIC($account_type_id)) { $this->Errors("Invalid account type"); return False; }

		if (!EMPTY($billing_country_id) && !IS_NUMERIC($billing_country_id)) { $this->Errors("Invalid billing country id"); return False; }
		if (!EMPTY($shipping_country_id) && !IS_NUMERIC($shipping_country_id)) { $this->Errors("Invalid shipping country id"); return False; }

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		$sql="INSERT INTO ".$GLOBALS['database_prefix']."crm_account_master
					(account_name,industry_id,user_id_assigned,phone_number,fax_number,email_address,account_type_id,
					billing_address,billing_city,billing_state,billing_postal_code,billing_country_id,
					shipping_address,shipping_city,shipping_state,shipping_postal_code,shipping_country_id,
					other_info,workspace_id,teamspace_id,date_added)
					VALUES (
					'".EscapeData($account_name)."',
					".$industry_id.",
					".$user_id_assigned.",
					'".EscapeData($phone_number)."',
					'".EscapeData($fax_number)."',
					'".EscapeData($email_address)."',
					".$account_type_id.",
					'".EscapeData($billing_address)."',
					'".EscapeData($billing_city)."',
					'".EscapeData($billing_state)."',
					'".EscapeData($billing_postal_code)."',
					".$billing_country_id.",
					'".EscapeData($shipping_address)."',
					'".EscapeData($shipping_city)."',
					'".EscapeData($shipping_state)."',
					'".EscapeData($shipping_postal_code)."',
					".$shipping_country_id.",
					'".EscapeData($other_info)."',
					".$GLOBALS['ui']->WorkspaceID().",
					".$GLOBALS['teamspace_id'].",
					now()
					)";
		$result=$db->query($sql);
		if ($result) {
			//$this->account_id=GetSequenceCurrval("s_document_categories_account_id");
			//if ($this->AddCategorySecurity()) {
				return True;
			//}
			//else {
				//return False;
			//}
		}
		else {
			return False;
		}
	}

	public function CategoryExists($par_category_name,$par_parent_id) {

		/* CHECKS */
		if (EMPTY($par_category_name)) { $this->Errors("Invalid Category Name"); return False; }
		if (!IS_NUMERIC($par_parent_id)) { $this->Errors("Invalid Parent"); return False; }

		$db=$GLOBALS['db'];

		$sql="SELECT 'x'
					FROM ".$GLOBALS['database_prefix']."document_categories dc
					WHERE dc.category_name = '".EscapeData($par_category_name)."'
					AND dc.parent_id = ".$par_parent_id."
					AND dc.workspace_id = ".$GLOBALS['workspace_id']."
					AND dc.teamspace_id ".$GLOBALS['teamspace_sql']."
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

	private function AddCategorySecurity() {

		/* DATABASE CONNECTION */
		$db=$GLOBALS['db'];

		/* INSERT TO THE DATABASE */
		$sql="INSERT INTO ".$GLOBALS['database_prefix']."document_category_role_security
					(account_id,role_id)
					VALUES (
					".$this->account_id.",
					".$this->role_id."
					)";
		$result=$db->query($sql);
		if ($result) {
			return True;
		}
		else {
			return False;
		}
	}

	private function GenerateCategoryHeirarchy($par_parent_id) {
		//print_r ($this->heirarchy_array);
		//echo "<br>";

		$db=$GLOBALS['db'];

		$sql="SELECT parent_id, category_name
					FROM ".$GLOBALS['database_prefix']."document_categories dc
					WHERE dc.account_id = '".$par_parent_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				array_push($this->heirarchy_array,$row['category_name']);
				$this->GenerateCategoryHeirarchy($row['parent_id']);			}
		}
		else {
			//return $this->heirarchy_array;
		}
	}

	function CategoryHeirarchy($par_parent_id) {

		if (!IS_NUMERIC($par_parent_id)) { $this->Errors("Invalid Parent"); return False; }

		$c="";
		$this->heirarchy_array[]="";

		$this->GenerateCategoryHeirarchy($par_parent_id);

		$this->heirarchy_array=array_reverse($this->heirarchy_array);

		for ($i=0;$i<count($this->heirarchy_array);$i++) {
			$c.=$this->heirarchy_array[$i]." -> ";
		}
		return SUBSTR($c, 0, -7);
	}

	public function CountFiles($par_account_id) {
		//print_r ($this->heirarchy_array);
		//echo "<br>";

		$db=$GLOBALS['db'];

		$sql="SELECT count(*) AS total
					FROM ".$GLOBALS['database_prefix']."document_files
					WHERE account_id = '".$par_account_id."'
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				return $row['total'];
			}
		}
		else {
			return 0;
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