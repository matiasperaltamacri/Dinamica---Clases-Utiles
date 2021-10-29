<?php

session_start();


require_once "Facebook/autoload.php";

$fb = new \Facebook\Facebook([
    'app_id' => '1010660586452076', // app name = pruebaLogin
    'app_secret' => '06cb0f4476611546790c0e4a9abb7865',
    'default_graph_version' => 'v2.10',
]);

// Obtener el apoyo de logueo
$helper = $fb->getRedirectLoginHelper();

?>
