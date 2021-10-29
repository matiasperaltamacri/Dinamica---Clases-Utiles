<?php

class Publicacion
{
    private $id_pub;
    private $contenido;
    private $tiempo;
    private $objUsuario;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->id_pub = "";
        $this->contenido = "";
        $this->tiempo = "";
        $this->objUsuario = "";
        $this->mensajeoperacion = "";
    }

    public function setear($id_pub, $contenido, $tiempo, $objUsuario)
    {
        $this->setId_pub($id_pub);
        $this->setContenido($contenido);
        $this->setTiempo($tiempo);
        $this->setObjUsuario($objUsuario);
    }

    public function getId_pub()
    {
        return $this->id_pub;
    }

    public function setId_pub($id_pub)
    {
        $this->id_pub = $id_pub;
    }

    public function getContenido()
    {
        return $this->contenido;
    }

    public function setContenido($contenido)
    {
        $this->contenido = $contenido;
    }

    public function getTiempo(){
        return $this->tiempo;
    }

    public function setTiempo($tiempo){
        $this->tiempo = $tiempo;
    }

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql="SELECT * FROM publicacion WHERE id_pub = ".$this->getId_pub();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $usuario = new Usuario();
                    $usuario->setId($row['id']);
                    $usuario->cargar();
                    $this->setear($row['id_pub'], $row['contenido'], $row['tiempo'], $usuario);
                }
            }
        } else {
            $this->setmensajeoperacion("Publicacion->cargar: ".$base->getError());
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $idUsuario = $this->getObjUsuario()->getId();
        $sql = "INSERT INTO publicacion(contenido, tiempo, id)  VALUES('".$this->getContenido()."','".$this->getTiempo()."', ".$idUsuario.");";
        if ($base->Iniciar()) {
            if ($id = $base->Ejecutar($sql)) {
                $this->setId_pub($id);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Publicacion->insertar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Publicacion->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM publicacion WHERE id=" . $this->getObjUsuario()->getId();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Usuario->eliminar 1: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("Usuario->eliminar 2: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($condicion = ""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM publicacion ";
        if ($condicion!="") {
            $sql.='WHERE '.$condicion;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                while ($row = $base->Registro()){                    
                    $obj= new Publicacion();
                    $usuario = new Usuario();
                    $usuario->setId($row['id']);
                    $usuario->cargar(); // el cargar de usuario, lo q hace es $sql="SELECT * FROM usuario WHERE id = ".$this->getId(); me trae un usuario con el unico parametro que necesito, q es el id
                    $obj->setear($row['id_pub'], $row['contenido'], $row['tiempo'], $usuario);
                    array_push($arreglo, $obj);
                }
               
            }
            
        } else {
            $this->setmensajeoperacion("Publicacion->listar: ".$base->getError());
        }

        return $arreglo;
    }
}
?>