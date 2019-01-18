<?php
	if(!isset($_GET["id"])) {
		redirect_home();
	}
	$product_id = $_GET["id"];
	$product_c = new products;
	
	$product = $product_c->showProduct($product_id);
?>

<?php
if(isset($_POST["inactive_prod"])) {
	if($product["status"] != "inactive") {
		$product_c->change_status("inactive", $product["ID"]);
	}
}

if(isset($_POST["disable_prod"])) {
	if($product["status"] != "disabled") {
		$product_c->change_status("disabled", $product["ID"]);
	}
}

if(isset($_POST["active_prod"])) {
	if($product["status"] != "active") {
		$product_c->change_status("active", $product["ID"]);
	}
}

if(isset($_POST["progress_prod"])) {
	if($product["status"] != "in progress") {
		$product_c->change_status("in progress", $product["ID"]);
	}
}

if(isset($_POST["delete_prod"])) {
	$product_c->delete($product["ID"]);
}

if(isset($_POST["submit_changes"])) {
	$reset_exp = false;
	if($product_c->isdomain($product["type"])) {
		$nameserver = array();
		array_push($nameserver, $_POST["ns"]);
		$product_c->change_domain($product["ID"], $nameserver);
	}
	
	if($product_c->isweb($product["type"])) {
		if(empty($_POST["username"])) {
			$_POST["username"] = "0";
		}
		
		if(empty($_POST["password"])) {
			$_POST["password"] = "0";
		}
		
		$login = array($_POST["username"], $_POST["password"]);
		$panel = $_POST["panel"];
		$product_c->change_web($product["ID"], $login, $panel);
	}
	
	if($product_c->isvps($product["type"])) {
		if(empty($_POST["username"])) {
			$_POST["username"] = "0";
		}
		
		if(empty($_POST["password"])) {
			$_POST["password"] = "0";
		}
		
		$login = array($_POST["username"], $_POST["password"]);
		$panel = $_POST["panel"];
		$os = $_POST["vps_os"];
		$ip = $_POST["ip"];
		$product_c->change_vps($product["ID"], $login, $ip, $os, $panel);
	}
	
	if($product["status"] = "ordered") {
		$reset_exp = true;
	}
	$product_c->change_product($product["ID"], $reset_exp);
}
?>

<section class="container">
    <div class="row">
    	<b>Product-ID:</b> <?php echo $product["ID"]; ?><br>
        <b>Product:</b> <?php echo $product["product_name"]; ?><br>
        <b>Type:</b> <?php echo $product_c->getName_item($product["type"]); ?><br>
        <b>Status:</b> <?php echo $product["status"]; ?><br><br>
        <b>Username (ID):</b> <?php echo $product["username"] . ' (' . $product["user_id"] . ')'; ?><br>
        <b>Order-Date:</b> <?php echo $product["order_date"]; ?><br>
        <b>Expires:</b> <?php echo date("d.j.Y h:i", $product["expire"]); ?><br><br>
        <?php
			if($product_c->isvps($product["type"])) {
				echo '<b>IP:</b> ' . $product["ip"] . '<br><br>';
				echo '<b>OS:</b> ' . $product["os"] . '<br><br>';
				echo '<b>Userlogin:</b> ' . $product["userlogin"] . '<br>';
				echo '<b>Panel:</b> ' . $product["panel"] . '<br>';
			}
			
			if($product_c->isdomain($product["type"])) {
				echo '<b>Domain:</b> ' . $product["domain"] . '<br>';
				echo '<b>Nameserver:</b> ' . $product["nameserver"] . '<br><br>';
			}
			
			if($product_c->isweb($product["type"])) {
				echo '<b>Userlogin:</b> ' . $product["userlogin"] . '<br>';
        		echo '<b>Panel:</b> ' . $product["panel"] . '<br>';
			}
		?>
        
        <hr><br>
        
        <center style="color:#669C5C;">
			<?php
                if(!empty($product_c->success)) {
                    echo $product_c->success[0];
                } 
            ?>
        </center>
        
         <form class="text-center" action="<?php echo $action_form . '&id=' . $product_id;?>" method="post">
			 <?php
			 	if($product_c->isweb($product["type"])) {
					$l_userlogin = explode(";",$product["userlogin"]);
					
					
					if(!isset($l_userlogin[1])) {
						$l_userlogin[1] = "0";
					}
					
					echo '<b>Username: </b><br>';
					echo '<input type="text" name="username" value="' . $l_userlogin[0] . '"><br>';
					
					echo '<b>Password: </b><br>';
					echo '<input type="text" name="password" value="' . $l_userlogin[1] . '"><br>';
					
					echo '<b>Panel Link: </b><br>';
					echo '<input type="text" name="panel" value="' . $product["panel"] . '"><br>';
				}
				
				if($product_c->isdomain($product["type"])) {
					$l_nameserver = explode(";",$product["nameserver"]);
					echo '<b>Nameserver: </b><br>';
					echo '<input type="text" name="ns" value="' . $product["nameserver"] . '"><br>';
				}
				
				if($product_c->isvps($product["type"])) {
					$l_userlogin = explode(";",$product["userlogin"]);
					
					
					if(!isset($l_userlogin[1])) {
						$l_userlogin[1] = "0";
					}
					
					echo '<b>Username: </b><br>';
					echo '<input type="text" name="username" value="' . $l_userlogin[0] . '"><br>';
					
					echo '<b>Password: </b><br>';
					echo '<input type="text" name="password" value="' . $l_userlogin[1] . '"><br>';
					
					echo '<b>IP: </b><br>';
					echo '<input type="text" name="ip" value="' . $product["ip"] . '"><br>';
					
					echo '<b>Panel Link: </b><br>';
					echo '<input type="text" name="panel" value="' . $product["panel"] . '"><br>';
					
					echo '<b>OS: </b><br>';
					echo '<select name="vps_os">';
                		$product_c->draw_os($product["ID"]);
             	    echo '</select><br>';
				}
				
				echo '<input type="submit" name="submit_changes" value="save"><br>';
             ?>
         </form>
        
        <form class="text-center" action="<?php echo $action_form . '&id=' . $product_id;?>" method="post">
            <div class="row">
                <div class="large-12 column">
                    
                    <?php 
						if($product["status"] != "inactive") {
							echo '<button name="inactive_prod" type="submit">Set inactive</button>';
						}
						
						if($product["status"] != "disabled") {
							echo '<button name="disable_prod" type="submit">Disable</button>';
						}
						
						if($product["status"] != "active") {
							echo '<button name="active_prod" type="submit">Set active</button>';
						}
						
						if($product["status"] != "in progress") {
							echo '<button name="progress_prod" type="submit">Set in progress</button>';
						}
						echo '<button name="delete_prod" type="submit">Delete</button>';
                    ?>
                </div>
            </div>
        </form>
    </div>
</section>

