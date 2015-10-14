<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

require_once($GLOBALS['dr']."classes/form/show_results.php");

function BrowseCategories() {

	$c="";

	$sql="SELECT category_id,category_name,
				concat('<a href=index.php?module=leave&task=categories&subtask=auto_approve&category_id=',category_id,' title=ChangeAutoApprove>',auto_approve,'</a>') as f4,
				concat('<a href=index.php?module=leave&task=categories&subtask=planning&category_id=',category_id,' title=ChangePlanning>',planning,'</a>') as f5,
				concat('<a href=index.php?module=leave&task=categories&subtask=edit&category_id=',category_id,' title=Edit>Edit</a>') as f1,
				concat('<a href=index.php?module=leave&task=categories&subtask=delete&category_id=',category_id,' title=Delete>Delete</a>') as f2
				FROM ".$GLOBALS['database_prefix']."leave_category_master
				WHERE workspace_id = ".$GLOBALS['workspace_id']."
				ORDER BY category_name";

	$sql = str_replace("\n","",$sql);
	//$sql = str_replace("  ","",$sql);
	$sql = str_replace("\t","",$sql);
	//$sql = str_replace(Chr(13),"",$sql);
	//echo $sql;
	$_SESSION['ex2'] = $sql;

	$head = $GLOBALS['head'];
	$head->IncludeFile("rico2rc2");

	$c="";
	$c.="<p class='ricoBookmark'><span id='ex2_timer' class='ricoSessionTimer'></span><span id='ex2_bookmark'>&nbsp;</span></p>\n";
	$c.="<table id='ex2' class='ricoLiveGrid' cellspacing='0' cellpadding='0'>\n";
	$c.="<colgroup>\n";
	$c.="<col style='width:30px;' >\n";
	$c.="<col style='width:100px;' >\n";
	$c.="<col style='width:90px;' >\n";
	$c.="<col style='width:60px;' >\n";
	$c.="<col style='width:50px;' >\n";
	$c.="<col style='width:50px;' >\n";

	$c.="</colgroup>\n";
	  $c.="<tr>\n";
		  $c.="<th>ID</th>\n";
		  $c.="<th>Category</th>\n";
		  $c.="<th>Auto Approve</th>\n";
		  $c.="<th>Planning</th>\n";
		  $c.="<th>Edit</th>\n";
		  $c.="<th>Delete</th>\n";
	  $c.="</tr>\n";
	$c.="</table>\n";


	return $c;

	$sr=new ShowResults;
	$sr->SetParameters(True);
	$sr->DrawFriendlyColHead(array("","Category Name","Allow Negative Bal","Carry Forward","Paid/Unpaid","Edit","Delete")); /* COLS */
	$sr->Columns(array("category_id","category_name","allow_negative_balance","allow_balance_carry_forward","paid_unpaid","edit","del"));
	$sr->Query("SELECT category_id,category_name,allow_negative_balance,allow_balance_carry_forward,paid_unpaid,'edit' AS edit,'delete' AS del
							FROM ".$GLOBALS['database_prefix']."leave_category_master
							WHERE workspace_id = ".$GLOBALS['workspace_id']."
							ORDER BY category_name");

	for ($i=0;$i<$sr->CountRows();$i++) {
		$category_id=$sr->GetRowVal($i,0); /* FASTER THAN CALLING EACH TIME IN THE NEXT 2 LINES */
		$sr->ModifyData($i,5,"<a href='index.php?module=leave&task=categories&subtask=edit&category_id=".$category_id."' title='Edit'>Edit</a>");
		$sr->ModifyData($i,6,"<a href='index.php?module=leave&task=categories&subtask=delete&category_id=".$category_id."' title='Delete'>Delete</a>");
	}
	$sr->RemoveColumn(0);
	$sr->ColDefault(2	,"yesnoimage"); /* SET POPUP TO YES/NO */
	$sr->ColDefault(3	,"yesnoimage"); /* SET POPUP TO YES/NO */


	$sr->WrapData();
	$sr->Footer();
	$sr->TableTitle("../modules/leave/images/default/categories.png","Browsing categories");
	return $sr->Draw();

}

?>