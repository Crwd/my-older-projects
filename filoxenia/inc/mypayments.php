<?php
	$payments = $mypayment->loadPayments();
	
?>

<div class="row title">
	<div class="small-12 column">
		<h6>My Payments</h6>
	</div>
</div>

<center>
<?php if(!empty($payments)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Method</th><th>Status</th><th>PIN</th><th>Value</th><th>Date</th>
        </tr>
        <?php
			foreach($payments as $pmt) {
				if($pmt["pin"] == "none") {
					$pmt["pin"] = "-";
				}
				echo '<tr>';
					echo '<td>' . $pmt["ID"] . '</td>' . '<td>' . $pmt["method"] . '</td>' . '<td>' . $pmt["state"] . '</td>' . '<td>' . $pmt["pin"] . '</td>' . '<td>' . $pmt["value"] . '</td>' . '<td>' . $pmt["date"] . '</td>';
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	echo "No payments.";
}
?>
</center>
