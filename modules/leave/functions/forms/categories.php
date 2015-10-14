<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Categories($category_name="",$allow_negative_balance="",$allow_balance_carry_forward="",$paid_unpaid="",$category_id="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("categories");
	$fv->OpenTag();
	$fv->ValidateFields("category_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=categories","post","categories","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/leave/images/default/categories.png","Leave Categories");
	if (!EMPTY($category_id)) {
		$form->Hidden("category_id",$category_id);
	}
	$form->Input("Category Name","category_name","","","",$category_name);
	$form->Checkbox("Allow negative balance","allow_negative_balance",$allow_negative_balance);
	$form->Checkbox("Allow carry forward","allow_balance_carry_forward",$allow_balance_carry_forward);
	$form->ManualDropDown("Paid / Unpaid",array("paid","unpaid"),"paid_unpaid",$paid_unpaid);


	$form->SetFocus("category_name");

	$c.=$form->DrawForm();

	return $c;
}
?>