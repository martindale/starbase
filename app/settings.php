<?php

if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); }

# Errors
error_reporting(E_ALL);

# Secret key
$setting["secret"]	= md5("9yev]W|R.~@gdus.)f82(!E8(:=Do645^o..YJM,|?kzF");

$setting["path"]	= "/test/";