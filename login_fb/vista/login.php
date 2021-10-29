<?php
$titulo="Log In";
$estructuraAMostrar = "desdeVista";
include_once("estructura/cabecera.php");
    
	require_once("../util/fb-app-config.php");

	if ((isset($_SESSION['access_token'])) && (isset($_SESSION['oauth_uid']))) {
		$obj = new abmUsuario();
		$usuario = $obj->buscar(['oauth_uid'=>$_SESSION['oauth_uid']]);
		if (!empty($usuario)){
			if ($usuario[0]->getOauthProvider()=="facebook"){
				header('Location: indexFb.php');
			}
		}
	}

	$redirectURL = "http://localhost/login_fb/vista/accion/fb-callback.php";
	$permissions = ['email', 'user_posts'];
	$loginURL = $helper->getLoginUrl($redirectURL, $permissions);
?>
<link rel='stylesheet' href='css/face.css'>
	</br>
	</br>
	</br>
	<div class="col-4 card border-dark rounded mt-5">
              <img class="mt-2" src="img/Facebook-logo.png">
              <div class="card-body mt-5">
			  	<input type="button" onclick="window.location = '<?php echo $loginURL ?>';" value="Iniciar Sesion" class="btn btn-lg btn-primary">
			  </div>
	</div>

<?php
include_once("estructura/pie.php");
?>