<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";
require_once $GLOBALS['dr']."classes/design/tab_boxes.php";
require_once $GLOBALS['dr']."modules/leave/functions/forms/user_balances.php";

function LoadTask() {

	$c="";

	/*
	**************************************************************************
		PERFORM THE ACTIONS HERE BEFORE WE START DISPLAYING STUFF
	***************************************************************************
	*/
	/* ADD */
	if (ISSET($_POST['FormSubmit']) && ISSET($_POST['SaveResults'])) {

		$obj_ub=new UserBalances;
		$result_param=$obj_ub->SetParameters($_POST['user_id']);
		if (!$result_param) { $c."failed"; $c.=$obj_ub->ShowErrors(); return $c; }
		/* GET ALL CATEGORIES */
		$db=$GLOBALS['db'];
		$sql="SELECT category_id,category_name FROM ".$GLOBALS['database_prefix']."leave_category_master WHERE workspace_id = ".$GLOBALS['workspace_id'];
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {

				$post_form_name="cat_".$row['category_id'];
				$result_update=$obj_ub->Update($row['category_id'],$_POST[$post_form_name]);
				if (!$result_update) {
					$c.="Failed";
					$c.=$obj_ci->ShowErrors();
				}
			}
		}
	}
	/* DELETE */
	if (ISSET($_GET['subtask'])) {
		if ($_GET['subtask']=="delete" && ISSET($_GET['category_id'])) {
			$obj_ci=new CategoryID;
			$obj_ci->SetParameters($_GET['category_id']);
			$result_del=$obj_ci->Delete();
			if (!$result_del) {
				$c.="Failed";
				$c.=$obj_ci->ShowErrors();
			}
			else {
				$c.="Success";
			}
		}
	}

	if (ISSET($_POST['user_id'])) {
		$user_id=$_POST['user_id'];
	}
	else {
		$user_id="";
	}

	/* LAYOUT OF GUI */
	$c.=UserBalances($user_id);

	return $c;
}
?>