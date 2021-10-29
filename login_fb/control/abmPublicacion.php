<?php
class abmPublicacion
{
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Publicacion
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if ( array_key_exists('contenido', $param) and array_key_exists('tiempo', $param) and array_key_exists('objUsuario', $param)) {
            $obj = new Publicacion();
            $obj->setear("", $param['contenido'], $param['tiempo'], $param['objUsuario']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['objUsuario'])) {
            $resp = true;
        }
        return $resp;
    }

    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['objUsuario'])) { 
            $obj = new Publicacion();
            $obj->setear("", "", "", $param['objUsuario']);
        }
        return $obj;
    }


    /**
     *
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $elObjPublicacion = $this->cargarObjeto($param);
        if ($elObjPublicacion != null and $elObjPublicacion->insertar()) {
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
            $elObjPublicacion = $this->cargarObjetoConClave($param);
            if ($elObjPublicacion != null and $elObjPublicacion->eliminar()) {
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
            $elObjPublicacion = $this->cargarObjeto($param);
            if ($elObjPublicacion != null and $elObjPublicacion->modificar()) {
                $resp = true;
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
            if (isset($param['id_pub'])) {
                $where .= " and id_pub =" . $param['id_pub'];
            }

            if (isset($param['contenido'])) {
                $where .= " and contenido ='" . $param['contenido'] . "'";
            }

            if (isset($param['id'])) {
                $where .= " and id =" . $param['id'];
            }
        }
        $arreglo = Publicacion::listar($where);
        return $arreglo;
    }
}
?>