<?php
namespace core;
class Controller extends Base
{

    public $args = array();

    public function __construct ()
    {
        parent::__construct();
    }

    protected function loadModel ($class = null, $alias = null)
    {
        $class = 'model\\' . $class;
        if ( !empty($alias) ) {
            $this->$alias = new $class();
            return $this->$alias;
        }
        return new $class();
    }
    
    protected function loadView($view, $data, $output=true)
    {
       if( $data ) {
	   extract($data);
       }
       ob_start();
       include_once($this->appRoot . '/view/' . $view);
       $buffer = ob_get_contents();
       ob_end_clean();
       if( $output === true ){
	   echo $buffer;
	   exit;
       }else{
	   return $buffer;
       }
    }

    protected function getArgs ()
    {
        return $this->args;
    }

    public function _acl($controller, $action)
    {
	return true;
    }
}
