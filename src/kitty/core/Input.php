<?php
namespace core;
class Input
{
    public function __construct(){
        
    }
    
    public function get($key=''){
        if (!empty($key)){
            return $_GET[$key];
        }else{
            return $_GET;
        }
    }
    
    public function post($key=''){
        if (!empty($key)){
            return $_POST[$key];
        }else{
            return $_POST;
        }
    }
    
    public function cookie(){
        
    }
    
    
}
