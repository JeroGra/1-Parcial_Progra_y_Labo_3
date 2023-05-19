<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;

if(isset($_GET["tabla"]) == "mostrar")
{
    $tabla = "<table><tr><td>ID</td><td>MARCA</td><td>MEDIDAS</td><td>PRECIO</td><td>FOTO</td></tr>";
    $array = NeumaticoBd::traer();

    $neu = new stdClass;

    foreach($array as $e)
    {
        $neu = json_decode($e->toJSON());
        $tabla .= "<tr><td>{$neu->id}</td><td>{$neu->marca}</td><td>{$neu->medidas}</td><td>{$neu->precio}</td><td><img src={$neu->pathFoto}  ></td></tr></br>";
    }

    $tabla.="</table>";

    echo $tabla;

}
else
{
    $array = NeumaticoBd::traer();
    $arrRt = array();
        foreach($array as $obj)
        {
            $stdRt = new stdClass;
            $stdRt->id = $obj->GetID();
            $stdRt->marca = $obj->GetMarca();
            $stdRt->medidas = $obj->GetMedidas();
            $stdRt->precio = $obj->GetPrecio();
            $stdRt->pathFoto = $obj->GetFoto();
            array_push($arrRt,$stdRt);
        }

        echo json_encode($arrRt);
}

?>