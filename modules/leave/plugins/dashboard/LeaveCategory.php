<?php
include_once($GLOBALS['dr']."classes/reporting/fusion_pie2d.php");

class LeaveCategory {
	public function __construct() {
		$this->ReportTitle = "Leave by Category";
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

		$sql="SELECT count(*) as total, lcm.category_name as legend
					FROM ".$GLOBALS['database_prefix']."leave_applications la, ".$GLOBALS['database_prefix']."leave_category_master lcm
					WHERE la.period_id = '".$GLOBALS['obj_us']->GetInfo("period_id")."'
					AND la.workspace_id = '".$GLOBALS['workspace_id']."'
					AND la.user_id = ".$_SESSION['user_id']."
					AND la.category_id = lcm.category_id
					GROUP BY lcm.category_name
		";

		$obj = new FusionPie2d;
		$obj->GenHead();
		$obj->GenLegendDB($sql);
		$obj->GenFooter();
		$obj->SaveToDir();

		$c = "";

		$c .= "<h3>Leave Category</h3>
					<div id='chartdiv'>FusionCharts.</div>
      		<script type=\"text/javascript\">
			   		var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Pie2D.swf\",\"ChartId\",\"250\",\"180\");
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