<?php

class abmUsuario
{
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Persona
     */
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('oauth_provider', $param) and array_key_exists('oauth_uid', $param) and array_key_exists('first_name', $param) and array_key_exists('last_name', $param) and array_key_exists('email', $param) and array_key_exists('gender', $param) and array_key_exists('picture', $param)) {
            $obj = new Usuario();
            $obj->setear("", $param['oauth_provider'], $param['oauth_uid'], $param['first_name'], $param['last_name'], $param['email'], $param['gender'], $param['picture']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Persona
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['id'])) { // id correspondiente al input de borrar
            $obj = new Usuario();
            $obj->setear($param['id'], null, null, null, null, null);
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['oauth_uid'])) {
            $resp = true;
        }
        return $resp;
    }

    /**
     *
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $elObjUsuario = $this->cargarObjeto($param);
        $nuevoArray['oauth_uid'] = $param['oauth_uid'];
        $usuarioExiste = $this->buscar($nuevoArray); // verifico si la persona ya existe en la bd
        if ((count($usuarioExiste) == 0) and $elObjUsuario != null and $elObjUsuario->insertar()) {
            $resp = true;
        }

        return $resp;

    }

    /**
     * permite eliminar un objeto
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjUsuario = $this->cargarObjetoConClave($param);
            $nuevoArray['oauth_uid'] = $param['oauth_uid'];
            $usuarioExiste = $this->buscar($nuevoArray); // verifico si la persona ya existe en la bd
            if ((count($usuarioExiste) > 0) and $elObjUsuario != null and $elObjUsuario->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjUsuario = $this->cargarObjeto($param);
            $nuevoArray['oauth_uid'] = $param['oauth_uid'];
            $usuarioExiste = $this->buscar($nuevoArray); // verifico si la persona ya existe en la bd
            if ((count($usuarioExiste) > 0) and $elObjUsuario != null) {
                $elObjUsuario->setId($usuarioExiste[0]->getId());
                echo "<p>OBJ USUARIO</p><pre>";
                print_r($elObjUsuario);
                echo "<p>]]]]]]]]]]]]]]]</p><pre>";
                if ($elObjUsuario->modificar()) {
                    $resp = true;
                }
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return boolean
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param != null) {
            if (isset($param['oauth_provider'])) {
                $where .= " and oauth_provider ='" . $param['oauth_provider'] . "'";
            }

            if (isset($param['oauth_uid'])) {
                $where .= " and oauth_uid ='" . $param['oauth_uid'] . "'";
            }

            if (isset($param['first_name'])) {
                $where .= " and first_name ='" . $param['first_name'] . "'";
            }

            if (isset($param['last_name'])) {
                $where .= " and last_name ='" . $param['last_name'] . "'";
            }

            if (isset($param['email'])) {
                $where .= " and email ='" . $param['email'] . "'";
            }

            if (isset($param['gender'])) {
                $where .= " and gender ='" . $param['gender'] . "'";
            }

            if (isset($param['picture'])) {
                $where .= " and picture ='" . $param['picture'] . "'";
            }

        }
        //print_r($where);
        $arreglo = Usuario::listar($where);
        return $arreglo;

    }

    public function buscarId($param)
    {
        $where = " true ";
        $idUsuario = -1;
        if ($param != null) {
            if (isset($param['oauth_uid'])) {
                $where .= " and oauth_uid ='" . $param['oauth_uid'] . "'";
            }
        }
        $arreglo = Usuario::listar($where);
        if (count($arreglo) == 1) { // si encontró usuario
            $objUsuario = $arreglo[0];
            $idUsuario = $objUsuario->getId();
        }
        return $idUsuario;
    }

    public function armarObjUsuario($objUsuario){
        $objUser = null;
        $obj = $this->cargarObjeto();
        if($obj != null){
            $objUser = $obj;
        }
        return $objUser;
    }
}
