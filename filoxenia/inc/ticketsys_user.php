<?php
if(isset($_POST["submit_ticket"])) {
	if(isset($_POST["ticket_title"], $_POST["ticket_msg"])) {
		$ticketsystem->createTicket($_POST["ticket_title"], $_POST["ticket_msg"]);
	}
	
}
?>
<div class="row title">
	<div class="small-12 column">
		<h6>Create Ticket</h6>
	</div>
</div>

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

		
<form class="text-center" action="<?php echo $action_form;?>" method="post">
	<input style="width:400px; margin:0 auto;" type="text" placeholder="Title" name="ticket_title"><br>
	<textarea name="ticket_msg" style="width:400px; margin:0 auto;" placeholder="Message"></textarea><br>
	<div class="row">
		<div class="large-12 column">
			<button name="submit_ticket" type="submit">Send</button>
		</div>
	</div>
</form>