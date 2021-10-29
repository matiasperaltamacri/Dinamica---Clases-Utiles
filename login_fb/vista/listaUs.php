<?php
    $estructuraAMostrar="desdeVista";
    $titulo="Lista Usuarios";
    include_once("estructura/cabecera.php");
    echo "<link rel='stylesheet' href='css/lista.css'>";
    $abmUs=new abmUsuario();
    $lista=$abmUs->buscar("");
    if (!empty($lista)){
        $logoUrl="";
        echo '</br></br></br>';
        echo '<div class="tabla col-md-offset-3 col-md-12 mt-5" style="margin:auto">
            <table class="table table-responsive" style="background-color:rgba(255, 255, 255, 0.3); border: 2px #a0bbe8 solid;">';
        echo '<h4 class="bg-primary text-center pad-basic">LISTA DE USUARIOS</h4>';
        echo '<thead><th>Nombre</th>';
        echo '<th>Apellido</th>';
        echo '<th>Correo</th>';
        echo '<th>Genero</th>';
        echo '<th>Social Network</th>';
        echo '<th>Id Usuario</th>';
        echo '<th>Foto</th></thead>';
        foreach($lista as $usuario){
            if ($usuario->getOauthProvider()=="facebook"){
                $logoUrl="img/facebook-icon.png";
            }else if($usuario->getOauthProvider()=="google"){
                $logoUrl="img/gmail-icon.png";
            }
            echo '<tr><td>' . $usuario->getFirstName().'</td>';
            echo '<td>' .$usuario->getLastName().'</td>';
            echo '<td>' . $usuario->getEmail().'</td>';
            echo '<td>' . $usuario->getGender().'</td>';
            echo '<td><img style="margin:auto 30%; width:25px" src="'. $logoUrl.'"></td>';
            echo '<td>' . $usuario->getOauthUid().'</td>';
            echo '<td><img style="width:50px" src="'.$usuario->getPicture().'"></td></tr>';
            
        }
        echo '</table></div>';
    }
   include_once("estructura/pie.php"); 
?>