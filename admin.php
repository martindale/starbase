<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("admin.php");

# Database connection
$db = Database::start();

if (!$db->connect()) {
	header("Location: logout.php?msg=bad:Oh noes. We can't connect to the database.<br />Please try again later.");
	exit();
} else {
	# Get user info
	$user = new User($_COOKIE["id"]);

	if (!$user->right(2)) {
		header("Location: index.php");
		exit();
	}

	# Close link
	$db->disconnect();

	# Start template system
	$html = new Template("index.php");

	# include_once("html/admin.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Starbase Tracker - Admin</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css"  href="css/main.css" />
	<style tyle="text/css">
		#name { width: 200px; }
	</style>
</head>
<body>
<div class="wrapper">
	<h1 class="logo">Starbase Tracker</h1>
	<p class="welcome">Welcome, <strong><a href="user.php"><?php echo $user->detail["name"]; ?></a></strong> (<a href="search.php?q=<?php echo $user->detail["corp"]; ?>"><?php echo $user->detail["corp"]; ?></a>) <span class="seperator">|</span> <a href="logout.php"><img src="img/door_out.png" />Logout</a></p>

	<div class="navigation">
		<ul>
			<li><a href="index.php"><img src="img/house.png" />Overview</a></li>
			<li><a href="#"><img src="img/lorry.png" />Logistics</a></li>
			<li><a href="#"><img src="img/world.png" />Recon</a></li>
			<?php if ($user->right(2)): ?><li><a href="#"><img src="img/add.png" />New POS</a></li><?php endif; ?>
			<?php if ($user->right(1)): ?><li><a href="admin.php" class="active"><img src="img/wrench.png" />Admin</a></li><?php endif; ?>
		</ul>

			<div id="search">
				<form method="get" action="search.php">
					<input type="search" placeholder="search a system, pos, etc." name="q" required />
					<input type="submit" value="Search &raquo;" />
				</form>
			</div>
	</div>

	<div class="clear"></div>

	<div class="content">

		<div class="section" id="settings">
			<h2>Settings</h2>
		</div>

		<div class="line"></div>

		<div class="notice info">Registration key (expires in <?php echo(60 - date("i")); ?>m): <code><?php echo md5(date("dmyH").$setting["secret"].$_SERVER["SERVER_NAME"]) ?></code><br />
		This key is required to register, only give it to people you trust. New accounts must be activated before they can be used.</div>

		<div class="section form">
			<form id="form" name="form" method="post" action="">
				<p><strong>Alliance</strong><br /><input type="text" name="alliance" placeholder="Alliance Name" tabindex="1" /></p>

				<p><strong>Corporations</strong> (comma seperated)<br /><input type="text" name="corps" placeholder="Corps." tabindex="2" /></p>

				<p><input name="submit" type="submit" id="submit" value="Submit &raquo;" tabindex="4" /></p>
			</form>
		</div>

		<div class="line"></div>

		<div class="section" id="users">
			<h2>Users</h2>
		</div>

		<div class="line"></div>

				<div class="section"> 
					<table width="720" cellspacing="0" cellpadding="10" class="list"> 
						<tr style="background-color:#d9d8d8; font-size:14px; font-weight:bold;"> 
							<td width="150">User</td> 
							<td width="150">Corp.</td> 
							<td width="90">Director</td> 
							<td width="90">Manager</td> 
							<td width="90">Logistics</td> 
							<td width="90">Active</td> 
							<td width="60"></td> 
						</tr>
						<tr class="gray">
							<td>demo</td>
							<td>Dreddit</td>
							<td><input type="checkbox" name="" id="right" /></td>
							<td><input type="checkbox" name="" id="right" /></td>
							<td><input type="checkbox" name="" id="right" /></td>
							<td><input type="checkbox" name="" id="right" /></td>
							<td style="text-align:center;"><a href="user.php?id=8">View</a></td>
						</tr>
					</table>
				</div>

	</div>
</div>
</body>
</html>