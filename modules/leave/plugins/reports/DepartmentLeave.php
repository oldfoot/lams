<?php
class DepartmentLeave {
	public function __construct() {
		$this->ReportTitle = "Department Leave";
		$this->ReportDescription = "Display user by department on leave";
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

		$sql="SELECT dm.department_name, um.full_name, la.date_from, la.date_to, la.total_days
					FROM leave_applications la, core_user_master um, hrms_user_department ud, hrms_department_master dm
					WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
					AND la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					AND la.user_id = um.user_id
					AND um.user_id = ud.user_id
					AND ud.department_id = dm.department_id
					ORDER BY um.full_name
								";

		$sql = str_replace("\n","",$sql);
		//$sql = str_replace("  ","",$sql);
		$sql = str_replace("\t","",$sql);
		//$sql = str_replace(Chr(13),"",$sql);
		//echo $sql;
		$_SESSION['ex2'] = $sql;

		$head = $GLOBALS['head'];
		$head->IncludeFile("rico21");

		$c="";
		$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
		$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
		$c.="<colgroup>\n";
		$c.="<col style='width:110px;' >\n";
		$c.="<col style='width:110px;' >\n";
		$c.="<col style='width:70px;' >\n";
		$c.="<col style='width:70px;' >\n";
		$c.="<col style='width:70px;' >\n";

		$c.="</colgroup>\n";
		  $c.="<tr>\n";
			  $c.="<th>Department</th>\n";
			  $c.="<th>User</th>\n";
			  $c.="<th>From</th>\n";
			  $c.="<th>To</th>\n";
			  $c.="<th>Days</th>\n";
		  $c.="</tr>\n";
		$c.="</table>\n";


		return $c;

	}
}
?>