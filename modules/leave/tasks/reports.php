<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."modules/leave/classes/pluginsreports.php");

function LoadTask() {
	$c="";

	$obj = new pluginsreports;

	if (ISSET($_GET['plugin'])) {
		$c .= $obj->DrawReport($_GET['plugin']);
	}
	else {
		$c .= "<h3>Reports</h3>\n";

		$titles = $obj->GetTitles();
		$desc = $obj->GetDescriptions();
		$img = $obj->GetImage();
		foreach ($titles as $key => $title) {
			$c .= "<div class=reportlinkmoo onMouseOver=\"this.className='reportlinkmov'\" onMouseOut=\"this.className='reportlinkmoo'\">";
			$plugin = $obj->GetReportPluginName($key);
			$c .= "<a href=index.php?module=leave&task=reports&plugin=$plugin><span class=title>$title</span></a>\n";
			$c .= "<span class=desc>".$desc[$key]."</span>\n";
			if ($img[$key] == "text") {
				$c .= "<span class=reportimg><img src=images/core/text.png></span>\n";
			}
			$c .= "</div>";
		}
	}

	return $c;
}
?>