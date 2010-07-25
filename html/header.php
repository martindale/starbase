<!DOCTYPE html>
<html>
<head>
	<title>Starbase Tracker<?php echo $html->title(); ?></title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<link rel="stylesheet" type="text/css"  href="css/main.css" />
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
			<?php if ($user->right(1)): ?><li><a href="admin.php"><img src="img/wrench.png" />Admin</a></li><?php endif; ?>
		</ul>

			<div id="search">
				<form method="get" action="search.php">
					<input type="search" placeholder="search a system, pos, etc." name="q" required />
					<input type="submit" value="Search &raquo;" />
				</form>
			</div>
	</div>