<?php

//logout.php

include('../../configGoogle.php');

//Reset OAuth access token
//$google_client->revokeToken();

//Destroy entire session data.
session_unset();
session_destroy();

//redirect page to index.php
header('Location:../loginGmail.php');

?>