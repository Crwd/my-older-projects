<?php
	if(!isset($_GET["id"])) {
		redirect_home();
	}
	
	$myrequest = new myrequest;
	$request_id = $_GET["id"];
	$request = $myrequest->showRequest($request_id);
	$answers = $myrequest->loadAnswers($request_id);
	
	if(isset($_POST["submit_answer"])) {
		$myrequest->createAnswer($request_id, $_POST["request_answer"]);
	}

?>

<section class="container">
    <div class="row">
    	<b>Request-ID:</b> <?php echo $request["ID"]; ?><br>
        <b>Product:</b> <?php echo $order->getName_item($request["product_id"]); ?><br>
        <b>Type:</b> <?php echo $request["type"]; ?><br>
        <b>Date:</b> <?php echo $request["date"]; ?><br>
        <b>Status:</b> <?php echo $request["status"]; ?><br><br>
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
            <textarea name="request_answer" style="width:400px; margin:0 auto;" placeholder="Message"></textarea><br>
            <div class="row">
                <div class="large-12 column">
                    <button name="submit_answer" type="submit">Reply</button>
                </div>
            </div>
        </form>
    </div>
</section>

