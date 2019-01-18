<?php
final class user {
	public $stmt;
	public function __construct($stmt) {
		$this->stmt = $stmt;
	}
		
	public function getUserinfo($column, $username) {
		$query = $this->stmt->query('SELECT ' . $column . ' FROM users WHERE username = "' . $username . '"');
		$userinfo = $query->fetch_assoc();
		return($userinfo[$column]);
	}
	
	public function getUserTransid($transid, $username) {
		$query = $this->stmt->query('SELECT ID FROM cms_payments WHERE username = "' . $username . '" AND transid ="' . $transid . '" ');
		$results = $query->num_rows;
		return($results);
	}
	
	
	public function getUsername() {
		return $_SESSION['USERNAME'];
	}
}

$user = new user($stmt);
?>