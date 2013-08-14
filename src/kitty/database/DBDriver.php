<?php
namespace database;
use core\Base;
use exception\InvalidArgumentException;
abstract class DBDriver extends Base {

    protected $driver;
    
    public $leftIdentifier = '`';

    public $rightIdentifier = '`' ;

    public $opertation = '';

    protected function __construct (){
	parent::__construct();
	$this->driver = str_replace('\drivers', '', get_called_class());
	$this->config = $this->loadConfig($this->driver);
	$this->init();
    }

    abstract public function escape($str);

    protected function init(){
	$this->op = array(
	    'insert' => '',
	    'insertValues' => '',
	    'delete' => '',
	    'update' => '',
	    'select' => '',
	    'where'  => '',
	    'order'  => '',
	    'group'  => '',
	    'limit'  => ''
	);
	$this->operation = array();
    }

    public function __get($tableName){
	return $this->table($tableName);
    }
    
    public function table($tableName){
	$this->table = $this->identifier($tableName); 
    	$this->init();	
	return $this;
    }
   
    public function identifier($str){
	return $this->leftIdentifier . $str . $this->rightIdentifier;	
    } 

    public function select($fields = NULL, $table = NULL){
	$sql = '';
	$table = isset($table) ? $table : $this->table;
	if ( is_array($fields) ) {
	    $tmp = array();
	    foreach($fields as $val){
		$tmp[] = $this->identifier($val);
	    }
	    $sql = implode(', ', $tmp);
	}else{
	    $sql = '*';
	}
    	$this->op['select'] = 'SELECT ' . $sql . ' FROM '. $table ;
	$this->operation[] = 'select';
	return $this;
    }

    public function insert($params, $mode = NULL){
	$this->operation[] = 'insert';
	return $this->_insert_replace($params, $mode);
    }

    public function replace($params, $mode = NULL){
	$this->opertation[] = 'replace';
	return $this->_insert_replace($params, $mode, 'REPLACE');
    }

    protected function _insert_replace(array $params, $mode, $operation='INSERT'){
	$sql = '';
	$fields = $datas = array();
	foreach($params as $key=>$val){
	    $fields[] = $this->identifier($key);
	    $datas[] = $this->escape($val);
	}
	$modes = array('LOW_PRIORITY', 'DELAYED', 'HIGH_PRIORITY');
	$modeKeyword = isset($modes[$mode]) ? $modes[$mode] : '';
	$this->op['insert'] = $operation . ' ' . $modeKeyword .' INTO ' . $this->table .' ('. implode(', ', $fields) . ')';
	$this->op['insertValues'] = 'VALUES (' . implode(', ', $datas)  . ')';
	return $this;
    }

    public function update(array $datas){
	$tmp = array();
	foreach( $datas as $key => $val ) {
	    if( is_array($val) ){
		$tmp[] = $this->identifier($key) . '=' . $this->identifier($key) . ' ' . $val[0] . ' ' . $this->escape($val[1]);
	    }else{
		$tmp[] = $this->identifier($key) . '=' . $this->escape($val);
	    }
	}
	$this->op['update'] = 'UPDATE ' . $this->table . ' SET ' . implode(', ', $tmp);
	$this->operation[] = 'update';
	return $this;
    }

    public function delete($params = NULL){
	$this->where($params);
	$this->op['delete'] = 'DELETE FROM ' . $this->table;	
	$this->operation[] = 'delete';
	return $this;
    }

    public function where($params = NULL, $link = 'AND'){
	$sql = '';
	if ( is_array($params) ){
	    $tmp = array();
	    foreach( $params as $key => $val ) {
		if( is_array($val) ){
		    $arr = $this->expr($key, $val);
		    $tmp[] = implode(' AND ', $arr);
		}else{
		    $tmp[] = $this->identifier($key) . '=' . $this->escape($val);
		}
	    }
	    if( count($tmp) > 1 ){
		$sql = '(' . implode(') ' . $link  . ' (', $tmp) . ')';
	    }else{
		$sql = implode(' ' . $link  . ' ', $tmp);
	    }
	}else{
	    $sql = ' 1 ';
	}
	$this->op['where'] = 'WHERE ' . $sql;
	return $this;
    }

    public function expr($field, $data){
	if( empty($data) ){
	    throw new InvalidArgumentException();
	}
	$wheres = array();
	foreach($data as $key=>$val){
	    $key = strtoupper($key);
	    switch( $key ){
		case 'IN':
		    $tmp = array();
		    foreach ( $val as $v ) {
			$tmp[] = $this->escape($v);
		    }
		    $wheres[] = $this->identifier($field) . ' ' . $key .' ' . '(' . implode(', ', $tmp) . ')';
		    break;
		case 'BETWEEN':
		    $wheres[] =  $this->identifier($field) . ' '. $key . ' ' . $this->escape($val[0]) . ' AND ' . $this->escape($val[1]);
		    break;
		default:
		    $wheres[] .= $this->identifier($field) . ' ' .$key . ' ' . $this->escape($val);
	    }
	}
	
	return $wheres;
    }

    public function order($params){
	return $this->_order_group($params, 'ORDER');
    }

    public function group($params){
	return $this->_order_group($params, 'GROUP');
    }

    public function _order_group($params, $type){
	$tmp = array();
	foreach($params as $key=>$val){
	    $tmp[] = $this->identifier($key) . ' ' . $val;
	}
	$this->op['order'] = $type. ' BY ' . implode(', ', $tmp);
	return $this;
    }

    public function limit($offset, $rowCount = NULL){
	if( isset($offset) && isset($rowCount) ){
	    $this->op['limit'] = 'LIMIT ' . $rowCount . ' OFFSET ' . $offset;
	}else{
	    $this->op['limit'] = 'LIMIT ' . $offset;
	}
	return $this; 
    }

    public function sql($sql = NULL){
	if($sql !== NULL) {
	    return $sql;
	}

	$express['insert'] = array('insertValues');
	$express['delete'] = array('where', 'order', 'limit');
	$express['update'] = array('where', 'order', 'limit');
	$express['select'] = array('where', 'group', 'order', 'limit');

	$sqlArr = array();	
	foreach ( $this->operation as $key=>$operation ) {
	    $tmp = array();
	    $tmp[] = $this->op[$operation];
	    foreach($express[$operation] as $condition){
		if( $this->op[$condition] ){
		    $tmp[] = $this->op[$condition];
		}
	    }
	    $sqlArr[] = $tmp;
	}
	
	if ( count($sqlArr) <= 1 ){
	    $sql = implode(' ', $sqlArr[0]);
	}else{
	    $sql = '';
	    if ( implode('', $this->operation) === 'insertselect' ){ // INSERT ... SELECT ...
		unset( $sqlArr[0][1] );
	    }
	    foreach($sqlArr as $val){
		$sql .= implode(' ', $val) . ' ';
	    } 
	}	
    	return trim($sql);
    }

    public function countSql(){
	$sql = $this->sql();
	if( $sql ){
	    return preg_replace('/select[\S\s]+?from/i', 'SELECT COUNT(1) FROM', $sql);
	}else{
	    throw new InvalidArgumentException(); 
	}
    }
} 
