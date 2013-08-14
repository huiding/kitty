<?php
namespace controller;
use core\Controller;
class Aloha extends Controller
{
    public function index(){
	$data['title'] = 'aloha title!';
	$data['body']  = 'aloha body!';
	$this->loadModel('User')->getName();
	$this->loadView('aloha.html', $data);
    }

    public function test(){
	echo 'this is public controller test function';
    }

    private function privateTest(){
	echo 'can\'t call Private function';
    }

    public function _acl($controller, $action){
	return true;
    }

    public function _post(){
	echo 'post';
    }


}
