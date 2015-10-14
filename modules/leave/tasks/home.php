<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function home() {
	
	$c = "";
	$c .= "<h3>Welcome to the leave module</h3>";
	
	$c .= "Use the menu on the left hand side to navigate this module";
	
	return $c;
}
?>