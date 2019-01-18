<?php
	if(!isset($_GET["id"])) {
		redirect_home();
	}
	
	$myrequest = $request;
	$request_id = $_GET["id"];
	$requesting = $myrequest->showRequest($request_id);
	$answers = $myrequest->loadAnswers($request_id);
	
	if(isset($_POST["submit_answer"])) {
		$request->createAnswer($request_id, $_POST["request_answer"]);
	}
	
	if(isset($_POST["submit_finish"])) {
		$request->finish($request_id);
	}
	
	if(isset($_POST["submit_open"])) {
		$request->open($request_id);
	}
	
	if(isset($_POST["submit_os"])) {
		if(isset($_POST["change_os"])) {
			$valid_os = $request->loadOS();
			if(in_array($_POST["change_os"], $valid_os)) {
				$request->change_os($_POST["change_os"], $request_id);
			}
		}
	}
	
	if(isset($_POST["submit_ns"])) {
		$all_ns = $request->load_nameserver($request_id);
		$nameserver = array();
		array_push($nameserver, $_POST["ns"]);
		
		if(empty($myrequest->custom_errors)) {
			$request->change_ns($nameserver, $request_id);
		}
	}

?>

<section class="container">
    <div class="row">
    	<b>Request-ID:</b> <?php echo $requesting["ID"]; ?><br>
        <b>Product:</b> <?php echo $request->getName_item($requesting["product_id"]); ?><br>
        <b>Type:</b> <?php echo $requesting["type"]; ?><br>
        <b>Date:</b> <?php echo $requesting["date"]; ?><br>
        <b>Status:</b> <?php echo $requesting["status"]; ?><br><br>
        <hr><br>
        
         <?php
			foreach($answers as $reply) {
				echo '<b>Answer: #</b>' . $reply["answer_id"] . '<br>';
				echo '<b>Username:</b>' . $reply["username"] . '<br>';
				echo '<b>Message:</b><br>';
				echo nl2br($reply["message"]);
				echo '<br><br>';
			}
		?>
        
        <center style="color:#FF4F4F;">
			<?php
                if(!empty($myrequest->custom_errors)) {
                    echo $myrequest->custom_errors[0];
                } 
            ?>
        </center>
        
       <center style="color:#FF4F4F;">
			<?php
                if(!empty($myrequest->errors["user"])) {
                    echo $myrequest->errors["user"][0];
                } 
            ?>
        </center>
        
        <center style="color:#669C5C;">
			<?php
                if(!empty($myrequest->success["user"])) {
                    echo $myrequest->success["user"][0];
                } 
            ?>
        </center>
        
        <form class="text-center" action="<?php echo $action_form . '&id=' . $request_id;?>" method="post">
        	 <?php
				switch($requesting["type"]) {
					case "reinstall":
						$all_os = $request->loadOS();
						echo '<b>Requested OS:</b> ' . $requesting["os"] . '<br>';
						echo '<select name="change_os">';
						foreach($all_os as $os) {
							$sel = "";
							if($requesting["os"] == $os) {
								$sel = "selected";
							}
							echo '<option value="' . $os . '"' . $sel . '>' . $os . '</option>';
						}
						echo '</select><br>';
						echo '<button name="submit_os" type="submit">Change OS</button><br><br>';
						break;
						
					case "nameserver":
						$all_ns = $request->load_nameserver($request_id);
						echo '<b>Requested nameserver:</b><br>';
						foreach($all_ns as $ns) {
							echo '- ' . $ns . '<br>';
						}
						
						$ns_input = implode(";", $all_ns);
						
						echo '<input type="text" name="ns" value="' . $ns_input . '"><br>';
						
						echo '<button name="submit_ns" type="submit">Change nameserver</button><br><br>';
						break;
					
				}
			?>
            <textarea name="request_answer" style="width:400px; margin:0 auto;" placeholder="Message"></textarea><br>
            <div class="row">
                <div class="large-12 column">
                    <button name="submit_answer" type="submit">Reply</button>
                    
                    <?php
                    if($requesting["status"] == "finished") {
                    	echo '<button name="submit_open" type="submit">Open</button>';
                    } else {
						echo '<button name="submit_finish" type="submit">Finish</button>';
					}
					?>
                </div>
            </div>
        </form>
    </div>
</section>

