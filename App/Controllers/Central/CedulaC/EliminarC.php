<?php
include '../librerias.php';

$modelCedulaM = new ModelCedulaM();
$bitacoraM = new BitacoraM();

$condicion = [
    'id_ctrl_cedula_empleados_hraes' => $_POST['id_object']
];

if (isset($_POST['id_object'])){
    if ($modelCedulaM-> eliminarByArray($connectionDBsPro, $condicion)){
        $dataBitacora = [
            'nombre_tabla' => 'central.ctrl_cedula_empleados_hraes',
            'accion' => 'ELIMINAR',
            'valores' => json_encode($condicion),
            'fecha' => $timestamp,
            'id_users' => $_SESSION['id_user']
        ];
        $bitacoraM->agregarByArray($connectionDBsPro,$dataBitacora,'central.bitacora_hraes');
        echo 'delete';
    }
} 