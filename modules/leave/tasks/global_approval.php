<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once $GLOBALS['dr']."modules/leave/functions/browse/global_approval.php";
require_once $GLOBALS['dr']."modules/leave/classes/global_approval.php";

function LoadTask() {

	/* FORM PROCESSING */

	if (ISSET($_POST['submit'])) {

		if (ISSET($_POST['select1'])) {

			/* ADD */
			$v_user_id_add_arr=$_POST['select1'];
			for ($i=0;$i<count($v_user_id_add_arr);$i++) {
				$v_user_id=EscapeData($v_user_id_add_arr[$i]);
				$cs=new GlobalApproval;
				$cs->SetParameters($v_user_id);
				$cs->Add();
			}
		}
		/* DELETE */
		if (ISSET($_POST['select2'])) {
			$v_user_id_del_arr=$_POST['select2'];
			for ($i=0;$i<count($v_user_id_del_arr);$i++) {
				$v_user_id=EscapeData($v_user_id_del_arr[$i]);
				$cs=new GlobalApproval;
				$cs->SetParameters($v_user_id);
				$cs->Delete();
			}
		}
	}
	/* SHOW THE FORM */
	return GlobalApproval();
}
?>