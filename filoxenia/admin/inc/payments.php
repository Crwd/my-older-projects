<?php
	$allpay = $payments->loadPayments();
	$statuses = array("waiting","failed","paid", "all");
	
	if(isset($_POST["status_change"])) {
		$state = $_POST["status_change"];
		if(in_array($state, $statuses)) {
			$allpay = $payments->loadPayments($state);
		}
	}

	if(isset($_GET["action"],$_GET["id"])) {
		$id = $_GET["id"];
		$action = $_GET["action"];
		$payments->checkpayment($id, $action);
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

<?php if(!empty($allpay)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Method</th><th>Status</th><th>PIN</th><th>Value</th><th>Date</th><th>Action</th>
        </tr>
        <?php
			foreach($allpay as $pmt) {
				if($pmt["pin"] == "none") {
					$pmt["pin"] = "-";
				}
				echo '<tr>';
					echo '<td>' . $pmt["ID"] . '</td>' . '<td>' . $pmt["method"] . '</td>' . '<td>' . $pmt["state"] . '</td>' . '<td>' . $pmt["pin"] . '</td>' . '<td>' . $pmt["value"] . '</td>' . '<td>' . $pmt["date"] . '</td>'; 
					if($pmt["method"] != "PayPal") {
					echo '<td><a href="' . $action_form . '&action=paid&id=' . $pmt["ID"] . '"><i class="icon-ok"></i><span class="hidden-tablet"></span></a><a href="' . $action_form . '&action=failed&id=' . $pmt["ID"] . '"><i class="icon-remove"></i><span class="hidden-tablet"></span></a></td>';
					}
				echo '</tr>';
			}
		?>
    </tbody>
</table>
<?php 
} else {
	if(isset($_POST["status_change"])) {
		$select = $_POST["status_change"];
		echo "No " .  $select . " payments.";
	} else {
		echo "No payments.";
	}
}
?>

