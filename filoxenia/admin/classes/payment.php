<?php
final class payments {
	public function __construct () {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function loadPayments($status = "all") {
		$pmts = array();
		
		if($status != "all") {
			$query = $this->stmt->query('SELECT * FROM cms_payments WHERE state="' . $status . '" ORDER BY ID DESC');
		} else {
			$query = $this->stmt->query('SELECT * FROM cms_payments ORDER BY ID DESC');
		}
		
		while($r = $query->fetch_assoc()) {
			$pmts[] = $r;
		}
		
		return $pmts;
	}
	
	public function checkpayment($id, $action) {
		$query = $this->stmt->query('SELECT * FROM cms_payments WHERE ID="' . $id . '" ORDER BY ID DESC')->fetch_assoc();
		$value = $query["value"];
		global $user;
		global $action_form;
		
		$action =  htmlspecialchars($this->stmt->real_escape_string($action));
		
		$userid = $query["userid"];
		$balance = $user->getUserinfo_id("credits",$userid) + $value;

		if($query["method"] != "PayPal") {
			$this->stmt->query('UPDATE cms_payments SET state="' . $action . '" WHERE ID="' . $id . '"');
			if($action == "paid") {
				if($query["state"] != "paid") {
					$this->stmt->query('UPDATE users SET credits="' . $balance . '" WHERE ID="' . $userid . '"');
				}
			}
		}
		
		header("Location: " . $action_form);
	
	}
}

$payments = new payments();


?>

