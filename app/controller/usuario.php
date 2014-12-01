<?php

/**
 * 
 * @author Carlos
 *
 */
class Usuario extends Controller
{

    /**
     * PÁGINA: index
     *
     * http://problema1/usuario/index
     */
    public function index($pag = 1)
    {        
        // cargamos modelo, realizamos accion, guardamos resultado en una variable
        $usuario_model = $this->loadModel('UsuarioModel');
        $zona_model = $this->loadModel('ZonaModel');
        
        // comprueba si existe en sesion variables campos para filtrar busqueda
        $filtro = $this->compruebaFiltro();
        $totalRows = $usuario_model->getTotalRows($filtro);
        $pagination = $this->pagination($pag, $totalRows);
        
        $usuarios = $usuario_model->getUsuarios(isset($filtro) ? $filtro : null, $pagination['offset'], null);       
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('usuarios/index', array(
            'tabla' => 'Usuarios',
            'cabecera' => 'Lista usuarios',
            'usuario' => $_SESSION['usuario_nombre'],
            'usuarios' => $usuarios,
            'zona_usuario' => $zona['nombrezona'],
            'page' => $pagination['pag'],
            'totalpag' => $pagination['totalPag'],
            'inicio' => $pagination['inicio'],
            'fin' => $pagination['fin'],
            'filtro' => isset($filtro) ? $filtro : '',
        ));     
       
    }
    
    
    
    /**
     * ACCIÓN: Filtro listado envío
     *
     * recoge campos del formulario filtrar listado
     */
    public function filtroPaginacion()
    {
        if (isset($_REQUEST['filtro'])) {
    
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formFiltro());
    
            // guardamos valores de campos a filtrar en la sesion
            Session::start();
    
            foreach ($data['datos'] as $key => $value) {
                if ($value) {
                    Session::set('filtro_' . $key, $value);
                }
            }
        }
    
        header('location: ' . URL . 'usuario');
    }
    
    /**
     * Comprueba si hay filtros de búsqueda guardados en sesion
     *
     * @return unknown
     */
    public function compruebaFiltro()
    {
        $filtro = '';
        if (isset($_SESSION['filtro_texto'])) {
            $filtro['filtro_texto'] = $_SESSION['filtro_texto'];
        }      
    
        return $filtro;
    }

    /**
     * PÁGINA: alta usuario
     */
    public function add()
    {
        $this->render('usuarios/form_registro', array(
            'tabla' => 'Usuario',
            'title' => 'Alta usuario'
        ));
    }

    /**
     * ACCIÓN: recoge información formulario de registro
     */
    public function add_accion()
    {
        if (isset($_REQUEST['add_accion'])) {
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');
            
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formAddUsuario());
            
            // Si validación ok
            if ($this->validation($data)) {
                // insertamos nuevo envío y redireccionamos a envios index
                $usuario_model->addUsuario($data['datos']);                
                header('location: ' . URL . 'home');
            } else {
                $this->render('usuarios/form_registro', array(
                    'tabla' => 'Usuario',
                    'title' => 'Alta usuario',
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
                ));
            }
        }
    }

    /**
     * PÁGINA: configuración
     *
     * http://problema1/usuario/configuracion
     */
    public function configuracion()
    {
        $zona_model = $this->loadModel('ZonaModel');
        $zona = $zona_model->getZona($_SESSION['usuario_zona']);
        
        // creamos la vista, pasamos datos de envío obtenidos
        $this->render('usuarios/configuracion', array(
            'tabla' => 'Configuración',
            'cabecera' => 'parámetros',
            'usuario' => $_SESSION['usuario_nombre'],            
            'zona_usuario' => $zona['nombrezona']
        ));
    }
    
    /**
     * ACCIÓN: recoge información formulario de configuración
     */
    public function configuracion_accion(){
        if (isset($_REQUEST['config_accion'])) {  
            // cargamos el modelo y realizamos la acción
            $usuario_model = $this->loadModel('UsuarioModel');
        
            // filtramos y sanitizamos formulario
            $data = $this->filtraFormulario($this->formConfiguracion());
        
            // Si validación ok
            if ($this->validation($data)) {               
                // insertamos nuevo envío y redireccionamos a envios index
                $usuario_model->setConfigParams($data['datos'], Session::get('usuario_id'));
                
               header('location: ' . URL . 'home');
            } else {
                $this->render('usuarios/configuracion', array(
                    'tabla' => 'Configuración',
                    'title' => 'parámetros',
                    'datos' => $data['datos'],
                    'errores' => $data['errores']
                ));
            }
        }
    }

    /**
     * ACCIÓN: Cerrar sesión del usuario, borramos cookie y sesion
     */
    public function logout()
    {
        // eliminamos cookie según explica el enlace de abajo
        // ponemos una fecha antigua.
        // @see http://stackoverflow.com/a/686166/1114320
        setcookie('rememberme', false, time() - (3600 * 3650), '/', COOKIE_DOMAIN);
        
        // borramos sesion
        Session::destroy();
        
        // redireccionamos al formulario de login
        header('location: ' . URL . 'login');
    }
    
    /**
     * ACCIÓN: borrar parámetros de filtro guardados en la sesión
     */
    public function borraFiltros()
    {
        Session::start();
        Session::_unset('filtro_texto');      
    
        // Redireccionamos al listado de envíos
        header('location: ' . URL . 'usuario/index');
    }

    /**
     * Rellena el array con campos esperados del formulario usuario -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formAddUsuario()
    {
        // campos esperados del formulario envio -nombre/tipo-
        $formEnvio = array(
            'username' => 'alfanum',
            'email' => 'email',
            'password_hash' => 'alfanum'
        );
        
        return $formEnvio;
    }
    
    /**
     * Rellena el array con campos esperados del formulario configuracion -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formConfiguracion(){
        // campos esperados del formulario envio -nombre/tipo-
        $formConfig = array(
            'REGS_PAG' => 'numerico',
            'COOKIE_RUNTIME' => 'numerico'                
        );
        
        return $formConfig;
    }
    
    /**
     * Rellena el array con campos esperados del formulario filtrar -nombre/tipo-
     *
     * @return multitype:string
     */
    public function formFiltro()
    {
        $formFiltrar = array(
            'texto' => 'alfanum'           
        );
    
        return $formFiltrar;
    }
}