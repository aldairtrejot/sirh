<?php

class catalogoPuestoM
{
    public function listarByAll()
    {
        $listado = pg_query("SELECT id_cat_puesto_hraes, codigo_puesto, nombre_posicion
                             FROM central.cat_puesto_hraes
                             ORDER BY nombre_posicion ASC");
        return $listado;
    }

    public function obtenerElemetoById($idObject)
    {
        $listado = pg_query("SELECT id_cat_puesto_hraes, CONCAT(nombre_posicion, ' - ', codigo_puesto) 
                             FROM central.cat_puesto_hraes
                             WHERE id_cat_puesto_hraes = $idObject");
        return $listado;
    }

    public function nameOfPuesto($id){
        $query = pg_query("SELECT nivel
                             FROM central.cat_puesto_hraes
                             WHERE id_cat_puesto_hraes = $id;");
        return $query;
    }

    public function listOfSpecificName($id){
        $query = pg_query ("SELECT 
                                DISTINCT central.cat_puesto_nombre_especifico.id_cat_puesto_nombre_especifico,
                                UPPER(central.cat_puesto_nombre_especifico.nombre) AS is_specific_name
                            FROM central.cat_aux_puesto
                            INNER JOIN central.cat_puesto_nombre_especifico
                                ON central.cat_aux_puesto.id_cat_puesto_nombre_especifico =
                                central.cat_puesto_nombre_especifico.id_cat_puesto_nombre_especifico
                            WHERE central.cat_aux_puesto.id_cat_puesto_hraes = $id
                            ORDER BY is_specific_name ASC;");
        return $query;
    }

    public function listOfCategoName($id_cat_puesto_hraes,$id_cat_puesto_nombre_especifico){
        $query = pg_query ("SELECT 
                                DISTINCT central.cat_puesto_categoria.id_cat_puesto_categoria,
                                UPPER (central.cat_puesto_categoria.nombre) AS is_name_especific
                            FROM central.cat_aux_puesto
                            INNER JOIN central.cat_puesto_categoria
                                ON central.cat_aux_puesto.id_cat_puesto_categoria =
                                    central.cat_puesto_categoria.id_cat_puesto_categoria
                            WHERE central.cat_aux_puesto.id_cat_puesto_hraes = $id_cat_puesto_hraes
                            AND central.cat_aux_puesto.id_cat_puesto_nombre_especifico = $id_cat_puesto_nombre_especifico
                            ORDER BY is_name_especific ASC;");
        return $query;
    }

    public function getIdOfTableAux($id_cat_puesto_hraes, $id_cat_puesto_nombre_especifico, $id_cat_puesto_categoria){
        $query = pg_query("SELECT 
                                central.cat_aux_puesto.id_cat_aux_puesto
                            FROM central.cat_aux_puesto
                            WHERE central.cat_aux_puesto.id_cat_puesto_hraes = $id_cat_puesto_hraes
                            AND central.cat_aux_puesto.id_cat_puesto_nombre_especifico = $id_cat_puesto_nombre_especifico
                            AND central.cat_aux_puesto.id_cat_puesto_categoria = $id_cat_puesto_categoria;");
        return $query;
    }

    public function getEditCatAux($isId){
        $query = pg_query("SELECT 
                                central.cat_aux_puesto.id_cat_aux_puesto,
                                central.cat_aux_puesto.id_cat_puesto_hraes,
                                central.cat_aux_puesto.id_cat_puesto_nombre_especifico,
                                central.cat_aux_puesto.id_cat_puesto_categoria
                            FROM central.cat_aux_puesto
                            WHERE central.cat_aux_puesto.id_cat_aux_puesto = $isId;");
        return $query;
    }

    public function editSpecificName($id){
        $query = pg_query("SELECT 
                                central.cat_puesto_nombre_especifico.id_cat_puesto_nombre_especifico,
                                UPPER(central.cat_puesto_nombre_especifico.nombre)
                            FROM central.cat_puesto_nombre_especifico
                            WHERE central.cat_puesto_nombre_especifico.id_cat_puesto_nombre_especifico = $id;");
        return $query;
    }

    public function editCatName($isId){
        $query = pg_query ("SELECT 
                                central.cat_puesto_categoria.id_cat_puesto_categoria,
                                UPPER(central.cat_puesto_categoria.nombre)
                            FROM central.cat_puesto_categoria
                            WHERE central.cat_puesto_categoria.id_cat_puesto_categoria = $isId;");
        return $query;
    }

    public function listarByAllPuesto()
    {
        $listado = pg_query("SELECT central.cat_puesto_hraes.id_cat_puesto_hraes, 
                                CONCAT(central.cat_puesto_hraes.codigo_puesto, ' - ', 
                                central.cat_puesto_hraes.nombre_posicion)
                             FROM central.cat_puesto_hraes
                             ORDER BY nombre_posicion ASC");
        return $listado;
    }

    public function editByAllPuesto($id)
    {
        $listado = pg_query("SELECT central.cat_puesto_hraes.id_cat_puesto_hraes, 
                                CONCAT(central.cat_puesto_hraes.codigo_puesto, ' - ', 
                                central.cat_puesto_hraes.nombre_posicion)
                             FROM central.cat_puesto_hraes
                             WHERE central.cat_puesto_hraes.id_cat_puesto_hraes = $id");
        return $listado;
    }

    public function getEntity($id){
        $query = pg_query("SELECT CONCAT(public.cat_entidad.clave_entidad, ' - ', public.cat_entidad.entidad)
                            FROM central.tbl_control_plazas_hraes
                            INNER JOIN central.tbl_centro_trabajo_hraes
                                ON central.tbl_control_plazas_hraes.id_tbl_centro_trabajo_hraes =
                                    central.tbl_centro_trabajo_hraes.id_tbl_centro_trabajo_hraes
                            INNER JOIN public.cat_entidad
                                ON central.tbl_centro_trabajo_hraes.id_cat_entidad =
                                    public.cat_entidad.id_cat_entidad
                            WHERE central.tbl_control_plazas_hraes.id_tbl_control_plazas_hraes = $id;");
        return $query;
    }

}
