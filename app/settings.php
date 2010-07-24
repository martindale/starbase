<?php

if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); }

# Errors
error_reporting(E_ALL);

# Secret key
$setting["secret"]	= md5("9yev]W|R.~@gdus.)f82(!E8(:=Do645^o..YJM,|?kzF");

$setting["path"]	= "/test/";

# Database settings
$settings["db_host"] = "localhost";
$settings["db_username"] = "sam";
$settings["db_password"] = "HyperL0g0";
$settings["db_database"] = "starbase";