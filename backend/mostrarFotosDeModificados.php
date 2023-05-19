<?php
require_once "./clases/neumaticoBd.php";

use Jeronimo_Granadillo\NeumaticoBd;

$arr = array();
$arr = NeumaticoBd::mostrarModificados();

$tabla = "<table><tr><td>ID</td><td>FOTO</td></tr>";

foreach($arr as $n)
{
    $tabla .= "<tr><td>{$n->id}</td><td><img src={$n->pathFoto} width=50 height=50 ></td></tr></br>";
}

$tabla .= "</table>";

echo $tabla;
?>