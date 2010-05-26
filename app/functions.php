<?php

if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); }

function _start($page) {
	global $setting;

	session_start();

	if (($page == "login.php" || $page == "register.php") && !isset($_SERVER["HTTPS"])) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $setting["path"] . $page);
		exit();
	}

	if ($page != "login.php" && $page != "register.php") {
		$_SESSION["target"] = $page;

		if (!isset($_SESSION["user"]) || !isset($_SESSION["status"])) {
			header("Location: logout.php?msg=bad:You must login to view that page.");
			exit();
		} elseif ($_SESSION["status"] != true || $_SESSION["user"] != $_COOKIE["id"]) {
			header("Location: logout.php?msg=bad:Invalid session data. Please login again.");
			exit();
		}
	} else {
		if (isset($_SESSION["user"]) && isset($_SESSION["status"])) {
			if ($_SESSION["user"] == $_COOKIE["id"] && $_SESSION["status"] == true) {
				header("Location: http://" . $_SERVER["HTTP_HOST"] . $setting["path"] . "index.php");
				exit();
			}
		}
	}
}