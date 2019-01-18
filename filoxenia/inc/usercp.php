<?php include "inc/current_site.php"; ?>

		<section class="container">
		 <div class="row">
			<?php
				echo "<p>Welcome, <b>" . $_SESSION['USERNAME'] . "</b>!</p>";
				$credits = $user->getUserinfo("credits", $_SESSION['USERNAME']);
				echo "<b>Credits:</b> " . number_format($credits, 2, $eco['KOMMA'], '.')  . " " . $eco['CURRENCY'];
			?>
		</div>
        
        <?php include_once "inc/user_overview.php" ?>		
		<?php include_once "inc/credits.php" ?>
		<?php include "inc/ticketsys_user.php"; ?>
		<?php include "inc/mytickets.php"; ?>
		<?php include "inc/myrequests.php"; ?>
		<?php include "inc/mypayments.php"; ?>