<?php
	$myrequest = new myrequest;
	$requests = $myrequest->loadRequests();
	
?>

<div class="row title">
	<div class="small-12 column">
		<h6>My Requests</h6>
	</div>
</div>

<center>
<?php if(!empty($requests)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Product</th><th>Type</th><th>Status</th><th>Date</th>
        </tr>
        <?php
			foreach($requests as $req) {
				echo '<tr>';
					echo '<td>' . $req["ID"] . '</td>' . '<td><a href="?site=myrequest&id=' . $req["ID"]  . '">' . $order->getName_item($req["product_id"]) . ' #' . $req["product_id"] . '</a></td>' . '<td>' . $req["type"] . '</td>' . '<td>' . $req["status"] . '</td>' . '<td>' . $req["date"] . '</td>';
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	echo "No requests.";
}
?>
</center>
