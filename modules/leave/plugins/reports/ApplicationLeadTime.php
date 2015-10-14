<?php
class ApplicationLeadTime {
	public function __construct() {
		$this->ReportTitle = "Application Lead Time";
		$this->ReportDescription = "Display how long in advance leave is applied for";
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

		$sql="SELECT AVG(TO_DAYS(date_from) - TO_DAYS(date_application)) as total, um.full_name as legend
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."core_user_master um
					WHERE la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					AND la.workspace_id = ".$GLOBALS['workspace_id']."
					AND la.user_id = um.user_id
					GROUP BY la.user_id
					ORDER BY total";

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
		<col style='width:150px;' >
		<col style='width:360px;' >
		</colgroup>
		  <tr>
			  <th>Lead Time (days)</th>
			  <th>User</th>
		  </tr>
		</table>
		";

		return $c;

	}
}
?>