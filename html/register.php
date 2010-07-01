<?php if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); } ?>
<!DOCTYPE html>
<html>
<head>
	<title>Starbase Tracker - Register</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css"  href="css/main.css" />
	<style type="text/css">
		input[type="text"], input[type="password"] { width: 325px; }
		input[type="checkbox"] { margin-right: 10px; }
		input[type="submit"] { margin-top: 0; }
		select { width: 350px; }
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
	</style>
</head>
<body>
<div class="wrapper">
	<h1 class="logo">Starbase Tracker</h1>

	<div class="clear"></div>

	<div class="content">
		<div class="section">
			<h2>Register</h2>
		</div>

		<div class="line"></div>

		<?php $html->notice($notice); ?>

		<div class="section form">
			<form id="register" name="register" method="post" action="">
				<p><?php $html->register_input("text", "user", "EVE Character Name", 1); ?></p>

				<p>
					<select name="corp" tabindex="2">
					<optgroup label="EVE Corporation">
					<option>Dreddit</option>
					<option>Did I Just Do That</option>
					<option>Ars Ex Discordia</option>
					</optgroup>
					</select>
				</p>

				<p><?php $html->register_input("password", "pass", "Password", 3); ?></p>

				<p><?php $html->register_input("password", "pass_check", "Password Confirmation", 4); ?></p>

				<p><?php $html->register_input("text", "key", "Registration Key", 5); ?></p>

				<p><input name="submit" type="submit" id="submit" value="Register &raquo;" tabindex="6" /></p>
			</form>
		</div>
	</div>
</div>
</body>
</html>