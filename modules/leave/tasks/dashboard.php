<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

//require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";
//require_once $GLOBALS['dr']."modules/leave/functions/browse/browse_applications.php";
//require_once($GLOBALS['dr']."modules/leave/classes/pluginsdashboard.php");

function LoadTask() {
	$c="";

	$c.="<table width=100%>\n";
	$count = 0;
	$breakafter = 2;
	$dir = $GLOBALS['dr']."modules/leave/plugins/dashboard/";
		//echo $dir;
		// READ ALL THE PLUGINS INTO A DIRECTORY
	if ($handle = opendir($dir)) {
	 	while (false !== ($file = readdir($handle))) {
	   	if ($file != "." && $file != ".." && substr($file,-4) == ".php") {
	     	//echo "$dir$file <br />";
	     	require_once "$dir$file";

				$count++;
				if ($count == 1) {
					$c.="<tr>\n";
				}

	     	// INSTANTIATE ALL THE PLUGIN OBJECTS
	     	$obj = $file;
	     	//$obj = strtolower($file);
	     	//echo $obj;
	     	$obj = str_replace(".php","",$obj);
	     	$obj = new $obj;
	     	$c .= "<td>";
	     	$c .= $obj->DrawReport();
	     	$c .= "</td>";

	     	if ($count == $breakafter) {
	     		$c.="</tr>";
	     		$count = 0;
	     	}
			}
		}
	}

	$c.="</table>";

	return $c;

	//888888888888888888888888888888
	$obj = new pluginsdashboard;
	$reports = $obj->GetReports();


	foreach ($reports as $dashlet) {

	}




		/*
		$c.="<tr class=colhead>\n";
			$c.="<td>Current Applications</td>\n";
		$c.="</tr>\n";
		$c.="<tr>\n";
			$c.="<td>".Browseapplications("my",false,false)."</td>\n";
		$c.="</tr>\n";
		*/
	$c.="</table>\n";

	return $c;
}

function DashTableTotals() {
	$c="";
	$period_id = $GLOBALS['obj_us']->GetInfo("period_id");
	$obj_ub=new UserBalances;
	$obj_ub->SetParameters($_SESSION['user_id']);
	$c.="<table class='plain'>\n";
		$c.="<tr class='formbuttonrow'>\n";
			$c.="<td colspan='4'>Your Totals</td>\n";
		$c.="</tr>\n";
		$c.="<tr class='colhead'>\n";
					$c.="<td>Category</td>\n";
					$c.="<td>Entitled</td>\n";
					$c.="<td>Taken</td>\n";
					$c.="<td>Balance</td>\n";
				$c.="</tr>\n";
		$db=$GLOBALS['db'];
		$sql="SELECT cm.category_id,cm.category_name,ub.leave_total
					FROM ".$GLOBALS['database_prefix']."leave_category_master cm,".$GLOBALS['database_prefix']."leave_user_balances ub
					WHERE cm.workspace_id = ".$GLOBALS['workspace_id']."
					AND cm.category_id = ub.category_id
					AND ub.user_id = ".$_SESSION['user_id']."
					AND period_id = $period_id
					";
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row = $db->FetchArray($result)) {
				$leave_taken=$obj_ub->LeaveTaken($row['category_id']);
				$c.="<tr class='dashboard_figures'>\n";
					$c.="<td>".$row['category_name']."</td>\n";
					$c.="<td>".$row['leave_total']."</td>\n";
					$c.="<td>".$leave_taken."</td>\n";
					$c.="<td>".($row['leave_total']-$leave_taken)."</td>\n";
				$c.="</tr>\n";
			}
		}
	$c.="</table>\n";

	return $c;
}
?>