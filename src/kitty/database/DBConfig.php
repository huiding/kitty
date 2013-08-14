<?php
namespace database;
use core\Config;
use database\IDBConfig;
class DBConfig extends Config implements IDBConfig{

    public function getHost(){
	return $this->get('host');
    }

    public function getUser(){
	return $this->get('user');
    }

    public function getPassword(){
	return $this->get('password');
    }

    public function getPort(){
	return $this->get('port');
    }

    public function getDatabase(){
	return $this->get('database');
    }
}
