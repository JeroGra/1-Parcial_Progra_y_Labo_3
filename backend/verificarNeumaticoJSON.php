<?php
require_once "./clases/neumatico.php";
use Jeronimo_Granadillo\Neumatico;

$n = new Neumatico($_POST["marca"],$_POST["medidas"]);

echo Neumatico::verificarNeumaticoJSON($n);
?>