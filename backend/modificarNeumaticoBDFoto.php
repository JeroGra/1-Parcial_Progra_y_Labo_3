<?php
require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;


$nStd = new stdClass;
$nStd = json_decode($_POST["neumatico_json"]);

$objRt = new stdClass;
$objRt->exito = false;
$objRt->mensaje = "No se pudo modificar el neumatico";

$arr = NeumaticoBd::traer();
$date = date("His");
foreach($arr as $n)
{
    if($nStd->id == $n->GetID())
    {
        $nStd->pathFoto = $n->GetFoto();
    }
}

$destinoModificado = "./neumaticosModificados/".$nStd->id.".".$nStd->marca."."."modificado.".$date .".".pathinfo($_FILES["foto"]["name"],PATHINFO_EXTENSION);
$destinoModificadoParaFront = "./backend/neumaticosModificados/".$nStd->id.".".$nStd->marca."."."modificado.".$date .".".pathinfo($_FILES["foto"]["name"],PATHINFO_EXTENSION);
$neumatico = new NeumaticoBd($nStd->marca,$nStd->medidas,$nStd->precio,$nStd->id,$destinoModificadoParaFront);

function AgregarImagen($destino):bool
{
    return move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
}

if(AgregarImagen($destinoModificado))
{
    if($neumatico->modificar())
    {
        if($nStd->pathFoto != "")
        {
            $pathArr = array();
            $pathArr = str_split($nStd->pathFoto,2);
            unset($pathArr[1]);
            unset($pathArr[2]);
            unset($pathArr[3]);
            unset($pathArr[4]);
            $nStd->pathFoto = implode($pathArr);
            $objRt->exito =  unlink($nStd->pathFoto);
        }
        else
        {
            $objRt->exito = true;
        }  

        $ar = fopen("./neumaticosModificados/neumaticos.json","a");
        $cant = fwrite($ar,$neumatico->ToJSON()."\r\n");
        if($cant > 0)
        {
            $objRt->mensaje = "Neumatico modificado correctamente!";
            echo json_encode($objRt);
        }
        fclose($ar);
    }
}
?>