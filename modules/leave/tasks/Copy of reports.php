<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function LoadTask() {
	$c="";

	if (ISSET($_GET['subtask'])) {
		$subtask_file=$GLOBALS['dr']."modules/leave/modules/reports/".$_GET['subtask'].".php";
		if (file_exists($subtask_file)) {
			require_once $subtask_file;
			$c.=SubTask();
		}
		else {
			$c.="Sample report. To view similiar reports, please purchase our premium version to support this product.<br>\n";
			$c.="<img src='modules/leave/images/reporting/".$_GET['subtask'].".png'>\n";
		}
	}
	else {
		/* LOAD THE LINKS */
		$c.="<table class='plain'>";
			$c.="<tr class='modulehead'>";
				$c.="<td colspan='2'>Reports</td>";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=department_leave'>Department Leave</a> - display all the users in a particular department on leave</li></ul></td>\n";
				$c.="<td><img src='images/core/text.png'></td>\n";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=monthly_leave_total_user_bar_chart'>Monthly leave total user</a> - display the number of users on leave per month</li></ul></td>\n";
				$c.="<td><img src='images/core/bar.png'></td>\n";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=year_to_date_leave_category'>Current Year Leave Category Breakdown</a> - gives you an idea of which category of leave is being used the most</li></ul></td>\n";
				$c.="<td><img src='images/core/pie3d.png'></td>\n";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=average_leave_per_month'>Average leave per month</a> - an indication of how many days on average are taken per month</li></ul></td>\n";
				$c.="<td><img src='images/core/bar.png'></td>\n";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=application_search'>Leave Application Search</a> - search for applications</li></ul></td>\n";
				$c.="<td><img src='images/core/text.png'></td>\n";
			$c.="</tr>";
			$c.="<tr>";
				$c.="<td><ul><li><a href='index.php?module=leave&task=reports&subtask=year_planner'>Year Planner</a> - a list of all users on leave in the year</li></ul></td>\n";
				$c.="<td><img src='images/core/text.png'></td>\n";
			$c.="</tr>";
		$c.="</table>";
	}

	return $c;
}
?>