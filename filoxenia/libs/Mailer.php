<?php

require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/PHPMailer/class.phpmailer.php';

// AJAX check
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) OR
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest')
    die('Denied.');

// Create a new request using PHP's super globals.
$request = new Request();

// Get and format user submitted values.
$name = $request->get('name');
$from = $request->get('email');
$subject = preg_replace("/%subject%/", $request->get('subject'), $subjectPattern);
$message = nl2br($request->get('message'));

// Check for errors
if ( ! $name OR ! $from OR ! $subject OR ! $message) die('Invalid data.');

// Append any available additional form fields.
if (is_array($appendFields) AND count($appendFields) > 0)
{
    $additionalInfo =  '';

    foreach ($appendFields as $field)
    {
        $value = $request->get($field);

        if ( ! is_array($value))
        {
            $additionalInfo .= "<strong>{$field}:</strong> {$value}<br/>";
        }
    }

    $message = $additionalInfo . '<br/>' . $message;
}

// Construct email
$mail = new PHPMailer;
$mail->isHTML(true);

// Email sender and recipient
$mail->From = $from;
$mail->FromName = $name;
$mail->addAddress($email);
$mail->addReplyTo($from, $name);

// Email information
$mail->Subject = $subject;
$mail->Body = $message;

// Send it
$status = 'error';
if($mail->send())
{
    $status = 'success';
}

// Return response
echo json_encode(
    array(
        'status' => $status
    )
);
