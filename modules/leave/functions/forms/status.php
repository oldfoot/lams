<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Status($status_id="",$status_name) {

	$ui=$GLOBALS['ui'];

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("status_name","date_to");
	$fv->OpenTag();
	$fv->ValidateFields("periods");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=status","post","status","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/leave/images/default/status.png","Leave Status");

	$form->Hidden("status_id",$status_id);
	$form->Input("Name","status_name","","","",$status_name);

	$form->CloseForm();

	$c.=$form->DrawForm();

	return $c;
}
?>