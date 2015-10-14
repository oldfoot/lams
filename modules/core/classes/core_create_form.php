<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class CreateForm {

	function __construct() {
		$this->btn_save="Save";
		$this->btn_cancel="Cancel";
		$this->show_btn_cancel=True;
	}

	function SetParam($n,$v) {
		$this->$n=$v;
	}

	function SetCredentials($action,$method,$name,$extra="",$table_width="100%",$table_class="plain") {
		$this->form_name=$name;
		$this->form="<form ".$extra." action='".$action."' method='".$method."' name='".$name."' >\n";
		$this->form.="<table width='".$table_width."' class='".$table_class."'>\n";
	}

	function Header($logo,$desc) {
		if (!defined('_VALID_DIR__MOBILE_')) {
			$img="<img src='".$GLOBALS['wb']."images/".$logo."'>";
		}
		else {
			$img="";
		}
		//$this->form.="<tr class='modulehead'>\n";
			//$this->form.="<td colspan='2' valign='top'>".$img.$desc."</td>\n";
		//$this->form.="</tr>\n";

		/* THE SAVE BUTTON */
		//$this->SaveCancelRow($desc);
	}

	private function SaveCancelRow($desc="") {
		$this->form.="<tr>\n";
			$this->form.="<td colspan='2'>\n";
			$this->form.="<table width='100%' class='plain'>\n";
				$this->form.="<tr class='formbuttonrow'>\n";
					$this->form.="<td valign='top' width=90%>".$desc."</td>\n";
					$this->form.="<td valign='top'><nobr><input type='submit' name='FormSubmit' value='".$this->btn_save."'>";
					if ($this->show_btn_cancel) {
						$this->form.="<input type='button' value='".$this->btn_cancel."' onClick=\"history.back()\"></nobr></td>\n";
					}
				$this->form.="</tr>\n";
			$this->form.="</table>\n";
			$this->form.="</td>\n";
		$this->form.="</tr>\n";
	}

	function Input($desc,$name,$popup="",$form_name="",$field_text="",$field_value="",$size="25",$class="inputbox",$field_extra="") {
		if ($popup=="user") { $show_popup="<img src='images/nuovext/16x16/actions/new_window.png' OnClick=\"window.open('bin/new_window/select_user.php?form_name=".$form_name."&field_text=".$field_text."&field_value=".$field_value."','Select User','scrollbars=no,status=no,width=400,height=350')\">\n"; }
		elseif ($popup=="logo") { $show_popup="<img src='images/nuovext/16x16/actions/new_window.png' OnClick=\"window.open('bin/new_window/browse_icons.php?form_name=".$form_name."&field_text=".$field_text."&field_value=".$field_value."','Browse Icons','scrollbars=no,status=no,width=400,height=350')\">\n"; }
		elseif ($popup=="location") { $show_popup="<img src='images/nuovext/16x16/actions/new_window.png' OnClick=\"window.open('bin/new_window/select_location.php?form_name=".$form_name."&field_text=".$field_text."&field_value=".$field_value."','Browse Locations','scrollbars=no,status=no,width=400,height=350')\">\n"; }
		else { $show_popup=""; }
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td><input type='text' name='".$name."' class='".$class."' value='".$field_value."' size='".$size."' $field_extra>".$show_popup."</td>\n";
		$this->form.="</tr>\n";
	}
	/* THIS WILL REPLACE THE ABOVE WHERE WE USE A POPUP TO RETRIEVE THE VALUES */
	function InputPopup($desc,$name,$field_value="",$popuptype="",$form_name="",$ins_field_text="",$ins_field_value="",$size="25",$class="inputbox") {

		$show_popup="<img src='images/nuovext/16x16/actions/new_window.png' OnClick=\"window.open('bin/new_window/".$popuptype.".php?form_name=".$form_name."&field_text=".$ins_field_text."&field_value=".$ins_field_value."','Insert Data','scrollbars=no,status=no,width=400,height=350')\">\n";

		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td><input type='text' name='".$name."' class='".$class."' value='".$field_value."' size='".$size."'>".$show_popup."</td>\n";
		$this->form.="</tr>\n";
	}

	function TimeFromTo($desc,$name_from,$name_to,$name_from_val,$name_to_val,$not_avail_name="",$not_avail_name_checked="") {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>";
			/*
			$this->form.="<input type='text name='".$name_from."' value='".$name_from_val."' size='6'>\n";
			$this->form.=JCalendarTime($name_from,$name_from);
			$this->form.="<input type='text name='".$name_to."' value='".$name_to_val."' size='6'>\n";
			$this->form.=JCalendarTime($name_to,$name_to);
			*/
			/* TRIM THE SELECTED VALUES TO THE FIRST 5 CHARACTERS FOR HOUR AND MINUTE ONLY */
			$name_from_val=SUBSTR($name_from_val,0,5);
			$name_to_val=SUBSTR($name_to_val,0,5);

			$this->form.="<select name='".$name_from."'>\n";
			for ($i=0;$i<24;$i++) {
				for ($j=0;$j<46;$j) {
					if ($i==0) { $i_show="00"; } else { if ($i<10) { $i_show="0".$i; } else { $i_show=$i; } }
					if ($j==0) { $j_show="00"; } else { $j_show=$j; }

					$friendly_val=$i_show.":".$j_show;
					//echo $friendly_val." versus ".$name_from_val."<br>";
					if ($friendly_val==$name_from_val) { $selected="selected"; } else { $selected=""; }
					$this->form.="<option value='".$friendly_val.":00' ".$selected.">".$friendly_val."</option>\n";
					$j+=15;
				}
			}
			$this->form.="</select>\n";
			$this->form.=" to \n";
			$this->form.="<select name='".$name_to."'>\n";
			for ($i=0;$i<24;$i++) {
				for ($j=0;$j<46;$j) {
					if ($i==0) { $i_show="00"; } else { if ($i<10) { $i_show="0".$i; } else { $i_show=$i; } }
					if ($j==0) { $j_show="00"; } else { $j_show=$j; }

					$friendly_val=$i_show.":".$j_show;
					//echo $friendly_val." versus ".$name_from_val."<br>";
					if ($friendly_val===$name_to_val) { $selected="selected"; } else { $selected=""; }
					$this->form.="<option value='".$friendly_val.":00' ".$selected.">".$friendly_val."</option>\n";
					$j+=15;
				}
			}
			$this->form.="</select>\n";
			if (!EMPTY($not_avail_name)) {
				if ($not_avail_name_checked) { $checked="checked"; } else { $checked=""; }
				$this->form.=" Not available: <input type='checkbox' name='".$not_avail_name."' value='y' ".$checked.">\n";
			}
			$this->form.="</td>\n";
		$this->form.="</tr>\n";
	}

	function Date($desc,$name_from,$name_from_val,$extra_data="") {
		$this->form.="
			  <script>
			  $(function() {
				$( \"#".$name_from."\" ).datepicker({ dateFormat: \"yy-mm-dd\" });
			  });
			  </script>
		";
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>";
			$this->form.="<input type='text' id='".$name_from."'>";			
			$this->form.="</nobr>".$extra_data."</td>";
		$this->form.="</tr>\n";
	}

	function DateTime($desc,$name_from,$name_from_val) {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>";
			$this->form.="<nobr><input type='text' name='".$name_from."' id='".$name_from."' value='".$name_from_val."' size='18'>\n";
			/* FOR MOBILE DON'T SHOW THE JCALENDAR */
			if (!defined( '_VALID_DIR__MOBILE_' )) {
				$this->form.=JCalendarTime($name_from,$name_from."_id");
			}
			else {
				$this->form.="<font size=1>YYYY-MM-DD</font>";
			}
			$this->form.="</nobr></td>";
		$this->form.="</tr>\n";

	}

	function DateYearDropDown($desc,$sel,$name) {

		if (strlen($sel) == 0 || (!is_numeric($sel))) {
			$this_year = date("Y");
		}
		else {
			$this_year = $sel;
		}

		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>";
			$this->form.="<select name='".$name."'>";
			for ($i=$this_year-4;$i<$this_year+4;$i++) {
				if ($this_year == $i) {	$selected = "selected";	}	else { $selected = ""; }
				$this->form.="<option ".$selected." value='".$i."'>".$i."</option>";
			}
			$this->form.="</select>";
			$this->form.="</td>";
		$this->form.="</tr>\n";
	}

	function Hidden($name,$value="") {
		$this->form.="<input type='hidden' name='".$name."' value='".$value."'>\n";
	}

	function TextArea($desc,$name,$rows,$cols,$value="") {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td><textarea rows='".$rows."' cols='".$cols."' name='".$name."'>".$value."</textarea></td>\n";
		$this->form.="</tr>\n";
	}

	function DescriptionCell($desc,$value) {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>".$value."</td>\n";
		$this->form.="</tr>\n";
	}

	function BreakCell($desc) {
		$this->form.="<tr class='rowhead'>\n";
			$this->form.="<td colspan='2'>".$desc."</td>\n";
		$this->form.="</tr>\n";
	}

	function WarningCell($desc) {
		$this->form.="<tr>\n";
			$this->form.="<td></td>\n";
			$this->form.="<td><table class='warning'><tr><td><img src='images/nuvola/22x22/actions/messagebox_warning.png'>".$desc."</td></tr></table></td>\n";
		$this->form.="</tr>\n";
	}

	function InformationCell($desc) {
		$this->form.="<tr>\n";
			$this->form.="<td></td>\n";
			$this->form.="<td><table class='information'><tr><td><img src='images/nuvola/22x22/actions/messagebox_info.png'>".$desc."</td></tr></table></td>\n";
		$this->form.="</tr>\n";
	}

	function TextAreaEnterSubmit($textarea_name) {
		$this->form.="<script type='text/javascript'><!--\n";
			$this->form.="function entertag(evt){\n";
			$this->form.="evt=(evt)?evt:event;\n";
			$this->form.="charCode=(evt.which)?evt.which:evt.keyCode;\n";
			$this->form.="if(charCode==13)document.".$this->form_name.".submit();\n";
		$this->form.="}\n";
		$this->form.="window.onload=attach;\n";
		$this->form.="function attach(){\n";
			$this->form.="document.".$this->form_name.".".$textarea_name.".onkeypress = entertag;\n";
		$this->form.="}\n";
		$this->form.="//--></script>\n";
	}

	function File($name) {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>Select file:</td>\n";
			$this->form.="<td><input name='".$name."' type='file'></td>\n";
		$this->form.="</tr>\n";
	}

	function Checkbox($desc,$name,$checked="n",$extra="") {
		if ($checked=="y") { $checked="checked"; } else { $checked=""; }
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td><input type='checkbox' name='".$name."' value='y' ".$checked.">".$extra."</td>\n";
		$this->form.="</tr>\n";
	}

	function CheckboxDBMultiple($desc,$f1,$f2,$tb,$sn,$sl,$sql="",$sql_extra="",$extra="",$class="inputbox",$arr_selected=false,$incl_html=true) {
		$db=$GLOBALS['db'];

		$checkboxes="";

	 	if (EMPTY($sql)) {
		 	$sql="SELECT DISTINCT $f1 as $f1, $f2 as $f2
		 	      FROM ".$GLOBALS['database_prefix']."$tb
		 	      $sql_extra
		 	      ORDER BY $f2";
		}

		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				$key = $row[$f1];
				//if (is_array($arr_selected)) { echo "Ok"; } else { echo "err"; }
				//echo $key;
				//print_r($arr_selected);
				if ($arr_selected && in_array($key,$arr_selected)) { $checked="checked"; } else { $checked=""; }
				$checkboxes.="<input type='checkbox' name='".$sn."[]' value='".$row[$f1]."' $checked $extra>".$row[$f2]."<br />\n";
			}
		}
		else {
			$checkboxes="Not setup.";
		}
		if ($incl_html) {
			$this->form.="<tr>\n";
				$this->form.="<td valign='top'>".$desc."</td>\n";
				$this->form.="<td>".$checkboxes."</td>\n";
			$this->form.="</tr>\n";
		}
		else {
			$this->form.=$desc;
			$this->form.=$checkboxes;
		}
	}
	function RadioDBMultiple($desc,$f1,$f2,$tb,$sn,$sl,$sql="",$sql_extra="",$extra="",$class="inputbox",$arr_selected=false,$incl_html=true) {
		$db=$GLOBALS['db'];

		$checkboxes="";

	 	if (EMPTY($sql)) {
		 	$sql="SELECT DISTINCT $f1 as $f1, $f2 as $f2
		 	      FROM $tb
		 	      $sql_extra
		 	      ORDER BY $f2";
		}

		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			while($row=$db->FetchArray($result)) {
				$key = $row[$f1];
				//if (is_array($arr_selected)) { echo "Ok"; } else { echo "err"; }
				//echo $key;
				//print_r($arr_selected);
				if ($arr_selected && in_array($key,$arr_selected)) { $checked="checked"; } else { $checked=""; }
				$checkboxes.="<input type='radio' name='".$sn."' value='".$row[$f1]."' $checked $extra>".$row[$f2]."<br />\n";
			}
		}
		else {
			$checkboxes="Not setup.";
		}
		if ($incl_html) {
			$this->form.="<tr>\n";
				$this->form.="<td valign='top'>".$desc."</td>\n";
				$this->form.="<td>".$checkboxes."</td>\n";
			$this->form.="</tr>\n";
		}
		else {
			$this->form.=$desc;
			$this->form.=$checkboxes;
		}
	}

	function Radio($desc,$name,$arr,$selected="") {
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>";
			for ($i=0;$i<count($arr);$i++) {
				if ($arr[$i]==$selected) { $checked_disp="checked"; } else { $checked_disp=""; }
				$this->form.=$arr[$i].":<input type='radio' name='".$name."' value='".$arr[$i]."' ".$checked_disp.">";
			}
			$this->form.="</td>\n";
		$this->form.="</tr>\n";
	}

	function ShowDropDown($desc,$f1,$f2,$tb,$sn,$sl,$sql="",$sql_extra="",$extra="",$class="inputbox") {
		$db=$GLOBALS['db'];
	 	if (EMPTY($sql)) {
		 	$sql="SELECT DISTINCT $f1 as $f1, $f2 as $f2
		 	      FROM ".$GLOBALS['database_prefix']."$tb
		 	      $sql_extra
		 	      ORDER BY $f2";
		}

		//echo $sql;
		$result = $db->Query($sql);
		if ($db->NumRows($result) > 0) {
			$drop_down="<select name='".$sn."' class='".$class."' ".$extra.">";
			$drop_down.="<option value=''>--==Select One==--</option>";
			while($row=$db->FetchArray($result)) {
				if ($sl==$row[$f1]) { $sel="selected"; } else { $sel=""; }
				$drop_down.="<option value='".@$row[$f1]."' ".$sel.">".@$row[$f2]."</option>";
			}
			$drop_down.="</select>";
		}
		else {
			$drop_down="Not setup.";
		}
		$this->form.="<tr>\n";
			$this->form.="<td valign='top'>".$desc."</td>\n";
			$this->form.="<td>".$drop_down."</td>\n";
		$this->form.="</tr>\n";
	}

	function Submit($value,$name) {
		$this->form.="<tr>\n";
			$this->form.="<td colspan='2'>\n";
			$this->form.="<input type='submit' value='".$value."' name='".$name."' class='buttonstyle'>\n";
			$this->form.="</td>\n";
		$this->form.="</tr>\n";
	}

	function SetFocus($field) {
		$this->form.="<script language='JavaScript'>\n";
		$this->form.="document.".$this->form_name.".".$field.".focus();\n";
		$this->form.="</script>\n";
	}

	function CloseForm() {
		$this->form.="</form>\n";
	}

	function DrawForm($scrow=true,$close_table=false) {
		/* THE SAVE BUTTON */
		if ($scrow) {
			$this->SaveCancelRow();
		}

		if ($close_table) {
			$this->form.="</table>\n";
		}

		return $this->form;
	}
}
?>