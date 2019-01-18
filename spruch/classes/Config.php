<?php
    final class Config {
        const SITE_NAME = "SpruchUniversum";
        const PATH = "http://localhost";
        const PHP_PATH = "E:/xampp/htdocs/spruch";
        const TEMPLATE_PATH = Config::PHP_PATH . "/templates";
        
        const DB_HOST = "127.0.0.1";
        const DB_USERNAME = "root";
        const DB_PASSWORD = "";
        const DB_DBNAME = "spruchuniverse";
    }
    
    // AUTOLOADER
    spl_autoload_register(function($class) {
        require_once(Config::PHP_PATH . '/classes/' . $class . '.php');
    });