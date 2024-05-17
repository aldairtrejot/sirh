<?php

class ModelMovimientosM
{
    public function listarByIdEmpleado($idEmpleado)
    {
        $listado = pg_query("SELECT tbl_plazas_empleados_hraes.id_tbl_plazas_empleados_hraes, 
                                    tbl_plazas_empleados_hraes.fecha_inicio, 
                                    tbl_plazas_empleados_hraes.fecha_movimiento,
                                    tbl_plazas_empleados_hraes.id_tbl_movimientos, 
                                    tbl_plazas_empleados_hraes.fecha_movimiento, 
                                    tbl_plazas_empleados_hraes.id_tbl_empleados_hraes,
                                    CONCAT(tbl_movimientos.codigo,' - ',tbl_movimientos.nombre_movimiento),
                                    tbl_movimientos.tipo_movimiento,
                                    tbl_control_plazas_hraes.num_plaza
                            FROM tbl_plazas_empleados_hraes
                            INNER JOIN tbl_movimientos
                            ON tbl_plazas_empleados_hraes.id_tbl_movimientos = 
                                tbl_movimientos.id_tbl_movimientos
                            INNER JOIN tbl_control_plazas_hraes
                            ON tbl_plazas_empleados_hraes.id_tbl_control_plazas_hraes =
                                tbl_control_plazas_hraes.id_tbl_control_plazas_hraes
                            WHERE tbl_plazas_empleados_hraes.id_tbl_empleados_hraes = $idEmpleado
                            ORDER BY tbl_plazas_empleados_hraes.id_tbl_plazas_empleados_hraes DESC
                            LIMIT 5;");

        return $listado;
    }

    public function listarByEdit($idMovimiento){
        $listado = pg_query("SELECT id_tbl_plazas_empleados_hraes,fecha_inicio,
                                    fecha_termino,id_tbl_movimientos,fecha_movimiento,
                                    id_tbl_control_plazas_hraes,id_tbl_empleados_hraes
                             FROM tbl_plazas_empleados_hraes
                             WHERE id_tbl_plazas_empleados_hraes = $idMovimiento;");
        return $listado;
    }

    public function listarByNull()
    {
        return $array = [
            'id_tbl_plazas_empleados_hraes' => null,
            'fecha_inicio' => null,
            'fecha_termino' => null,
            'id_tbl_movimientos' => null,
            'fecha_movimiento' => null,
            'id_tbl_control_plazas_hraes' => null,
            'id_tbl_empleados_hraes' => null,
        ];
    }


    public function countUltimoMovimiento($idEmpleado)
    {
        $listado = pg_query("SELECT COUNT(id_tbl_plazas_empleados_hraes)
                             FROM tbl_plazas_empleados_hraes 
                             WHERE id_tbl_empleados_hraes = $idEmpleado");
        return $listado;
    }


    public function listadoUltimoMovimiento($idEmpleado)
    {
        $listado = pg_query("SELECT tbl_movimientos.id_tipo_movimiento
                             FROM tbl_plazas_empleados_hraes 
                             INNER JOIN tbl_movimientos
                             ON tbl_plazas_empleados_hraes.id_tbl_movimientos =
                                tbl_movimientos.id_tbl_movimientos
                             WHERE tbl_plazas_empleados_hraes.id_tbl_empleados_hraes = $idEmpleado
                             GROUP BY tbl_plazas_empleados_hraes.fecha_movimiento,
                                    tbl_movimientos.id_tipo_movimiento
                             ORDER BY tbl_plazas_empleados_hraes.fecha_movimiento DESC
                             LIMIT 1;");
        return $listado;
    }

    public function listadoByIdPlaza($idEmpleado)
    {
        $listado = pg_query("SELECT id_tbl_control_plazas_hraes
                             FROM tbl_plazas_empleados_hraes
                             WHERE id_tbl_empleados_hraes = $idEmpleado
                             ORDER BY fecha_movimiento DESC
                             LIMIT 1");
        return $listado;
    }

    function editarByArray($conexion, $datos, $condicion, $name)
    {
        $pg_update = pg_update($conexion, $name, $datos, $condicion);
        return $pg_update;
    }

    function agregarByArray($conexion, $datos, $name)
    {
        $pg_add = pg_insert($conexion, $name, $datos);
        return $pg_add;
    }

    function eliminarByArray($conexion, $condicion, $name)
    {
        $pgs_delete = pg_delete($conexion, $name, $condicion);
        return $pgs_delete;
    }
}