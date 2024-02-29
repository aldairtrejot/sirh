<?php
include('../../validar_sesion.php'); //Se incluye validar_sesion
include('../../conexion.php'); // Se incluye la conexion a la db

$id_tbl_empleados = base64_decode($_GET['D-F']);
$id_ctrl_telefono = base64_decode($_GET['D-F2']);
$id_tbl_control_plazas = $_GET['D-F3'];
$crypt = base64_encode ($id_tbl_empleados);

try {
$pgs_QRY = pg_delete(
    $connectionDBsPro,
    'ctrl_telefono',
    array(
        'id_ctrl_telefono' => $id_ctrl_telefono
    )
);
if ($pgs_QRY ) {
    header("Location: ../../view/Telefono/Listar.php?D-F=".$crypt.'&D-F3='.$id_tbl_control_plazas); //Regreso a la tabla
} 
} catch (\Throwable $th) {
    header("Location: error.php".$th); //Muestra error
}
