<?php
	require_once (__DIR__ . "/core/settings_cms.php"); // Settings (IMMER ZUERST LADEN)
	require_once (__DIR__ . "/core/mysql.php"); // MySQL Verbindung 
	require_once (__DIR__ . "/classes/login.php"); // Login & Session
	require_once (__DIR__ . "/core/general.php"); // Allgemeine Settings
	require_once (__DIR__ . "/core/functions.php"); // Wichtige Funktionen
	require_once  (__DIR__ . "/vendor/autoload.php"); // PayPal API 
	require_once  (__DIR__ . "/api/api.php"); // PayPal API Settings
	
	$title = $siteManage->getTitle();
?>

<!doctype html>
<html>

<head>
	<?php include "head.php"; ?>
    <header class="contain-to-grid">
		<?php include "header.php"; ?>
    </header>
</head>

<body>
	<script src="libs/noUiSlider/nouislider.min.js"></script>
    <script src="libs/noUiSlider/wNumb.js"></script>
    
    <main>
    	<?php
		if(!empty($_SESSION["ALERT"])) {
		?>
        	<script>
				swal({
				title: <?php echo json_encode($_SESSION["ALERT"]["TITLE"]);?>,
				text: <?php echo json_encode($_SESSION["ALERT"]["TEXT"]);?>,
				type: <?php echo json_encode($_SESSION["ALERT"]["STYLE"]);?>,
				confirmButtonColor: "#E67953"})
            </script>
        <?php
			$_SESSION["ALERT"] = "";
			unset($_SESSION["ALERT"]);
		}
		?>
		<?php
			if($secure_login->is_loggedin() && isset($_GET['loggedout']) && (time() - $_GET['loggedout']) < 36000) {
				$secure_login->logout();
				redirect_home();
			} else {
				$banned = false;
				if($secure_login->is_loggedin()) {
					$status = $user->getUserinfo("status", $_SESSION['USERNAME']);
					if($status == "banned") {
						$banned = true;
					}
				}
				
				if(!$banned) {
					if($siteManage->getSite()) {
						$r_site = $siteManage->request;
						if (requestSite($r_site)) {
							require_once (__DIR__ . "/inc/" . $r_site . ".php");
						} else {
							redirect_home();
						}
					} else {	
						require_once (__DIR__ . "/home.php");
					}
				} else {
					echo "<center><div class='alert alert-danger'>Your account is currently banned.</div></center>";
				}
				
			}
        ?>
    </main>
	
	<footer>
		<?php include "footer.php"; ?>
	</footer>
    
</body>

</html>
