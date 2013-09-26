<?php
/*************************************************************
 *
 *  Kitty for php 
 *  
 *  Kitty is a php develop framework.  See 
 *  http://kitty.catro.me/ for details.
 *  
 *  ---------------------------------------------------------------------
 *  
 *  Copyright (c) 2013 catro.me
 * 
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

ini_set('display_errors', '1');

spl_autoload_register(function ($class){
   $file = dirname(__FILE__) . '/' . str_replace('\\', '/', $class) . '.php';
   if ( is_file($file) ){
        require_once($file);
   }
});

use core\Base;
use core\Route;
use core\Output;
final class Kitty extends Base
{
    public function __construct ()
    {
        parent::__construct();
        spl_autoload_register(function($class){
                $file =	$this->appRoot . '/' . str_replace('\\', '/', $class) . '.php';
                if ( is_file($file) ) {
                    require_once($file);
                }
        });
        $this->loadConfig( 'System', 'system' );
    }	

    public function init ()
    {
        $route = Route::getInstance();
        try {
            $this->call($route);
        } catch (Exception $e) {
	    Output::getInstance()->showException($e);
	}
    }

    private function call ( Route $route )
    {
        $controller = $route->getController();
        if ( empty($controller) ) {
            $controller = $this->system->get('defalutController');
        }
        $action = $route->getAction();

        $controllerClass = 'controller\\' . $controller;
        $controllerObject = new $controllerClass();

        if ( false === is_callable( array($controllerObject, $action) ) ) {
            throw new \exception\HttpException();
            return false;
        }

	if ( true === $controllerObject->_acl($controller, $action) ){
	    $controllerObject->$action();
	}else{
	    throw new \exception\NoPermissionException;
	}
        return true;
    }
}
