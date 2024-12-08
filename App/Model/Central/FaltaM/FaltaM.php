<?php

class FaltaModelM
{
    function listarById($id_object, $paginator)
    {
        $listado = pg_query("SELECT
                                    central.ctrl_faltas.id_ctrl_faltas,
                                    CASE 
                                        WHEN es_por_retardo THEN 'FALTA POR RETARDO'
                                        ELSE 'FALTA'
                                    END,
                                    TO_CHAR(central.ctrl_faltas.fecha_desde, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha_hasta, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha_registro, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.hora, 'HH:MM'),
                                    UPPER(central.ctrl_faltas.codigo_certificacion),
                                    UPPER(central.cat_retardo_estatus.descripcion),
                                    UPPER(central.cat_retardo_tipo.descripcion),
                                    UPPER(central.ctrl_faltas.observaciones),
                                    central.ctrl_faltas.id_user
                                FROM central.ctrl_faltas
                                LEFT JOIN central.cat_retardo_estatus
                                    ON central.ctrl_faltas.id_cat_retardo_estatus =
                                        central.cat_retardo_estatus.id_cat_retardo_estatus
                                LEFT JOIN central.cat_retardo_tipo
                                    ON central.ctrl_faltas.id_cat_retardo_tipo =
                                        central.cat_retardo_tipo.id_cat_retardo_tipo
                                WHERE central.ctrl_faltas.id_tbl_empleados_hraes = $id_object
                                ORDER BY central.ctrl_faltas.id_ctrl_faltas DESC
                                LIMIT 3 OFFSET $paginator;");
        return $listado;
    }

    function listarEditById($id_object)
    {
        $listado = pg_query("SELECT *
                            FROM central.ctrl_faltas
                            WHERE id_ctrl_faltas = $id_object
                            LIMIT 1;");
        return $listado;
    }

    function listarByNull()
    {
        return $raw = [
            'id_ctrl_retardo_hraes' => null,
            'fecha' => null,
            'hora_entrada' => null,
            'minuto_entrada' => null,
            'hora_salida' => null,
            'minuto_salida' => null,
            'id_tbl_empleados_hraes' => null,
        ];
    }

    function listarByBusqueda($id_object, $busqueda, $paginator)
    {
        $listado = pg_query("SELECT
                                    central.ctrl_faltas.id_ctrl_faltas,
                                    CASE 
                                        WHEN es_por_retardo THEN 'FALTA POR RETARDO'
                                        ELSE 'FALTA'
                                    END,
                                    TO_CHAR(central.ctrl_faltas.fecha_desde, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha_hasta, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha_registro, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.fecha, 'DD/MM/YYYY'),
                                    TO_CHAR(central.ctrl_faltas.hora, 'HH:MM'),
                                    UPPER(central.ctrl_faltas.codigo_certificacion),
                                    UPPER(central.cat_retardo_estatus.descripcion),
                                    UPPER(central.cat_retardo_tipo.descripcion),
                                    UPPER(central.ctrl_faltas.observaciones),
                                    central.ctrl_faltas.id_user
                                FROM central.ctrl_faltas
                                LEFT JOIN central.cat_retardo_estatus
                                    ON central.ctrl_faltas.id_cat_retardo_estatus =
                                        central.cat_retardo_estatus.id_cat_retardo_estatus
                                LEFT JOIN central.cat_retardo_tipo
                                    ON central.ctrl_faltas.id_cat_retardo_tipo =
                                        central.cat_retardo_tipo.id_cat_retardo_tipo
                                WHERE central.ctrl_faltas.id_tbl_empleados_hraes = $id_object
                                AND (
                                    TO_CHAR(central.ctrl_faltas.fecha_desde, 'DD/MM/YYYY')::TEXT LIKE '%$busqueda%' OR 
                                    TO_CHAR(central.ctrl_faltas.fecha_hasta, 'DD/MM/YYYY')::TEXT LIKE '%$busqueda%' OR
                                    TO_CHAR(central.ctrl_faltas.fecha_registro, 'DD/MM/YYYY')::TEXT LIKE '%$busqueda%' OR
                                    TO_CHAR(central.ctrl_faltas.fecha, 'DD/MM/YYYY')::TEXT LIKE '%$busqueda%' OR
                                    TO_CHAR(central.ctrl_faltas.hora, 'HH:MM')::TEXT LIKE '%$busqueda%' OR
                                    TRIM(UNACCENT(UPPER(central.ctrl_faltas.codigo_certificacion))) LIKE '%$busqueda%' OR
                                    TRIM(UNACCENT(UPPER(central.cat_retardo_estatus.descripcion))) LIKE '%$busqueda%' OR
                                    TRIM(UNACCENT(UPPER(central.cat_retardo_tipo.descripcion))) LIKE '%$busqueda%' OR
                                    TRIM(UNACCENT(UPPER(central.ctrl_faltas.observaciones))) LIKE '%$busqueda%' 
                                )
                                ORDER BY central.ctrl_faltas.id_ctrl_faltas DESC
                                LIMIT 3 OFFSET $paginator;");
        return $listado;
    }

    function editarByArray($conexion, $datos, $condicion)
    {
        $pg_update = pg_update($conexion, 'central.ctrl_faltas', $datos, $condicion);
        return $pg_update;
    }

    function agregarByArray($conexion, $datos)
    {
        $pg_add = pg_insert($conexion, 'central.ctrl_faltas', $datos);
        return $pg_add;
    }

    function eliminarByArray($conexion, $condicion)
    {
        $pgs_delete = pg_delete($conexion, 'central.ctrl_faltas', $condicion);
        return $pgs_delete;
    }


    public function catFaltaEstatus()
    {
        $query = pg_query("SELECT 
                                central.cat_retardo_estatus.id_cat_retardo_estatus,
                                UPPER(central.cat_retardo_estatus.descripcion)
                            FROM central.cat_retardo_estatus
                            ORDER BY central.cat_retardo_estatus.descripcion ASC;");
        return $query;
    }

    public function catFaltaEstatusEdit($id)
    {
        $query = pg_query("SELECT 
                                central.cat_retardo_estatus.id_cat_retardo_estatus,
                                UPPER(central.cat_retardo_estatus.descripcion)
                            FROM central.cat_retardo_estatus
                            WHERE central.cat_retardo_estatus.id_cat_retardo_estatus = $id;");
        return $query;
    }
    public function catFaltaTipo()
    {
        $query = pg_query("SELECT 
                                central.cat_retardo_tipo.id_cat_retardo_tipo,
                                UPPER(central.cat_retardo_tipo.descripcion)
                            FROM central.cat_retardo_tipo
                            ORDER BY central.cat_retardo_tipo.descripcion ASC;");
        return $query;
    }

    public function catFaltaTipoEdit($id)
    {
        $query = pg_query("SELECT 
                                central.cat_retardo_tipo.id_cat_retardo_tipo,
                                UPPER(central.cat_retardo_tipo.descripcion)
                            FROM central.cat_retardo_tipo
                            WHERE central.cat_retardo_tipo.id_cat_retardo_tipo = $id;");
        return $query;
    }

    /// reporte de faltas para todos los empleados
    

    public function getAllFaltas($paginator)
{
    $query = ("SELECT 
                    CONCAT(UPPER(central.tbl_empleados_hraes.nombre), ' ',
                        UPPER(central.tbl_empleados_hraes.primer_apellido), ' ',
                        UPPER(central.tbl_empleados_hraes.segundo_apellido)) AS nombre_completo,
                    UPPER(central.tbl_empleados_hraes.rfc) AS rfc,
                    'FALTA POR RETARDO',
                    to_char(central.ctrl_faltas.fecha, 'DD-MM-YYYY'),
                    to_char(central.ctrl_faltas.hora, 'HH24:MI'),
                    central.ctrl_faltas.cantidad,
                    UPPER(central.cat_retardo_tipo.descripcion),
                    UPPER(central.cat_retardo_estatus.descripcion),
                    central.ctrl_faltas.id_user,
                    central.ctrl_faltas.id_ctrl_faltas
                FROM central.ctrl_faltas
                INNER JOIN central.cat_retardo_tipo
                    ON central.ctrl_faltas.id_cat_retardo_tipo =
                        central.cat_retardo_tipo.id_cat_retardo_tipo
                INNER JOIN central.cat_retardo_estatus
                    ON central.ctrl_faltas.id_cat_retardo_estatus =
                        central.cat_retardo_estatus.id_cat_retardo_estatus
                INNER JOIN central.tbl_empleados_hraes
                    ON central.ctrl_faltas.id_tbl_empleados_hraes =
                        central.tbl_empleados_hraes.id_tbl_empleados_hraes 
                WHERE central.ctrl_faltas.es_por_retardo 
                AND NOT EXISTS (
                    SELECT 1
                    FROM central.masivo_ctrl_temp_faltas_just J
                    JOIN central.tbl_empleados_hraes E ON J.rfc = E.rfc
                    WHERE central.ctrl_faltas.id_tbl_empleados_hraes = E.id_tbl_empleados_hraes
                    AND central.ctrl_faltas.fecha = J.fecha::DATE
                    AND central.ctrl_faltas.id_cat_retardo_tipo = (
                        SELECT id_cat_retardo_tipo
                        FROM central.cat_retardo_tipo
                        WHERE descripcion = UPPER(J.tipo_falta)
                    )
                )
                ORDER BY central.ctrl_faltas.fecha DESC
                LIMIT 5 OFFSET $paginator;");
    return $query;
}


public function getAllFaltasBusqueda($busqueda, $paginator)
{
    $query = ("SELECT 
                    CONCAT(UPPER(central.tbl_empleados_hraes.nombre), ' ',
                        UPPER(central.tbl_empleados_hraes.primer_apellido), ' ',
                        UPPER(central.tbl_empleados_hraes.segundo_apellido)) AS nombre_completo,
                    UPPER(central.tbl_empleados_hraes.rfc) AS rfc,
                    'FALTA POR RETARDO',
                    to_char(central.ctrl_faltas.fecha, 'DD-MM-YYYY') AS fecha,
                    to_char(central.ctrl_faltas.hora, 'HH24:MI') AS hora,
                    central.ctrl_faltas.cantidad,
                    UPPER(central.cat_retardo_tipo.descripcion) AS tipo,
                    UPPER(central.cat_retardo_estatus.descripcion) AS estatus,
                    central.ctrl_faltas.id_user,
                    central.ctrl_faltas.id_ctrl_faltas
                FROM central.ctrl_faltas
                INNER JOIN central.cat_retardo_tipo
                    ON central.ctrl_faltas.id_cat_retardo_tipo = central.cat_retardo_tipo.id_cat_retardo_tipo
                INNER JOIN central.cat_retardo_estatus
                    ON central.ctrl_faltas.id_cat_retardo_estatus = central.cat_retardo_estatus.id_cat_retardo_estatus
                INNER JOIN central.tbl_empleados_hraes
                    ON central.ctrl_faltas.id_tbl_empleados_hraes = central.tbl_empleados_hraes.id_tbl_empleados_hraes 
                WHERE central.ctrl_faltas.es_por_retardo 
                AND (
                        CONCAT(UPPER(central.tbl_empleados_hraes.nombre), ' ',
                            UPPER(central.tbl_empleados_hraes.primer_apellido), ' ',
                            UPPER(central.tbl_empleados_hraes.segundo_apellido)) LIKE '%$busqueda%'
                        OR UPPER(central.tbl_empleados_hraes.rfc) LIKE '%$busqueda%'
                        OR to_char(central.ctrl_faltas.fecha, 'DD-MM-YYYY') LIKE '%$busqueda%'
                        OR to_char(central.ctrl_faltas.hora, 'HH24:MI') LIKE '%$busqueda%'
                        OR CAST(central.ctrl_faltas.cantidad AS TEXT) LIKE '%$busqueda%'
                        OR UPPER(central.cat_retardo_tipo.descripcion) LIKE '%$busqueda%'
                        OR UPPER(central.cat_retardo_estatus.descripcion) LIKE '%$busqueda%'
                    )
                      
             
                ORDER BY central.ctrl_faltas.fecha DESC
                LIMIT 5 OFFSET $paginator;");
    return $query;
}


        ///SCRIP PARA CALCULO DE FLATAS DE FORMA MASIVApublic function process_1()
    public function process_1()
    {
        $query = pg_query(" INSERT INTO central.ctrl_retardo (
                fecha, 
                hora, 
                observaciones, 
                id_cat_retardo_tipo, 
                id_cat_retardo_estatus, 
                id_tbl_empleados_hraes, 
                id_user
            )
            SELECT 
                Entradas.fecha,
                Entradas.hora, 
                NULL AS observaciones,
                1 AS id_cat_retardo_tipo,  -- Entrada 
                5 AS id_cat_retardo_estatus,  -- Por Aplicar
                Entradas.id_tbl_empleados_hraes,
                NULL AS id_user
            FROM 
            (
                SELECT 
                    MIN(ctrl_asistencia.hora) AS hora, -- Tomar la primera checada
                    ctrl_asistencia.fecha, 
                    ctrl_asistencia.id_tbl_empleados_hraes
                FROM central.ctrl_asistencia
                WHERE ctrl_asistencia.fecha NOT IN (SELECT fecha FROM central.cat_dias_festivos) -- Excluir días no laborables
                GROUP BY ctrl_asistencia.fecha, ctrl_asistencia.id_tbl_empleados_hraes
            ) AS Entradas
            WHERE 
                Entradas.hora >= '09:16:00' -- Hora mínima para retardo
                AND Entradas.hora <= '09:30:00' -- Hora máxima para retardo
                AND NOT EXISTS ( -- Validar que no exista un registro duplicado en ctrl_retardo
                    SELECT 1
                    FROM central.ctrl_retardo
                    WHERE central.ctrl_retardo.fecha = Entradas.fecha
                      AND central.ctrl_retardo.hora = Entradas.hora
                      AND central.ctrl_retardo.id_tbl_empleados_hraes = Entradas.id_tbl_empleados_hraes
                );
        ");
        return $query;
    }
    

    public function process_2()
    {
        $query = pg_query(" INSERT INTO central.ctrl_faltas (
                id_tbl_empleados_hraes,
                observaciones,
                es_por_retardo,
                id_cat_retardo_tipo,
                id_cat_retardo_estatus,
                id_user,
                fecha,
                hora,
                cantidad
            )
            SELECT 
                Entradas.id_tbl_empleados_hraes,
                NULL AS observaciones,
                TRUE AS es_por_retardo,
                1 AS id_cat_retardo_tipo, -- Entrada
                7 AS id_cat_retardo_estatus, -- Retardo Mayor
                NULL AS id_user,
                Entradas.fecha,
                Entradas.hora,
                1 AS cantidad
            FROM (
                SELECT 
                    MIN(ctrl_asistencia.hora) AS hora, -- Tomar la primera checada
                    ctrl_asistencia.fecha,
                    ctrl_asistencia.id_tbl_empleados_hraes
                FROM central.ctrl_asistencia
                WHERE ctrl_asistencia.fecha NOT IN (SELECT fecha FROM central.cat_dias_festivos) -- Excluir días festivos
                GROUP BY ctrl_asistencia.fecha, ctrl_asistencia.id_tbl_empleados_hraes
            ) AS Entradas
            WHERE 
                Entradas.hora > '09:30:00' -- Hora mínima para falta por retardo
                AND NOT EXISTS ( -- Evita duplicados en ctrl_faltas
                    SELECT 1
                    FROM central.ctrl_faltas
                    WHERE central.ctrl_faltas.fecha = Entradas.fecha
                      AND central.ctrl_faltas.hora = Entradas.hora
                      AND central.ctrl_faltas.id_tbl_empleados_hraes = Entradas.id_tbl_empleados_hraes
                );
        ");
        return $query;
    }
    

    public function process_3()
    {
        $query = pg_query("INSERT INTO central.ctrl_faltas (
                id_tbl_empleados_hraes,
                observaciones,
                es_por_retardo,
                id_cat_retardo_tipo,
                id_cat_retardo_estatus,
                id_user,
                fecha,
                hora,
                cantidad
            )
            SELECT 
                Salidas.id_tbl_empleados_hraes,
                NULL AS observaciones,
                TRUE AS es_por_retardo,
                2 AS id_cat_retardo_tipo, -- Salida
                4 AS id_cat_retardo_estatus, -- Falta por salida anticipada
                NULL AS id_user,
                Salidas.fecha,
                Salidas.hora,
                1 AS cantidad
            FROM (
                SELECT 
                    MAX(ctrl_asistencia.hora) AS hora, -- Tomar el último registro de checada
                    ctrl_asistencia.fecha,
                    ctrl_asistencia.id_tbl_empleados_hraes
                FROM central.ctrl_asistencia
                WHERE ctrl_asistencia.fecha NOT IN (SELECT fecha FROM central.cat_dias_festivos) -- Excluir días festivos
                GROUP BY ctrl_asistencia.fecha, ctrl_asistencia.id_tbl_empleados_hraes
            ) AS Salidas
            WHERE 
                Salidas.hora < '19:00:00' -- Salida antes de las 7:00 PM
                AND NOT EXISTS ( -- Evita duplicados en ctrl_faltas
                    SELECT 1
                    FROM central.ctrl_faltas
                    WHERE central.ctrl_faltas.fecha = Salidas.fecha
                      AND central.ctrl_faltas.hora = Salidas.hora
                      AND central.ctrl_faltas.id_tbl_empleados_hraes = Salidas.id_tbl_empleados_hraes
                );
        ");
        return $query;
    }
    

    public function process_4()
    {
        $query = pg_query("INSERT INTO central.ctrl_faltas (
                cantidad,
                id_tbl_empleados_hraes,
                es_por_retardo,
                id_cat_retardo_tipo,
                id_cat_retardo_estatus,
                fecha
            )
            SELECT 
                CASE 
                    WHEN (NoRet >= 3 AND NoRet <  6) THEN 1  									-- Una falta cuando los retardos estan entre 3 y 6
			        WHEN (NoRet >= 6 AND NoRet <  9) THEN 2										-- Dos faltas cuando los retardos estan entre 6 y 9
			        WHEN (NoRet >= 9 AND NoRet < 12) THEN 3										-- Tres faltas cuando los retardos estan entre 9 y 12
			        WHEN (NoRet >= 12 			   ) THEN 4										-- Cuatro faltas 
                END AS Faltas, id_tbl_empleados_hraes, TRUE es_por_retardo, 3 id_cat_retardo_tipo, 6 id_cat_retardo_estatus,
		CASE WHEN SUBSTRING(CURRENT_DATE::TEXT,9,2) <= '15' 
			 THEN ('15'||'/'||SUBSTRING(CURRENT_DATE::TEXT,6,2)||'/'||SUBSTRING(CURRENT_DATE::TEXT,1,4))::DATE	-- Toma el día 15 del Mes
		    ELSE (date_trunc('MONTH', CURRENT_DATE) + INTERVAL '1 MONTH' - INTERVAL '1 DAY')::DATE					-- Toma el último día del Mes
		    END AS fecha    --Para este caso tomará el día 15 del mes cuando la fecha actual sea menor o igual a 15, de lo contrario el último día del mes
                FROM(SELECT COUNT(*) NoRet, id_tbl_empleados_hraes
		FROM central.ctrl_retardo
	WHERE fecha <= '15/08/2024' 							-- ### Aquí ponemos una condición para una fecha mayor o igual, o se quita para procesar todo
	GROUP BY id_tbl_empleados_hraes
	HAVING COUNT(*) >= 3
	) AS Retardos;
        ");
        return $query;
    }
    

    public function process_5()
    {
        $query = pg_query("--########################
                            --### PROCESO VALIDADO ### 5
                            --########################
                            --==========================================================================
                            --### Script para Actualizar la Fecha de Último Proceso en Configuración ###
                            --==========================================================================
                           UPDATE central.cat_asistencia_config
SET fecha_ult_proceso = CURRENT_DATE 
WHERE id_cat_asistencia_config = 1; 
(SELECT fecha_ult_proceso FROM central.cat_asistencia_config WHERE id_cat_asistencia_config = 1) -- Agregar esta condición");
        return $query;
    }

    public function process_6()
    {
        $query = pg_query("INSERT INTO central.ctrl_faltas (id_tbl_empleados_hraes,es_por_retardo,id_cat_retardo_tipo,id_cat_retardo_estatus,fecha)

        SELECT CASE WHEN (NoRet >= 3 AND NoRet <  6) THEN 1
                    WHEN (NoRet >= 6 AND NoRet <  9) THEN 2
                    WHEN (NoRet >= 9 AND NoRet < 12) THEN 3
                    WHEN (NoRet >= 12 			   ) THEN 4
                END AS Falta
        FROM
            (
            SELECT COUNT(*)+6 NoRet
                FROM central.ctrl_retardo
            WHERE fecha <= '15/08/2024'
            AND id_tbl_empleados_hraes = 3925
            ) AS Retardos;
            ");
        return $query;

    }        

    public function process_7()
    { 
        $query = pg_query("INSERT INTO central.ctrl_faltas (
    id_tbl_empleados_hraes,
    observaciones,
    es_por_retardo,
    id_cat_retardo_tipo,
    id_cat_retardo_estatus,
    id_user,
    fecha,
    hora,
    cantidad
)
SELECT 
    e.id_tbl_empleados_hraes,                      -- ID del empleado
    'FALTA POR OMISIÓN' AS observaciones,          -- Observación
    FALSE AS es_por_retardo,                       -- No es por retardo
    3 AS id_cat_retardo_tipo,                      -- Tipo de falta por omisión
    8 AS id_cat_retardo_estatus,                   -- Estatus para falta por omisión
    NULL AS id_user,                               -- Usuario que procesa
    f.fecha,                                       -- Fecha omitida
    '00:00:00' AS hora,                            -- Hora predeterminada
    1 AS cantidad                                  -- Una falta
FROM (
    -- Generar todas las combinaciones de empleados y fechas del rango
    SELECT 
        e.id_tbl_empleados_hraes,
        g.fecha
    FROM 
        central.tbl_empleados_hraes e
    CROSS JOIN (
        -- Generar el rango de fechas a evaluar
        SELECT 
            GENERATE_SERIES(
                (SELECT fecha_inicio FROM central.cat_asistencia_config WHERE id_cat_asistencia_config = 1),
                (SELECT fecha_fin FROM central.cat_asistencia_config WHERE id_cat_asistencia_config = 1),
                '1 day'::INTERVAL
            )::DATE AS fecha
    ) g
) f
LEFT JOIN central.ctrl_asistencia a
    ON f.id_tbl_empleados_hraes = a.id_tbl_empleados_hraes
    AND f.fecha = a.fecha -- Verificar si ya hay un registro de asistencia en esa fecha
WHERE 
    a.fecha IS NULL -- No hay registros de asistencia en esa fecha
    AND NOT EXISTS ( -- Evitar duplicados en ctrl_faltas
        SELECT 1 
        FROM central.ctrl_faltas cf
        WHERE cf.id_tbl_empleados_hraes = f.id_tbl_empleados_hraes
          AND cf.fecha = f.fecha
          AND cf.id_cat_retardo_tipo = 3 -- Ya existe una falta por omisión
    );");
        return $query;

    }        


    public function truncateTableTmpFaltas()
    {
        $query = pg_query("TRUNCATE TABLE central.masivo_ctrl_temp_faltas_just RESTART IDENTITY;");
    }

    public function addInfoFaltaTemp(
        $rfc,
        $fecha,
        $observaciones,
        $tipo,
        $tipo_falta
    ) {
        $query = pg_query("INSERT INTO central.masivo_ctrl_temp_faltas_just(
                            rfc, fecha, observaciones, tipo, tipo_falta)
                            VALUES ('$rfc', '$fecha', '$observaciones', '$tipo', '$tipo_falta ');");
        return $query;
    }

    public function udpdateFaltas()
    {
        $query = pg_query(" UPDATE central.ctrl_retardo R
                            SET id_cat_retardo_estatus = 3, -- JUSTIFICADA
                                observaciones = J.observaciones	-- Observaciones del Justificación
                            FROM central.masivo_ctrl_temp_faltas_just J	-- Justificaciones
                                JOIN central.tbl_empleados_hraes  E ON  J.rfc = E.rfc
                            WHERE R.id_tbl_empleados_hraes = E.id_tbl_empleados_hraes
                            AND R.fecha = J.fecha::DATE
                            AND UPPER(J.tipo) = 'RETARDO';
                            
                            -- #Script Para Actualización de Faltas #
                            -- ###Proceso Validado ##################
                            UPDATE central.ctrl_faltas F
                            SET id_cat_retardo_estatus = 3, -- JUSTIFICADA
                                observaciones = J.observaciones	-- Observaciones del Justificación
                            FROM central.masivo_ctrl_temp_faltas_just J	-- Justificaciones
                                JOIN central.tbl_empleados_hraes  E ON  J.rfc = E.rfc
                            WHERE F.id_tbl_empleados_hraes = E.id_tbl_empleados_hraes
                            AND F.fecha = J.fecha::DATE
                            AND UPPER(J.tipo) = 'FALTA'
                            AND F.id_cat_retardo_tipo = (SELECT id_cat_retardo_tipo
                                                        FROM central.cat_retardo_tipo
                                                        WHERE descripcion = UPPER(J.tipo_falta));");
        return $query;
    }

}