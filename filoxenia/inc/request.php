<?php
$request = new request();
?>

<form action="<?php echo $action_form . '&' . $request->site . '=' . $_GET[$request->site];?>" method="post">
<?php

if(isset($_POST["submit_request"])) {
	if($request->site == "reinstall") {
		$request->create_request($_GET[$request->site],$_POST["vps_os"]);
	} elseif($request->site == "nameserver") {
		$nameserver_c = $request->get_nameserver($_GET[$request->site]);
		$nameserver = array();

		for($i=0;$i<=$nameserver_c;$i++) {
			if(!empty($_POST["ns" . $i])) {
				array_push($nameserver, $_POST["ns" . $i]);
			} else {
				array_push($request->errors, "Fill all fields for the nameserver!");
			}
		}
		
		if(empty($request->errors)) {
			$request->create_request($_GET[$request->site],$nameserver);
		}
	} else {
		$request->create_request($_GET[$request->site]);
	}
}

if(!empty($request->errors)) {
	$error_rep = $request->errors;
	echo '<center style="color:#FF4F4F;">';
	echo $error_rep[0];
	echo '</center>';
}

switch($request->site) {
	case "reset":
		?>
        <div class="row">
            <div class="large-12 column">
            	<br>
            	<span>Do you really want to <b>reset</b> your webspace ?</span><br>
                <button name="submit_request" type="submit">Reset</button>
            </div>
        </div>
        <?php
		break;
	case "nameserver":
		?>
        <div class="row">
            <div class="large-12 column">
            	<br>
                <span>Change the <b>nameserver</b> you want:</span><br>
            	<?php $request->draw_nameserver($_GET[$request->site]); ?>
                <button name="submit_request" type="submit">Change</button>
            </div>
        </div>
        <?php
		break;
	case "reinstall":
		?>
        <div class="row">
            <div class="large-12 column">
            	<br>
            	<span>Select new operating system of your VPS</span><br>
                <select name="vps_os">
                	<?php $request->draw_os(); ?>
                </select>
                <button name="submit_request" type="submit">Reinstall</button>
            </div>
        </div>
        <?php
		break;
}
	
?>
</form>
