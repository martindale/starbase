<?php
session_start();

if (!isset($_COOKIE["cookies"])) {
	header("Location: login.php?cookies=false");
	exit();
}

if (isset($_SESSION["user"])) {
	$_SESSION = array();
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]);
	}
	session_destroy();
	if (isset($_COOKIE["key"])) {
		setcookie("key", null, time() - 100);
	}
}

if (isset($_GET["msg"])) {
	setcookie("msg", $_GET["msg"]);
} else {
	setcookie("msg", "good:You are now logged out.");
}

header("Location: login.php");
?>