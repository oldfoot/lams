<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

class FieldValidation {

	function FormName($form_name) {
		$this->form_name=$form_name;
	}

	function OpenTag() {
		$this->content="<script language='javascript'>\n";
		$this->content.="function ValidateForm(this) {\n";
	}

	function ValidateFields($arr) {

		$vals=explode(",", $arr);
		for ($i=0;$i<count($vals);$i++) {
			$this->content.="if (this.$vals[$i].value == \"\"){\n";
				$this->content.="alert(\"One or more required fields, indicated by a *, are empty!\");\n";
				$this->content.="this.".$vals[$i].".className='err_input';\n";
				$this->content.="this.".$vals[$i].".focus();\n";
				$this->content.="return false;\n";
			$this->content.="}\n";
		}

	}

	function SubmitOnce() {
		//$this->content.="function SubmitOnce(theform) {\n";
			// if IE 4+ or NS 6+
			$this->content.="if (document.all || document.getElementById) {\n";
				// hunt down "submit" and "reset"
				$this->content.="for (i=0;i<this.length;i++) {\n";
					$this->content.="var tempobj=this.elements[i];\n";
					$this->content.="if(tempobj.type.toLowerCase()==\"submit\") {\n";
						$this->content.="tempobj.disabled=true;\n";
					$this->content.="}\n";
				$this->content.="}\n";
			$this->content.="}\n";
		//$this->content.="}\n";
	}

	function OnlyInteger($fld) {
		$this->content.="var _x = this.".$fld.".value;
					if (parseInt(_x) != _x) {
  					alert('Only numbers allowed');
  					this.".$fld.".focus();
  					this.".$fld.".select();
  					return false;
  				}";
	}

	function CloseTag() {
		$this->content.="}\n";
		$this->content.="</script>\n";
	}

	function Draw() {
		return $this->content;
	}
}
?>