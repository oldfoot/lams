<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function UserBalances($user_id="") {

	$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("user_balance");
	$fv->OpenTag();
	$fv->ValidateFields("default_balance");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=user_balances","post","user_balance","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/leave/images/default/user_balances.png","User Leave Balance");

	$form->ShowDropDown("User","user_id","full_name","core_user_master","user_id",$user_id,"","WHERE workspace_id = ".$GLOBALS['workspace_id']." AND teamspace_id ".$GLOBALS['teamspace_sql'],"onChange=\"this.form.submit()\"","input_reqd");

	if (!EMPTY($user_id)) {

		$form->Hidden("SaveResults","y");
		$db=$GLOBALS['db'];
		/* GET ALL CATEGORIES */
		$sql="SELECT category_id,category_name
					FROM ".$GLOBALS['database_prefix']."leave_category_master
					WHERE workspace_id = ".$GLOBALS['workspace_id']."
					";
		//echo $sql."<br>";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				/* GET THE USER'S TOTAL */
				$sql1="SELECT leave_total
					FROM ".$GLOBALS['database_prefix']."leave_user_balances
					WHERE user_id = ".$user_id."
					AND category_id = ".$row['category_id']."
					AND period_id = $current_period
					";
				//echo $sql."<br>";
				$result1 = $db->Query($sql1);
				if ($db->NumRows($result1) > 0) {
					while($row1 = $db->FetchArray($result1)) {
						$leave_total = $row1['leave_total'];
					}
				}
				else {
					$leave_total = "0";
				}
				$form->Input($row['category_name'],"cat_".$row['category_id'],"","",$leave_total,$leave_total,"3");
			}
		}
	}
	//$form->SetFocus("category_name");

	$c.=$form->DrawForm(true,true);

	return $c;
}
?>