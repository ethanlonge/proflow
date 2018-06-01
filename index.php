<?php
$latest_version = "2.0";
$devel_version = "3.0";
session_start();
if (isset($_GET['dev'])) {
	$_SESSION['dev'] = true;
}
if (isset($_SESSION['dev'])) {
	define('version', $devel_version);
	include("v" . $devel_version . "/index.php");
} else {
	define('version', $latest_version);
	include("v" . $latest_version . "/index.php");
}
?>
