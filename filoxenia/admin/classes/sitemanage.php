<?php
final class site_manager {
	public $request;
	public $title;
	public function getSite () {
		if(isset($_GET['site'])) {
			$this->request = $_GET['site'];
			if(file_exists('inc/' . $this->request . '.php')) {
				return true;
			}
		}
		return false;
	}
	
	public function getTitle() {
		global $secure_login;
		global $lang;
			if(isset($_GET['site'])) {
				$site = $_GET['site'];
				
				switch ($site) {
					default:
						$this->title = ucfirst($site);
						break;
				}
				
			} else {
				if($secure_login->is_loggedin()) {
					$this->title = "Dashboard";
				} else {
					$this->title = "Home";
				}
			}
		return $this->title;
	}
}

$siteManage = new site_manager;
?>