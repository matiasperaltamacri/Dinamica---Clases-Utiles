<?php

//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('873777259177-5i3jdgi8n8av5b3oc0ntrrs4jn62fpjl.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/login_fb/vista/loginGmail.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>