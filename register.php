<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("register.php");

# Global Variables
$notice = array();

# Database connection
$db = Database::start();

if (!$db->connect()) {
	$notice = array("bad", "Oh noes. We can't connect to the database.<br />Please try again later.");
} else {
	# register...
	if (isset($_POST["submit"])) {
		if (empty($_POST["user"]) || empty($_POST["pass"]) || empty($_POST["pass_check"])) {
			$notice = array("bad", "Please fill in all the fields.");
		} elseif ($_POST["pass"] != $_POST["pass_check"]) {
			$notice = array("bad", "The passwords did not match, try again.");
		} elseif ($_POST["key"] != md5(date("dmyH").$setting["secret"].$_SERVER["SERVER_NAME"])) {
			$notice = array("bad", "Your key is invalid.");
		} else {
			$sql = "INSERT INTO user (user, pass, rights, corp, active) VALUES ('" . $db->clean($_POST["user"]) . "','" . md5($setting["secret"].$_POST["pass"]) . "','0,0,0','" . $db->clean($_POST["corp"]) . "','false');";
			if ($db->query($sql)) {
				setcookie("new", true);
				setcookie("msg", "good:You are now registered.<br />Please wait for an admin to accept your account.");
				header("Location: login.php");
				exit();
			} else {
				$notice = array("bad", "For some reason the registration was unsuccessful.<br />Please try again later.");
			}
		}
	}
	# Close link
	$db->disconnect();

	# Start template system
	$html = new Template("register.php");

	# Include the template file
	include_once("html/register.php");
}
?>