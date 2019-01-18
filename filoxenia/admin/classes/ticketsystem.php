<?php
final class ticketsystem {
	public $stmt;
	public $errors = array();
	public $success = array();
	
	public function __construct ($stmt) {
		$this->stmt = $stmt;
	}
	
	public function loadTickets($status = "open") {
		$tickets = array();
		
		if($status == "all") {
			$query = $this->stmt->query('SELECT * FROM cms_tickets ORDER BY date DESC');
		} else {
			$query = $this->stmt->query('SELECT * FROM cms_tickets WHERE status="' . $status . '" ORDER BY date DESC');
		}
		
		while($t = $query->fetch_assoc()) {
			$tickets[] = $t;
		}
		
		return $tickets;
	}
	
	public function loadAnswers($id) {
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
		$query = $this->stmt->query('SELECT * FROM cms_tickets WHERE ID="' . $id . '" ORDER BY ID DESC');
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
				$this->stmt->query('INSERT INTO cms_ticket_answers(`answer_id`,`ticket_id`,`username`,`message`) VALUES ("' . $answer_id . '","' . $id . '","' . $username . '","' . $msg . '")');			$this->stmt->query('UPDATE cms_tickets SET status="answered" WHERE ID="' . $id . '"');
				header('Location:' . $action_form . '&id=' . $id);
			}
		}
		
	}
	
	public function closeTicket($id) {
		global $user;
		$status = "closed";
		$this->stmt->query('UPDATE cms_tickets SET status="' . $status . '" WHERE ID="' . $id . '"');
		$success = array();
		
		$success["TITLE"] = "Ticket closed";
		$success["TEXT"] = "Ticket successfully closed!";
		$success["STYLE"] = "success";
		
		redirect_home($success);
	}
	
	public function openTicket($id) {
		global $user;
		$status = "closed";
		$this->stmt->query('UPDATE cms_tickets SET status="open" WHERE ID="' . $id . '"');
		$success = array();
		
		$success["TITLE"] = "Ticket opened";
		$success["TEXT"] = "Ticket successfully opened!";
		$success["STYLE"] = "success";
		
		redirect_home($success);
	}
	
}

$ticketsystem = new ticketsystem($stmt);
?>