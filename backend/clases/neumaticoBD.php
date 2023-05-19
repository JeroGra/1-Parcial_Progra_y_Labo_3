<?php
namespace Jeronimo_Granadillo
{
    require_once "neumatico.php";
    require_once "IParte1.php";
    require_once "accesoDatos.php";
    require_once "IParte2.php";
    require_once "IParte3.php";
    require_once "IParte4.php";

    use Jeronimo_Granadillo\IParte1;
    use Jeronimo_Granadillo\IParte2;
    use Jeronimo_Granadillo\Neumatico;
    use POO\AccesoDatos;
    use PDO;
    use stdClass;

    class NeumaticoBd extends Neumatico implements IParte1, IParte2, IParte3, IParte4
    {
        protected int $id;
        protected $pathFoto;


        public function __construct(string $marca = "", string $medidas ="", float $precio = 0,$id = 0,$pathFoto = "")
        {
            parent::__construct($marca,$medidas,$precio);
            $this->id = intval($id);
            $this->pathFoto = $pathFoto;
        }

        public function toJSON():string
        {
            return json_encode(get_object_vars($this));
        }

        public function SetFoto(string $foto)
        {
            $this->pathFoto = $foto;
        }

        public function GetID()
        {
            return $this->id;
        }
        public function GetMarca()
        {
            return $this->marca;
        }
        public function GetMedidas()
        {
            return $this->medidas;
        }
        public function GetPrecio()
        {
            return $this->precio;
        }
        public function GetFoto()
        {
            return $this->pathFoto;
        }

        public function agregar(): bool
        {
            $rt = false;
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
            $consulta =$objetoAccesoDato->retornarConsulta("INSERT INTO neumaticos (marca, medidas, precio, foto)"
                                                        . "VALUES(:marca, :medidas, :precio, :foto)");
            
            $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
            $consulta->bindValue(':medidas', $this->medidas, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':foto', $this->pathFoto, PDO::PARAM_STR);
    
            if($consulta->execute() == 1)   
            {
                $rt = true;
            }
            
            return $rt;
            
        }

        public static function traer():array
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $arr = array();
        
            $consultaUs = $objetoAccesoDato->retornarConsulta("SELECT id, marca AS marca, medidas AS medidas, precio AS precio, foto AS pathFoto "
                                                            . "FROM neumaticos");        
            $consultaUs->execute();
            
            $consultaUs->setFetchMode(PDO::FETCH_INTO, new NeumaticoBd());

            foreach($consultaUs as $e)
            {
                $rtObj = new NeumaticoBd($e->marca,$e->medidas,$e->precio,$e->id,$e->pathFoto);
                array_push($arr,$rtObj);
            }
            return $arr; 
        }

        public static function eliminar($id): bool
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            
            $consulta =$objetoAccesoDato->retornarConsulta("DELETE FROM neumaticos WHERE id = :id");
            
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);

            $usExiste = false;

            $ar = NeumaticoBd::traer();
    
            foreach($ar as $n)
            {
                if($n->id == $id)
                {
                    $usExiste = true;
                    break;
                }
            }

            if($usExiste)
            {
                $usExiste = false;
                if($consulta->execute())
                {
                    $usExiste = true;
                }
            }
    
            return $usExiste;
        }

        public function modificar(): bool
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        
            $consulta =$objetoAccesoDato->retornarConsulta("UPDATE neumaticos SET marca = :marca, medidas = :medidas, precio = :precio, 
                                                            foto = :foto WHERE id = :id");
            
            $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
            $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
            $consulta->bindValue(':medidas', $this->medidas, PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $consulta->bindValue(':foto', $this->pathFoto, PDO::PARAM_STR);

            $rt = false;
    
    
            if($consulta->execute() == 1)
            {
                $rt = true;
            }

            return $rt;
        }

        public function existe(array $neumaticos):bool
        {
            $rt = false;

            foreach($neumaticos as $n)
            {
                if($this->marca == $n->marca && $this->medidas == $n->medidas)
                {
                    $rt = true;
                    break;
                }

            }

            return $rt;
            
        }

        public function guardarEnArchivo():string
        {
            $date = date("His").".jpg";
            $nuevoDirectorio = "./neumaticosBorrados/".$this->id.".".$this->marca.".". $date;
            $pathFrontDeNuevoDirectorio = "./backend/neumaticosBorrados/".$this->id.".".$this->marca.".". $date;
            $path = "./archivos/neumaticos_borrados.json";
            $objRt = new stdClass;
            $objRt->mensaje = "No se ha pudo eliminar el neumatico";
            $objRt->exito = false;
           
            $pathArr = str_split($this->pathFoto,2);
            unset($pathArr[1]);
            unset($pathArr[2]);
            unset($pathArr[3]);
            unset($pathArr[4]);
            $this->pathFoto = implode($pathArr);

            if (copy( $this->pathFoto,$nuevoDirectorio) && unlink( $this->pathFoto)) 
            {
                $this->pathFoto = $pathFrontDeNuevoDirectorio;
                $ar = fopen($path,"a");

                $cant = fwrite($ar,$this->ToJSON()."\r\n");

                if($cant > 0)
                {
                    $objRt->exito = true;
                    $objRt->mensaje = "Se elimino el neumatico y Registro guardado con exito en neumatico_borrados!";
                }

                fclose($ar);
            }
            
            return json_encode($objRt);
        }



        public static function mostrarBorradosJson():array
        {
            $path = "./archivos/neumaticos_borrados.json";
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

        public static function mostrarModificados():array
        {
            $path = "./neumaticosModificados/neumaticos.json";
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
        
    }
}
?>