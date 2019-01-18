<?php
final class Quotes {
    private $connection;
    public function __construct() {
        $this->connection = new Connection();
    }
    
    public function getTopquote() {
        $today = time() - 3600 * 24;
        $result = $this->connection->query('SELECT * FROM quotes WHERE likes >= 10 AND time > :today ORDER BY likes DESC LIMIT 1', [
            "today" => $today
        ]);
        if($result->num_rows) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    public function getAllQuotes() {
        $query = $this->connection->query('SELECT * FROM quotes ORDER BY ID DESC');
        
        $quotes = [];
        while($row = $query->fetch_assoc()) {
            $quotes[] = $row;
        }
        
        return $quotes;
    }
    
    public function getQuotes($limit = 5) {
        $query = $this->connection->query('SELECT * FROM quotes ORDER BY ID DESC LIMIT :limit',[
            "limit" => $limit
        ], true);
        
        $quotes = [];
        while($row = $query->fetch_assoc()) {
            $quotes[] = $row;
        }
        
        return $quotes;
    }
    
    public function getNewQuotes($id) {
        $query = $this->connection->query('SELECT * FROM quotes WHERE ID > :id ORDER BY ID DESC',[
            "id" => $id
        ], true);
        
        $quotes = [];
        while($row = $query->fetch_assoc()) {
            $quotes[] = $row;
        }
        
        return $quotes;
    }
    
    public function getDate($timestamp) {
        $time = time();
        $date = date('H:i', $timestamp);

        if(date('d',$time) != date('d', $timestamp)) {
           $date = date('d-m-y', $timestamp) . ', ' . $date;
        }

        if(($time - $timestamp) < 60) {
            $date = 'Vor ' . ($time - $timestamp) . ' Sekunden';
        } else {
            if(($time - $timestamp) < 3600) {
                $minutes = floor(($time - $timestamp) / 60);
                $date = 'Vor ' . ($minutes) . ' Minuten';
            } else {
                if(($time - $timestamp) < 86400) {
                    $hours = floor(($time - $timestamp) / 3600);
                    $date = 'Vor ' . ($hours) . ' Stunden';
                }
            }
        }
        
        return $date;
    }
    
    public function loadMore($loaded = 1, $next = 5) {
        $query = $this->connection->query('SELECT * FROM quotes ORDER BY ID DESC');
        $quotes = [];
        while($row = $query->fetch_assoc()) {
            $quotes[] = $row;
        }
        
        $content = [];
        
        for($i = $loaded; $i < ($loaded + $next); $i++) {
            if(array_key_exists($i, $quotes)) {
                array_push($content, $quotes[$i]);
            }
        }
        
        return $content;
    }
    
    public function addQuote($data) {
        $this->connection->query('INSERT INTO quotes (author, content, time) VALUES (:name, :text, :time)', [
            "name" => $data['name'],
            "text" => $data['quote'],
            "time" => $data['time']
        ]);
    }
}