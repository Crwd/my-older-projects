<?php

final class requests {
	public $custom_errors = array();
	public function __construct() {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function loadRequests($status = "in progress") {
		$requests = array();
		if($status == "all") {
			$query = $this->stmt->query('SELECT * FROM cms_requests ORDER BY date DESC');
		} else {
			$query = $this->stmt->query('SELECT * FROM cms_requests WHERE status="' . $status . '" ORDER BY date DESC');
		}
		
		while($r = $query->fetch_assoc()) {
			$requests[] = $r;
		}
		
		return $requests;
	}
	
	public function exists_item($item) {
		$query = $this->stmt->query('SELECT * FROM cms_products WHERE ID="' . $item . '"');
		if($query->num_rows) {
			return true;
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
	
	public function finish($id) {
		global $action_form;
		$order_id = $this->stmt->query('SELECT order_id FROM cms_requests WHERE ID="'. $id . '"')->fetch_assoc();
		$this->stmt->query('UPDATE cms_requests SET status="finished" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET status="active" WHERE order_id="' . $order_id["order_id"] . '"');

		header('Location:' . $action_form . '&id=' . $id);
	}
	
	public function open($id) {
		global $action_form;
		$order_id = $this->stmt->query('SELECT order_id FROM cms_requests WHERE ID="'. $id . '"')->fetch_assoc();
		$this->stmt->query('UPDATE cms_requests SET status="in progress" WHERE ID="' . $id . '"');
		$this->stmt->query('UPDATE user_products SET status="in progress" WHERE order_id="' . $order_id["order_id"] . '"');
		header('Location:' . $action_form . '&id=' . $id);
	}
	
	public function showRequest($id) {
		global $user;
		$username = $user->getUsername();
		$query = $this->stmt->query('SELECT * FROM cms_requests WHERE ID="' . $id . '" ORDER BY ID DESC');
		$req = $query->fetch_assoc();
		
		if($query->num_rows) {
			return $req;
		} else {
			redirect_home();
		}
	}
	
	public function loadAnswers($id) {
		global $user;
		$username = $user->getUsername();
		$answers = array();
		$query = $this->stmt->query('SELECT * FROM cms_request_answers WHERE request_id="' . $id . '" ORDER BY ID ASC');
		
		while($t = $query->fetch_assoc()) {
			$answers[] = $t;
		}
		
		return $answers;
	}
	
	public function countAnswers($id) {
		$results = $this->stmt->query('SELECT * FROM cms_request_answers WHERE request_id="' . $id . '"')->num_rows;
		return $results;
	}
	
	public function createAnswer($id, $msg) {
		$msg = trim(htmlspecialchars($this->stmt->real_escape_string($msg)));
		$answer_id = $this->countAnswers($id);
		$answer_id++;
		
		global $action_form;
		global $secure_login;
		if($secure_login->is_loggedin()) {
			global $user;
			$this->errors["user"] = array();
			$this->success["user"] = array();
			
			if(empty($msg)) {
				array_push($this->errors["user"], "Type a message");
			}
			
			$username = $user->getUsername();
			
			if(empty($this->errors["user"])) {
				array_push($this->success["user"], "Answer was sent successfully");
				$this->stmt->query('INSERT INTO cms_request_answers(`answer_id`,`request_id`,`username`,`message`) VALUES ("' . $answer_id . '","' . $id . '","' . $username . '","' . $msg . '")');			$this->stmt->query('UPDATE cms_requests SET status="answered" WHERE ID="' . $id . '"');
				header('Location:' . $action_form . '&id=' . $id);
			}
		}
		
	}
	
	public function loadOS() {
		$query = $this->stmt->query('SELECT name FROM cms_vpsos');
		$os = array();
		while($row = $query->fetch_assoc()) {
			$os[] = $row["name"];
		}
		
		return $os;
	}
	
	public function load_nameserver($id) {
		$nameserver = $this->stmt->query('SELECT nameserver FROM cms_requests WHERE ID="' . $id . '"')->fetch_assoc();
		$nameserver = explode(";",$nameserver["nameserver"]);
		return $nameserver;
		
	}
	
	public function change_os($os, $id) {
		$os =  htmlspecialchars($this->stmt->real_escape_string($os));
		$order_id = $this->stmt->query('SELECT order_id FROM cms_requests WHERE ID="' . $id . '"')->fetch_assoc();
		$this->stmt->query('UPDATE user_products SET os="' . $os .'" WHERE order_id="' . $order_id["order_id"] . '"');
		echo "Changed successfully!";
	}
	
	public function change_ns($ns, $id) {
		$nameserver = htmlspecialchars($this->stmt->real_escape_string(implode(";", $ns)));
		$order_id = $this->stmt->query('SELECT order_id FROM cms_requests WHERE ID="' . $id . '"')->fetch_assoc();
		$this->stmt->query('UPDATE user_products SET nameserver="' . $nameserver .'" WHERE order_id="' . $order_id["order_id"] . '"');
		echo "Changed successfully!";
	}
}

$request = new requests;

?>