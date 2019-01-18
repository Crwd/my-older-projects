<?php

/*
|--------------------------------------------------------------------------
| Email Address
|--------------------------------------------------------------------------
| Here, you specify the address that will be used for all e-mails to be sent to.
*/

$email = 'your-email-address@example.com';

/*
|--------------------------------------------------------------------------
| Subject Pattern
|--------------------------------------------------------------------------
|
| You may want to customize the subject of the emails that you'll receive to be more
| distinctive or personalized.
|
| Note: The %subject% is a wild card.
*/

$subjectPattern = '[CONTACT FORM] %subject%';

/*
|--------------------------------------------------------------------------
| Append Fields
|--------------------------------------------------------------------------
|
| If you want to add more fields to form, you sure can. For each additional input,
| add its name to the array bellow.
*/

$appendFields = array();

/**
 * DO NOT REMOVE THIS LINE
 */
require_once('libs/Mailer.php');
