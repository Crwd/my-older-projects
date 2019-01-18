<?php
if(isset($_POST["reg_username"])) {
		$username_ = $_POST["reg_username"];
	} else {
		$username_ = "";
	}
	
	if(isset($_POST["reg_email"])) {
		$mail_ = $_POST["reg_email"];
	} else {
		$mail_ = "";
	}

	final class registration {
		public $errors = array();
		public $username, $password, $mail;
		public function validate ($username, $email, $password) {
			global $stmt;
			global $lang;
			
			// Username
			if(!empty($username)) {
				$username = $stmt->real_escape_string(htmlspecialchars(trim($username)));
				$this->username = $username;
				$username_r = str_replace(" ", "",$username);
				
				if($username_r != $username) {
					array_push($this->errors, "No whitespaces in the username");
				}
				
				if (strlen($username) < 3) {
					array_push($this->errors, "Username is too short (at least 3 chars)");
				}
				
				if (strlen($username) > 25) {
					array_push($this->errors, "Username is too long (maximum 25 chars)");
				}
				
				$query = $stmt->query('SELECT * FROM users WHERE username = "' . $username . '"');
				$count = $query->num_rows;
				if($count > 0) {
					array_push($this->errors, "Username is already taken");
				}
			} else {
				array_push($this->errors, "Choose an username");
			}
						
			// Password
			if(isset($password)) {
				$password = $stmt->real_escape_string (htmlspecialchars(trim($password)));
				$this->password = $password;
				if(strlen($password) < 6) {
					array_push($this->errors, "Password is too short (at least 6 chars)");
				} else {
					if(strlen($password) > 35) {
						array_push($this->errors, "Password is too long (maximum 35 chars)");
					}
				}
			} else {
				array_push($this->errors, "Choose a password");
			}
			
			// Mail
			if(isset($email)) {
				$mail = $stmt->real_escape_string (htmlspecialchars(trim($email)));
				$this->mail = $mail;
				if(strlen($mail) > 100) {
					array_push($this->errors, "Email is too long");
				} else {
					if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
						$query = $stmt->query('SELECT * FROM users WHERE email = "' . $mail . '"');
						$count = $query->num_rows;
						if($count > 0) {
							array_push($this->errors, "Email is already taken");
						}
					} else {
						array_push($this->errors, "Email is invalid");
					}
				}
			} else {
				array_push($this->errors, "Choose an email");
			}
			
			if(empty($this->errors)) {
				return true;
			} 
			
			
			return false;
			
		}
		
		public function execute() {
			global $stmt;
	
			$username = $this->username;
			$password = $this->password;
			$mail = $this->mail;
			
			$expire = time() + (3600*5); // 5 STUNDEN
			$password = hash('sha224', md5($password));
			$ip = $_SERVER['REMOTE_ADDR'];
			$stmt->query('INSERT INTO `users` (`username`, `password`, `email`, `ip`) VALUES ("' . $username . '", "' . $password . '", "' . $mail . '", "' . $ip . '")');
			
			session_regenerate_id();
			$id = md5(session_id());
			$hash_id = hash('sha224', $id);
			
			$_SESSION['USERNAME'] = $username; 
			$_SESSION['SESSION_ID'] = $id . $expire; 
			$_SESSION['SECURE_ID'] = $hash_id . $expire;
			
			setcookie ("USERNAME", $username, $expire);
			setcookie ("SESSION_ID", $_SESSION['SESSION_ID'], $expire);
			setcookie ("SECURE_ID", $_SESSION['SECURE_ID'], $expire);
			
			$stmt->query('DELETE FROM cms_secure_login WHERE username ="' . $username . '"');
			$stmt->query('INSERT INTO `cms_secure_login`( `username`, `session_id`, `secure_id`, `expire`) VALUES ( "' . $username . '", "' . $_SESSION['SESSION_ID'] . '","' . $_SESSION['SECURE_ID'] . '","' . $expire . '")');
			
			header ('Location: http://' . $_SERVER["HTTP_HOST"]);
			
		}
	}
	
	$register = new registration;
	?>