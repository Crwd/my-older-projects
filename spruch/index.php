<?php
require_once('classes/Config.php');

$tplHandler = new TemplateHandler([
    "community" => [
        "name" => "community",
        "file" => "community.php"
    ]
]);

$CONNECTION = new Connection();

// SITE CONSTRUCTOR
include_once('inc/head.php');
include($tplHandler->display());
include_once('inc/footer.php');