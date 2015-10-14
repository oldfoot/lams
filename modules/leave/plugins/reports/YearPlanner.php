<?php
require_once($GLOBALS['dr']."modules/leave/classes/year_planner_xml.php");

class YearPlanner {
	public function __construct() {
		$this->ReportTitle = "Year Planner";
		$this->ReportDescription = "Display a year planner of users on leave";
		$this->ReportImage = "gantt";
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

		$xml=new YearPlannerXML;
		$xml->Draw($GLOBALS['dr']."modules/leave/modules/reports/temp_xml/".$_SESSION['sid'].".xml");

		$c="

		<div id='chartdiv' align='center'>FusionCharts.</div>

	     	<script type=\"text/javascript\">
			   var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Gantt.swf\",\"ChartId\",\"600\",\"450\");
			   chart.setDataURL(\"".$GLOBALS['wb']."modules/leave/modules/reports/temp_xml/".$_SESSION['sid'].".xml\");
			   chart.render(\"chartdiv\");
			</script>
		";


		return $c;

	}
}
?>