<?php
namespace core;
use core\Singleton;
class Output extends Singleton
{
    public function __construct(){
        header("X-Powered-By:Kitty 1.0.1");
        header("Server:apache/1.3.11");
    }
    
    public function showException(\Exception $e){
	echo 'code: ' . $e->getCode();
	echo 'message:' . $e->getMessage();
	echo 'file:' . $e->getFile() . ':' . $e->getLine(); 	
    }    
}
