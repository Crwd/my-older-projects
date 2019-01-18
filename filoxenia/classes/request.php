<?php
final class request {
	public $stmt;
	public $user;
	public $order;
	public $site;
	public $valid_states = array("active");
	public $os = array();
	public $errors = array();
	
	public function __construct() {
		global $stmt;
		global $user;
		global $order;
		$this->stmt = $stmt;
		$this->user = $user;
		$this->order = $order;
		
		if(!isset($_GET["reset"]) && !isset($_GET["reinstall"]) && !isset($_GET["nameserver"])) {
			redirect_home();
		}
		
		$redirect = false; 
		
		if(isset($_GET["reset"])) {
			if($this->check_request($_GET["reset"])) {
				if($order->isweb($order->getCategory_user($_GET["reset"]))) {
					$redirect = $_GET["reset"];
					$site = "reset";
				}
			}
		} elseif(isset($_GET["reinstall"])) {
			if($this->check_request($_GET["reinstall"])) {
				if($order->isvps($order->getCategory_user($_GET["reinstall"]))) {
					$query = $this->stmt->query('SELECT name FROM cms_vpsos');
					while($system = $query->fetch_assoc()) {
						array_push($this->os, $system["name"]);
					}
								
					$redirect = $_GET["reinstall"];
					$site = "reinstall";
				}
			}
		} elseif(isset($_GET["nameserver"])) {
			if($this->check_request($_GET["nameserver"])) {
				if($order->isdomain($order->getCategory_user($_GET["nameserver"]))) {
					$redirect = $_GET["nameserver"];
					$site = "nameserver";
				}
			}
		}
		
		
		if(!$redirect) {
			redirect_home();
		}
		
		$this->site = $site;
	}
	
	public function check_request($req) {
		$username = $this->user->getUsername();
		$result = $this->stmt->query('SELECT status FROM user_products WHERE ID="' . $req . '" AND username="' . $username . '"');
		$state = $result->fetch_assoc();
		if($result->num_rows) {
			if(in_array($state["status"], $this->valid_states)) {
				return true;
			}
		}
		return false;
	}
	
	public function create_request($id, $extra = "") {
		$username = $this->user->getUsername();
		$product_id = $this->stmt->query('SELECT product_id FROM user_products WHERE ID="' . $id . '"')->fetch_assoc();
		$product_id = $product_id["product_id"];
		
		$order_id = $this->stmt->query('SELECT order_id FROM user_products WHERE ID="' . $id . '"')->fetch_assoc();
		$order_id = $order_id["order_id"];
		
		$product_type = $this->order->getCategory_user($id);
		$userid = $this->user->getUserinfo("ID", $username);
		$os = "";
		$type = $this->site;
		$nameserver = "";		
		if(is_array($extra)) {
			$nameserver = implode(";", $extra);
		} else {
			if(in_array($extra, $this->os)) {
				$os = $extra;
			} else {
				array_push($this->errors, "invalid os");
			}
		}
		
		$os = trim(htmlspecialchars($this->stmt->real_escape_string($os)));
		$nameserver = trim(htmlspecialchars($this->stmt->real_escape_string($nameserver)));
		
		$this->stmt->query('INSERT INTO cms_requests (`user_id`,`username`,`type`,`product_type`,`product_id`,`os`,`nameserver`,`order_id`) VALUES ("' . $userid . '","' . $username . '","' . $type . '","' . $product_type . '","' . $product_id . '","' . $os . '","' . $nameserver . '","' . $order_id . '")');
		
		$this->stmt->query('UPDATE user_products SET status="in progress" WHERE ID="' . $id . '"');
		
		$alerting = array();
		$alerting["TITLE"] = "New request";
		$alerting["TEXT"] = "Successfully requested!";
		$alerting["STYLE"] = "success";
		
		redirect_home($alerting);
	}
	
	public function draw_os() {
		foreach($this->os as $value) {
			echo '<option value="' . $value . '">' . $value . '</option>';
		}
	}
 	
	public function draw_nameserver($id) {
		$nameserver = $this->stmt->query('SELECT nameserver FROM user_products WHERE ID="' . $id . '" AND username ="' . $this->user->getUsername() . '"')->fetch_assoc();
		$nameserver = explode(";",$nameserver["nameserver"]);
		foreach($nameserver as $key=>$ns) {
			echo '<input type="text" name="ns' . $key . '" value="' . $ns . '"><br>';
		}
	}
	
	public function get_nameserver($id) {
		$nameserver = $this->stmt->query('SELECT nameserver FROM user_products WHERE ID="' . $id . '" AND username ="' . $this->user->getUsername() . '"')->fetch_assoc();
		$nameserver = explode(";",$nameserver["nameserver"]);
		$count = count($nameserver)-1;
		return $count;
	}
}

final class myrequest {
	public function __construct() {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function loadRequests() {
		global $user;
		$username = $user->getUsername();
		$requests = array();
		$query = $this->stmt->query('SELECT * FROM cms_requests WHERE username="' . $username . '" ORDER BY ID DESC');
		
		while($r = $query->fetch_assoc()) {
			$requests[] = $r;
		}
		
		return $requests;
	}
	
	public function showRequest($id) {
		global $user;
		$username = $user->getUsername();
		$query = $this->stmt->query('SELECT * FROM cms_requests WHERE ID="' . $id . '" AND username="' . $username . '" ORDER BY ID DESC');
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
				$this->stmt->query('INSERT INTO cms_request_answers(`answer_id`,`request_id`,`username`,`message`) VALUES ("' . $answer_id . '","' . $id . '","' . $username . '","' . $msg . '")');			$this->stmt->query('UPDATE cms_requests SET status="in progress" WHERE ID="' . $id . '"');
				header('Location:' . $action_form . '&id=' . $id);
			}
		}
		
	}
}

?>