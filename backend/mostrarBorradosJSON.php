<?php
require_once "./clases/neumaticoBd.php";

use Jeronimo_Granadillo\NeumaticoBd;

echo json_encode(NeumaticoBd::mostrarBorradosJson());
?>