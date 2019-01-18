<div class="row title">
    <div class="small-12 column">
        <h6>Overview</h6>
    </div>
</div>


<?php
// LIST PRODUCTS
$all_cats = $stmt->query('SELECT * FROM cms_product_cats');
$all_cats = $usercp->fetch_sql($all_cats);

$username = $user->getUsername();
foreach ($all_cats as $cat) {
	if($usercp->has_category($cat["ID"],$username)) {
		echo '<center><div class="title">
					<h4>' . $cat["name"] . '</h4>
			</div>';
		$all_products = $stmt->query('SELECT * FROM user_products WHERE type="' . $cat["ID"] . '" AND username="' . $username . '" ORDER BY ID DESC');
		$all_products = $usercp->fetch_sql($all_products);
		$expire_txt = "Waiting for activation";
		foreach($all_products as $prod) {
			echo "<u>" . $prod["product_name"] . " #" . $prod["ID"] . "</u><br>";
			echo "Status: " . $prod["status"] . "<br>";
			if($prod["status"] != "ordered") {
				$expire = floor(($prod["expire"] - time()) / 86400);
				$expire_txt = $expire . " Days";
				if($prod["expire"]  < time()) {
					$expire_txt = "expired";
				}
				echo "Expire: " . $expire_txt . "<br>";
				
				if($prod["status"] == "active" or $prod["status"] == "in progress") {
					$details = $usercp->load_product($prod["ID"]);
					if($order->isdomain($cat["ID"])) {
						$nameserver = explode(";",$details["nameserver"]);
						
						echo "Domain: " . $details["domain"] . "<br>";
						
						foreach($nameserver as $index=>$ns) {
							if(!empty($ns)) {
								echo "ns" . $index . ": " .  $ns . "<br>";
							}
						}
						
						if($prod["status"] != "in progress") {
							echo '<a href="?site=request&nameserver=' . $prod["ID"] . '">Change nameserver</a><br>';
						}
					} elseif($order->isvps($cat["ID"])) {
						echo "IP: " . $prod["ip"] . "<br>"; 
						echo "OS: " . $prod["os"] . "<br>";
						echo "<a target='_blank' href='" . $details['panel'] . "'>Panel</a><br>";
						if($prod["status"] != "in progress") {
							echo "<a href='?site=request&reinstall=" . $prod["ID"] . "'>Reinstall</a><br>";
						}
					} elseif($order->isweb($cat["ID"])) {
						$login = explode(";",$details["userlogin"]);
						echo "User: " . $login[0] . "<br>"; 
						echo "Password: " . $login[1] . "<br>"; 
						echo "<a target='_blank' href='" . $details['panel'] . "'>Panel</a><br>";
						
						if($prod["status"] != "in progress") {
							echo "<a href='?site=request&reset=" . $prod["ID"] . "'>Reset</a><br>";
						}
					}
				}
				if($prod["status"] != "disabled") {
					echo "<a href='?site=order&extend=" . $prod["ID"] . "'>Extend</a><br>";
				}
			} else {
				echo "Expire: " . $expire_txt . "<br>";
			}
		}
		echo "</center><br>";
	}
}
?>
