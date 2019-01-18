<?php

if(!isset($_GET['value'],$_GET['transid'])) {
	header("Location: " . $direction);
}

$fee = ($_GET['value'] / 100) * $paypal['FEE'];
$price = abs($_GET['value'] + $fee + $paypal['EXTRA_CHARGE']);
$product = "Credits: " . $price . " " . $paypal['CURRENCY'];
$transid = $_GET['transid'];
$total = $price  + $paypal['SHIPPING'];

if($price < 5) {
	header("Location: " . $direction);
}

if(!is_numeric($price)) {
	header("Location: " . $direction);
}

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$item = new Item();
$item->setName($product)
	->setCurrency($paypal['CURRENCY'])
	->setQuantity(1)
	->setPrice($price);
	
$itemList = new ItemList();
$itemList->setItems([$item]);

$details = new Details();
$amount = new Amount();
$transaction = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();


// Details
$details->setShipping($paypal['SHIPPING'])
	->setSubtotal($price);

// Amount
$amount->setCurrency($paypal['CURRENCY'])
	->setTotal($total)
	->setDetails($details);
	
// Transaction
$transaction->setAmount($amount)
	->setItemList($itemList)
	->setDescription($paypal['DESC'])
	->setInvoiceNumber(uniqid());
	
// Redirect URLs
$redirectUrls->setReturnUrl($paypal['REDIRECT'] . '/?payment=approved&transid=' . $transid)
	->setCancelUrl($paypal['REDIRECT'] . '/?payment=failed&transid=' . $transid);

// Payment
$payment->setIntent('sale')
	->setPayer($payer)
	->setTransactions([$transaction])
	->setRedirectUrls($redirectUrls);
	

// Start Pay-Event
try {
	$payment->create($api_pp);
} catch(Exception $e) {
	die($e);
}

$approvalUrl = $payment->getApprovalLink();

header("Location:" . $approvalUrl);

	

?>