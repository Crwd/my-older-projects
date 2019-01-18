<?php
$tickets = $ticketsystem->loadTickets();
$statuses = array("open","closed","all","answered");
if(isset($_POST["status_change"])) {
	$state = $_POST["status_change"];
	if(in_array($state, $statuses)) {
		$tickets = $ticketsystem->loadTickets($state);
	}
}


?>
<form  action="<?php echo $action_form;?>" method="post" name="status">
	<select  onchange="this.form.submit();" name="status_change">
        <?php
			foreach($statuses as $option) {
				$selected = "";
				if($_POST["status_change"] == $option) {
					$selected = "selected";
				}
				echo '<option value="' . $option . '"' . $selected .'>' . $option . '</option>';
			}
		?>
    </select>
</form>

<?php if(!empty($tickets)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Ticket</th><th>Status</th><th>Username</th><th>Date</th>
        </tr>
        <?php
			foreach($tickets as $ticket) {
				echo '<tr>';
					echo '<td>' . $ticket["ID"] . '</td>' . '<td><a href="?site=ticket&id=' . $ticket["ID"]  . '">' . $ticket["title"] . '</a></td>' . '<td>' . $ticket["status"] . '</td>' . '<td>' . $ticket["username"] . '</td>'  . '<td>' . $ticket["date"] . '</td>';
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	if(isset($_POST["status_change"])) {
		$select = $_POST["status_change"];
	} else {
		$select = "open";
	}
	echo "No " .  $select . " tickets.";
}
?>