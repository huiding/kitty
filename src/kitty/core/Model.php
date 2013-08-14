<?php
/**
 * Kitty for php
 *
 * An open source application development framework for PHP 5.4.0 or later
 *  
 * @author	catro
 * @license GNU
 * @link	http://kitty.catro.me
 */

namespace core;
class Model extends Base{

    /**
     * object of cached database driver. 
     * 
     * @var	object 
     * @access protected
     */
    protected $db = null;

    protected function __construct(){
	parent::__construct();
    }

    /**
     * load database config
     * 
     * what's database canbe use for this application.
     *
     * @access protected
     * @param  string $dirver
     * @return object
     */
    protected function loadDatabase ($driver){
	$class = 'database\drivers\\' . $driver;
	if ( isset($this->db->$driver)  && $this->db->$driver instanceof IDBDriver ){
	    return $this->db->$driver;
	}else{
	    if ( null === $this->db  ) {
		$this->db = new \stdClass();
	    }
	    $this->db->$driver = new \stdClass();
	}
	$this->db->$driver = new $class();
	return $this->db->$driver; 
    }
}
