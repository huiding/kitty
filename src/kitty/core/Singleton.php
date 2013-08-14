<?php
namespace core;
class Singleton{
    
    public static $_instance;
    
    public static function getInstance() {
        $class = get_called_class();
        
        if (! ($class::$_instance instanceof $class)) {
            $class::$_instance = new $class;
        }
        return $class::$_instance;
    }
}
