<?php
	$tickets = $ticketsystem->loadTickets();
?>

<div class="row title">
	<div class="small-12 column">
		<h6>My Tickets</h6>
	</div>
</div>

<center>
<?php if(!empty($tickets)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Ticket</th><th>Status</th><th>Date</th>
        </tr>
        <?php
			foreach($tickets as $ticket) {
				echo '<tr>';
					echo '<td>' . $ticket["ID"] . '</td>' . '<td><a href="?site=ticket&id=' . $ticket["ID"]  . '">' . $ticket["title"] . '</a></td>' . '<td>' . $ticket["status"] . '</td>' . '<td>' . $ticket["date"] . '</td>';
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	echo "No tickets.";
}
?>
</center>
