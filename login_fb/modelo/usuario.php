<?php

class Usuario
{
    private $id;
    private $oauth_provider;
    private $oauth_uid;
    private $first_name;
    private $last_name;
    private $email;
    private $gender;
    private $picture;
    private $colObjPublicacion;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->id = "";
        $this->oauth_provider = "";
        $this->oauth_uid = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->email = "";
        $this->gender = "";
        $this->picture = "";
        $this->colObjPublicacion = array();
        $this->mensajeoperacion = "";
    }

    public function setear($id, $oauth_provider, $oauth_uid, $first_name, $last_name, $email, $gender, $picture)
    {
        $this->setId($id);
        $this->setOauthProvider($oauth_provider);
        $this->setOauthUid($oauth_uid);
        $this->setFirstName($first_name);
        $this->setLastName($last_name);
        $this->setEmail($email);
        $this->setGender($gender);
        $this->setPicture($picture);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOauthProvider()
    {
        return $this->oauth_provider;
    }

    public function setOauthProvider($oauth_provider)
    {
        $this->oauth_provider = $oauth_provider;
    }

    public function getOauthUid()
    {
        return $this->oauth_uid;
    }

    public function setOauthUid($oauth_uid)
    {
        $this->oauth_uid = $oauth_uid;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function getColObjPublicacion()
    {
        $coleccion = array();
        $condicion = "id=" . $this->getId();
        $objPublicacion = new Publicacion();
        $colPublicacion = $objPublicacion->listar($condicion);
        for ($i = 0; $i < (count($colPublicacion)); $i++) {
            array_push($coleccion, $colPublicacion[$i]);
        }
        return $coleccion;
    }

    public function setColObjPublicacion($colObjPublicacion)
    {
        $this->colObjPublicacion = $colObjPublicacion;

    }

    public function getmensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setmensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE id= " . $this->getId();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['id'], $row['oauth_provider'], $row['oauth_uid'], $row['first_name'], $row['last_name'], $row['email'], $row['gender'], $row['picture']);
                }
            }
        } else {
            $this->setmensajeoperacion("Usuario->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuario (oauth_provider, oauth_uid, first_name, last_name, email, gender, picture)";
        $sql .= "VALUES ('" . $this->getOauthProvider() . "','" . $this->getOauthUid() . "','" . $this->getFirstName() . "','" . $this->getLastName() . "','" . $this->getEmail() . "','" . $this->getGender() . "','" . $this->getPicture() . "');";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setId($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Usuario->insertar 1: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("Usuario->insertar 2: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuario SET oauth_provider= '" . $this->getOauthProvider() . "', oauth_uid= '" . $this->getOauthUid() . "', first_name= '" . $this->getFirstName() . "', last_name= '" . $this->getLastName() . "', email= '" . $this->getEmail() . "', gender= '" . $this->getGender() . "', picture= '" . $this->getPicture() . "' WHERE id=" . $this->getId();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Usuario->modificar 1: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("Usuario->modificar 2: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE id='" . $this->getId() . "'";
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

    public static function listar($condicion = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario ";
        if ($condicion != "") {
            $sql .= 'WHERE ' . $condicion;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Usuario();
                    $obj->setear($row['id'], $row['oauth_provider'], $row['oauth_uid'], $row['first_name'], $row['last_name'], $row['email'], $row['gender'], $row['picture']);
                    $colPublicacion = $obj->getColObjPublicacion();
                    $obj->setColObjPublicacion($colPublicacion);
                    array_push($arreglo, $obj);
                }
            }

        } else {
            $this->setmensajeoperacion("Usuario->seleccionar: " . $base->getError());
        }

        return $arreglo;
    }

}
