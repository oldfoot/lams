<?php
require_once $GLOBALS['dr']."modules/leave/classes/user_balances.php";
class LeaveEntitlement {
	public function __construct() {
		$this->ReportTitle = "Leave by entitlement";
		$this->ReportDescription = "Display all the users leave by category";
		$this->ReportImage = "text";
	}
	public function GetInfo($var) {
		if (ISSET($this->$var)) {
			return $this->$var;
		}
		else {
			return false;
		}
	}
	public function DrawReport() {
		
		$c="";
		$period_id = $GLOBALS['obj_us']->GetInfo("period_id");
		$obj_ub=new UserBalances;
		$obj_ub->SetParameters($_SESSION['user_id']);
		$c.="<table class='plain' height=100% width=100% border=1>\n";
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
}
?>