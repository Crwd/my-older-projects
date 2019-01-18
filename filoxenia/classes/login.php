<?php
	session_start();
	final class secure_login {
		public $stmt;
		public $cookie_time;
		public $captcha = false;
		public function __construct($stmt) {
			$this->cookie_time = time() + (3600*5); // 5 STUNDEN
			$this->stmt = $stmt;
			
			if(!isset($_SESSION['SESSION_ID'])) {
				session_regenerate_id();
				$_SESSION['SESSION_ID'] = session_id() . "" . $this->cookie_time;
			}
					
			if(!isset($_SESSION['SECURE_ID'])) {
				$id = md5(session_id());
				$hash_id = hash('sha224', $id);
				$_SESSION['SECURE_ID'] = $hash_id . "" . $this->cookie_time;
			}
			
			
			if(!isset($_SESSION['FORGOT_PW'])) {
				$_SESSION['FORGOT_PW'] = false;
			}
			
			if(!isset($_SESSION['FORGOT_PW_EXP'])) {
				$_SESSION['FORGOT_PW_EXP'] = 0;
			}
		}
		
		public function is_loggedin() {
			if (isset($_SESSION['USERNAME'])) {
				$query = $this->stmt->query('SELECT * FROM cms_secure_login WHERE username = "' . $_SESSION['USERNAME'] . '"');
				$result = $query->num_rows;
				if($result != 0) {
					if(isset($_SESSION['SECURE_ID'])) {
						if(isset($_SESSION['SESSION_ID'])) {
							$userdb = $query->fetch_assoc();
							$sec_id = $_SESSION['SECURE_ID'];
							$sess_id = $_SESSION['SESSION_ID'];
							if (($sec_id == $userdb['secure_id']) && ($sess_id == $userdb['session_id'])) {
								if (time() < $userdb['expire']) {
									$expire = $this->cookie_time;
									
									if(!isset($_COOKIE['USERNAME'])) {
										setcookie('USERNAME', $_SESSION['USERNAME'], $expire);
										setcookie('SESSION_ID', $sess_id, $expire);
										setcookie('SECURE_ID', $sec_id, $expire);
									}
									return true;
								} else {
									$this->logout();
								}
							}
						}
					}
				}
			}
			elseif(isset($_COOKIE['USERNAME'])) {
				$query = $this->stmt->query('SELECT * FROM cms_secure_login WHERE username = "' . $_COOKIE['USERNAME'] . '"');
				$result = $query->num_rows;
				if($result != 0) {
					if(isset($_COOKIE['SECURE_ID'])) {
						if(isset($_COOKIE['SESSION_ID'])) {
							$userdb = $query->fetch_assoc();
							$sec_id = $_COOKIE['SECURE_ID'];
							$sess_id = $_COOKIE['SESSION_ID'];
							if (($sec_id == $userdb['secure_id']) && ($sess_id == $userdb['session_id'])) {
								if (time() < $userdb['expire']) {
									$_SESSION['USERNAME'] = $_COOKIE['USERNAME'];
									$_SESSION['SECURE_ID'] = $_COOKIE['SECURE_ID'];
									$_SESSION['SESSION_ID'] = $_COOKIE['SESSION_ID'];
									return true;
								} else {
									$this->logout();
								}
							}
						}
					}
				}
			}
			return false;
		}
		
		public $errors = array();
		
		public function execute($email, $password) {
			
			// VALIDATION
			if(!empty($email)) {
				$email = $this->stmt->real_escape_string(htmlspecialchars(trim($email)));
				$query = $this->stmt->query('SELECT * FROM users WHERE email = "' . $email . '"');
				$results = $query->num_rows;
				
				if($results == 0) {
					array_push($this->errors, "Email or password is wrong");
					return false;
				}
				
			} else {
				array_push($this->errors, "Email or password is wrong");
				return false;
			}
			
			if(!empty($password)) {
				$password = hash('sha224', md5(htmlspecialchars(trim($this->stmt->real_escape_string($password)))));
				$query = $this->stmt->query('SELECT * FROM users WHERE email = "' . $email . '" AND password ="' . $password . '"');
				$results = $query->num_rows;
				
				if($results == 0) {
					array_push($this->errors, "Email or password is wrong");
					return false;
				}
				
			} else {
				array_push($this->errors, "Email or password is wrong");
				return false;
			}
			
		
			if(empty($this->errors)) {
				$expire = $this->cookie_time;
				$userSQL = $this->stmt->query('SELECT * FROM users WHERE email = "' . $email . '" AND password ="' . $password . '"');
				$userdb = $userSQL->fetch_assoc();
				session_regenerate_id();
				$_SESSION['USERNAME'] = $userdb['username'];
				$_SESSION['SESSION_ID'] = session_id() . "" . $expire;
				$id = md5(session_id());
				$hash_id = hash('sha224', $id);
				$_SESSION['SECURE_ID'] = $hash_id . "" . $expire;
				
				$sess_id = $_SESSION['SESSION_ID'];
				$sec_id = $_SESSION['SECURE_ID'];
				
				setcookie('USERNAME', $userdb['username'], $expire);
				setcookie('SESSION_ID', $sess_id, $expire);
				setcookie('SECURE_ID', $sec_id, $expire);
				
				$this->stmt->query('UPDATE users SET ip = "' . $_SERVER['REMOTE_ADDR'] . '" WHERE email ="' . $email . '"');
				$this->stmt->query('DELETE FROM cms_secure_login WHERE username ="' . $userdb['username'] . '"');
				$this->stmt->query('INSERT INTO `cms_secure_login`( `username`, `session_id`, `secure_id`, `expire`) VALUES ( "' . $userdb['username'] . '", "' . $sess_id . '","' . $sec_id . '","' . $expire . '")');
		
				return true;
			} else {
				return false;
			}
			
		}

		public function logout() {
			setcookie ("USERNAME", "", time()-64);
			setcookie ("SESSION_ID", "", time()-64);
			setcookie ("SECURE_ID", "", time()-64);
			
			$_SESSION['USERNAME'] = ""; 
			$_SESSION['SESSION_ID'] = ""; 
			$_SESSION['SECURE_ID'] = "";
			
			session_destroy();
		}
	}
	
	$secure_login = new secure_login($stmt);
	


	
?>
