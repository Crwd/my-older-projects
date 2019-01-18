<?php
final class payment {
	
	public $stmt;
	public $value;
	public $errors = array();
	public $success = array();
	
	public function __construct ($stmt) {
		$this->stmt = $stmt;
	}
	
	public function empty_ () {
		array_push($this->errors, "Choose a payment method");
	}
	
	public function send_error ($error) {
		array_push($this->errors, $error);
	}
	
	public function send_success ($info) {
		array_push($this->success, $info);
	}
	
	public function check_method($method, $value, $pin) {
		$this->value = trim(htmlspecialchars($this->stmt->real_escape_string($value)));
		$method = trim(htmlspecialchars($this->stmt->real_escape_string($method)));
		$pin = trim(htmlspecialchars($this->stmt->real_escape_string($pin)));
		if(!empty($value)) {
			if($method == "pp") {
				$this->paypal();
			} elseif($method == "psc") {
				if(!empty($pin)) {
					$this->paysafecard($pin);
				} else {
					array_push($this->errors, "Please type the PaySafecard PIN");
				}
			} else {
				array_push($this->errors, "Invalid payment method");
			}
		} else {
			array_push($this->errors, "Please type a value");
		}
	}
	
	public function paysafecard($pin) {
		global $user;
		if(strlen($pin) < 16) {
			array_push($this->errors, "A PaySafecard PIN has 16 numbers");
		} else {
			if(is_numeric($this->value)) {
				$this->value = abs($this->value);

				if($this->value < 1) {
					array_push($this->errors, "The value needs to be at least 1");
				} else {
					$username = $user->getUsername();
					$email = $user->getUserinfo("email", $username);
					$method = "PaySafecard";
					$pin = $pin;
					$userid = $user->getUserinfo("ID", $username);
					
					array_push($this->success, "Successfully sent! Please wait for confirmation of a supporter.");
					
					$this->stmt->query('INSERT INTO `cms_payments` (`username`,`email`,`method`,`pin`,`value`,`userid`) VALUES ("' . $username . '", "' . $email . '", "' . $method . '", "' . $pin . '", "' . $this->value . '","' . $userid . '")');
				}
			} else {
				array_push($this->errors, "Invalid value");
			}
		}
	}
	
	public function paypal() {
		global $user;
		global $direction;
		
		if(is_numeric($this->value)) {
			$this->value = htmlspecialchars($this->stmt->real_escape_string(abs($this->value)));

			if($this->value < 1) {
				array_push($this->errors, "The value needs to be at least 1");
			} else {
				
				$username = $user->getUsername();
				$email = $user->getUserinfo("email", $username);
				$userid = $user->getUserinfo("ID", $username);
				$method = "PayPal";
				$transid = md5($username . time() . uniqid());
				$this->stmt->query('INSERT INTO `cms_payments` (`username`,`email`,`method`,`transid`,`value`,`userid`) VALUES ("' . $username . '", "' . $email . '", "' . $method . '", "' . $transid . '", "' . $this->value . '", "' . $userid . '")');
				
				header("Location: ?site=payment&value=" . $this->value . "&transid=" . $transid);
				
			}
		} else {
			array_push($this->errors, "Invalid value");
		}
}
	
}

final class mypayment {
	public function __construct () {
		global $stmt;
		$this->stmt = $stmt;
	}
	
	public function loadPayments() {
		global $user;
		$username = $user->getUsername();
		$pmts = array();
		$query = $this->stmt->query('SELECT * FROM cms_payments WHERE username="' . $username . '" ORDER BY ID DESC');
		
		while($r = $query->fetch_assoc()) {
			$pmts[] = $r;
		}
		
		return $pmts;
	}
	
}

$mypayment = new mypayment();
$payment = new payment($stmt);

?>

