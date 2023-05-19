<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;

$objRt = new stdClass;
$objRt->exito = false;
$objRt->mensaje = "No se pudo agregar el neumatico";

$path = $_POST["marca"]."_".date("His").".".pathinfo($_FILES["foto"]["name"],PATHINFO_EXTENSION);
$destino = "./neumaticos/imagenes/". $path;
$destinoFront = "./backend/neumaticos/imagenes/". $path;

$neumatico = new NeumaticoBd($_POST["marca"],$_POST["medidas"],$_POST["precio"],0,$destinoFront);

if($neumatico->existe(NeumaticoBd::traer()))
{
    $objRt->mensaje.=", debido a que ya existe";
    echo json_encode($objRt);
}
else
{
    function AgregarImagen($destino):bool
    {
        return move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
    }
    

    if(AgregarImagen($destino))
    {
        if($neumatico->Agregar())
        {
            $objRt->exito = true;
            $objRt->mensaje = "Neumatico Agregado!";
        }

        echo json_encode($objRt);
    }

}

?>