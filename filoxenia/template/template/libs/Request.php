<?php

class Request {

    private $request;

    function __construct() {
        $this->request = array_merge($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function get($key = null, $default = null)
    {
        if ( ! is_null($key) && isset($this->request[$key]))
        {
            return $this->request[$key];
        }

        if ( ! is_null($default))
        {
            return $default;
        }

        return $this->request;
    }
}
