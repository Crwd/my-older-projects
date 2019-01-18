<?php
	// All navigation sites
	$navi = array ("contact","blog","support","forgot-password","plans","features","about","privacy","terms","payment","order","checkout","request","ticket","myrequest");
	$guestNavi = array ("contact","blog","support", "login","forgot-password","plans","features","about","signup","privacy","terms");
	// Site Constructor
	$direction = "http://" . $_SERVER["HTTP_HOST"] . "/bitbucket/projects/filoxenia";
	
	function requestSite ($rsite) {
		global $guestNavi;
		global $secure_login;
		global $siteManage;
		global $navi;
		if($secure_login->is_loggedin()) {
			if (in_array($rsite, $navi)) {
				return true;
			}
		} else {
			if (in_array($rsite, $guestNavi)) {
				return true;
			}
		}
		return false;
	}
	
	if (isset($_GET['site'])) {
		$p = $_GET['site'];
		if($secure_login->is_loggedin()) {
			if (in_array($p, $navi)) {
				$instructor = "&";
				$home = "?site=" . $_GET['site'];
			} else {
				header("Location: " . $direction);
				$home = $_SERVER['PHP_SELF'];
				$instructor = "?";
			}
		} else {
			if (in_array($p, $guestNavi)) {
				$instructor = "&";
				$home = "?site=" . $_GET['site'];
			} else {
				header("Location: " . $direction);
				$home = $_SERVER['PHP_SELF'];
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