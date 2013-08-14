<?php
namespace database\drivers;
use database\IDBDriver;
use database\DBDriver;
use exception\InvalidSqlException;
use PDO;

class Mysql extends DBDriver implements IDBDriver{

    public function __construct(){
	parent::__construct();
	$this->connect();
    }

    public function connect(){
        $this->db = new PDO('mysql:dbname=' . $this->config->getDatabase() .';host='. $this->config->getHost(),
	    $this->config->getUser(),
	    $this->config->getPassword()
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function escape($str){
	return $this->db->quote($str);
    }

    public function query($sql = NULL){
	$sql = $this->sql($sql);
	if($sql !== NULL){
	    return $this->db->query($sql);
	}else{
	    throw new InvalidSqlException();
	}
    }

    public function row(){
	$st = $this->query();
	return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function all(){
	$st = $this->query();
	return $st->fetchAll();
    }

    public function exe($sql = NULL){
	$sql = $this->sql($sql);
	if($sql !== NULL){
	    return $this->db->exec($sql);
	}else{
	    throw new InvalidSqlException();
	}
    }

    public function count(){
	$this->countSql = $this->countSql();	
	$st = $this->query($this->countSql);
	return (int)$st->fetchColumn();
    }
}

