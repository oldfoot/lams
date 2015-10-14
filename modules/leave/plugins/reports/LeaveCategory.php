<?php
include_once($GLOBALS['dr']."classes/reporting/fusion_pie2d.php");
//include_once($GLOBALS['dr']."classes/reporting/gen_data.php");

class LeaveCategory {
	public function __construct() {
		$this->ReportTitle = "Leave by Category";
		$this->ReportDescription = "A breakdown of leave per category";
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

		//$y = date("y");
		//if (ISSET($_GET['y']) && IS_NUMERIC($_GET['y'])) {
			//$y = $_GET['y'];
		//}

		$sql = "SELECT count(*) as total, cm.category_name as legend
				FROM leave_applications la, leave_category_master cm
				WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
				AND la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
				AND la.category_id = cm.category_id
				GROUP BY cm.category_id";

		$obj = new FusionPie2d;
		$obj->GenHead();
		$obj->GenLegendDB($sql);
		$obj->GenFooter();
		$obj->SaveToDir();

		$c = "";

		$c .= "<h3>Leave transactions by category for the current period</h3>
					<div id='chartdiv' align='center'>FusionCharts.</div>
      		<script type=\"text/javascript\">
			   		var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Pie2D.swf\",\"ChartId\",\"500\",\"369\");
			   		chart.setDataURL(\"".$GLOBALS['wb']."bin/reporting/xml/pie2d_".$_SESSION['sid'].".xml\");
			   		chart.render(\"chartdiv\");
					</script> ";

		//for ($i = date("Y") -5;$i <= date("Y"); $i++) {
			//$c .= "<a href=index.php?module=leave&task=reports&plugin=leavecategory&y=$i>$i</a> | ";
		//}

		return $c;

	}
}
?>