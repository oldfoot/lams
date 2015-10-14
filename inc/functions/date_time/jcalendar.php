<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function JCalendar($field_id,$button_id) {

	$c="<img src='images/nuvola/16x16/actions/view_icon.png' id='".$button_id."'>";
	$c.="<script type='text/javascript'>\n";
		$c.="Calendar.setup(\n";
		$c.="{\n";
		$c.="inputField : \"".$field_id."\", // ID of the input field\n";
		$c.="ifFormat : \"%Y-\%m-\%d\", // the date format\n";
		//$c.="showsTime : true,\n";
		$c.="button : \"".$button_id."\" // ID of the button\n";
		$c.="}\n";
		$c.=");\n";
	$c.="</script>\n";

	return $c;
}

function JCalendarMonthYear($field_id,$button_id) {
	$c="<img src='images/nuvola/16x16/actions/view_icon.png' id='".$button_id."'>";
	$c.="<script type='text/javascript'>\n";
		$c.="Calendar.setup(\n";
		$c.="{\n";
		$c.="inputField : \"".$field_id."\", // ID of the input field\n";
		$c.="ifFormat : \"%Y-\%m\", // the date format\n";
		$c.="button : \"".$button_id."\" // ID of the button\n";
		$c.="}\n";
		$c.=");\n";
	$c.="</script>\n";

	return $c;
}

function JCalendarTime($field_id,$button_id) {
	$c="<img src='images/nuvola/16x16/actions/view_icon.png' id='".$button_id."'>";
	$c.="<script type='text/javascript'>\n";
		$c.="Calendar.setup(\n";
		$c.="{\n";
		$c.="inputField : \"".$field_id."\", // ID of the input field\n";
		$c.="ifFormat : \"%Y-\%m-\%d %H-\%M\", // the date format\n";
		$c.="showsTime : true,\n";
		$c.="button : \"".$button_id."\" // ID of the button\n";
		$c.="}\n";
		$c.=");\n";
	$c.="</script>\n";

	return $c;
}
?>