<?php
final class products {
	public $stmt;
	public $errors = array();
	public $success = array();
	public $os = array();
	
	public function __construct () {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function loadProducts($status = "ordered") {
		$products = array();
		
		if($status == "all") {
			$query = $this->stmt->query('SELECT * FROM user_products ORDER BY date_update DESC');
		} elseif($status == "expired") {
			$query = $this->stmt->query('SELECT * FROM user_products WHERE expire < ' . time() . ' ORDER BY date_update DESC');
		} else {
			$query = $this->stmt->query('SELECT * FROM user_products WHERE status="' . $status . '" ORDER BY date_update DESC');
		}
		
		while($p = $query->fetch_assoc()) {
			$products[] = $p;
		}
		
		return $products;
	}
	
	public function getName_item($id) {
		$query = $this->stmt->query('SELECT name FROM cms_product_cats WHERE ID="' . $id . '"');
		$name = $query->fetch_assoc();
		return $name["name"];
	}
	
	public function isdomain($type) {
		$query = $this->stmt->query('SELECT isdomain FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
		return $query["isdomain"];
	}
	
	public function isvps($type) {
		$query = $this->stmt->query('SELECT isvps FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
		return $query["isvps"];
	}
	
	public function isweb($type) {
		$query = $this->stmt->query('SELECT isweb FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
		return $query["isweb"];
	}
	
	public function showProduct($id) {
		$query = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $id . '"  ORDER BY ID DESC');
		$product = $query->fetch_assoc();
		
		if($query->num_rows) {
			return $product;
		} else {
			redirect_home();
		}
	}
	
	public function change_status($status, $id) {
		$status = htmlspecialchars($this->stmt->real_escape_string($status));
		$cur_stat = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $id . '"')->fetch_assoc();
		$lenght = $this->stmt->query('SELECT * FROM cms_orders WHERE order_id="' . $cur_stat["order_id"] . '"')->fetch_assoc();
		$extend = time() + $lenght["lenght"] * 24 * 60 * 60;
		if($cur_stat["status"] == "ordered") {
			$this->stmt->query('UPDATE user_products SET expire="' . $extend . '" WHERE ID="' . $id . '"');
		}
		$this->stmt->query('UPDATE user_products SET status="' . $status . '" WHERE ID="' . $id . '"');
		array_push($this->success, "Successfully changed status");
		header( "refresh:2;url=?site=product&id=" . $id );
	}
	
	public function delete($id) {
		$this->stmt->query('DELETE FROM user_products WHERE ID="' . $id . '"');
		array_push($this->success, "Successfully deleted");
		header( "refresh:2;url=?site=products" );
	}
	
	public function draw_os($id) {
		$query = $this->stmt->query('SELECT name FROM cms_vpsos');
		$cur_os = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $id . '"')->fetch_assoc();
		
		while($system = $query->fetch_assoc()) {
			array_push($this->os, $system["name"]);
		}
				
		foreach($this->os as $value) {
			$sel = "";
			if($value == $cur_os["os"]) {
				$sel = "selected";
			}
			echo '<option value="' . $value . '" ' . $sel . '>' . $value . '</option>';
		}
	}
	
	public function change_domain($id, $nameserver) {
		$ns = htmlspecialchars($this->stmt->real_escape_string(implode(";", $nameserver)));
		$this->stmt->query('UPDATE user_products SET nameserver="' . $ns . '" WHERE ID="' . $id . '"');
	}
	
	public function change_web($id, $login, $panel) {
		$ul = htmlspecialchars($this->stmt->real_escape_string(implode(";", $login)));
		$panel = htmlspecialchars($this->stmt->real_escape_string($panel));
		$this->stmt->query('UPDATE user_products SET userlogin="' . $ul . '" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET panel="' . $panel . '" WHERE ID="' . $id . '"');
	}
	
	public function change_vps($id, $login, $ip, $os, $panel) {
		$ul = htmlspecialchars($this->stmt->real_escape_string(implode(";", $login)));
		$panel = htmlspecialchars($this->stmt->real_escape_string($panel));
		$ip = htmlspecialchars($this->stmt->real_escape_string($ip));
		$os = htmlspecialchars($this->stmt->real_escape_string($os));
		
		$this->stmt->query('UPDATE user_products SET userlogin="' . $ul . '" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET panel="' . $panel . '" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET os="' . $os . '" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET ip="' . $ip . '" WHERE ID="' . $id . '"');
	}
	
	public function change_product ($id, $reset) {
		if($reset) {
			$this->stmt->query('UPDATE user_products SET expire="' . time() . '" WHERE ID="' . $id . '"');
		}
		array_push($this->success, "Successfully changed");
		header( "refresh:2;url=?site=product&id=" . $id );
	}

}
?>