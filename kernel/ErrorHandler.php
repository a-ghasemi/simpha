<?php

namespace Kernel;

use Kernel\Abstractions\IErrorHandler;

class ErrorHandler implements IErrorHandler
{
    protected array $error_stack;
    protected string $status;

    public function __construct()
    {
        $this->error_stack = [];
    }

    public function addError($title, $message)
    {
        array_push($this->error_stack, ['title' => $title , 'message' => $message]);
        $this->status = 'error';
    }

    public function throwError(){
        $lastError = array_pop($this->error_stack);
        if($lastError) throw new \Exception($lastError['message']);
    }
}