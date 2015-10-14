<?php
define( '_VALID_DIR_', 1 );
require_once "config.php";

session_destroy();
unset($_SESSION['userid']);
$_SESSION['userid'] = "";
$_SESSION = array();
print_r($_SESSION);
//echo $_SESSION['userid'];
header("location:splash.php");
?>