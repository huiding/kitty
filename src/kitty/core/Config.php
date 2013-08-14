<?php
namespace core;
class Config
{
    public function get($key){

	// todo if $key is null,  except some error
        return $this->$key;
    }
    
    public function set($key, $value){
        return $this->$key = $value;
    }
}
