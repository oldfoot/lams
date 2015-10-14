<?php
class GeneralReport {
	public function __construct() {
		$this->ReportTitle = "General Report";
		$this->ReportDescription = "Display all the users on leave with searching";
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
		$current_period = $GLOBALS['obj_us']->GetInfo("period_id");

		$sql="SELECT la.application_id, um.full_name, la.date_from, la.date_to, cm.category_name
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."core_user_master um, ".$GLOBALS['database_prefix']."leave_category_master cm
					WHERE la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					AND la.workspace_id = ".$GLOBALS['workspace_id']."
					AND la.user_id = um.user_id
					AND period_id = $current_period
					AND la.category_id = cm.category_id ";

		$sql = str_replace("\n","",$sql);
		//$sql = str_replace("  ","",$sql);
		$sql = str_replace("\t","",$sql);
		//$sql = str_replace(Chr(13),"",$sql);
		//echo $sql;
		$_SESSION['ex2'] = $sql;

		$GLOBALS['head']->IncludeFile("rico21");

		//require "include/rico2rc2/examples/php/chklang2.php";

		$c="
		<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>
		<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>
		<colgroup>
		<col style='width:40px;' >
		<col style='width:160px;' >
		<col style='width:100px;' >
		<col style='width:100px;' >
		<col style='width:110px;' >
		</colgroup>
		  <tr>
			  <th>ID</th>
			  <th>User</th>
			  <th>From</th>
			  <th>To</th>
			  <th>Category</th>
		  </tr>
		</table>
		";

		return $c;

	}
}
?>