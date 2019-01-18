<?php
final class Connection {

    static $error_mysqli = array();
    public $mysqli;

    public function __construct() {
        $this->mysqli = new mysqli(Config::DB_HOST, Config::DB_USERNAME, Config::DB_PASSWORD, Config::DB_DBNAME);
        try {
            if ($this->mysqli->connect_error) {
                throw new Exception(mysqli_connect_error());
                exit();
            }
        } catch (Exception $error) {
            array_push($error_mysqli, $error->getMessage());
        }
    }

    public function query($qry, $params = [], $partype = false) {
        $paramsGiven = [];
        $paramsReplace = [];

        foreach ($params as $param => $value) {
            $paramsGiven[] = ':' . $param;
            
            if($partype) {
                $paramsReplace[] = $this->mysqli->real_escape_string($value);
            } else {
                $paramsReplace[] = "'" . $this->mysqli->real_escape_string($value) . "'";
            }
        }

        $qryString = str_replace($paramsGiven, $paramsReplace, $qry);
        $query = $this->mysqli->query($qryString) or die($this->mysqli->error);
		
        return $query;
    }

    public function __destruct() {
        $this->mysqli->close();
    }

    public function getInsertId() {
        return $this->mysqli->insert_id;
    }
}