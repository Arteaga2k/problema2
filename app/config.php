<?php
        /**
        * Configuración: La URL del proyecto problema2
        */
        define('URL', 'http://localhost/problema2/');
        
        
        
        /**
        * Configuración: Parámetros de la base de datos
        * Contraseña, tipo, usuario...etc
        */
        define('DB_TYPE', 'mysql');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'kenollega');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('HASH_COST_FACTOR','10' );
        
        /**
        * Configuración : Vistas
        *
        * PATH_VIEWS es la ruta donde se encuentran los archivos con las vistas
        * PATH_VIEW_FILE_TYPE es la extensión de las vistas, en este caso usaremos motor plantilla twig.
        */
        define('PATH_VIEWS', 'views/');
        define('PATH_VIEW_FILE_TYPE', '.twig');