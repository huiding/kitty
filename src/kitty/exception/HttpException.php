<?php
namespace exception;
class HttpException extends \Exception{
    public $code = 404;
    public $message = 'NOT FOUND';
}
