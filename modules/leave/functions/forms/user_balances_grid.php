<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* REQUIRED INCLUDES */
require_once $GLOBALS['dr']."classes/form/create_form.php";
require_once $GLOBALS['dr']."classes/form/field_validation.php";

require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";

function UserBalancesGrid() {
	$GLOBALS['head']->IncludeFile("ajax");
	$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

	$db=$GLOBALS['db'];
	$c = "";
	/* GET ALL CATEGORIES */
	$arr_categories = array();

	$sql="SELECT category_id,category_name
				FROM ".$GLOBALS['database_prefix']."leave_category_master
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				";
	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			$id=$row['category_id'];
			$nm=$row['category_name'];
			$arr_categories[$id] = $nm;
		}
	}
	/* GET ALL USERS */

	$arr_users = array();

	$sql="SELECT lus.user_id,cm.full_name
				FROM ".$GLOBALS['database_prefix']."leave_user_settings lus, ".$GLOBALS['database_prefix']."core_user_master cm
				WHERE lus.user_id = cm.user_id
				AND cm.workspace_id = ".$GLOBALS['workspace_id']."
				";

	$sql="SELECT cm.user_id,cm.full_name
				FROM ".$GLOBALS['database_prefix']."core_space_users csu, ".$GLOBALS['database_prefix']."core_user_master cm
				WHERE csu.workspace_id = ".$GLOBALS['workspace_id']."
				AND csu.user_id = cm.user_id
				";

	//echo $sql."<br>";
	$result = $db->Query($sql);
	if ($db->NumRows($result) > 0) {
		while($row = $db->FetchArray($result)) {
			//$c.= $row['full_name'];
			//$c.= $row['full_name'];
			$id = $row['user_id'];
			$arr_users[$id] = $row['full_name'];
		}
	}
	//print_r($arr_categories);
	$c.="<div id=targetDiv>Waiting for changes...</div>\n";
	$c.="<table border=1>\n";
		$c.="<tr class=colhead>\n";
			$c.="<td>\n";
				$c.="Name\n";
			$c.="</td>\n";
			foreach ($arr_categories as $ckey => $cat) {
				$c.="<td>\n";
					$c.="$cat\n";
				$c.="</td>\n";
			}
		$c.="</tr>\n";
		foreach ($arr_users as $ukey => $u) {
			$c.="<tr>\n";
				$c.="<td>\n";
				$c.="$u\n";
				$c.="</td>\n";
				foreach ($arr_categories as $ckey => $cat) {
				//for ($i=0;$i<count($arr_categories);$i++) {
					$c.="<td>\n";
					//$c.="<input type='text' name='user_".$arr_categories[$i]."'>\n";

					$ub = new UserBalances;
					$ub->SetParameters($ukey);
					$bal = $ub->LeaveAvailable($ckey);

					$c.="<input type='text' id='user_".$ckey."' name='user_".$ckey."' value='$bal' size=2 onChange=\"getDataReturnText('modules/leave/ajax/update_user_balances.php?user_id=$ukey&cat=$ckey&bal='+document.getElementById('user_".$ckey."').value,'targetDiv')\">\n";
					$c.="</td>\n";
				}
			$c.="</tr>\n";
		}
	$c.="</table>\n";

	return $c;
}
?>