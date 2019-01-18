<?php
// API

define('SITE_URL', $paypal['REDIRECT'] );

$api_pp = new \PayPal\Rest\ApiContext(
	new \PayPal\Auth\OAuthTokenCredential(
		$paypal['CLIENT_ID'],
		$paypal['SECRET'] 
	)
);	

//$api_pp->setConfig( array( 'mode' => 'live' ) ); 

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

if(isset($_GET["payment"])) {
	//$stmt->query('INSERT INTO fee (fee) VALUES ("' . $fee . '")');
	
	if($secure_login->is_loggedin()) {
		if(isset($_GET["transid"])) {
			$username = $user->getUsername();
			$trans = $_GET["transid"];
			$trans_db = $user->getUserTransid($trans, $username);
			if($trans_db > 0) {
				$state = $stmt->query('SELECT state FROM cms_payments WHERE transid ="' . $trans . '"');
				$state = $state->fetch_assoc();
				
				if($state["state"] == "waiting") {
					if(!isset($_GET['success'], $_GET['paymentId'], $_GET['PayerID'])) {
						header("Location: " . $direction);
					}
					
					if($_GET["payment"] == "approved") {
						if((bool)$_GET['success'] === false) {
							header("Location: " . $direction);
						}
						
						$paymentId = $_GET['paymentId'];
						$payerId = $_GET['PayerID'];
						
						$payaction = Payment::get($paymentId,$api_pp);

						$execute = new PaymentExecution();
						$execute->setPayerId($payerId);
						
						
						try {
							$result_pp = $payaction->execute($execute, $api_pp);
						} catch(Exception $e) {
							$stmt->query('UPDATE cms_payments SET state="failed" WHERE transid="' . $trans . '"');
							$payment->send_error("The payment failed");
						}
						
						$stmt->query('UPDATE cms_payments SET state="paid" WHERE transid="' . $trans . '"');
						
						$value = $stmt->query('SELECT value FROM cms_payments WHERE transid ="' . $trans . '"');
						$state = $stmt->query('SELECT state FROM cms_payments WHERE transid ="' . $trans . '"');
						$credits = $stmt->query('SELECT credits FROM users WHERE username ="' . $username . '"');
						$credits = $credits->fetch_assoc();
						$value = $value->fetch_assoc();

						$new_balance = $credits["credits"] + $value["value"];
						$stmt->query('UPDATE users SET credits="' . $new_balance . '" WHERE username="' . $username . '"');
						
						$payment->send_success("You successfully added credit to your account");
					} elseif ($_GET["payment"] == "failed") {
						$stmt->query('UPDATE cms_payments SET state="failed" WHERE transid="' . $trans . '"');
						$payment->send_error("The payment failed");
					}
				}
			}
			
		}
	}
}
?>