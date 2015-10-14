<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");
require_once($GLOBALS['dr']."include/functions/filesystem/size_int.php");

function SubTask() {

	if (ISSET($_GET['department_id'])) {
		$sr=new ShowResults;
		$sr->SetParameters(True);
		$sr->DrawFriendlyColHead(array("Name","From","To","Days")); /* COLS */
		$sr->Columns(array("full_name","date_from","date_to","total_days"));
		$sql="SELECT um.full_name, la.date_from, la.date_to, la.total_days
					FROM leave_applications la, core_user_master um, hrms_user_department ud
					WHERE la.workspace_id = ".$GLOBALS['workspace_id']."
					AND la.user_id = um.user_id
					AND um.user_id = ud.user_id
					AND ud.department_id = 1
					ORDER BY um.full_name
								";
		//echo $sql;
		$sr->Query($sql);

		//for ($i=0;$i<$sr->CountRows();$i++) {
			//$department_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
			//$department_name=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
			//$sr->ModifyData($i,1,"<a href='index.php?module=leave&task=reports&subtask=department_leave&department_id=".$department_id."'>".$department_name."</a>");
		//}
		//$sr->RemoveColumn(0);

		$sr->WrapData();
		$sr->TableTitle("nuvola/22x22/actions/gohome.png","Users on leave");
		return $sr->Draw();
	}
	else {
		$sr=new ShowResults;
		$sr->SetParameters(True);
		$sr->DrawFriendlyColHead(array("","Department")); /* COLS */
		$sr->Columns(array("department_id","department_name"));
		$sql="SELECT department_id,department_name
								FROM ".$GLOBALS['database_prefix']."hrms_department_master
								WHERE workspace_id = ".$GLOBALS['workspace_id']."
								AND teamspace_id ".$GLOBALS['teamspace_sql']."
								ORDER BY department_name
								";
		//echo $sql;
		$sr->Query($sql);

		for ($i=0;$i<$sr->CountRows();$i++) {
			$department_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
			$department_name=$sr->GetRowVal($i,1); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
			$sr->ModifyData($i,1,"<a href='index.php?module=leave&task=reports&subtask=department_leave&department_id=".$department_id."'>".$department_name."</a>");
		}
		$sr->RemoveColumn(0);

		$sr->WrapData();
		$sr->TableTitle("nuvola/22x22/actions/gohome.png","Department List");
		return $sr->Draw();
	}
}
?>