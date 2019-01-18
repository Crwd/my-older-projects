<?php
	if(!isset($_GET["id"])) {
		redirect_home();
	}
	$ticket_id = $_GET["id"];
	$ticket = $ticketsystem->showTicket($ticket_id);
	$answers = $ticketsystem->loadAnswers($ticket_id);
	
	if(isset($_POST["submit_answer"])) {
		$ticketsystem->createAnswer($ticket_id, $_POST["ticket_answer"]);
	}
	
	if(isset($_POST["close_ticket"])) {
		$ticketsystem->closeTicket($ticket_id);
	}
	
	if(isset($_POST["open_ticket"])) {
		$ticketsystem->openTicket($ticket_id);
	}
?>

<section class="container">
    <div class="row">
    	<b>Ticket-ID:</b> <?php echo $ticket["ID"]; ?><br>
        <b>Title:</b> <?php echo $ticket["title"]; ?><br>
        <b>Date:</b> <?php echo $ticket["date"]; ?><br>
        <b>Status:</b> <?php echo $ticket["status"]; ?><br><br>
        <b>Message:</b><br>
        <?php echo nl2br($ticket["message"]); ?>
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
        <hr><br>
        
       <center style="color:#FF4F4F;">
			<?php
                if(!empty($ticketsystem->errors["user"])) {
                    echo $ticketsystem->errors["user"][0];
                } 
            ?>
        </center>
        
        <center style="color:#669C5C;">
			<?php
                if(!empty($ticketsystem->success["user"])) {
                    echo $ticketsystem->success["user"][0];
                } 
            ?>
        </center>
        
        <form class="text-center" action="<?php echo $action_form . '&id=' . $ticket_id;?>" method="post">
            <textarea name="ticket_answer" style="width:400px; margin:0 auto;" placeholder="Message"></textarea><br>
            <div class="row">
                <div class="large-12 column">
                    <button name="submit_answer" type="submit">Reply</button>
                    
                    <?php if($ticket["status"] != "closed") { 
                    	 echo '<button name="close_ticket" type="submit">Close</button>';
					} else {
						echo '<button name="open_ticket" type="submit">Open</button>';
					}
                    ?>
                </div>
            </div>
        </form>
    </div>
</section>

