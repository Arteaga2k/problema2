<?php

/**
 * Clase instaladormodel
 * Crea una conexión con la base de datos con los parámetros introducidos por el usuario
 * Genera las tablas y su contenido
 * Genera el archivo config.php
 * @author Carlos
 *
 */
class InstaladorModel
{

    /**
     *
     * @var unknown
     */
    private $DB_TYPE = null;

    /**
     *
     * @var unknown
     */
    private $DB_HOST = null;

    /**
     */
    /**
     *
     * @var unknown
     */
    private $DB_NAME = null;

    /**
     *
     * @var unknown
     */
    private $DB_USER = null;

    /**
     *
     * @var unknown
     */
    private $DB_PASS = null;

    /**
     *
     * @var conexion PDO
     */
    private $db;

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require_once 'libs/db.php';
    }

    public function compruebaConexion($data)
    {
        
        /**
         * Configuración: Parámetros de la base de datos
         * Contraseña, tipo, usuario...etc
         */
        $this->DB_TYPE = 'mysql';
        $this->DB_HOST = $data['DB_HOST'];
        $this->DB_NAME = $data['DB_NAME'];
        $this->DB_USER = $data['DB_USER'];
        $this->DB_PASS = $data['DB_PASS'];
        
        if (! defined('DB_TYPE'))
            define('DB_TYPE', $this->DB_TYPE);
        if (! defined('DB_HOST'))
            define('DB_HOST', $this->DB_HOST);
        if (! defined('DB_NAME'))
            define('DB_NAME', $this->DB_NAME);
        if (! defined('DB_USER'))
            define('DB_USER', $this->DB_USER);
        if (! defined('DB_PASS'))
            define('DB_PASS', $this->DB_PASS);
       
         $this->db = Db::singleton();
       
         try {
             $sql = file_get_contents('install/install.sql');
             $qr = $this->db->exec($sql);
             $this->generaConfig();
         } catch (Exception $e) {
             //echo $e->getMessage();             
         }     
       header('location: ' . URL.'login');
    }

    /**
     * Generamos documento config.php
     */
    public function generaConfig()
    {
        $fichero = 'config.php';
        $cadena = "<?php
        /**
        * Configuración: La URL del proyecto problema2
        */
        define('URL', 'http://localhost/problema2/');
        
        
        
        /**
        * Configuración: Parámetros de la base de datos
        * Contraseña, tipo, usuario...etc
        */
        define('DB_TYPE', '$this->DB_TYPE');
        define('DB_HOST', '$this->DB_HOST');
        define('DB_NAME', '$this->DB_NAME');
        define('DB_USER', '$this->DB_USER');
        define('DB_PASS', '$this->DB_PASS');
        define('HASH_COST_FACTOR','10' );
        
        /**
        * Configuración : Vistas
        *
        * PATH_VIEWS es la ruta donde se encuentran los archivos con las vistas
        * PATH_VIEW_FILE_TYPE es la extensión de las vistas, en este caso usaremos motor plantilla twig.
        */
        define('PATH_VIEWS', 'views/');
        define('PATH_VIEW_FILE_TYPE', '.twig');";
        
        $myfile = fopen($fichero, "w");
        fwrite($myfile, $cadena, strlen($cadena));
        fclose($myfile);
    }
}