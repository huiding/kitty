<?php
namespace database;
use core\Singleton;
use exception\InvalidArgumentException;
class SqlMaker extends Singleton{
    
    /**
     *
     * @var callable $quoteFunction
     *
     */
    public $quoteFunction = NULL;

    public function quote($str) {
	if( is_callable($this->quoteFunction) ){
	    return call_user_func($this->quoteFunction, $str);
	}else{
	    return '\'' . $str . '\'';
	}
    }

    public function select($datas) {
	if( $datas === NULL) {
	   return '*';
	}	
	if ( !is_array($datas) ) {
	    throw new InvalidArgumentException();
	}
	return implode(', ', $datas);
    }

    public function where($datas) {
	if ( $datas === NULL ){
            return '1';
        }
	if ( is_array($datas) ){
	    $tmp = array();
	    foreach( $datas as $key => $val ) {
		if( is_array($val) ){
		    $tmp[] = '`' . $key . '`' . $val[0] . $this->quote($val[1]);
		}else{
		    $tmp[] = '`' . $key . '`' .  '=' . $this->quote($val);
		}
	    }
	    return implode(' AND ', $tmp);
	}else{
	    throw new InvalidArgumentException(); 
	}	
    }

    public function insert($data){
	
    }

    public function update($data){
	
    }

}
