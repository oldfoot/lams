<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function AddAccount($account_name="",$industry_id="",$account_type_id="",$user_id_assigned="",$phone_number="",$fax_number="",$email_address="",
										$billing_address="",$billing_city="",$billing_state="",$billing_postal_code="",$billing_country_id="",
										$shipping_address="",$shipping_city="",$shipping_state="",$shipping_postal_code="",$shipping_country_id="",
										$other_info="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("add_account");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=crm&task=add_account","post","add_account","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Account");

	//$form->Hidden("module_id",$module_id);

	$form->Input("Account Name","account_name","","","",$account_name,"25","input_reqd");
	$form->ShowDropDown("Industry","industry_id","industry_name","crm_industry_master","industry_id",$industry_id,"","","","input_reqd");
	$form->ShowDropDown("Account Type","account_type_id","account_type_name","crm_account_type_master","account_type_id",$account_type_id,"","","","input_reqd");
	$form->Input("Assigned to","user_assigned","user","add_account","user_assigned","user_id_assigned",$user_id_assigned,"input_reqd");
	$form->Hidden("user_id_assigned",$user_id_assigned);

	$form->BreakCell("Contact Information");
	$form->Input("Phone","phone_number","","","",$phone_number);
	$form->Input("Fax","fax_number","","","",$fax_number);
	$form->Input("Email","email_address","","","",$email_address);

	$form->BreakCell("Billing Information");
	$form->Textarea("Address","billing_address",5,30,$billing_address);
	$form->Input("City","billing_city","","","",$billing_city);
	$form->Input("State","billing_state","","","",$billing_state);
	$form->Input("Code","billing_postal_code","","","",$billing_postal_code);
	$form->ShowDropDown("Country","country_id","country_name","hrms_country_master","billing_country_id",$billing_country_id,"","","","input_reqd");

	$form->BreakCell("Shipping Information");
	$form->Textarea("Address","shipping_address",5,30,$shipping_address);
	$form->Input("City","shipping_city","","","",$shipping_city);
	$form->Input("State","shipping_state","","","",$shipping_state);
	$form->Input("Code","shipping_postal_code","","","",$shipping_postal_code);
	$form->ShowDropDown("Country","country_id","country_name","hrms_country_master","shipping_country_id",$shipping_country_id,"","","","input_reqd");

	$form->BreakCell("Other Information");
	$form->Textarea("Other","other_info",5,50,$other_info);
	/*
	$form->Textarea("Description","description",5,30,$description);
	$form->Checkbox("Available Teamspaces","available_teamspaces",$available_teamspaces);
	$form->Input("Logo","logo","logo","add_module","logo",$logo,40);
	$form->Checkbox("Available Signup","signup_module",$signup_module);
	*/

	//$form->Submit("Save","FormSubmit");

	$form->SetFocus("account_name");

	$c.=$form->DrawForm();

	return $c;
}
?>