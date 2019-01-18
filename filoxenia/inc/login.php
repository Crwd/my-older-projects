<?php include "inc/current_site.php"; ?>
<?php 
if(isset($_POST["submit_login"])) {
	if($secure_login->execute($_POST["email"],$_POST["password"])) {
		header("Location: " . $direction);
	}
}
?>

        <section class="container">
            <form action="<?php echo $action_form;?>" method="post">
                <div class="row title">
                    <div class="small-12 column">
                        <h6>Customer Login</h6>
                    </div>
                </div>
				
                    <center style="color:#FF4F4F;margin-bottom:10px;">
						<?php
							if(!empty($secure_login->errors)) {
								echo $secure_login->errors[0];
							}
						?>
					</center>

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
                    <div class="large-12 column text-center">
                        <button name="submit_login" type="submit">Log In</button>
                    </div>
                </div>
            </form>

            <p class="text-center">
                <!--<a href="forgot-password.html">Forgot Password?</a>-->
            </p>
        </section>