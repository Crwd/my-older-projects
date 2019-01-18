<?php 

// DATABASE
$mysql_db = array();
$mysql_db['HOST'] = "localhost";
$mysql_db['DBNAME'] = "filoxenia";
$mysql_db['USER'] = "root";
$mysql_db['PASSWORD'] = "";

// PAGE
$page = array();
$page['site'] = "Filoxenia"; 

// ECONOMY
$eco = array();
$eco['CURRENCY'] = "EUR"; 
$eco['CURRENCY_CHAR'] = "€"; 
$eco['KOMMA'] = ","; 

// PAYPAL
$paypal = array();
$paypal['MAIL'] = "";
$paypal['CLIENT_ID'] = "AX7YxQwPPzMVy434RTmbMCsBLJKWNxl0YFlUzrIS9TmkCsTWF9LtRqsq-e0gRfXskNA-v8x8DPPKHtzB";
$paypal['SECRET'] = "EBKHDGRmmQZsLAdSEKtI_vrSFl6mSyhOdjzArc5hj1t6xIMWMdfCX_9CY4HfrhE29T3ygN0GJeHA1vtp";

$paypal['SHIPPING'] = "0.00";
$paypal['CURRENCY'] = "EUR";
$paypal['DESC'] = "Credit";
$paypal['REDIRECT'] = "http://localhost";

$paypal['FEE'] = 1.9; // Gebühren in Prozent: 1.9 = 1,9%
$paypal['EXTRA_CHARGE'] = 1; // Aufschlag: 1 = 1€ Aufschlag


?>