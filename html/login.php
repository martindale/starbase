<?php if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); } ?>
<!DOCTYPE html>
<html>
<head>
	<title>Starbase Tracker - Login</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css"  href="css/main.css" />
	<style type="text/css">
		input[type="text"], input[type="password"] { width: 325px; }
		input[type="checkbox"] { margin-right: 10px; }
		input[type="submit"] { margin-top: 0; }
		label { cursor: pointer; }
		.wrapper { width: 400px; }
		.section { width: 350px; }
		.content { padding-bottom: 0; }
		.form p {
			color: #707070;
			font-size: 16px; }
		.notice.warning {
			background-color: #ffff88;
			border: 1px solid #ffb00f; }
		.notice.bad {
			background-color: #ffc1c1;
			border: 1px solid #cd0000; }
		.notice.good {
			background-color: #ccffcc;
			border: 1px solid #4cbb17; }
		.notice.info, .notice.help {
			background-color: #c1f0f6;
			border: 1px solid #66cccc; }
		#register {
			float: right;
			margin: 10px 10px 0 0; }
	</style>
</head>
<body>
<div class="wrapper">
	<h1 class="logo">Starbase Tracker</h1>

	<div class="clear"></div>

	<div class="content">
		<div class="section">
			<h2>Login</h2>
		</div>

		<div class="line"></div>

		<?php $html->notice($notice); ?>

		<div class="section form">
			<form id="login" name="login" method="post" action="">
				<p><?php $html->login_input("text", "user", "Username", 1); ?></p>

				<p><?php $html->login_input("password", "pass", "Password", 2); ?></p>

				<p><label><input type="checkbox" name="remember" tabindex="3" <?php if (!empty($notice) && (strpos($notice[1], "database") || strpos($notice[1], "cookies"))): ?>disabled<?php endif; ?> />Remember me?</label></p>

				<p><input name="submit" type="submit" id="submit" value="Login &raquo;" tabindex="4" <?php if (!empty($notice) && (strpos($notice[1], "database") || strpos($notice[1], "cookies"))): ?>disabled<?php endif; ?> /><span id="register"><a href="register.php">Register</a></span></p>
			</form>
		</div>
	</div>
</div>
</body>
</html>