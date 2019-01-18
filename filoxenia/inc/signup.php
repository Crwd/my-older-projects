<?php include "inc/current_site.php"; ?>
<?php
if(isset($_POST["submit_register"])) {
	if($register->validate($_POST["name"],$_POST["email"],$_POST["password"])) {
		$register->execute();
	}
}
?>

        <section class="container">
            <form class="text-center" action="<?php echo $action_form;?>" method="post">
                <div class="row title">
                    <div class="small-12 column">
                        <h6>New Account</h6>
                    </div>
                </div>
				
				 <center style="color:#FF4F4F;margin-bottom:10px;">
						<?php
							if(!empty($register->errors)) {
								echo $register->errors[0];
							}
						?>
					</center>

                <div class="row">
                    <div class="large-4 large-centered column">
                        <input id="name" name="name" type="text" placeholder="Name">
                    </div>
                </div>

                <div class="row">
                    <div class="large-4 large-centered column">
                        <input id="email" name="email" type="text" placeholder="Email">
                    </div>
                </div>

                <div class="row">
                    <div class="large-4 large-centered column">
                        <input id="password" name="password" type="text" placeholder="Password">
                    </div>
                </div>

                <div class="row">
                    <div class="large-12 column">
                        <button name="submit_register" type="submit">Sign Up</button>
                    </div>
                </div>
            </form>

            <div class="row text-center">
                <div class="large-12 column">
                    <a href="?site=login">Already a member?</a>
                </div>
            </div>
        </section>