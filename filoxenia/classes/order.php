<?php
final class order {
	public $stmt;
	public function __construct($stmt) {
		$this->stmt = $stmt;
	}
	
	public function exists_item($item) {
		$query = $this->stmt->query('SELECT * FROM cms_products WHERE ID="' . $item . '"');
		if($query->num_rows) {
			return true;
		}
		return false;
	}
	
	public function exists_type($type) {
		$query = $this->stmt->query('SELECT * FROM cms_product_cats WHERE ID="' . $type . '"');
		if($query->num_rows) {
			return true;
		}
		return false;
	}
	
	public function getProduct_id($item) {
		global $user;
		$username = $user->getUsername();
		$result = $this->stmt->query('SELECT product_id FROM user_products WHERE ID="' . $item . '"')->fetch_assoc();
		return $result["product_id"];
	}
	
	public function getProduct_domain($item) {
		global $user;
		$username = $user->getUsername();
		$result = $this->stmt->query('SELECT domain FROM user_products WHERE ID="' . $item . '" AND username="' . $username . '"')->fetch_assoc();
		return $result["domain"];
	}
	
	public function exists_product($id) {
		global $user;
		$username = $user->getUsername();
		$result = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $id . '"');
		if($result->num_rows) {
			return true;
		}
		return false;
	}
	
	public function getCategory_user($item) {
		
		$query = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $item . '"')->fetch_assoc();
		$type = $query["type"];
		
		if($this->exists_type($type)) {
			return $type;
		}
		return false;
	}
	
	public function getName_item($id) {
		if($this->exists_item($id)) {
			$query = $this->stmt->query('SELECT name FROM cms_products WHERE ID="' . $id . '"');
			$name = $query->fetch_assoc();
			return $name["name"];
		}
		return false;
	}
	
	public function getName_type($id) {
		if($this->exists_type($id)) {
			$query = $this->stmt->query('SELECT name FROM cms_product_cats WHERE ID="' . $id . '"');
			$name = $query->fetch_assoc();
			return $name["name"];
		}
		return false;
	}
	
	public function getTime_max($type) {
		if($this->exists_type($type)) {
			$query = $this->stmt->query('SELECT max_time FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
			$max_time = $query["max_time"];
			return $max_time;
		}
		return false;
	}
	
	public function getTime_min($type) {
		if($this->exists_type($type)) {
			$query = $this->stmt->query('SELECT min_time FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
			$min_time = $query["min_time"];
			return $min_time;
		}
		return false;
	}
	
	public function isdomain($type) {
		if($this->exists_type($type)) {
			$query = $this->stmt->query('SELECT isdomain FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
			return $query["isdomain"];
		}
		return false;
	}
	
	public function isvps($type) {
		if($this->exists_type($type)) {
			$query = $this->stmt->query('SELECT isvps FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
			return $query["isvps"];
		}
		return false;
	}
	
	public function isweb($type) {
		if($this->exists_type($type)) {
			$query = $this->stmt->query('SELECT isweb FROM cms_product_cats WHERE ID ="' . $type . '"')->fetch_assoc();
			return $query["isweb"];
		}
		return false;
	}
	
	public function calcprice($item, $days, $type, $ext = "", $extending = false) {
		if($extending) {
			$extending = "ext_price";
		} else {
			$extending = "price";
		}

		if($this->exists_item($item)) {
			$query = $this->stmt->query('SELECT ' . $extending . ' FROM cms_products WHERE ID ="' . $item . '"')->fetch_assoc();
			
			if($this->isdomain($type)) {
				$query = $this->stmt->query('SELECT ' . $extending . ' FROM cms_domains WHERE extension ="' . $ext . '"')->fetch_assoc();
			}
			
			$factor = $query[$extending] / $this->getTime_min($type);
			$price = $factor * $days;
			
			return $price;
		}
		return false;
	}
	
	public function isdomain_avb($domain) {
		@$data = dns_get_record($domain);
		if (is_array($data)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function domain_exist($ext) {
		$query = $this->stmt->query('SELECT * FROM cms_domains WHERE extension="' . $ext . '"');
		if($query->num_rows) {
			return true;
		}
		return false;
	}
}

final class checkout {
	public $stmt;
	public function __construct($stmt) {
		$this->stmt = $stmt;
	}
	
	public function enough_money($balance, $price) {
		if($price > $balance) {
			return false;
		}
		return true;
	}
	
	public function order($username, $balance, $type, $item, $days, $price, $rental_expire, $domain, $extend) {
		global $user;
		global $order;
		$new_balance = ($balance - $price);
		$state = "ordered";
		$user_id = $user->getUserinfo("ID", $username);
		$username = htmlspecialchars($this->stmt->real_escape_string($username));
		$type = $type;
		$product_id = $item;
		$product_name = $order->getName_item($item);
		$expire = $rental_expire;
		$domain = trim(htmlspecialchars($this->stmt->real_escape_string($domain)));
		if(!$order->isdomain($type)) {
			$domain = 0;
		}
		
		$this->stmt->query('UPDATE users SET credits="' . $new_balance . '" WHERE ID="' . $user_id . '"');
		
		$order_id = uniqid();
		
		if($extend) {
			$status_current = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $item . '"')->fetch_assoc();
			$this->stmt->query('UPDATE user_products SET expire="' . $rental_expire . '" WHERE ID="' . $item . '"');
			
			if ($status_current["status"] == "inactive") {
				$this->stmt->query('UPDATE user_products SET status="ordered" WHERE ID="' . $item . '"');
			}
			
		} else {
		$this->stmt->query('INSERT INTO user_products (`user_id`,`type`,`product_id`,`product_name`,`status`,`expire`,`username`,`domain`,`order_id`) VALUES ("' . $user_id . '","' . $type . '","' . $product_id . '","' . $product_name . '","' . $state . '","' . $expire . '","' . $username  . '","' . $domain  . '","' . $order_id  . '")');
	
		$this->stmt->query('INSERT INTO cms_orders (`username`,`user_id`,`lenght`,`order_id`) VALUES ("' . $username . '","' . $user_id . '","' . $days . '","' . $order_id . '")');
		}
	}
}

$checkout = new checkout($stmt);
$order = new order($stmt);

?>
