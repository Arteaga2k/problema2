<?php

/**
 * CLASE home
 *
 * Controlador página principal (dashboard)
 *
 * @author Carlos
 */
class Instalador extends Controller
{

    public function __construct()
    {
        if (! defined('URL'))
            define('URL', 'http://localhost/problema2/');
        if (! defined('PATH_VIEWS'))
            define('PATH_VIEWS', 'views/');
        if (! defined('PATH_VIEW_FILE_TYPE'))
            define('PATH_VIEW_FILE_TYPE', '.twig');
    }

    /**
     * PÁGINA: Mensaje bienvenida instalacion
     *
     * http://problema3/
     */
    public function index()
    {
        $this->render('instalador/step0', array(
            'login' => TRUE,
            'tema' => 'blue-theme.css'
        ));
    }

    /**
     * PÁGINA: dashboard
     *
     * http://problema3/index
     */
    public function configuracion()
    {
        $this->render('instalador/step1', array(
            'login' => TRUE,
            'tema' => 'blue-theme.css'
        ));
    }

    /**
     * ACCION: leer campos del formulario y crear archivo config.php
     */
    public function add_configuracion()
    {
        if (isset($_REQUEST['install'])) {
            
            $data['DB_TYPE'] = 'mysql';
            $data['DB_HOST'] = filter_var($_REQUEST['servidor'], FILTER_SANITIZE_STRING);
            $data['DB_NAME'] = filter_var($_REQUEST['bbdd'], FILTER_SANITIZE_STRING);
            $data['DB_USER'] = filter_var($_REQUEST['user'], FILTER_SANITIZE_STRING);
            $data['DB_PASS'] = filter_var($_REQUEST['password'], FILTER_SANITIZE_STRING);
            
            // cargamos el modelo
            $instalador_model = $this->loadModel('InstaladorModel');
            $result = $instalador_model->compruebaConexion($data);
        } else {}
    }

    /**
     * PÁGINA: error
     *
     * http://problema3/instalador/error
     */
    public function error()
    {
        $this->render('instalador/error', array(
            'login' => TRUE,
            'tema' => 'blue-theme.css'
        )
        );
    }
}
	
	
