<?php
final class usercp {
	public $stmt;
	public function __construct($stmt) {
		$this->stmt = $stmt;
	}
	
	public function fetch_sql($sql) {
		$fetch = array();
		while($row = $sql->fetch_assoc()) {
			$fetch[] = $row;
		}
		return $fetch;
	}
	
	public function has_category($type, $username) {
		$results = $this->stmt->query('SELECT * FROM user_products WHERE type="' . $type . '" AND username="' . $username . '"')->num_rows;
		if($results) {
			return true;
		}
		return false;
	}
	
	public function load_product($id) {
		$product = $this->stmt->query('SELECT * FROM user_products WHERE ID="' . $id . '"')->fetch_assoc();
		return $product;
	}
}

$usercp = new usercp($stmt);
?>