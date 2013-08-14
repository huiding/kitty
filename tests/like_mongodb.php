<?php
class Table{
    function find($statement){
	return json_decode($statement, true);
    }   

    function limit($num){
	return 1; 
    }
}

class DB{ 
    function __get($name){
	$this->$name = new Table();
	return $this->$name;
    }   
}

$db = new DB; 
$result = $db->user->find('{ "age" : { "$gt":10} }')->limit(1);
var_dump($result);

