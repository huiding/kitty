<?php
namespace core;
class Base
{

    protected $appRoot;

    protected $wwwRoot;

    protected $config = array();

    protected function __construct ()
    {
        $this->wwwRoot = isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT']) ? trim($_SERVER['DOCUMENT_ROOT']) : dirname($_SERVER['SCRIPT_FILENAME']);
	$this->appRoot = dirname($this->wwwRoot);
    }

    protected function loadConfig ($class, $alias = null)
    {
	$class = 'config\\' . $class;
	if( isset($this->config[$class] ) && $this->config[$class] instanceof $class){
	    $obj = $this->config[$class];   
	}else{
	    $obj = new $class();
	}
	if ( !empty($alias) ) { 
            $this->$alias = $obj;
            return $this->$alias;
        }
        return $obj;
    }

    public function __set($name, $value){
        $this->$name = $value;
    }
}
