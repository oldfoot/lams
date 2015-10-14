<?php
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: ".gmdate("D, d M Y H:i:s",time()+(-1*60))." GMT");
header('Content-type: text/html; charset=utf-8');

define( '_VALID_DIR_', 1 );
require_once "../../../config.php";

/* DATABASE CONFIGURATION */
require_once "../../../db_config.php";

/* OTHER CLASSES AND COMMON INCLUDES */
require_once "../../../common_config.php";

if (!ISSET($_SESSION['period_master1']) || $_SESSION['period_master1'] != "yes") {
	die("Access Denied");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>Rico LiveGrid-Example 2 (editable)</title>
<script src="<?php echo $GLOBALS['wb'];?>include/rico21/src/min.rico.js" type="text/javascript"></script>
<link href="<?php echo $GLOBALS['wb'];?>include/rico21/src/css/min.rico.css" type="text/css" rel="stylesheet" />
<link href="<?php echo $GLOBALS['wb'];?>include/rico21/client/css/demo.css" type="text/css" rel="stylesheet" />
<?php

$sqltext=".";  // force filtering to "on" in settings box
require $GLOBALS['dr']."include/rico21/examples/php/applib.php";
require $GLOBALS['dr']."include/rico21/plugins/php/ricoLiveGridForms.php";
require $GLOBALS['dr']."include/rico21/examples/php/settings.php";
?>

<script type='text/javascript'>
<?php
setStyle();
?>

// ricoLiveGridForms will call orders_FormInit right before grid & form initialization.

function orders_FormInit() {
  var cal=new Rico.CalendarControl("Cal");
  RicoEditControls.register(cal, Rico.imgDir+'calarrow.png');
  cal.addHoliday(25,12,0,'Christmas','#F55','white');
  cal.addHoliday(4,7,0,'Independence Day-US','#88F','white');
  cal.addHoliday(1,1,0,'New Years','#2F2','white');

  //var CustTree=new Rico.TreeControl("CustomerTree",$GLOBALS['dr']."include/rico21/examples/php/CustTree.php");
  //RicoEditControls.register(CustTree, Rico.imgDir+'dotbutton.gif');
}
</script>
<style type="text/css">
div.ricoLG_outerDiv thead .ricoLG_cell, div.ricoLG_outerDiv thead td, div.ricoLG_outerDiv thead th {
	height:1.5em;
}
div.ricoLG_cell {
  white-space:nowrap;
}
</style>
</head>
<body>

<?php
//************************************************************************************************************
//  LiveGrid Plus-Edit Example
//************************************************************************************************************
//  Matt Brown
//************************************************************************************************************
if (OpenGridForm("", "leave_period_master")) {
  if ($oForm->action == "table") {
    DisplayTable();
  }
  else {
    DefineFields();
  }
} else {
  echo 'open failed';
}
CloseApp();

function DisplayTable() {
  /*
  echo "<table id='explanation' border='0' cellpadding='0' cellspacing='5' style='clear:both'><tr valign='top'><td>";
  GridSettingsForm();
  echo "</td><td>This example demonstrates how database records can be updated via AJAX. ";
  echo "Try selecting add, edit, or delete from the pop-up menu. ";
  echo "If you select add, then click the '...' button next to customer, you will see the Rico tree control.";
  echo "The actual database updates have been disabled for security reasons and result in an error.";
  echo "</td></tr></table>";
  */
  $GLOBALS['oForm']->options["borderWidth"]=0;
  GridSettingsTE($GLOBALS['oForm']);
  $GLOBALS['oForm']->TableFilter="workspace_id=".$GLOBALS['workspace_id'];
  $GLOBALS['oForm']->TableFilter="teamspace_id=".$GLOBALS['teamspace_id_notnull'];
  //$GLOBALS['oForm']->options["DebugFlag"]=true;
  //$GLOBALS['oDB']->debug=true;
  DefineFields();
  //echo "<p><textarea id='orders_debugmsgs' rows='5' cols='80' style='font-size:smaller;'></textarea>";
}

function DefineFields() {
  global $oForm,$oDB;
  $oForm->options["FilterLocation"]=-1;

  $oForm->AddPanel("Basic Info");

  $oForm->AddEntryField("period_id", "ID", "B", "<auto>");
  $oForm->ConfirmDeleteColumn();
  $oForm->SortAsc();
  $oForm->CurrentField["width"]=50;

  $oForm->AddEntryField("date_from", "Date From", "T", "");
  $oForm->CurrentField["filterUI"]="t";
  $oForm->CurrentField["width"]=150;

  $oForm->AddEntryField("date_to", "Date To", "T", "");
  $oForm->CurrentField["width"]=150;

  $oForm->AddEntryField("active", "Active", "T", "");
  $oForm->CurrentField["width"]=100;
  
  $oForm->AddEntryField("workspace_id", "Workspace ID", "H", $GLOBALS['workspace_id']);
  $oForm->AddEntryField("teamspace_id", "Teamspace ID", "H", $GLOBALS['teamspace_id_null']);

  $oForm->DisplayPage();
}
?>


</body>
</html>
