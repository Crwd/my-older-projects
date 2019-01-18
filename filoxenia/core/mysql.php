<?php
/////////////////// OOP DEVELOP ////////////////////
$stmt;
final class database_connection {
	public static $error_mysqli = array();
	public $mysqli;
	public function __construct ($host, $user, $password, $db) {
		global $stmt;
		try {
			$this->mysqli = new mysqli ($host, $user, $password, $db);
			
			if ($this->mysqli->connect_error) {
				throw new Exception (mysqli_connect_error());
				exit();
			} else {
				$stmt = $this->mysqli;
				return $this->mysqli;
			}
		} catch(Exception $error) {
			array_push(database_connection::$error_mysqli, $error->getMessage());
		}
	}
}

/////////////////////////////////////////////////////




///////////////// DEFINE STATEMENT //////////////////

$mysqli_class = new database_connection($mysql_db['HOST'],$mysql_db['USER'],$mysql_db['PASSWORD'],$mysql_db['DBNAME']);

/////////////////////////////////////////////////////




/////////////////// ERROR REPORTING ////////////////////
if (!empty(database_connection::$error_mysqli)) {
	echo "<div style='width:95%;margin:0 auto 5% auto;%' class='alert alert-danger'>";
	echo "<strong>MySQLi Errors:</strong><br>";
	foreach(database_connection::$error_mysqli as $key_e) {
		echo $key_e . "<br>";
	}
	echo "</div>";
}
////////////////////////////////////////////////////////

?>