<?php
require_once "./clases/neumatico.php";

use Jeronimo_Granadillo\Neumatico;

$array = Neumatico::traerJSON("./archivos/neumaticos.json");

/*foreach($array as $obj)
{
   json_encode($obj);
}*/

echo json_encode($array);
?>