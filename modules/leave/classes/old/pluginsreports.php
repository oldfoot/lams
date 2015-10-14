<?php
class pluginsreports {
	public function __construct() {
		$this->classes = array();
		$dir = $GLOBALS['dr']."/modules/leave/plugins/reports/";
		//echo $dir;
		// READ ALL THE PLUGINS INTO A DIRECTORY
		if ($handle = opendir($dir)) {
	  	while (false !== ($file = readdir($handle))) {
	    	if ($file != "." && $file != ".." && substr($file,-4) == ".php") {
	      	require_once "$dir$file";

	      	// INSTANTIATE ALL THE PLUGIN OBJECTS
	      	$obj = strtolower($file);
	      	$obj = str_replace(".php","",$obj);
	      	$this->$obj = new $obj;

	      	// STORE EACH PLUGIN IN AN ARRAY, E.G DETAILS;TWITTER;ETC
	      	$this->classes[] = $obj;
	      }
	    }
	    closedir($handle);
		}
	}
	public function GetReportPluginName($i) {
		if (ISSET($this->classes[$i])) {
			return $this->classes[$i];
		}
		else {
			return false;
		}
	}

	public function GetTitles() {
		$arr = array();
		$this->arr_plugins = array();
		foreach ($this->classes as $obj) {
			//echo $obj."<br />";
			$arr[] = $this->$obj->GetInfo("ReportTitle");
		}
		//print_r($arr);
		return $arr;
	}

	public function GetDescriptions() {
		$arr = array();
		$this->arr_plugins = array();
		foreach ($this->classes as $obj) {
			//echo $obj."<br />";
			$arr[] = $this->$obj->GetInfo("ReportDescription");
		}
		//print_r($arr);
		return $arr;
	}

	public function GetImage() {
		$arr = array();
		$this->arr_plugins = array();
		foreach ($this->classes as $obj) {
			//echo $obj."<br />";
			$arr[] = $this->$obj->GetInfo("ReportImage");
		}
		//print_r($arr);
		return $arr;
	}

	public function DrawReport($plugin) {
		if (ISSET($this->$plugin)) {
			return $this->$plugin->DrawReport();
		}
		else {
			return "Invalid report";
		}
	}
}
?>