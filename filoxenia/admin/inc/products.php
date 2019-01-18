<?php
$products = new products;

$all_products = $products->loadProducts();
$statuses = array("ordered","expired","in progress","active","inactive","disabled","all");
if(isset($_POST["status_change"])) {
	$state = $_POST["status_change"];
	if(in_array($state, $statuses)) {
		$all_products = $products->loadProducts($state);
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

<?php if(!empty($all_products)) { ?>
<table>
	<tbody>
    	<tr>
        	<th>ID</th><th>Product</th><th>Type</th><th>Status</th><th>Username</th><th>Date</th>
        </tr>
        <?php
			foreach($all_products as $prod) {
				echo '<tr>';
					echo '<td>' . $prod["ID"] . '</td>' . '<td><a href="?site=product&id=' . $prod["ID"]  . '">' . $prod["product_name"] . '</a></td>' . '<td>' . $products->getName_item($prod["type"]) . '</td>' . '<td>' . $prod["status"] . '</td>' . '<td>' . $prod["username"] . '</td>'  . '<td>' . $prod["date_update"] . '</td>';
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
		$select = "ordered";
	}
	echo "No " .  $select . " products.";
}
?>