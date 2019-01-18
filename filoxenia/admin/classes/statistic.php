<?php
final class statistic {
	public $stmt;
	
	public function __construct () {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function count_table($table) {
		$results = $this->stmt->query('SELECT * FROM ' . $table)->num_rows;
		return $results;
	}
	
	public function load_users($limit = "5") {
		$results = $this->stmt->query('SELECT * FROM users');
		$user = array();
		
		while($row = $results->fetch_assoc()) {
			$user[] = $row;
		}
		
		return $user;
	}
}

$stats = new statistic;
?>