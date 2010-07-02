<?php

$safe = true;

include_once("app/settings.php");
include_once("app/classes.php");
include_once("app/functions.php");

_start("user.php");

# Database connection
$db = Database::start();

if (!$db->connect()) {
	header("Location: logout.php?msg=bad:Oh noes. We can't connect to the database.<br />Please try again later.");
	exit();
} else {
	$user = new User($_COOKIE["id"]);
}

if (isset($_POST["submit"]) && $user->right(1)) {
	$post = $db->clean($_POST);

	$rights = "";
	if (isset($post["admin"])) { $rights .= "1,"; } else { $rights .= "0,"; }
	if (isset($post["pos"])) { $rights .= "1,"; } else { $rights .= "0,"; }
	if (isset($post["mod"])) { $rights .= "1"; } else { $rights .= "0"; }
	
	if (isset($post["active"])) { $post["active"] = "true"; } else { $post["active"] = "false"; }

	$sql = "UPDATE `user` SET
			`user` = '" . $post["user"] . "',
			`rights` = '" . $rights . "',
			`corp` = '" . $post["corp"] . "',
			`active` = '" . $post["active"] . "'
			WHERE `id` = '" . $db->clean($_GET["id"]) . "'
			LIMIT 1";

	if ($db->query($sql)) {
		$msg[0] = "good";
		$msg[1] = "Details updated successfully";
	} else {
		$msg[0] = "bad";
		$msg[1] = "Dammit, something broke. Try again.";
	}
}

if (isset($_GET["id"])) {
	$data = new User($_GET["id"]);
}

# Start template system
$html = new Template("user.php");

?>
<!DOCTYPE html>
<html>
<head>
	<title>Starbase Tracker - Profile</title>
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
			<?php if ($user->right(2)): ?><li><a href="add.php"><img src="img/add.png" />New POS</a></li><?php endif; ?>
			<?php if ($user->right(1)): ?><li><a href="admin.php"><img src="img/wrench.png" />Admin</a></li><?php endif; ?>
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

		<?php if ($user->right(1) && isset($_GET["id"])): ?>
		<div class="section">
			<h2>Profile Settings for <?php echo $data->detail["name"]; ?></h2>
		</div>

		<div class="line"></div>

		<?php if (isset($msg)) { $html->notice($msg); } ?>

		<div class="section form">
			<form id="profile" name="profile" method="post" action="">
				<p><strong>Username</strong><br />
				<input type="text" name="user" id="user" value="<?php echo $data->detail["name"]; ?>" /></p>

				<p><strong>Corporation</strong><br />
					<select name="corp">
					<option>Dreddit</option>
					<option>Did I Just Do That</option>
					<option>Ars Ex Discordia</option>
					</select>
				</p>

				<p><strong>Permissions</strong><br />
				<label><input type="checkbox" name="admin" id="right" <?php if ($data->right(1)) { echo "checked"; } ?> /> Director</label><br />
				<label><input type="checkbox" name="pos" id="right" <?php if ($data->right(2)) { echo "checked"; } ?> /> Starbase Manager</label><br />
				<label><input type="checkbox" name="mod" id="right" <?php if ($data->right(3)) { echo "checked"; } ?> /> Starbase Logistics</label></p>

				<p><label><input type="checkbox" name="active" id="right" <?php if ($data->active()) { echo "checked"; } ?> /> Account active</label>

				<p><input name="submit" type="submit" id="submit"  tabindex="5" value="Update &raquo;" /></p>
			</form>
		</div>
		<?php else: ?>
		<div class="section">
			<h2>Your Profile</h2>
		</div>

		<div class="line"></div>

		<div class="section form">
			<form id="profile" name="profile" method="post" action="">
				<p><strong>Username</strong><br />
				<input type="text" name="name" id="name" value="<?php echo $user->detail["name"]; ?>" disabled /></p>

				<p><strong>Corporation</strong><br />
					<select name="corp" disabled>
					<option selected="selected">Dreddit</option>
					<option>Did I Just Do That</option>
					<option>Ars Ex Discordia</option>
					</select>
				</p>

				<p><strong>Permissions</strong><br />
				<label><input type="checkbox" name="rights" id="rights" <?php if ($user->right(1)) { echo "checked"; } ?> disabled /> Director</label><br />
				<label><input type="checkbox" name="rights" id="rights" <?php if ($user->right(2)) { echo "checked"; } ?> disabled /> Starbase Manager</label><br />
				<label><input type="checkbox" name="rights" id="rights" <?php if ($user->right(3)) { echo "checked"; } ?> disabled /> Starbase Logistics</label><br /></p>
			</form>
		</div>
		<?php endif; ?>
	</div>
</div>
</body>
</html>