<?php
include_once($GLOBALS['dr']."classes/reporting/fusion_pie2d.php");

class LeaveStatus {
	public function __construct() {
		$this->ReportTitle = "Leave by Status";
		$this->ReportDescription = "Display all the users leave by category";
		$this->ReportImage = "pie";
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

		global $head;
		$head->IncludeFile("fusioncharts");

		$sql="SELECT count(*) as total, lsm.status_name as legend
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."leave_status_master lsm
					WHERE la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					AND la.workspace_id = '".$GLOBALS['workspace_id']."'
					AND la.user_id = ".$_SESSION['user_id']."
					AND la.status_id = lsm.status_id
					GROUP BY lsm.status_name
		";
		//echo $sql;

		$obj = new FusionPie2d;
		$obj->GenHead();
		$obj->GenLegendDB($sql);
		$obj->GenFooter();
		$obj->SaveToDir("ls");

		$c = "";

		$c .= "<h3>".$this->ReportTitle."</h3>
					<div id='chartdivls'>FusionCharts.</div>
      		<script type=\"text/javascript\">
			   		var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Pie2D.swf\",\"ChartIdLS\",\"250\",\"180\");
			   		chart.setDataURL(\"".$GLOBALS['wb']."bin/reporting/xml/pie2d_ls".$_SESSION['sid'].".xml\");
			   		chart.render(\"chartdivls\");
					</script> ";

		//for ($i = date("Y") -5;$i <= date("Y"); $i++) {
			//$c .= "<a href=index.php?module=leave&task=reports&plugin=leavecategory&y=$i>$i</a> | ";
		//}

		return $c;

	}
}
?>