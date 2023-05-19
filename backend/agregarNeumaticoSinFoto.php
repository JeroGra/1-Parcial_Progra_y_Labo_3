<?php
require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBD;

$neuJSON = $_POST["neumatico_json"];
$nStd = new stdClass;
$nStd = json_decode($neuJSON);

$objRt = new stdClass;
$objRt->exito = false;
$objRt->mensaje = "No se pudo agregar el neumatico";

$n = new NeumaticoBD($nStd->marca, $nStd->medidas,$nStd->precio);

if($n->agregar())
{
    $objRt->exito = true;
    $objRt->mensaje = "Neumatico Agregado!";
}

echo json_encode($objRt);

?>