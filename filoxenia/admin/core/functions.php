<?php
// Load classes
include( "classes/sitemanage.php");
include( "classes/user.php");
include( "/classes/ticketsystem.php");
include( "/classes/request.php");
include( "/classes/payment.php");
include( "/classes/products.php");
include( "/classes/statistic.php");

function drawTitle ($string = "no title") {
	echo '<div class="row title">';
    echo '<div class="small-12 column">';
    echo '<h6>' . $string . '</h6>';
    echo '</div>';
    echo '</div>';
}

function redirect_start() {
	header("Location: " . "http://" . $_SERVER["HTTP_HOST"]);
}


function redirect_home($alert = "") {
	global $direction;
	if(!empty($alert)) {
		$_SESSION["ALERT"] = $alert;
	}

	header("Location: " . $direction);
}


?>