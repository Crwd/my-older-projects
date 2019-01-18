
<?php
$extendable = [1, 2, 3];
$error_domain = array();
$valid_states = array("active","inactive","in progress");
$headline = "Order";

if(isset($_GET["extend"])) {
	$item_id = $_GET["extend"];
	$item = $order->getProduct_id($_GET["extend"]);
	$type = $order->getCategory_user($_GET["extend"]);
	$headline = "Extension";
	if(!$order->exists_product($item_id)) {
		redirect_home();
	}
	
	if(!in_array($type, $extendable)) {
		redirect_home();
	}
	
	$bool = false;
	$username = $user->getUsername();
	$result = $stmt->query('SELECT status FROM user_products WHERE ID="' . $item_id . '" AND username="' . $username . '"');
	$state = $result->fetch_assoc();
	if($result->num_rows) {
		if(in_array($state["status"], $valid_states)) {
			$bool = true;
		}
	}
	
	if(!$bool) {
		redirect_home();
	}
	$action = "&extend=" . $_GET["extend"];
	$product_id = $order->getProduct_id($item);
} else {
	if(!isset($_GET["type"], $_GET["item"])) {
		redirect_home();
	} else {
		$type = $_GET["type"]; 
		$item = $_GET["item"];
		$action = "&type=" . $type . "&item=" . $item;
		if(!$order->exists_item($item)) {
			redirect_home();
		}
		if(!$order->exists_type($type)) {
			redirect_home();
		}
	}
}

if(isset($_POST["submit_next"])) {
	$days = $_POST["price_value"];
	$_SESSION["order"] = array();
	$_SESSION["order"]["item"] = $item;
	$_SESSION["order"]["type"] = $type;
	$_SESSION["order"]["days"] = $days;
	$_SESSION["order"]["domain"] = "";
	$_SESSION["order"]["domain_ext"] = "";
	$domain_level = "";
	
	if(isset($_GET["extend"])) {
		$_SESSION["order"]["product"] = $_GET["extend"];
	}
	
	$confirm = true; 

	if($order->isdomain($type)) {
		if(!isset($_GET["extend"])) {
			if(isset($_POST["domain_ext"])) {
				$_POST["domain_ext"] = str_replace('.','', $_POST["domain_ext"]);
				if($order->domain_exist($_POST["domain_ext"])) {
					$confirm = true;
					$_SESSION["order"]["domain"] = $_POST["domain"] . "." . $_POST["domain_ext"];
					$_SESSION["order"]["domain_ext"] = $_POST["domain_ext"];
					$domain_level = $_POST["domain_ext"];
				} else {
					$confirm = false;
					array_push($error_domain, "Invalid domain extension");
				}
			} else {
				$confirm = false;
				array_push($error_domain, "Choose a valid extension");
			}
		} else {
			$_SESSION["order"]["domain"] = $order->getProduct_domain($_GET["extend"]);
		}
	}
	
	$_SESSION["order"]["price"] = $order->calcprice($item, $days, $type, $domain_level);
	
	$credits = $user->getUserinfo("credits", $user->getUsername());
	
	if($_SESSION["order"]["price"] > $credits) {
		$confirm = false;
		array_push($error_domain, "You have not enough credits. <a href='" . $direction . "'><b>Add Credits</b></a>.");
	}
	
	if($confirm) {
		header('Location: ' . $direction . '?site=checkout');
	}
}
?>

<section class="container">
    <div class="row">
		<?php
        	echo "<strong>" . $headline . ":</strong> <br>" . $order->getName_item($item) . " (" . $order->getName_type($type) . ") <br>";
        ?>
  		<div id="price-range" style="background:#f27950;width:300px;margin-top:10px;"></div><br>

        <strong>Days:</strong><br>
        <div id="huge-value"><?php echo $order->getTime_min($type);?></div>
    </div>
</section>

<form action="<?php echo $action_form . $action?>" method="post">
	 <input type="range" value="<?php echo $order->getTime_min($type);?>" max="<?php echo $order->getTime_max($type);?>" min="<?php echo $order->getTime_min($type);?>" name="price_value" id="inv_time" style="display:none"/>
        
	<?php
		if(!empty($error_domain)) {
			echo '<div style="color:#FF4F4F;" class="row">';
            echo '<div class="large-12 column">';
			echo $error_domain[0];
			echo '</div>';
			echo '</div>';
		}
		
		if($order->isdomain($type) && !isset($_GET["extend"])) {
	?>
        <div class="row">
            <div class="large-12 column">
            	<span>Check <a href="https://instantdomainsearch.com/" target="_blank">here</a> if the domain is already registred.</span>
                <input type="text" style="width:300px;margin-top:0px;" name="domain" placeholder="domain name..">
                <select style="width:300px;margin-top:0px;" name="domain_ext">
                	<?php
						$extension = $stmt->query('SELECT * FROM cms_domains');
						$extensions = array();
						while($row = $extension->fetch_assoc()) {
							$extensions[] = $row;
						}
						
						foreach($extension as $ext) {
							echo '<option value=".' . $ext["extension"] . '">.' . $ext["extension"] . ' - ' . number_format($ext["price"], 2, $eco['KOMMA'], '.')  . " " . $eco['CURRENCY'] . '</option>';
						}
					?>
                </select>
            </div>
        </div>
    <?php
		}
	?>
    
    <div class="row">
        <div class="large-12 column">
            <button name="submit_next" type="submit">Next</button>
        </div>
    </div>
</form>

<?php 
if($order->getTime_min($type) != $order->getTime_max($type)) {
?>
	<script type="text/javascript">
		var skipSlider = document.getElementById('price-range');
		var bigValueSpan = document.getElementById('huge-value');
		
		noUiSlider.create(skipSlider, {
			connect: 'lower',
			range: {
				'min': <?php echo $order->getTime_min($type);?>,
				'33.33%': 90,
				'66.66%': 180,
				'max': <?php echo $order->getTime_max($type);?>
			},
			snap: true,
			start: [<?php echo $order->getTime_min($type);?>],
			
		});
		
		
		skipSlider.noUiSlider.on('update', function ( values, handle ) {
			document.getElementById("inv_time").value = Math.floor(values);
			bigValueSpan.innerHTML = Math.floor(values);
		});
    </script>

<?php
}
?>