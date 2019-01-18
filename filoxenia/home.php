		<?php
			
			if(isset($_POST["submit_credit"])) {
				if(!empty($_POST["pmethod"])) {
					$payment->check_method($_POST["pmethod"],$_POST["pvalue"],$_POST["pscpin"]);
				} else {
					$payment->empty_();
				}
			}
		?>
		
		<?php if(!$secure_login->is_loggedin()) { ?>
		<section class="hero">
            <div class="row hero-message">
                <div class="small-10 small-centered column text-center">
                    <h1>Powerful Hosting Solution</h1>
                    <h4>The more affordable approach to stable web hosting solutions. Inexpensive, reliable, and feature-rich!</h4>

                    <a href="?site=signup" class="button">SIGN UP FOR A PLAN</a>
                </div>
            </div>
        </section>
		
        <section class="container">
            <?php drawTitle("What we offer"); ?>


            <?php include "inc/features_first.php"; ?>

            <div class="row ">
                <div class="large-12 column text-center">
                    <a href="?site=features" class="button">View Full Features List</a>
                </div>
            </div>
        </section>

        <section class="container">
		<?php include "inc/shared_hosting.php"; ?>
        </section>
		<?php include "inc/customers.php"; } else {
			include "inc/usercp.php";
		 } ?>
		
