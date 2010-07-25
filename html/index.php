<?php if (!$safe) { header("HTTP/1.0 404 Not Found"); exit(); } ?>

<?php include_once("header.php"); ?>

	<div class="clear"></div>

	<div class="content">
		<div class="section">
			<h2>News</h2>
		</div>

		<div class="line"></div>

		<div class="notice info"><?php echo $notice["info"]; ?></div>

		<?php #$html->notice("bad", $notice); ?>

		<?php #$html->notice("warning", $notice); ?>


		<div class="section">
			<h2>Sad Starbases</h2>
		</div>

		<div class="line"></div>

		<?php $html->display_list($bad, $user->detail["name"]); ?>

		<div class="section">
			<h2>Happy Starbases</h2>
		</div>

		<div class="line"></div>

		<?php $html->display_list($good, $user->detail["name"]); ?>
	</div>
</div>
</body>
</html>