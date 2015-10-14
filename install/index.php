<?php
set_time_limit(300);
echo "<html>\n";
echo "<title>Setup</title>\n";
echo "<link rel='stylesheet' href='../css/css.css' type='text/css'>\n";
echo "<a href='http://code.google.com/p/genusproject/'>Help</a><br /><br />\n";

echo "<table class='plain_border'>\n";
	echo "<tr>\n";
		echo "<td colspan='2' height='50'><h1>Setup of system</h1></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		echo "<td class='colhead' colspan='2'>PRE-INSTALLATION CHECK</td>\n";
		echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
		/* LEFT NAV BAR */

		echo "<td width='200' valign='top'>\n";
			if (!ISSET($_GET['step'])) { echo "<li>"; }
			echo "Step 1<br>";
			if (ISSET($_GET['step']) && $_GET['step'] == "2") { echo "<li>"; }
			echo "Step2<br>";
			if (ISSET($_GET['step']) && $_GET['step'] == "3") { echo "<li>"; }
			echo "Step3<br>";
		echo "</td>\n";

		/* STEPS NAV BAR */
		echo "<td valign='top'>\n";
		if (!ISSET($_GET['step'])) {
			require_once "step1.php";
		}
		elseif (ISSET($_GET['step']) && $_GET['step'] == "2") {
			require_once "step2.php";
		}
		elseif (ISSET($_GET['step']) && $_GET['step'] == "3") {
			require_once "step3.php";
		}
		echo "</td>\n";
	echo "</tr>\n";
echo "</table>\n";

echo "</html>\n";

function DrawRow($td1,$td2="",$class="plain") {
	$c="<tr class='$class'>\n";
		$c.="<td>$td1</td>\n";
		$c.="<td>$td2</td>\n";
	$c.="</tr>\n";

	return $c;
}

function DrawHeader($td1,$class="colhead") {
	$c="<tr>\n";
		$c.="<td colspan='2' class='$class'>$td1</td>\n";
	$c.="</tr>\n";

	return $c;
}
?>