<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

function TimestampToMySQL($dt,$format="Y-m-d") {

	return date($format, $dt);
	//return mktime($hour,$minute,$second,$month,$daynum,$year);
}
?>
