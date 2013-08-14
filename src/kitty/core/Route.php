<?php
namespace core;
class Route extends Singleton
{

    private $_uri;

    private $_uriArr;

    public function __construct ()
    {
        $this->_uri = trim($_SERVER['REQUEST_URI']);
        $this->parse();
    }

    private function parse ()
    {
        $uri = strtok($this->_uri, '?');
        $this->_uriArr = array();
        $this->_uriArr = explode('/', trim($uri, '/'));
    }

    public function getUri ()
    {
        return $this->_uri;
    }

    public function getController ()
    {
        if (! empty($this->_uriArr[0])) {
            return ucfirst($this->_uriArr[0]);
        }
        return null;
    }

    public function getAction ()
    {
        if (! empty($this->_uriArr[1])) {
            return $this->_uriArr[1];
        }
        return 'index';
    }

    public function getArgs ()
    {
        $args = array();
        foreach ($this->_uriArr as $key => $val) {
            if ($key > 1){
                $args[] = trim($val);
            }
        }
        return $args;
    }
}
