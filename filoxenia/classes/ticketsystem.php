<?php
final class ticketsystem {
	public $stmt;
	public $errors = array();
	public $success = array();
	
	public function __construct ($stmt) {
		$this->stmt = $stmt;
	}
	
	public function createTicket($title, $msg) { 
		$title = trim(htmlspecialchars($this->stmt->real_escape_string($title)));
		$msg = trim(htmlspecialchars($this->stmt->real_escape_string($msg)));
		global $secure_login;
		if($secure_login->is_loggedin()) {
			global $user;
			$this->errors["user"] = array();
			$this->success["user"] = array();
			if(empty($title)) {
				array_push($this->errors["user"], "Choose a title");
			}
			
			if(empty($msg)) {
				array_push($this->errors["user"], "Type a message");
			}
			
			$username = $user->getUsername();
			
			if(empty($this->errors["user"])) {
				array_push($this->success["user"], "Ticket was sent successfully");
				$this->stmt->query('INSERT INTO cms_tickets (`username`,`email`,`title`,`message`) VALUES ("' . $username . '","' . $user->getUserinfo("email", $username) . '","' . $title . '","' . $msg . '")');
			}
		}
	}
	
	public function loadTickets() {
		global $user;
		$username = $user->getUsername();
		$tickets = array();
		$query = $this->stmt->query('SELECT * FROM cms_tickets WHERE username="' . $username . '" ORDER BY ID DESC');
		
		while($t = $query->fetch_assoc()) {
			$tickets[] = $t;
		}
		
		return $tickets;
	}
	
	public function loadAnswers($id) {
		global $user;
		$username = $user->getUsername();
		$answers = array();
		$query = $this->stmt->query('SELECT * FROM cms_ticket_answers WHERE ticket_id="' . $id . '" ORDER BY ID ASC');
		
		while($t = $query->fetch_assoc()) {
			$answers[] = $t;
		}
		
		return $answers;
	}
	
	public function showTicket($id) {
		global $user;
		$username = $user->getUsername();
		$query = $this->stmt->query('SELECT * FROM cms_tickets WHERE ID="' . $id . '" AND username="' . $username . '" ORDER BY ID DESC');
		$ticket = $query->fetch_assoc();
		
		if($query->num_rows) {
			return $ticket;
		} else {
			redirect_home();
		}
	}
	
	public function countAnswers($id) {
		$results = $this->stmt->query('SELECT * FROM cms_ticket_answers WHERE ticket_id="' . $id . '"')->num_rows;
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
				$this->stmt->query('INSERT INTO cms_ticket_answers(`answer_id`,`ticket_id`,`username`,`message`) VALUES ("' . $answer_id . '","' . $id . '","' . $username . '","' . $msg . '")');			$this->stmt->query('UPDATE cms_tickets SET status="open" WHERE ID="' . $id . '"');
				header('Location:' . $action_form . '&id=' . $id);
			}
		}
		
	}
	
	public function closeTicket($id) {
		global $user;
		$username = $user->getUsername();
		$status = "closed";
		$this->stmt->query('UPDATE cms_tickets SET status="' . $status . '" WHERE ID="' . $id . '" AND username="' . $username . '"');
		$success = array();
		
		$success["TITLE"] = "Ticket closed";
		$success["TEXT"] = "Ticket successfully closed!";
		$success["STYLE"] = "success";
		
		redirect_home($success);
	}
	
}

$ticketsystem = new ticketsystem($stmt);
?>