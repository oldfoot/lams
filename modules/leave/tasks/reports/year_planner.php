<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

//require_once($GLOBALS['dr']."classes/reporting/gen_xml_file_pie3d.php");
//require_once($GLOBALS['dr']."classes/reporting/gen_data.php");
require_once($GLOBALS['dr']."classes/reporting/year_planner_xml.php");

function SubTask() {

	global $head;
	$head->IncludeFile("fusioncharts");

	$xml=new YearPlannerXML;
	$xml->Draw($GLOBALS['dr']."modules/leave/modules/reports/temp_xml/year_planner.xml");

	$c="

	<div id='chartdiv' align='center'>FusionCharts.</div>

     	<script type=\"text/javascript\">
		   var chart = new FusionCharts(\"".$GLOBALS['wb']."include/FusionChartsFree/Charts/FCF_Gantt.swf\",\"ChartId\",\"750\",\"450\");
		   chart.setDataURL(\"".$GLOBALS['wb']."modules/leave/modules/reports/temp_xml/year_planner.xml\");
		   chart.render(\"chartdiv\");
		</script>
	";


	return $c;
}
?>
;
