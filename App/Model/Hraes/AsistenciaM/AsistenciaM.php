<?php

class AsistenciaM
{

    public function listOfAsistencia($id)
    {
        $query = pg_query("SELECT
                                public.ctrl_asistencia_info.id_ctrl_asistencia_info,
                                public.ctrl_asistencia_info.no_dispositivo,
                                UPPER(public.cat_asistencia_ubicacion.descripcion),
                                UPPER(public.cat_asistencia_estatus.descripcion),
                                UPPER(public.ctrl_asistencia_info.observaciones)
                            FROM public.ctrl_asistencia_info
                            INNER JOIN public.cat_asistencia_ubicacion
                                ON public.ctrl_asistencia_info.id_cat_asistencia_ubicacion = 
                                    public.cat_asistencia_ubicacion.id_cat_asistencia_ubicacion
                            INNER JOIN public.cat_asistencia_estatus
                                ON public.ctrl_asistencia_info.id_cat_asistencia_estatus =
                                    public.cat_asistencia_estatus.id_cat_asistencia_estatus
                            WHERE public.ctrl_asistencia_info.id_tbl_empleados_hraes = $id
                            ORDER BY public.ctrl_asistencia_info.id_ctrl_asistencia_info DESC
                            LIMIT 1");
        return $query;
    }

    public function listOfJornada($id)
    {
        $query = pg_query("SELECT 
                                public.ctrl_jornada.id_ctrl_jornada,
                                UPPER(public.cat_jornada_turno.descripcion),
                                UPPER(public.cat_jornada_dias.descripcion),
                                CONCAT(TO_CHAR(public.cat_jornada_horario.hora_entrada, 'HH24:MI'), ' - ',
                                        TO_CHAR(public.cat_jornada_horario.hora_salida, 'HH24:MI'))
                            FROM public.ctrl_jornada
                            INNER JOIN public.cat_jornada_turno
                                ON public.ctrl_jornada.id_cat_jornada_turno = 
                                    public.cat_jornada_turno.id_cat_jornada_turno
                            INNER JOIN public.cat_jornada_dias
                                ON public.ctrl_jornada.id_cat_jornada_dias =
                                    public.cat_jornada_dias.id_cat_jornada_dias
                            INNER JOIN public.cat_jornada_horario
                                ON public.ctrl_jornada.id_cat_jornada_horario =
                                    public.cat_jornada_horario.id_cat_jornada_horario
                            WHERE public.ctrl_jornada.id_tbl_empleados_hraes = $id
                            ORDER BY public.ctrl_jornada.id_ctrl_jornada DESC
                            LIMIT 1");
        return $query;
    }

    public function editAsistenciaInfo($id)
    {
        $query = pg_query("SELECT * 
                            FROM public.ctrl_asistencia_info
                            WHERE id_tbl_empleados_hraes = $id
                            ORDER BY id_ctrl_asistencia_info ASC
                            LIMIT 1;");
        return $query;
    }

    public function editJornadaInfo($id)
    {
        $query = pg_query("SELECT * 
                            FROM public.ctrl_jornada
                            WHERE id_tbl_empleados_hraes = $id
                            ORDER BY id_ctrl_jornada DESC
                            LIMIT 1;");
        return $query;
    }

    function editAsistenciaInfoDB($conexion, $datos, $condicion)
    {
        $pg_update = pg_update($conexion, 'public.ctrl_asistencia_info', $datos, $condicion);
        return $pg_update;
    }

    function addAsistenciaInfoDB($conexion, $datos)
    {
        $pg_add = pg_insert($conexion, 'public.ctrl_asistencia_info', $datos);
        return $pg_add;
    }


    function editJornadaInfoDB($conexion, $datos, $condicion)
    {
        $pg_update = pg_update($conexion, 'public.ctrl_jornada', $datos, $condicion);
        return $pg_update;
    }

    function addJornadaInfoDB($conexion, $datos)
    {
        $pg_add = pg_insert($conexion, 'public.ctrl_jornada', $datos);
        return $pg_add;
    }

    public function validateNoBiometrico($no_dispositivo, $id_tbl_empleados_hraes)
    {
        $query = pg_query("SELECT id_ctrl_asistencia_info
                            FROM public.ctrl_asistencia_info
                            WHERE no_dispositivo = $no_dispositivo
                            AND id_tbl_empleados_hraes <> $id_tbl_empleados_hraes;");
        return $query;
    }

    public function listadoAsistenciaAll($idEmpleado, $paginator)
    {
        $query = pg_query("WITH MinMaxHoras AS (
                                SELECT
                                    fecha,
                                    MIN(hora) AS hora_minima,
                                    MAX(hora) AS hora_maxima
                                FROM public.ctrl_asistencia
                                WHERE id_tbl_empleados_hraes = $idEmpleado
                                GROUP BY fecha
                            )
                            SELECT
                                ca.id_ctrl_asistencia,
                                TO_CHAR(ca.fecha, 'DD/MM/YYYY') AS fecha_formateada,
                                TO_CHAR(ca.hora, 'HH24:MI') AS hora_formateada,
                                CASE 
                                    WHEN ca.hora = mmh.hora_minima THEN 'PRIMER REGISTRO'
                                    WHEN ca.hora = mmh.hora_maxima THEN 'ÚLTIMO REGISTRO'
                                    ELSE 'REGISTRO INTERMEDIO'
                                END AS tipo_registro,
                                UPPER(ca.dispositivo) AS dispositivo,
                                UPPER(ca.verificacion) AS verificacion,
                                UPPER(ca.estado) AS estado,
                                UPPER(ca.evento) AS evento,
                                ca.id_user
                            FROM public.ctrl_asistencia ca
                            INNER JOIN MinMaxHoras mmh
                                ON ca.fecha = mmh.fecha
                                AND (ca.hora = mmh.hora_minima OR ca.hora = mmh.hora_maxima)
                            WHERE ca.id_tbl_empleados_hraes = $idEmpleado
                            ORDER BY ca.fecha DESC, ca.hora
                            LIMIT 3 OFFSET $paginator;");
        return $query;
    }

    public function listadoAsistenciaBusq($idEmpleado, $busqueda, $paginator)
    {
        $query = pg_query("WITH MinMaxHoras AS (
                                SELECT
                                    fecha,
                                    MIN(hora) AS hora_minima,
                                    MAX(hora) AS hora_maxima
                                FROM public.ctrl_asistencia
                                WHERE id_tbl_empleados_hraes = $idEmpleado
                                GROUP BY fecha
                            )
                            SELECT
                                ca.id_ctrl_asistencia,
                                TO_CHAR(ca.fecha, 'DD/MM/YYYY') AS fecha_formateada,
                                TO_CHAR(ca.hora, 'HH24:MI') AS hora_formateada,
                                CASE 
                                    WHEN ca.hora = mmh.hora_minima THEN 'PRIMER REGISTRO'
                                    WHEN ca.hora = mmh.hora_maxima THEN 'ÚLTIMO REGISTRO'
                                    ELSE 'REGISTRO INTERMEDIO'
                                END AS tipo_registro,
                                UPPER(ca.dispositivo) AS dispositivo,
                                UPPER(ca.verificacion) AS verificacion,
                                UPPER(ca.estado) AS estado,
                                UPPER(ca.evento) AS evento,
                                ca.id_user
                            FROM public.ctrl_asistencia ca
                            INNER JOIN MinMaxHoras mmh
                                ON ca.fecha = mmh.fecha
                                AND (ca.hora = mmh.hora_minima OR ca.hora = mmh.hora_maxima)
                            WHERE ca.id_tbl_empleados_hraes = $idEmpleado
                            AND ( TO_CHAR(ca.fecha, 'DD/MM/YYYY')::TEXT LIKE '%$busqueda%' OR
                                  TO_CHAR(ca.hora, 'HH24:MI')::TEXT LIKE '%$busqueda%' OR
                                  TRIM(UPPER(UNACCENT(ca.dispositivo))) LIKE '%$busqueda%' OR
                                  TRIM(UPPER(UNACCENT(ca.verificacion))) LIKE '%$busqueda%' OR
                                  TRIM(UPPER(UNACCENT(ca.estado))) LIKE '%$busqueda%' OR
                                  TRIM(UPPER(UNACCENT(ca.evento))) LIKE '%$busqueda%')
                            ORDER BY ca.fecha DESC, ca.hora
                            LIMIT 3 OFFSET $paginator;");
        return $query;
    }

    public function editAsistencia($id)
    {
        $query = pg_query("SELECT * 
                            FROM public.ctrl_asistencia
                            WHERE id_ctrl_asistencia =  $id;");
        return $query;
    }

    public function asistenciaIsNUll()
    {
        return $array = [
            'id_ctrl_asistencia' => null,
            'fecha' => null,
            'hora' => null,
            'dispositivo' => null,
            'verificacion' => null,
            'estado' => null,
            'evento' => null,
            'id_tbl_empleados_hraes' => null,
            'id_user' => null
        ];
    }

    function editarByArray($conexion, $datos, $condicion)
    {
        $pg_update = pg_update($conexion, 'public.ctrl_asistencia', $datos, $condicion);
        return $pg_update;
    }

    function agregarByArray($conexion, $datos)
    {
        $pg_add = pg_insert($conexion, 'public.ctrl_asistencia', $datos);
        return $pg_add;
    }

    function eliminarByArray($conexion, $condicion)
    {
        $pgs_delete = pg_delete($conexion, 'public.ctrl_asistencia', $condicion);
        return $pgs_delete;
    }

    public function getNameOfUser($id)
    {
        $query = pg_query("SELECT UPPER(nombre)
                            FROM public.users
                            WHERE id_user = $id;");
        return $query;
    }

    public function truncateTable($table)
    {
        $query = pg_query("TRUNCATE TABLE $table;");
        return $query;
    }

    public function addInfoAsistenciaTemp(
        $tableName,
        $tiempo,
        $no_empleado,
        $nombre,
        $apellido,
        $num_tarjeta,
        $dispositivo,
        $punto_evento,
        $verificacion,
        $estado,
        $evento,
        $notas
    ) {
        $query = pg_query("INSERT INTO $tableName
                                    VALUES ('$tiempo', '$no_empleado', '$nombre', '$apellido', '$num_tarjeta', '$dispositivo', 
                                            '$punto_evento', '$verificacion', '$estado', '$evento', '$notas');");
        return $query;
    }

    public function getReporte()
    {
        $query = pg_query("WITH Filtradas AS (
                                    SELECT
                                        cti.id_tbl_empleados_hraes,
                                        TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') AS hora
                                    FROM public.ctrl_temp_asistencia cta
                                    INNER JOIN public.ctrl_asistencia_info cti
                                        ON cta.no_empleado::TEXT = cti.no_dispositivo::TEXT
                                    WHERE
                                        cti.id_cat_asistencia_estatus = 1
                                        AND TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI')::TIME > '05:00:00'
                                ),
                                Horas AS (
                                    SELECT
                                        id_tbl_empleados_hraes,
                                        MIN(hora) AS hora_minima,
                                        MAX(hora) AS hora_maxima
                                    FROM Filtradas
                                    GROUP BY id_tbl_empleados_hraes
                                )
                                SELECT DISTINCT ON (cti.id_tbl_empleados_hraes, hora)
                                    UPPER(emp.rfc),
                                    UPPER(emp.curp),
                                    UPPER(emp.nombre),
                                    UPPER(emp.primer_apellido),
                                    UPPER(emp.segundo_apellido),
                                    TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'YYYY-MM-DD')::DATE AS fecha,
                                    TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS')::TIME AS hora,
                                    CASE
                                        WHEN TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') = hm.hora_minima THEN 'PRIMER REGISTRO'
                                        WHEN TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') = hm.hora_maxima THEN 'ÚLTIMO REGISTRO'
                                    END AS tipo_registro,
                                    UPPER(cta.dispositivo) AS dispositivo,
                                    UPPER(cta.verificacion) AS verificacion,
                                    UPPER(cta.estado) AS estado,
                                    UPPER(cta.evento) AS evento,
                                    cti.no_dispositivo
                                FROM public.ctrl_temp_asistencia cta
                                INNER JOIN public.ctrl_asistencia_info cti
                                    ON cta.no_empleado::TEXT = cti.no_dispositivo::TEXT
                                INNER JOIN public.tbl_empleados_hraes emp 
                                    ON cti.id_tbl_empleados_hraes =
                                        emp.id_tbl_empleados_hraes
                                INNER JOIN Horas hm
                                    ON cti.id_tbl_empleados_hraes = hm.id_tbl_empleados_hraes
                                    AND TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') IN (hm.hora_minima, hm.hora_maxima)
                                WHERE cti.id_cat_asistencia_estatus = 1
                                ORDER BY cti.id_tbl_empleados_hraes, hora;");
        return $query;
    }

    public function addDataInTables()
    {
        $query = pg_query("INSERT INTO public.ctrl_asistencia(
                                fecha, hora, dispositivo, verificacion, estado, evento, id_tbl_empleados_hraes)
                            WITH Filtradas AS (
                                SELECT
                                    cti.id_tbl_empleados_hraes,
                                    TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') AS hora
                                FROM public.ctrl_temp_asistencia cta
                                INNER JOIN public.ctrl_asistencia_info cti
                                    ON cta.no_empleado::TEXT = cti.no_dispositivo::TEXT
                                WHERE
                                    cti.id_cat_asistencia_estatus = 1
                                    AND TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI')::TIME > '05:00:00'
                            ),
                            Horas AS (
                                SELECT
                                    id_tbl_empleados_hraes,
                                    MIN(hora) AS hora_minima,
                                    MAX(hora) AS hora_maxima
                                FROM Filtradas
                                GROUP BY id_tbl_empleados_hraes
                            )
                            SELECT DISTINCT ON (cti.id_tbl_empleados_hraes, hora)
                                TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'YYYY-MM-DD')::DATE AS fecha,
                                TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS')::TIME AS hora,
                                UPPER(cta.dispositivo) AS dispositivo,
                                UPPER(cta.verificacion) AS verificacion,
                                UPPER(cta.estado) AS estado,
                                UPPER(cta.evento) AS evento,
                                cti.id_tbl_empleados_hraes
                            FROM public.ctrl_temp_asistencia cta
                            INNER JOIN public.ctrl_asistencia_info cti
                                ON cta.no_empleado::TEXT = cti.no_dispositivo::TEXT
                            INNER JOIN Horas hm
                                ON cti.id_tbl_empleados_hraes = hm.id_tbl_empleados_hraes
                                AND TO_CHAR(TO_TIMESTAMP(cta.tiempo, 'MM/DD/YYYY HH24:MI'), 'HH24:MI:SS') IN (hm.hora_minima, hm.hora_maxima)
                            WHERE cti.id_cat_asistencia_estatus = 1
                            ORDER BY cti.id_tbl_empleados_hraes, hora;");
        return $query;
    }



    //listado para pbtener el total de asistencias
    public function listarAsistenciaDep($paginator)
    {
        $query = ("SELECT
                    CONCAT(UPPER(public.tbl_empleados_hraes.nombre), ' ',
                        UPPER(public.tbl_empleados_hraes.primer_apellido), ' ',
                        UPPER(public.tbl_empleados_hraes.segundo_apellido)),
                    UPPER(public.tbl_empleados_hraes.rfc),
                    TO_CHAR(public.ctrl_asistencia.fecha, 'DD-MM-YYYY'),
                    TO_CHAR(public.ctrl_asistencia.hora, 'HH24:MI'),
                    UPPER(public.ctrl_asistencia.dispositivo),
                    public.ctrl_asistencia.id_user,
                    public.tbl_empleados_hraes.id_tbl_empleados_hraes,
                    public.ctrl_asistencia.id_ctrl_asistencia  
                FROM public.tbl_empleados_hraes
                INNER JOIN public.ctrl_asistencia 
                    ON public.ctrl_asistencia.id_tbl_empleados_hraes =
                        public.tbl_empleados_hraes.id_tbl_empleados_hraes
                ORDER BY public.ctrl_asistencia.fecha  DESC
                LIMIT 5 OFFSET $paginator;");
        return $query;
    }

    public function listarAsistenciaDepBusqueda($busqueda, $paginator)
    {
        $query = ("SELECT
                        CONCAT(UPPER(public.tbl_empleados_hraes.nombre), ' ',
                            UPPER(public.tbl_empleados_hraes.primer_apellido), ' ',
                            UPPER(public.tbl_empleados_hraes.segundo_apellido)) AS nombre_completo,
                        UPPER(public.tbl_empleados_hraes.rfc) AS rfc,
                        TO_CHAR(public.ctrl_asistencia.fecha, 'DD-MM-YYYY') AS fecha,
                        TO_CHAR(public.ctrl_asistencia.hora, 'HH24:MI') AS hora,
                        UPPER(public.ctrl_asistencia.dispositivo) AS dispositivo,
                        public.ctrl_asistencia.id_user,
                        public.tbl_empleados_hraes.id_tbl_empleados_hraes,
                        public.ctrl_asistencia.id_ctrl_asistencia
                    FROM public.tbl_empleados_hraes
                    INNER JOIN public.ctrl_asistencia 
                        ON public.ctrl_asistencia.id_tbl_empleados_hraes = public.tbl_empleados_hraes.id_tbl_empleados_hraes
                    WHERE (
                        CONCAT(UPPER(public.tbl_empleados_hraes.nombre), ' ',
                            UPPER(public.tbl_empleados_hraes.primer_apellido), ' ',
                            UPPER(public.tbl_empleados_hraes.segundo_apellido)) LIKE '%$busqueda%' 
                        OR UPPER(public.tbl_empleados_hraes.rfc) LIKE '%$busqueda%' 
                        OR TO_CHAR(public.ctrl_asistencia.fecha, 'DD-MM-YYYY') LIKE '%$busqueda%' 
                        OR TO_CHAR(public.ctrl_asistencia.hora, 'HH24:MI') LIKE '%$busqueda%' 
                        OR UPPER(public.ctrl_asistencia.dispositivo) LIKE '%$busqueda%'
                    )
                    ORDER BY public.ctrl_asistencia.fecha DESC
                    LIMIT 5 OFFSET $paginator;");
        return $query;
    }


    public function getInfoAsistencia($id)
    {
        $query = pg_query("SELECT
                                CONCAT(UPPER(public.tbl_empleados_hraes.nombre), ' ',
                                    UPPER(public.tbl_empleados_hraes.primer_apellido), ' ',
                                    UPPER(public.tbl_empleados_hraes.segundo_apellido)),
                                UPPER(public.tbl_empleados_hraes.rfc),
                                TO_CHAR(public.ctrl_asistencia.fecha, 'DD-MM-YYYY'),
                                TO_CHAR(public.ctrl_asistencia.hora, 'HH24:MI'),
                                UPPER(public.ctrl_asistencia.dispositivo),
                                UPPER(public.ctrl_asistencia.verificacion),
                                UPPER(public.ctrl_asistencia.estado),
                                UPPER(public.ctrl_asistencia.evento),
                                public.tbl_empleados_hraes.id_tbl_empleados_hraes
                            FROM public.ctrl_asistencia  
                            INNER JOIN public.tbl_empleados_hraes
                                ON public.ctrl_asistencia.id_tbl_empleados_hraes =
                                    public.tbl_empleados_hraes.id_tbl_empleados_hraes
                            WHERE public.ctrl_asistencia.id_ctrl_asistencia  = $id;");
        return $query;
    }

    public function isTruncate()
    {
        $query = pg_query("TRUNCATE TABLE public.reporte_faltas;");
        return $query;
    }

    public function insertFalta()
    {
        $query = pg_query("INSERT INTO public.reporte_faltas
                            SELECT public.tbl_empleados_hraes.rfc,
                                    public.cat_unidad.nombre,
                                    public.cat_coordinacion.nombre,
                                    public.cat_puesto_hraes.nombre_posicion,
                                    (public.tbl_empleados_hraes.nombre ||' '|| public.tbl_empleados_hraes.primer_apellido ||' '|| public.tbl_empleados_hraes.segundo_apellido) as nombre_completo,
                                    public.ctrl_telefono_hraes.movil,
                                    public.ctrl_asistencia_info.no_dispositivo,
                                    public.ctrl_faltas.fecha,
                                    public.ctrl_faltas.hora,
                                    public.ctrl_faltas.cantidad,
                                    public.cat_retardo_estatus.descripcion
                            FROM public.tbl_empleados_hraes
                                INNER JOIN public.ctrl_asistencia_info -- 
                                ON public.tbl_empleados_hraes.id_tbl_empleados_hraes = public.ctrl_asistencia_info.id_tbl_empleados_hraes
                                INNER JOIN public.ctrl_telefono_hraes
                                ON public.tbl_empleados_hraes.id_tbl_empleados_hraes = public.ctrl_telefono_hraes.id_tbl_empleados_hraes 
                                AND public.ctrl_telefono_hraes.id_cat_estatus = 1
                                INNER JOIN public.tbl_plazas_empleados_hraes
                                ON public.tbl_empleados_hraes.id_tbl_empleados_hraes = public.tbl_plazas_empleados_hraes.id_tbl_empleados_hraes 
                                INNER JOIN public.tbl_control_plazas_hraes
                                ON public.tbl_plazas_empleados_hraes.id_tbl_control_plazas_hraes =  public.tbl_control_plazas_hraes.id_tbl_control_plazas_hraes
                                INNER JOIN public.cat_unidad
                                ON public.tbl_control_plazas_hraes.id_cat_unidad = public.cat_unidad.id_cat_unidad
                                INNER JOIN public.cat_coordinacion
                                ON public.tbl_control_plazas_hraes.id_cat_coordinacion = public.cat_coordinacion.id_cat_coordinacion
                                INNER JOIN public.cat_puesto_hraes
                                ON public.tbl_control_plazas_hraes.id_cat_puesto_hraes =	public.cat_puesto_hraes.id_cat_puesto_hraes
                                INNER JOIN public.ctrl_faltas
                                ON public.tbl_empleados_hraes.id_tbl_empleados_hraes = public.ctrl_faltas.id_tbl_empleados_hraes
                                INNER JOIN public.cat_retardo_estatus
                                ON public.ctrl_faltas.id_cat_retardo_estatus = public.cat_retardo_estatus.id_cat_retardo_estatus;");
        return $query;
    }

    public function selectFaltas()
    {
        $query = pg_query("SELECT rfc, unidad, coordinacion, puesto, nombre, movil, no_dispositivo, fecha, hora, cantidad,estatus
                            FROM public.reporte_faltas;");
        return $query;
    }

}