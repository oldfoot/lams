<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function periods() {
	if (ISSET($_GET['periodid']) && IS_NUMERIC($_GET['periodid'])) {
		$_SESSION['periodid'] = $_GET['periodid'];
		$GLOBALS['obj_us']->SetVar("periodid",$_GET['periodid']);
		$GLOBALS['obj_us']->SetVar("userid",$_SESSION['userid']);
		$GLOBALS['obj_us']->Edit();
		$GLOBALS['obj_us']->Info(); // REFRESH
	}
	
	$sql = "CALL sp_leave_period_master_browse(".$_SESSION['workspaceid'].")";
	//echo $sql;
	$result = $GLOBALS['db']->Query($sql);				
	while ($row = $GLOBALS['db']->FetchArray($result)) {
		$theme = "c";
		$icon = "plus";
		$link = " onClick=\"document.location.href='index.php?module=leave&task=periods&periodid=".$row['PeriodID']."'\"";
		if ($GLOBALS['obj_us']->GetVar("PeriodID") == $row['PeriodID']) { $theme = "b"; $icon="check"; $link = "disabled";}
		echo "<input value=\"".$row['DateFrom']." to ".$row['DateTo']."\" data-icon=\"$icon\" data-theme=\"$theme\" type=\"button\" $link>";
	}
	
	echo "<input value=\"Add new period\" data-icon=\"plus\" data-theme=\"a\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=periodadd'\">";
	echo "<input value=\"Edit existing periods\" data-icon=\"plus\" data-theme=\"a\" type=\"button\" onClick=\"document.location.href='index.php?module=leave&task=editperiod'\">";
}
?>