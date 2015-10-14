<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function Periods($period_id="",$date_from,$date_to) {

	$ui=$GLOBALS['ui'];

	/* INCLUDE THE JCALENDAR */
	$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("date_from","date_to");
	$fv->OpenTag();
	$fv->ValidateFields("periods");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=periods","post","periods","onSubmit=\"ValidateForm(this)\"");
	$form->Header("../modules/leave/images/default/periods.png","Leave Periods");

	$form->Hidden("period_id",$period_id);

	$form->Date("Due from","date_from",$date_from);
	$form->Date("Due to","date_to",$date_to);

	$form->CloseForm();

	$c.=$form->DrawForm();

	return $c;
}
?>