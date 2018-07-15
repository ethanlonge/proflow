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
	define('version', $devel_version);
	include("v" . $devel_version . "/index.php");
} else {
	define('version', $latest_version);
	include("v" . $latest_version . "/index.php");
}
?>
