<?php
include_once "../../configuracion.php";
require_once "../../util/fb-app-config.php";

try {
    $accessToken = $helper->getAccessToken();
} catch (\Facebook\Exceptions\FacebookResponseException$e) {
    echo "Response Exception: " . $e->getMessage();
    exit();
} catch (\Facebook\Exceptions\FacebookSDKException$e) {
    echo "SDK Exception: " . $e->getMessage();
    exit();
}

if (!$accessToken) {
    header('Location: ../login.php');
    exit();
}

// Controlador de cliente OAuth 2.0 ayuda a administrar tokens de acceso
$oAuth2Client = $fb->getOAuth2Client();
if (!$accessToken->isLongLived()) {
    // Intercambia una ficha de acceso de corta duración para una persona de larga vida
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
}
// Obtener información sobre el perfil de usuario facebook
$response = $fb->get("/me?fields=id, first_name, last_name, email, gender, picture.type(large), posts", $accessToken);
$userData = $response->getGraphNode()->asArray();

// datos de usuario que iran a  la base de datos
if (!isset($userData['gender'])){
    $gender="No disponible";
}else{
    $gender=$userData['gender'];
}
$fbUserData = array(
    'oauth_provider' => 'facebook',
    'oauth_uid' => $userData['id'],
    'first_name' => $userData['first_name'],
    'last_name' => $userData['last_name'],
    'email' => $userData['email'],
    'gender' => $gender,
    'picture' => $userData['picture']['url'],
);

$objUser = new abmUsuario();
$objPub = new abmPublicacion();
$usuarioExiste = $objUser->buscar(['oauth_uid'=>$fbUserData['oauth_uid']]);
if (count($usuarioExiste) > 0) { // si existe el usuario en la bd

    $arrayPub = [];
    foreach ($usuarioExiste[0]->getColObjPublicacion() as $pub) { // borro todas sus publicaciones
        $arrayPub['objUsuario'] = $pub->getObjUsuario();
        $respElim = $objPub->baja($arrayPub);
    }

    $colPublicacionMsj = [];
    $i = 0;
    foreach ($userData['posts'] as $publicacion) {
        if (isset($publicacion['message'])) {
            $fechaHora = $publicacion['created_time'];
            $fechaHora = $fechaHora->format('Y-m-d H:i:s');
            $mensaje = $publicacion['message'];

            $colPublicacionMsj[$i]['contenido'] = $mensaje;
            $colPublicacionMsj[$i]['tiempo'] = $fechaHora;
            $colPublicacionMsj[$i]['objUsuario'] = $usuarioExiste[0];
            $i++;
        }
    }

    foreach ($colPublicacionMsj as $publicacion) {
        $respuestaAbm = $objPub->alta($publicacion); // alta de publicacion (actualizacion)
        if ($respuestaAbm) {
            echo "<p>PUBLICACION INSERTADA</p>";
        } else {
            echo "<p>PUBLICACION NO INSERTADA</p>";
        }
    }

    $respuestaAbm = $objUser->modificacion($fbUserData);
    if ($respuestaAbm) {
        // Usuario modificado
    } else {
        // Usuario no modificado
    }
} else {
    // SI no existen usuarios en la bd
    $respuestaAbm = $objUser->alta($fbUserData);
    if ($respuestaAbm) { // si pudo insertar usuario

        $colPublicacionMsj = [];
        $usuarioCargado = $objUser->buscar($fbUserData['oauth_uid']);
        $i = 0;
        if (count($usuarioCargado) > 0) {
            foreach ($userData['posts'] as $publicacion) {
                if (isset($publicacion['message'])) {
                    $fechaHora = $publicacion['created_time'];
                    $fechaHora = $fechaHora->format('Y-m-d H:i:s');
                    $mensaje = $publicacion['message'];

                    $colPublicacionMsj[$i]['contenido'] = $mensaje;
                    $colPublicacionMsj[$i]['tiempo'] = $fechaHora;
                    $colPublicacionMsj[$i]['objUsuario'] = $usuarioCargado[0];
                    $i++;
                }
            }

            foreach ($colPublicacionMsj as $publicacion) {
                $respuestaAbm = $objPub->alta($publicacion); // alta de publicacion
                if ($respuestaAbm) {
                    echo "<p>PUBLICACION INSERTADA</p>";
                } else {
                    echo "<p>PUBLICACION NO INSERTADA</p>";
                }
            }

        } else {
            echo "NO SE ENCONTRO ID USUARIO (-1)";
        }

        echo "<p> USUARIO INSERTADO </p>";
    } else {
        echo "<p> ERROR INSERCION </p>";
        // Error insercion
    }
}

$_SESSION['oauth_uid'] = $userData['id'];
$_SESSION['access_token'] = (string) $accessToken;
header('Location: ../indexFb.php');
exit();
