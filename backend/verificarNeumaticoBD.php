<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;

$nJSON = $_POST["obj_neumatico"];
$nStd = new stdClass;
$nStd = json_decode($nJSON);
$neumatico = new NeumaticoBd($nStd->marca, $nStd->medidas);

if($neumatico->existe(NeumaticoBd::traer()))
{
    echo $neumatico->toJSON();
}
else
{
    $js = "";
    echo json_encode($js);
}

?>