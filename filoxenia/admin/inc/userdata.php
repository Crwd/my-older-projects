<?php
$errors = array();

if(!isset($_GET["user"])) {
	redirect_home();
} else {
	if(!$admin->exists_user($_GET["user"])) {
		redirect_home();
	}
}

$all_ranks = $stmt->query('SELECT * FROM admin_ranks')->num_rows;
$user_info = $admin->getUserinfo_id("*", $_GET['user']);

if(isset($_POST["submit_userdata"])) {
	$admin->change_userdata($_GET["user"], $_POST["username"],$_POST["email"],$_POST["rank"]);
}

if(isset($_POST["submit_password"])) {
	$admin->change_password($_GET["user"], $_POST["password"], $_POST["password_re"]);
}
?>

<center style="color:#FF4F4F;">
	<?php
        if(!empty($admin->errors)) {
            echo $admin->errors[0];
        } 
    ?>
</center>

<center style="color:#669C5C;">
    <?php
        if(!empty($admin->success)) {
            echo $admin->success[0];
        } 
    ?>
</center>

<h2>Change general Userdata</h2>
<form action="<?php echo $action_form . "&user=" . $_GET["user"];?>" method="post">
	Username:<br>
    <input type="text" name="username" value="<?php echo $user_info['username']; ?>"><br>
    
	E-Mail:<br>
    <input type="email" name="email" value="<?php echo $user_info['email']; ?>"><br>
     
    Rank:<br>
    <input type="number" min="0" max="<?php echo $all_ranks;?>" name="rank" value="<?php echo $user_info['rank']; ?>"><br>
  
	<input type="submit" value="Change Userdata" name="submit_userdata">
</form>

<hr>

<h2>Change Password</h2>
<form action="<?php echo $action_form . "&user=" . $_GET["user"];?>" method="post">
	New Password:<br>
	<input type="text" name="password" placeholder="Passwort"><br>
    <input type="text" name="password_re" placeholder="Passwort wiederholen"><br>
	<input type="submit" value="Change Password" name="submit_password">
</form>