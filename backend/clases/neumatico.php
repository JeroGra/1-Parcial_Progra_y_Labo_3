<?php
namespace  Jeronimo_Granadillo
{
    use stdClass;
    class Neumatico
    {
        protected string $marca;
        protected string $medidas;
        protected float $precio;

        public function __construct(string $marca = "", string $medidas ="", float $precio = 0)
        {
            $this->marca = $marca;
            $this->medidas = $medidas;
            $this->precio = floatval($precio);
        }

        public function toJSON():string
        {
            return json_encode(get_object_vars($this));
        }


        public function guardarJSON($path) : string
        {
            $objRespuesta = new stdClass();
            $objRespuesta->exito = false;
            $objRespuesta->mensaje = "Ocurrio un error. No se pudo guardar el archivo";

            $ar = fopen($path,"a");

            $cant = fwrite($ar,$this->ToJSON()."\r\n");

            if($cant > 0)
            {
                $objRespuesta->exito = true;
                $objRespuesta->mensaje = "Registro guardado con exito!";
            }

            fclose($ar);

            return json_encode(get_object_vars($objRespuesta));
        }


        public static function traerJSON($path)
        {
            
            $retorno = array();
            $str ="";
            $ar = fopen($path, "r");
    
            while(!feof($ar))
            {
                $str = fgets($ar);
                if($str != "")
                {
                    array_push($retorno,json_decode($str));	 
                }        	
            }
    
            fclose($ar);
    
            return $retorno;
        }


        public static function verificarNeumaticoJSON(Neumatico $neumatico):string
        {
            $path = "./archivos/neumaticos.json";

            $arrNeumaticos = array();

            $totalPrecios = 0.0;

            $objRespuesta = new stdClass();
            $objRespuesta->exito = false;
            $objRespuesta->mensaje = "No se encontro el neumatico";

            $arrNeumaticos = Neumatico::traerJSON($path);

            foreach($arrNeumaticos as $objNeumatico)
            {
                if($neumatico->marca == $objNeumatico->marca && $neumatico->medidas == $objNeumatico->medidas)
                {
                    $objRespuesta->exito = true;
                    $totalPrecios += $objNeumatico->precio;
                }
            }

            if($objRespuesta->exito)
            {
                $objRespuesta->mensaje = "Los neumaticos $neumatico->marca de la medida $neumatico->medidas se encuentran registrados. Su precio total es de $totalPrecios";
            }

            return json_encode(get_object_vars($objRespuesta));
        }

    }
}
?>