<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("login.php");

# Global Variables
$notice = array();

# Display any message in the cookie
if (isset($_COOKIE["msg"])) {
		$msg = explode(":", $_COOKIE["msg"]);
		$notice = array($msg[0], $msg[1]);
		setcookie("msg", null, time() - 100);
}

# Sets the checking cookie
if (!isset($_COOKIE["cookies"]) && !isset($_GET["cookies"])) {
	setcookie("cookies", true, time() + 60 * 60 * 24 * 14);
	header("Location: logout.php?cookies=set");
	exit();
}

# Database connection
$db = Database::start();

if (!$db->connect()) {
	$notice = array("bad", "Oh noes. We can't connect to the database.<br />Please try again later.");
} elseif (isset($_GET["cookies"])) {
	$notice = array("bad", "This site requires cookies for authentication.");
} else {
	# try and login...
	if (isset($_POST["submit"]) || isset($_COOKIE["key"])) {
		if ((empty($_POST["user"]) || empty($_POST["pass"])) && empty($_COOKIE["key"])) {
			$notice = array("bad", "Please fill in all the fields.");
		} else {
			# check valid user
			if (isset($_COOKIE["key"])) {
				$sql = "SELECT * FROM user WHERE id = '" . $db->clean($_COOKIE["id"]) . "' AND pass = '" . $db->clean($_COOKIE["key"]) . "' AND active = 'true';";
			} else {
				$sql = "SELECT * FROM user WHERE user = '" . $db->clean($_POST["user"]) . "' AND pass = '" . md5($setting["secret"] . $_POST["pass"]) . "' AND active = 'true';";
			}
			$result = $db->select($sql);

			if (empty($result)) {
				if (isset($_COOKIE["key"])) {
					$notice = array("bad", "Your key is invalid, please login.");
					setcookie("key", null, time() - 100);
				} else {
					$notice = array("bad", "Either your username or password is incorrect.");
				}
			} else {
				# valid user
				$id	= $result["id"];
				$_SESSION["user"] = $id;
				$_SESSION["status"] = true;
				session_regenerate_id(true);
				setcookie("id", $id, time() + 60 * 60 * 24 * 14);

				if (isset($_POST["remember"])) {
					$key = md5($setting["secret"] . $_POST["pass"]);
					setcookie("key", $key, time() + 60 * 60 * 24 * 14);
				}

				if (isset($_SESSION["target"])) {
					header("Location: http://" . $_SERVER["HTTP_HOST"] . $setting["path"] . $_SESSION["target"]);
				} else {
					header("Location: http://" . $_SERVER["HTTP_HOST"] . $setting["path"] . "index.php");
				}
			}
		}
	}
	# Close link
	$db->disconnect();
}

# Start template system
$html = new Template("login.php");

# Include the template file
include_once("html/login.php");
?>
