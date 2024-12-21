<?php

include '../librerias.php';
include '../../../Model/Hraes/Catalogos/CatPuestoM/CatPuestoM.php';

/*
|--------------------------------------------------------------------------
| Funcion para obtener el id de usuario y fecha
|--------------------------------------------------------------------------
| La funcion obtiene el id de usuario y fecha, si alguno de los dos trae datos en 0, es decir datos vacios
| se agregara un _ caso contrario se asignara el valor y se llamara la funcion la cual traera el nombre de usuario
| y la fecha de captura.
|
*/

$catalogoPlazasM = new catalogoPlazasM();
$catalogoPlazasC = new catalogoPlazasC();
$modelPlazasHraes = new modelPlazasHraes();
$catalogoTipoContratcionHraesC = new catalogoTipoContratcionHraesC();
$catalogoTipoContratacionM = new catalogoTipoContratacionM();
$catalogoUnidadResponsableC = new catalogoUnidadResponsableC();
$cataloUnidadResposableM = new cataloUnidadResposableM();
$catalogoPuestosC = new catalogoPuestosC();
$catalogoPuestoM = new catalogoPuestoM();
$catalogoTabularesC = new catalogoTabularesC();
$catalogoTabularesM = new catalogoTabularesM();
$catalogoNivelesC = new catalogoNivelesC();
$catalogoNivelesM = new catalogoNivelesM();
$catZonasPagoM = new CatZonasPagoM();
$catZonaPagoC = new CatZonaPagoC();
$catSelectC = new CatSelectC();
$modelCentroTrabajoHraes = new modelCentroTrabajoHraes();
$catUnidadAdM = new CatUnidadAdM();
$row = new Row();
$contratacionM = new ContratacionM();

$id_object = $_POST['id_object'];

if ($id_object != null) {

    $entity = $row->returnArray($modelPlazasHraes->listarByIdEdit($id_object));
    $niveles = $row->returnArrayById($catalogoPuestoM->nameOfPuesto($entity['id_cat_puesto_hraes']));
    $isValueAux = $row->returnArrayById($catalogoPuestoM->getEditCatAux($entity['id_cat_aux_puesto']));
    $zona = $row->returnArrayById($catalogoPuestoM->getEntity($id_object));

    $plazas = $catalogoPlazasC->returnCatPlazas($catalogoPlazasM->listarByAll()); //ok
    if ($entity['id_cat_tipo_plazas'] != '') {
        $plazas = $catalogoPlazasC->returnCatPLazasByIdObject($catalogoPlazasM->listarByAll(), $row->returnArrayById($catalogoPlazasM->obtenerElemetoById($entity['id_cat_tipo_plazas'])));
    }

    $puesto = $catSelectC->selectByAllCatalogo($catalogoPuestoM->listarByAllPuesto());
    if ($entity['id_cat_aux_puesto'] != '') {
        $puesto = $catSelectC->selectByEditCatalogo($catalogoPuestoM->listarByAllPuesto(), $row->returnArrayById($catalogoPuestoM->editByAllPuesto($isValueAux[1])));
    }

    $unidadCoor = $catSelectC->selectByAllCatalogo($catUnidadAdM->listOfCatCoordinacion());
    if ($entity['id_cat_coordinacion'] != '') {
        $unidadCoor = $catSelectC->selectByEditCatalogo($catUnidadAdM->listOfCatCoordinacion(), $row->returnArrayById($catUnidadAdM->editOfCatCoordinacion($entity['id_cat_coordinacion'])));
    }

    $unidadAdmin = $catSelectC->selectByAllCatalogo($catUnidadAdM->lisOfCatUnidad());
    if ($entity['id_cat_unidad'] != '') {
        $unidadAdmin = $catSelectC->selectByEditCatalogo($catUnidadAdM->lisOfCatUnidad(), $row->returnArrayById($catUnidadAdM->editOfCatUnidad($entity['id_cat_unidad'])));
    }

    $nomEspecifico = $catSelectC->selecStaticByNull();
    if ($entity['id_cat_aux_puesto'] != '') {
        $nomEspecifico = $catSelectC->selectByEditCatalogo($catalogoPuestoM->listOfSpecificName($isValueAux[1]), $row->returnArrayById($catalogoPuestoM->editSpecificName($isValueAux[2])));
    }

    $puestoCategoria = $catSelectC->selecStaticByNull();
    if ($entity['id_cat_aux_puesto'] != '') {
        $puestoCategoria = $catSelectC->selectByEditCatalogo($catalogoPuestoM->listOfCategoName($isValueAux[1], $isValueAux[2]), $row->returnArrayById($catalogoPuestoM->editCatName($isValueAux[3])));
    }

    $programa = $catSelectC->selectByAllCatalogo($contratacionM->listarByAllPrograma());
    if ($entity['id_cat_tipo_programa'] != '') {
        $programa = $catSelectC->selectByEditCatalogo($contratacionM->listarByAllPrograma(), $row->returnArrayById($contratacionM->listarByEditPrograma($entity['id_cat_tipo_programa'])));
    }

    $trabajador = $catSelectC->selectByAllCatalogo($contratacionM->listarByAllTrabajador());
    if ($entity['id_cat_tipo_trabajador'] != '') {
        $trabajador = $catSelectC->selectByEditCatalogo($contratacionM->listarByAllTrabajador(), $row->returnArrayById($contratacionM->listarByAEditTrabajador($entity['id_cat_tipo_trabajador'])));
    }

    $contratacion = $catSelectC->selecStaticByNull();
    if ($entity['id_cat_tipo_contratacion'] != '' && $entity['id_cat_tipo_trabajador'] != '') {
        $contratacion = $catSelectC->selectByEditCatalogo($contratacionM->listarByAllContratacion($entity['id_cat_tipo_trabajador']), $row->returnArrayById($contratacionM->listarByEditContratacion($entity['id_cat_tipo_contratacion'])));
    }

    $caracterNom = $catSelectC->selectByAllCatalogo($contratacionM->listarCatCaracter());
    if($entity['id_cat_caracter_nombramiento'] != ''){
        $caracterNom = $catSelectC->selectByEditCatalogo($contratacionM->listarCatCaracter(), $row->returnArrayById($contratacionM->editCatCaracter($entity['id_cat_caracter_nombramiento'])));
    }

    $raw = [
        'entity' => $entity,
        'niveles' => 'NIVEL',
        'zona' => $zona[0],
        'plazas' => $plazas,
        'puesto' => $puesto,
        'unidadCoor' => $unidadCoor,
        'unidadAdmin' => $unidadAdmin,
        'nomEspecifico' => $nomEspecifico,
        'puestoCategoria' => $puestoCategoria,
        'programa' => $programa,
        'trabajador' => $trabajador,
        'contratacion' => $contratacion,
        'caracterNom' => $caracterNom
    ];
    echo json_encode($raw);

} else { ///Agregar
    $entity = $modelPlazasHraes->listarByNull();
    $id_tbl_centro_trabajo_hraes = $_POST['id_tbl_centro_trabajo_hraes']; //ok
    $plazas = $catalogoPlazasC->returnCatPlazas($catalogoPlazasM->listarByAll()); //ok
    $puesto = $catSelectC->selectByAllCatalogo($catalogoPuestoM->listarByAllPuesto());
    $unidadCoor = $catSelectC->selectByAllCatalogo($catUnidadAdM->listOfCatCoordinacion());
    $unidadAdmin = $catSelectC->selectByAllCatalogo($catUnidadAdM->lisOfCatUnidad());
    $zona = $row->returnArrayById($modelCentroTrabajoHraes->getEntityZona($id_tbl_centro_trabajo_hraes));
    $nomEspecifico = $catSelectC->selecStaticByNull();
    $programa = $catSelectC->selectByAllCatalogo($contratacionM->listarByAllPrograma());
    $trabajador = $catSelectC->selectByAllCatalogo($contratacionM->listarByAllTrabajador());
    $contratacion = $catSelectC->selecStaticByNull();
    $caracterNom = $catSelectC->selectByAllCatalogo($contratacionM->listarCatCaracter());

    $raw = [
        'entity' => $entity,
        'niveles' => 'NIVEL',
        'plazas' => $plazas,
        'puesto' => $puesto,
        'unidadCoor' => $unidadCoor,
        'unidadAdmin' => $unidadAdmin,
        'zona' => $zona[0],
        'nomEspecifico' => $nomEspecifico,
        'puestoCategoria' => $nomEspecifico,
        'programa' => $programa,
        'trabajador' => $trabajador,
        'contratacion' => $contratacion,
        'caracterNom' => $caracterNom
    ];
    echo json_encode($raw);
}

