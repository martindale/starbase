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
	$html = new Template("admin.php");

	# include_once("html/admin.php");
}

?>
<?php include_once("html/header.php"); ?>

	<div class="clear"></div>

	<div class="content">

		<div class="notice info">Registration key (expires in <?php echo(60 - date("i")); ?>m): <code><?php echo md5(date("dmyH").$setting["secret"].$_SERVER["SERVER_NAME"]) ?></code><br />
		This key is required to register, only give it to people you trust. New accounts must be activated before they can be used.</div>

		<div class="section" id="settings">
			<h2>Settings</h2>
		</div>

		<div class="line"></div>

		<div class="section form">
			<form id="form" name="form" method="post" action="">

				<p><strong>Corporations</strong> (comma seperated)<br /><input type="text" name="corps" id="corps" placeholder="Corps." tabindex="1" /> <input name="submit" type="submit" id="submit" value="Update &raquo;" tabindex="2" /></p>

			</form>
		</div>

		<div class="section" id="users">
			<h2>Users</h2>
		</div>

		<div class="line"></div>

				<div class="section"> 
					<table width="850" cellspacing="0" cellpadding="10" class="list"> 
						<tr style="background-color:#d9d8d8; font-size:14px; font-weight:bold;"> 
							<td width="170">User</td> 
							<td width="240">Corp.</td> 
							<td width="80">Director</td> 
							<td width="80">Manager</td> 
							<td width="160">Logistics</td> 
							<td width="60">Active</td> 
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