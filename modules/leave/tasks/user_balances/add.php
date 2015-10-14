<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/forms/user_balances.php";

function Add() {

	$c="";

	if (ISSET($_GET['category_id']) && $_GET['subtask'] =="edit") {
		$obj_ci=new CategoryBalanceID;
		$obj_ci->SetParameters($_GET['category_id']);
		$default_balance=$obj_ci->GetInfo("default_balance");
		$category_id=$_GET['category_id'];
	}
	else {
		$category_id="";
		$default_balance="";
	}

	/* SHOW THE FORM */
	$c.=UserBalances();

	return $c;
}
?>