<?php
/** ensure this file is being included by a parent file */
defined( '_VALID_DIR_' ) or die( 'Direct Access to this location is not allowed.' );

/* EXPECT DATE IN THE FORMAT 2005-12-31 */

function ValidDate($dt) {
    if (!preg_match("/^\d\d\d\d-\d\d-\d\d$/",$dt)) {
        return False;
    }
    //echo $dt."<br />";
	/* SPLIT THE VARIABLE */
	$tempdate = explode('-', $dt);
	$year = $tempdate[0];
	$month = $tempdate[1];
	$day = $tempdate[2];
        //echo "$year-$month-$day";
	/* VALIDATE THE DATE */
	if (!checkdate($month,$day,$year)) { return False; }

	/* RETURN TRUE IF NO ERRORS FOUND */
	return True;
}
?>