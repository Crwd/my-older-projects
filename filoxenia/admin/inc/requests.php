<?php
$requests = $request->loadRequests();
$statuses = array("in progress","finished","all","answered");
if(isset($_POST["status_change"])) {
	$state = $_POST["status_change"];
	if(in_array($state, $statuses)) {
		$requests = $request->loadRequests($state);
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

<?php if(!empty($requests)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Product</th><th>Type</th><th>Status</th><th>Username</th><th>Date</th>
        </tr>
        <?php
			foreach($requests as $req) {
				echo '<tr>';
					echo '<td>' . $req["ID"] . '</td>' . '<td><a href="?site=request&id=' . $req["ID"]  . '">' . $request->getName_item($req["product_id"]). '</a></td>' . '<td>' . $req["type"] . '</td>' . '<td>' . $req["status"] . '</td>' . '<td>' . $req["username"] . '</td>'  . '<td>' . $req["date"] . '</td>';
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
		$select = "in progress";
	}
	echo "No " .  $select . " requests.";
}
?>