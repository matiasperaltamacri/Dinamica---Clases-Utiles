<?php

//Include Configuration File
include('../configGoogle.php');
$titulo="Login Gmail";
$estructuraAMostrar="desdeVista";
include_once("estructura/cabecera.php");
echo "<link rel='stylesheet' href='css/gmail.css'>";


if(isset($_GET["code"]))
{

 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);


 if(!isset($token['error']))
 {
 
  $google_client->setAccessToken($token['access_token']);

 
  $_SESSION['access_token'] = $token['access_token'];


  $google_service = new Google_Service_Oauth2($google_client);

 
  $data = $google_service->userinfo->get();

 
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }else{
    $_SESSION['user_gender'] = "No disponible";
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }

  if(!empty($data['id']))
  {
   $_SESSION['oauth_uid'] = $data['id'];
  }
 }
}

?>
  <div class="container">
   <br />
   <br />
   <div class="panel panel-default">
   <?php
   if ((isset($_SESSION['access_token'])) && (isset($_SESSION['oauth_uid']))){
        $abmUs = new abmUsuario();
        $usuario = $abmUs->buscar(['oauth_uid'=>$_SESSION['oauth_uid']]);
        $esGmail=true;
        if (!empty($usuario)){
          if ($usuario[0]->getOauthProvider()=="facebook"){
            $esGmail=false;
          }
        }

        if($esGmail)
        {
        $abmUs->alta([
            'oauth_provider'=> 'google',
            'first_name'    => $_SESSION['user_first_name'],
            'last_name'     => $_SESSION['user_last_name'],
            'email'         => $_SESSION['user_email_address'],
            'picture'       => $_SESSION["user_image"],
            'gender'       => $_SESSION["user_gender"],
            'oauth_uid'       => $_SESSION["oauth_uid"] ]);
            
        echo '<div id="profile" class="container" style="margin-top: 100px">
              <div class="row  mt-4">
              <div class="col-md-3 text-center">';
        echo '<img src="'.$_SESSION["user_image"].'">';
        echo '<br><a href="accion/logoutGmail.php">Cerrar Sesión</a></div>';
        echo '<div class="col-md-5">
              <table class="table table-hover table-bordered" style="box-shadow: 0px 0px 6px #0000008c;">
                <tbody>';
        echo '<tr>
                <td>Nombre</td>
                <td>'.$_SESSION['user_first_name'].' '.$_SESSION['user_last_name'].'</td>
              </tr>';
        echo '<tr>
                <td>Email</td>
                <td>'.$_SESSION['user_email_address'].'</td>
              </tr>';
        echo '<tr>
                <td>Género</td>
                <td>'.$_SESSION['user_gender'].'</td>
              </tr>';
        echo '<tr>
                <td>Id Usuario</td>
                <td>'.$_SESSION['oauth_uid'].'</td>
              </tr>';
        echo '</tbody></table></div></div></div>';
    
    
        }else{
     
         echo '<div class="col-4 card border-dark rounded mt-5">
                   <img class="mt-3" src="img/google-logo2.png">
                   <div class="card-body mt-5"><a class="btn btn-warning btn-lg" href="'.$google_client->createAuthUrl().'">Iniciar Sesion</a></div>
               </div>';
        }
   }
   else{

    echo '<div class="col-4 card border-dark rounded mt-5">
              <img class="mt-3" src="img/google-logo2.png">
              <div class="card-body mt-5"><a class="btn btn-warning btn-lg" href="'.$google_client->createAuthUrl().'">Iniciar Sesion</a></div>
          </div>';
   }
   ?>
   </div>
  </div>
<?php
  include_once("estructura/pie.php");
?>

						