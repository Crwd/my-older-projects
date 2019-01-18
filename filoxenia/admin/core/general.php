<?php
	// All navigation sites
	$navi = array();
	$navi[0] = array("");
	$navi[1] = array("tickets","requests","user","payments","ticket","request","products","product", "userdata");
	$navi[2] = array("");
	$navi[3] = array("");
	
	// Site Constructor
	$direction = "http://" . $_SERVER["HTTP_HOST"] . "/bitbucket/projects/filoxenia/admin";
	
	function requestSite ($rsite) {
		global $guestNavi;
		global $secure_login;
		global $siteManage;
		global $navi;
		if($secure_login->is_loggedin()) {
			foreach($navi as $n) {
				if (in_array($rsite, $n)) {
					return true;
				}
			}
		}
		return false;
	}
	
	if (isset($_GET['site'])) {
		$p = $_GET['site'];
		if($secure_login->is_loggedin()) {
			$ranked = false;
			foreach($navi as $k=>$n) {
				if($k <= $admin::$rank) {
					if (in_array($p, $n)) {
						$ranked = true;
					}
				}
			}
			if ($ranked) {
				$instructor = "&";
				$home = "?site=" . $_GET['site'];
			} else {
				header("Location: " . $direction);
				$home = $_SERVER['PHP_SELF'] . "/admin";
				$instructor = "?";
			}
		}
	
	} else {
		$home = $_SERVER['PHP_SELF'];
		$instructor = "?";
	}
	
	if($home != $_SERVER['PHP_SELF']) {
		$action_form = $_SERVER['PHP_SELF'] . $home;
	} else {
		$action_form = $_SERVER['PHP_SELF'];
	}

?>