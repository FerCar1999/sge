<?php
class Database
{
     /*Atributos de conexion a la base datos*/
     private static $connection;    
     private static $server = 'database';
     private static $database = 'diario_pedagogico';
     private static $username = 'root';
     private static $password =  'DPUser@123'; 

    // funcion privada que permite conectar mediante PDO
    private static function connect() 
    {       
        // indica la codificacion utf8 
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8");
        self::$connection = null;
        try
        {
            // realiza la conexion a la base datos
            self::$connection = new PDO("mysql:host=".self::$server."; dbname=".self::$database, self::$username, self::$password, $options);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $exception)
        {
            die($exception->getMessage());
        }
    }
    // funcion publica que conecta mediante mysqli utilizada unicamente para el read.php
    public static function conect_read(){
        // conecta con la db mediante mysqli       
        $con = new mysqli(self::$server,self::$username, self::$password,self::$database);
        if ($con->connect_errno) {
            ob_clean();            
        }
        // indica la codificacion utf8
        $con->set_charset("utf8");
        return $con;
    }

    // funcion para desconectar de la base datos
    private static function desconnect()
    {
        self::$connection = null;
    }
    // funcion llamada para ejecutar INSERT,UPDATE...
    public static function executeRow($query, $values,$accion = "")
    {
        // conecta prepara y executa la consulta
        self::connect();        
        $statement = self::$connection->prepare($query);
        $resultado = $statement->execute($values);
        
        // si la consulta es un insert devuelve el ultimo id
        if($accion==="INSERT"){
            $id = self::$connection->lastInsertId(); 
            self::desconnect();    
            return $id;
        }
        else{
            self::desconnect();    
            return $resultado;
            
        } 
    }
    // funcion para obtener un registro de la base de datos
    public static function getRow($query, $values)
    {
        self::connect();
        $statement = self::$connection->prepare($query);
        $statement->execute($values);
        self::desconnect();
        //retorna el primer registro
        return $statement->fetch(PDO::FETCH_BOTH);
    }
    // funcion para obtener varios registos
    public static function getRows($query, $values)
    {
        self::connect();
        $statement = self::$connection->prepare($query);
        $statement->execute($values);
        self::desconnect();
        // retorna todos los registros
        return $statement->fetchAll(PDO::FETCH_BOTH);
    }

    public static function getRowsAssoc($query, $values)
    {
        self::connect();
        $statement = self::$connection->prepare($query);
        $statement->execute($values);
        self::desconnect();
        // retorna todos los registros
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function setDatabase($nombre){
        self::$database = $nombre;
    }

}
?>
