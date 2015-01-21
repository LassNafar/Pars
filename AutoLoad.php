<?php

    class AutoLoad
    {
        private static $path;
        
        static public function load ($className){
            self::$path = "/www/Parsing/".str_replace("\\", "/", $className) . ".php";
            if(file_exists(self::$path) == true){
                require_once self::$path;
            }
        }
    }
    
    spl_autoload_register (array('Autoload', 'load'));

