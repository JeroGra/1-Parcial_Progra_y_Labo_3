<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;

$nJSON = $_POST["neumatico_json"];
$nStd = new stdClass;
$nStd = json_decode($nJSON);
$objRt = new stdClass;
$objRt->exito = false;
$objRt->mensaje = "No se pudo modificar el neumatico";


$neumatico = new NeumaticoBd($nStd->marca,$nStd->medidas,$nStd->precio,$nStd->id);

if($neumatico->Modificar())
{
    $objRt->exito = true;
    $objRt->mensaje = "Neumatico modificado!";
}

echo json_encode($objRt);

?>