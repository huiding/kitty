<?php
namespace model; 
use core\Model;
class User extends Model{
    
    public function __construct(){
	parent::__construct();	
    }

    public function getName()
    {
	$db = $this->loadDatabase('Mysql');
	
	//echo $db->t1->select(array('id', 'name'))->limit(1)->where(array('id' => '1'))->order(array('name'=>'desc'))->sql();
	
	//echo $db->t1->delete(array('id' => array('>'=>'1')))->sql();
	
	//echo $db->t1->insert( array('name'=>'ddodid', 'time'=> time()))->sql();
	
	//echo $db->table('t1')->insert( 
	//    array('name'=>'ddodid', 'time'=> time())
	//)->select(array('name', 'time'))->limit(1)->where(array(
	//    'id' => array('in'=>array(1,2,34,5,9)), 
	//    'time' => array('between'=>array(9999999999, 2222222))
	//))->group(
	//    array('name'=>'desc')
	//)->sql();
	
	//echo $db->t2->select()->where(array(
	//    'id' => array('>' => '3', '<' => 10),  
	//))->sql();

	echo $db->t2->update( array('name'=>'ddodid', 'time'=> time()) )->where(
	    array('id' => array('>'=>'1'))
	)->limit(3)->sql();
    }
}
