<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("index.php");

# Global Variables
$user = array();

# Database connection
$db = Database::start();

if (!$db->connect()) {
	header("Location: logout.php?msg=bad:Oh noes. We can't connect to the database.<br />Please try again later.");
	exit();
} else {
	# Get user info
	$user = new User($_COOKIE["id"]);

	# Get notices
	$sql = "SELECT * FROM setting WHERE name = 'notice';";
	$result = $db->select($sql);
	$notice["info"] = $result["value"];

	#$sql = "SELECT * FROM pos WHERE status = 'Under Attack!'";
	#$result = $db->select_all($sql);
	#foreach ($data as $result) {
	#	$notice["bad"][] = $data;
	#}

	# Get bad structures
	$sql = "SELECT * FROM pos WHERE status != 'Normal';";
	$bad = $db->select_all($sql);

	# Get good structures
	$sql = "SELECT * FROM pos WHERE status = 'Normal';";
	$good = $db->select_all($sql);

	# Close link
	$db->disconnect();

	# Start template system
	$html = new Template("index.php");

	include_once("html/index.php");
}
?>
