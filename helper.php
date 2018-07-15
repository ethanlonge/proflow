<?php
$latest_version = "3.0";
$devel_version = "3.1";
session_start();
if (isset($_GET['dev'])) {
	$_SESSION['dev'] = true;
}
if (isset($_GET['cur'])) {
  unset($_SESSION['dev']);
}
if (isset($_SESSION['dev'])) {
	include("v" . $devel_version . "/helper.php");
} else {
	include("v" . $latest_version . "/helper.php");
}
?>
