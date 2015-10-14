<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/forms/categories.php";

function Add() {

	$c="";

	if (ISSET($_GET['category_id']) && $_GET['subtask'] =="edit") {
		$obj_ci=new CategoryID;
		$obj_ci->SetParameters($_GET['category_id']);
		$category_name=$obj_ci->GetInfo("category_name");
		$allow_negative_balance=$obj_ci->GetInfo("allow_negative_balance");
		$allow_balance_carry_forward=$obj_ci->GetInfo("allow_balance_carry_forward");
		$paid_unpaid=$obj_ci->GetInfo("paid_unpaid");
		$category_id=$_GET['category_id'];
	}
	else {
		$category_name="";
		$allow_negative_balance="";
		$allow_balance_carry_forward="";
		$paid_unpaid="";
		$category_id="";
	}

	/* SHOW THE FORM */
	$c.=Categories($category_name,$allow_negative_balance,$allow_balance_carry_forward,$paid_unpaid,$category_id);

	return $c;
}
?>