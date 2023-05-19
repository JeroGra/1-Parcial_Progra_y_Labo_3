<?php

require_once "./clases/neumaticoBD.php";

use Jeronimo_Granadillo\NeumaticoBd;



if(isset($_POST["neumatico_json"]))
{

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
        echo $n->guardarEnArchivo();
    }
    else
    {
        echo json_encode($objRt);
    }
}
else
{

        $tabla = "<table><tr><td>ID</td><td>MARCA</td><td>MEDIDAS</td><td>PRECIO</td><td>FOTO</td></tr>";

        $ar = fopen("./archivos/neumaticos_borrados.json", "r");
        $neuArr = array();
        $str ="";
        while(!feof($ar))
        {
            $str = fgets($ar);
            if($str != "")
            {
                array_push($neuArr,json_decode($str));	 
            }        	
        }
        fclose($ar);

        if(count($neuArr)>0)
        {
            foreach($neuArr as $neu)
            {
                $tabla .= "<tr><td>{$neu->id}</td><td>{$neu->marca}</td><td>{$neu->medidas}</td><td>{$neu->precio}</td><td><img src={$neu->pathFoto}</td></tr></br>";
            }
        }    
        $tabla .= "</table>";

        echo $tabla;
    
}








?>