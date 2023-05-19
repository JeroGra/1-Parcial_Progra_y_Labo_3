<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;

$nJSON = $_POST["neumatico_json"];
$nStd = new stdClass;
$nStd = json_decode($nJSON);
$objRt = new stdClass;
$objRt->exito = false;
$objRt->mensaje = "No se pudo eliminar el neumatico";


$arr = NeumaticoBd::traer();
    foreach($arr  as $nObj)
    {
        if($nObj->GetID() == $nStd->id)
        {
            $n = new NeumaticoBd($nObj->GetMarca(),$nObj->GetMedidas(),$nObj->GetPrecio(),$nObj->GetID(),$nObj->GetFoto());
            break;
        }

    }

    if(NeumaticoBD::eliminar($nStd->id))
    { 
        $path = "./archivos/neumaticos_borrados.json";
        $ar = fopen($path,"a");

                $cant = fwrite($ar,$n->ToJSON()."\r\n");

                if($cant > 0)
                {
                    $objRt->exito = true;
                    $objRt->mensaje = "Neumatico eliminado con exito!, Se guardo su registro en ./backend/archivos/neumaticos_norrados.json";
                }

                fclose($ar);

                echo json_encode($objRt);
    }
    else
    {
        echo json_encode($objRt);
    }


?>