<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/leave/classes/pluginsreports.php");

function LoadTask() {
	$c = "";

	$sql="SELECT report_name, version, activated
				FROM ".$GLOBALS['database_prefix']."leave_report_master
				";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico21");

	$c="<h3>Installed Reports</h3>";
	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:270px;' >\n";
	$c.="<col style='width:150px;' >\n";
	$c.="<col style='width:40px;' >\n";

	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>Report</th>\n";
		  $c.="<th>Version</th>\n";
		  $c.="<th>Activated</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	//$arr_remote_reports = array();
	/*
	$xmlstr = file_get_contents("http://localhost/genus/modules/leave/plugins/reports/index.xml");
	$xml = simplexml_load_string($xmlstr);
	$c .= "<h3>Available reports</h3>";
	foreach ($xml->report as $report) {
   $c .= $report->name." is available at version ".$report->version."<br />";
   //$name = $report->name;
   //$arr_remote_reports[$name] = $report->version;
	}
	*/
	return $c;

}
?>