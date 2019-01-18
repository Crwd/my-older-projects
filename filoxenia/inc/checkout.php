<?php
if(!isset($_SESSION["order"]["item"],$_SESSION["order"]["type"],$_SESSION["order"]["days"],$_SESSION["order"]["price"])) {
	redirect_home();
}

$item = $_SESSION["order"]["item"];
$type = $_SESSION["order"]["type"];
$days = $_SESSION["order"]["days"];
$price = $_SESSION["order"]["price"];
$domain = $_SESSION["order"]["domain"];
$domain_lvl = $_SESSION["order"]["domain_ext"];

$extension = "";
$extending = false;

$username = $user->getUsername();
$balance = $user->getUserinfo("credits", $username);
$rental_expire = time() + ($days * 24 * 60 * 60);

if(isset($_SESSION["order"]["product"])) {
	$product = $_SESSION["order"]["product"];
	$extension = "(extension)";
	$extending = true;
	$expiration = $stmt->query('SELECT expire FROM user_products WHERE ID="' . $product . '" AND username="' . $username . '"')->fetch_assoc();
	
	if(time() > $expiration["expire"]) {
		$rental_expire = ($days * 24 * 60 * 60) + time();
	} else {
		$rental_expire = ($days * 24 * 60 * 60) + $expiration["expire"];
	}
}

if($price != $order->calcprice($item, $days, $type, $domain_lvl)) {
	redirect_home();
}

if(!$checkout->enough_money($balance, $price)) {
	redirect_home();
}

if(isset($_POST["submit_order"])) {
	$text = "ordered";
	
	if($extending) {
		$item = $product;
		$text = "extended";
	}
	
	$checkout->order($username, $balance, $type, $item, $days, $price, $rental_expire, $domain, $extending);
	$alerting = array();
	$alerting["TITLE"] = "New order";
	$alerting["TEXT"] = "Successfully " . $text . "!";
	$alerting["STYLE"] = "success";
	$_SESSION["order"] = "";
	redirect_home($alerting);
}
?>
<section class="container">
    <div class="row">
    	<h2>Order Confirmation</h2>  
        <table id="confirmtable">
            <tbody>
                <tr>
                    <td><b>Product:</b></td>
                    <td><?php echo $order->getName_type($type); ?></td>
                </tr>
                <tr>
                    <td><b>Package:</b></td>
                    <td><?php echo $order->getName_item($item) . " " . $extension; ?></td>
                </tr>
                <?php
				if($order->isdomain($type)) {
					echo '<tr>
                    <td><b>Domain:</b></td>
                    <td>' . $domain . '</td>
                	</tr>';
				}
				?>
                <tr>
                    <td><b>Rental time:</b></td>
                    <td><?php echo $days . " days (until " . date("M jS Y, H:i", $rental_expire). ")"; ?></td>
                </tr>
                <tr>
                    <td><b>Total cost:</b></td>
                    <td><?php echo number_format($price, 2, $eco['KOMMA'], '.') . " " . $eco['CURRENCY']; ?></td>
                </tr>
                <tr>
                    <td><b>Your credits before:</b></td>
                    <td><?php echo number_format($balance, 2, $eco['KOMMA'], '.') . " " . $eco['CURRENCY']; ?></td>
                </tr>
                <tr>
                    <td><b>Your credits after:</b></td>
                    <td><?php echo number_format($balance - $price, 2, $eco['KOMMA'], '.') . " " . $eco['CURRENCY']; ?></td>
                </tr>
            </tbody>
        </table>
        <form action="<?php echo $action_form; ?>" method="post">
        	<div class="row">
                <div class="large-12 column">
                    <button name="submit_order" type="submit">Order now</button>
                </div>
            </div>
        </form>
    </div>
</section>

