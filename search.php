<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("search.php");

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

	# Start template system
	$html = new Template("index.php");

	if (isset($_GET["q"])) {
		# Get search results
		$query = $db->clean($_GET["q"]);
		$sql = "SELECT * FROM pos WHERE name LIKE '%" . $query . "%' OR region LIKE '%" . $query . "%' OR system LIKE '%" . $query . "%' OR corp LIKE '%" . $query . "%' OR owner LIKE '%" . $query . "%' ORDER BY status, name;";
		$result = $db->select_all($sql);
	} else {
		$result = false;
	}

	# Close link
	$db->disconnect();

	# Remove private starbases
	if (!empty($result)) {
		foreach ($result as $i => $data) {
			if ($html->check_private($data["private"]) && $user->detail["name"] != $data["owner"]) {
				unset($result[$i]);
			}
		}
		$result = array_values($result);
	}

	include_once("html/search.php");
}
?>