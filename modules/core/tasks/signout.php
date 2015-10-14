<?php
function signout() {
	session_destroy();
	header("location:index.php");
}
?>