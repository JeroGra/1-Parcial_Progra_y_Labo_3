<?php

require_once "./clases/neumatico.php";

use Jeronimo_Granadillo\Neumatico;

$n = new Neumatico($_POST["marca"],$_POST["medidas"],$_POST["precio"]);

echo $n->guardarJSON("./archivos/neumaticos.json");
?>