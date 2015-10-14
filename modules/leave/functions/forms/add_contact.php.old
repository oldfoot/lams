<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

function AddContact($contact_id="",$title_id="",$first_name="",$last_name="",$user_id_assigned="",$date_birthday="",$lead_source_id="",
										$contact_id_reports_to="",$phone_number="",$fax_number="",$mobile_number="",$pager_number="",
										$address="",$city="",$state="",$postal_code="",$country_id="",$other_info="",
										$do_not_call="",$do_not_email="",$assistant_name="",$assistant_phone_number="",$email_address="") {

	$c="";

	/* FIELD VALIDATION */
	$fv=new FieldValidation;
	$fv->FormName("add_contact");
	$fv->OpenTag();
	$fv->ValidateFields("account_name");
	$fv->CloseTag();
	$c.=$fv->Draw();

	/* DESIGN THE FORM */
	$form=new CreateForm;
	$form->SetCredentials("index.php?module=crm&task=add_contact","post","add_contact","onSubmit=\"ValidateForm(this)\"");
	$form->Header("crystalclear/48x48/apps/access.png","Contacts");

	$form->BreakCell("Personal Details");
	$form->ShowDropDown("Title","title_id","title_name","hrms_title_master","title_id",$title_id,"","","","input_reqd");
	$form->Input("First Name","first_name","","","",$first_name,"25","input_reqd");
	$form->Input("Last Name","last_name","","","",$last_name,"25","input_reqd");
	$form->Input("Assigned to","user_assigned","user","add_contact","user_assigned","user_id_assigned",$user_id_assigned,"input_reqd");
	$form->Date("Birthdate","date_birthday",$date_birthday);

	$form->BreakCell("Account Information");
	$form->Input("Account","user_assigned","crm_account","add_contact","user_assigned","user_id_assigned",$user_id_assigned,"input_reqd");
	$form->Input("Reports to","user_assigned","crm_reports_to","add_contact","user_assigned","user_id_assigned",$user_id_assigned,"input_reqd");
	$form->ShowDropDown("Lead source","lead_source_id","lead_source_name","crm_lead_source_master","lead_source_id",$lead_source_id,"","","","input_reqd");

	$form->BreakCell("Contact Information");
	$form->Input("Phone","phone_number","","","",$phone_number);
	$form->Input("Fax","fax_number","","","",$fax_number);
	$form->Input("Mobile","mobile_number","","","",$mobile_number);
	$form->Input("Pager","pager_number","","","",$pager_number);
	$form->Input("Email","email_address","","","",$email_address);
	$form->Textarea("Address","address",5,30,$address);
	$form->Input("City","city","","","",$city);
	$form->Input("State","state","","","",$state);
	$form->Input("Code","postal_code","","","",$postal_code);
	$form->ShowDropDown("Country","country_id","country_name","hrms_country_master","country_id",$country_id,"","","","input_reqd");

	$form->BreakCell("Other Information");
	$form->Textarea("Other","other_info",5,50,$other_info);

	$form->Checkbox("Do not call","do_not_call",$do_not_call);
	$form->Checkbox("Do not email","do_not_email",$do_not_email);

	$form->Submit("Save","FormSubmit");
	$form->SetFocus("first_name");
	$c.=$form->DrawForm();

	return $c;
}
?>