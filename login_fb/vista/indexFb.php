<?php
$titulo="User Profile";
$estructuraAMostrar = "desdeVista";
include_once("estructura/cabecera.php");
include_once("../configuracion.php");

session_start();

	// echo "<pre>";
	// print_r($_SESSION);
	// echo "</pre>";


if ((!isset($_SESSION['access_token'])) || (!isset($_SESSION['oauth_uid']))){
    header('Location: login.php');
    exit();
}

$obj = new abmUsuario();
$usuario = $obj->buscar(['oauth_uid'=>$_SESSION['oauth_uid']]);
$esFacebook=true;
if (!empty($usuario)){
	if($usuario[0]->getOauthProvider()=="google"){
		$esFacebook=false;
	}
}
if ((empty($usuario)) || (!$esFacebook)){
	header('Location: login.php');
}
$colPublicaciones = $usuario[0]->getColObjPublicacion();



?>
<style>
	body{
		background-color: #bfc3ff;
	}
</style>
	<div class="container" style="margin-top: 100px">
		<div class="row justify-content-center">
			<div class="col-md-3 text-center">
				<img src="<?php echo $usuario[0]->getPicture(); ?>">
				<br>
				<a href="accion/closeLogin.php" style="margin: 15px 0; display: block;">Cerrar Sesión</a>
			</div>

			<div class="col-md-5">
				<table class="table table-hover table-bordered" style="background-color:white; box-shadow: 0px 0px 6px #0000008c;">
					<tbody>
						<tr>
							<td>Nombre</td>
							<td><?php echo $usuario[0]->getFirstName(); ?></td>
						</tr>
						<tr>
							<td>Apellido</td>
							<td><?php echo $usuario[0]->getLastName(); ?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td><?php echo $usuario[0]->getEmail(); ?></td>
						</tr>
						<tr>
							<td>Género</td>
							<td><?php echo $usuario[0]->getGender(); ?></td>
						</tr>
					</tbody>
				</table>
			</div>


						<?php 
							foreach($colPublicaciones as $posteo){
								$dateString = $posteo->getTiempo();
								$fecha = substr($dateString, 8, 2) ."/".substr($dateString, 5, 2)."/".substr($dateString, 0, 4);
								$hora = substr($dateString, 11, 2) .":".substr($dateString, 14, 2);
								?>
									<div class="col-md-9">
									<table class="table table-hover table-bordered">
										<tbody>
									<td style="background-color: #024bb973;"><img src="<?php echo $usuario[0]->getPicture();?>" width="35"><?php echo (" Publicado creada: ".$fecha."  ".$hora) ?></td>
										<tr>
											<td style="text-align:center;"><?php echo $posteo->getContenido(); ?></td>
										</tr>
										</tbody>
									</table>
									</div>
								<?php	
							}
						?>
		</div>
	</div>

<?php
include_once("estructura/pie.php");
?>