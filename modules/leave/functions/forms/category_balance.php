<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Categories($category_id="",$default_balance="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("category_balance");
	$fv->OpenTag();
	$fv->ValidateFields("default_balance");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=category_balance","post","category_balance","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Category Balance");
	if (!EMPTY($category_id)) {
		$form->Hidden("category_id",$category_id);
	}
	$form->ShowDropDown("Category","category_id","category_name","leave_category_master","category_id",$category_id,"","","","input_reqd");
	$form->Input("Default Balance","default_balance","","","",$default_balance);

	$form->SetFocus("category_name");

	$c.=$form->DrawForm();

	return $c;
}
?>