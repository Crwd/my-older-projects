<?php
class user {
	public $stmt;
	public function __construct($stmt) {
		$this->stmt = $stmt;
	}
		
	public function getUserinfo($column, $username) {
		$query = $this->stmt->query('SELECT ' . $column . ' FROM users WHERE username = "' . $username . '"');
		$userinfo = $query->fetch_assoc();
		
		if($column == "*") {
			return($userinfo);
		}
		return($userinfo[$column]);
	}
	
	public function getUserinfo_id($column, $id) {
		$query = $this->stmt->query('SELECT ' . $column . ' FROM users WHERE ID = "' . $id . '"');
		$userinfo = $query->fetch_assoc();
		
		if($column == "*") {
			return($userinfo);
		}
		return($userinfo[$column]);
	}
	
	public function getUserTransid($transid, $username) {
		$query = $this->stmt->query('SELECT ID FROM cms_payments WHERE username = "' . $username . '" AND transid ="' . $transid . '" ');
		$results = $query->num_rows;
		return($results);
	}
	
	public function changeUser($action, $id) {
		if($action == "lock") {
			$this->stmt->query('UPDATE users SET status = "banned" WHERE ID="' . $id . '"');
		} else {
			$this->stmt->query('UPDATE users SET status = "active" WHERE ID="' . $id . '"');
		}
		header( "Location: ?site=user" );
	}
	
	public function loadUsers($search = false, $key = "") {
		$users = array();
		$query = $this->stmt->query('SELECT * FROM users ORDER BY ID DESC');
		if($search) {
			$query = $this->stmt->query('SELECT * FROM users WHERE username LIKE "%' . $key . '%" ORDER BY ID DESC');
		}
		
		while($p = $query->fetch_assoc()) {
			$users[] = $p;
		}
		
		return $users;
	}
	
	public function getUsername() {
		return $_SESSION['USERNAME'];
	}
}

final class admin extends user {
	static $rank;
	public $stmt;
	public function __construct() {
		global $stmt;
		$this->stmt = $stmt;
		
		if(!$this->is_admin()) {
			redirect_start();
		}
		
		admin::$rank = $this->getUserinfo("rank",$this->getUsername());
	}
	
	public function is_admin() {
		if($this->getUserinfo("rank",$this->getUsername()) > 0) {
			return true;
		}
		return false;
	}
	
	// NEW FUNCTIONS
	
	public $errors = [];
	public $success = [];
	
	public function exists_user($id) {
		$query = $this->stmt->query('SELECT * FROM users WHERE ID = "' . $id . '"')->num_rows;
		if($query) {
			return true;
		}
		
		return false;
	}
	
	public function count_ranks() {
		$count = $this->stmt->query('SELECT * FROM admin_ranks')->num_rows;
		return $count;
	}
	
	public function change_userdata($id, $username, $email, $rank) {
		$username = trim(htmlspecialchars($this->stmt->real_escape_string($username)));
		$email = trim(htmlspecialchars($this->stmt->real_escape_string($email)));
		$rank = trim(htmlspecialchars($this->stmt->real_escape_string($rank)));
		
		$all_ranks = $this->count_ranks();
		
		if(empty($username)) {
			array_push($this->errors, "Fill in a username");
		}
		
		if(empty($email)) {
			array_push($this->errors, "Fill in an email");
		}
		
		if(!empty($rank)) {
			if($rank < 0) {
				array_push($this->errors, "The rank has to be at least 0");
			}
			
			if($rank > $all_ranks) {
				array_push($this->errors, "The maximum rank is " . $all_ranks);
			}
		} else {
			array_push($this->errors, "Type a rank number in");
		}
		
		$username_db = $this->getUserinfo_id("*", $id);
		$exists_email = $this->stmt->query('SELECT * FROM users WHERE email = "' . $email . '"')->num_rows;
		$exists_username = $this->stmt->query('SELECT * FROM users WHERE username = "' . $username . '"')->num_rows;
		
		if($exists_username) {
			if($username_db["username"] != $username) {
				array_push($this->errors, "Username exists already!");
			}
		}
		
		if($exists_email) {
			if($username_db["email"] != $email) {
				array_push($this->errors, "Email exists already!");
			}
		}
		
		if(empty($this->errors)) {
			if($username_db["username"] == $this->getUsername()) {
				$_SESSION["USERNAME"] = $username;
			}
			
			$this->stmt->query('UPDATE cms_orders SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_payments SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_requests SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_request_answers SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_secure_login SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_tickets SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE cms_ticket_answers SET username = "' . $username . '" WHERE username="' . $username_db["username"] . '"');
			$this->stmt->query('UPDATE user_products SET username = "' . $username . '" WHERE user_id="' . $id . '"');
			
			$this->stmt->query('UPDATE users SET username = "' . $username . '", email = "' . $email . '", rank = "' . $rank . '" WHERE ID="' . $id . '"');
			array_push($this->success, "Userdata successfully changed!");
		}
	}
	
	public function change_password($id, $password, $password_re) {
		$password = hash('sha224', md5(trim(htmlspecialchars($this->stmt->real_escape_string($password)))));
		$password_re = hash('sha224', md5(trim(htmlspecialchars($this->stmt->real_escape_string($password_re)))));
		
		if(!empty($_POST["password"]) && !empty($_POST["password_re"])) {
			if($password != $password_re) {
				array_push($this->errors, "Passwords do not match!");
			}
		} else {
			array_push($this->errors, "Fill the fields of the passwords");
		}
		
		if(empty($this->errors)) {
			$this->stmt->query('UPDATE users SET password = "' . $password . '" WHERE ID="' . $id . '"');
			array_push($this->success, "Password successfully changed!");
		}
	}
}

$user = new user($stmt);
$admin = new admin();
?>