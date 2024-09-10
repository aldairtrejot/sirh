<?php
include '../librerias.php';

$modelTelefonoM = new ModelTelefonoM();
$bitacoraM = new BitacoraM();

$condicion = [
    'id_ctrl_telefono_hraes' => $_POST['id_object']
];

if (isset($_POST['id_object'])){
    if ($modelTelefonoM-> eliminarByArray($connectionDBsPro, $condicion)){
        $dataBitacora = [
            'nombre_tabla' => 'central.ctrl_telefono_hraes',
            'accion' => 'ELIMINAR',
            'valores' => json_encode($condicion),
            'fecha' => $timestamp,
            'id_users' => $_SESSION['id_user']
        ];
        $bitacoraM->agregarByArray($connectionDBsPro,$dataBitacora,'central.bitacora_hraes');
        echo 'delete';
    }
} 
