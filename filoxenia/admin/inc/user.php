
<?php
$all_user = $user->loadUsers();

if(isset($_POST["submit_search"])) {
	if(!empty($_POST["user_search"])) {
		$key = $stmt->real_escape_string(htmlspecialchars($_POST["user_search"]));
		$all_user = $user->loadUsers(true, $key);
	}
}

if(isset($_GET["action"])) {
	if(isset($_GET["user"])) {
		$id = $stmt->real_escape_string(htmlspecialchars($_GET["user"]));
		$action = $stmt->real_escape_string(htmlspecialchars($_GET["action"]));
		$user->changeUser($action, $id);
	}
}

?>

<form  action="<?php echo $action_form;?>" method="post" name="status">
	<input type="search" name="user_search" placeholder="username..">
  	<input type="submit" name="submit_search" value="search">
</form>

<?php if(!empty($all_user)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Username</th><th>Status</th><th>E-Mail</th><th>Credits</th><th>Action</th>
        </tr>
        <?php
			foreach($all_user as $acc) {
				if($acc["status"] == "active") {
					$action = "lock";
				} else {
					$action = "unlock";
				}
				echo '<tr>';
					echo '<td>' . $acc["ID"] . '</td>' . '<td>' . $acc["username"] . '</td>' . '<td>' . $acc["status"] . '</td>' . '<td>' . $acc["email"] . '</td>' . '<td>' . $acc["credits"] . '</td>'  . '<td><a href="' . $action_form . '&action=' . $action . '&user=' . $acc["ID"] . '"><i class="icon-' . $action . '"></i></a><a href="?site=userdata&user=' . $acc["ID"] . '"><i class="icon-pencil"></i></a>' . '</td>';
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	echo "No user(s) found.";
}
?>