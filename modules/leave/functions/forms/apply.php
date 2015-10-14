<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."modules/core/classes/core_create_form.php";
require_once $GLOBALS['dr']."modules/core/classes/core_field_validation.php";

function Apply($date_from="",$date_from_half_day="",$date_from_half_day_am_pm="",$date_to="",$date_to_half_day="",$date_to_half_day_am_pm="",$categoryid="",$remarks="",$application_id="") {

	/* INCLUDE THE JCALENDAR */
	//$GLOBALS['head']->IncludeFile("jscalendar");

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("apply");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	//$c.=$fv->Draw();
	
	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=leave&task=apply","post","apply","onSubmit=\"ValidateForm(this)\"  enctype=\"multipart/form-data\"");
	$form->Header("../modules/leave/images/default/apply.png","Apply for Leave");
	
	
	$form->Date("Date From","date_from",$date_from);
	$form->Checkbox("Half day","date_from_half_day",$date_from_half_day);
	$form->Radio("am/pm","date_from_half_day_am_pm",array("am","pm"),$date_from_half_day_am_pm);
	
	$form->Date("Date To","date_to",$date_to,$date_to_half_day);
	
	$form->Checkbox("Half day","date_to_half_day",$date_to_half_day);
	$form->Radio("am/pm","date_to_half_day_am_pm",array("am","pm"),$date_to_half_day_am_pm);

	$form->ShowDropDown("Category","CategoryID","CategoryName","leave_category_master","CategoryID",$categoryid,"","WHERE WorkspaceID = ".$_SESSION['workspaceid'],"","input_reqd");
	$form->Textarea("Remarks","remarks",5,30,$remarks);
	
	$form->file("Attach document","attachment");

	$form->Hidden("application_id",$application_id);
	
	//$form->SetFocus("period_from");
	$c.=$form->CloseForm();
	$c.=$form->DrawForm(true,true);
	
	return $c;
}
?>